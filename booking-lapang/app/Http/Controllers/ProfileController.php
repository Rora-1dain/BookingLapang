<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\DataPrivasiService;
use App\Services\LoyaltyService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, LoyaltyService $loyaltyService): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'riwayatPoin' => $user->poinHistories()->latest()->limit(10)->get(),
            'tier' => $loyaltyService->tentukanTier($user),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun lewat anonimisasi (bukan delete()), supaya booking & transaksi tetap ada.
     */
    public function destroy(Request $request, DataPrivasiService $privasi): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            // Service mencatat audit memakai auth()->id(), jadi logout SETELAH ini
            $privasi->ajukanHapusAkun($user, $request->input('password'));
        } catch (Exception $e) {
            // Ditolak (booking aktif, refund diproses, payout belum selesai): akun tidak berubah
            return Redirect::back()->withErrors(['password' => $e->getMessage()], 'userDeletion');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
