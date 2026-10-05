import { apiFetch } from './client'

// GET /api/referral — kode, link, jumlah teman daftar & sukses (butuh login)
export function fetchReferralSaya() {
  return apiFetch('/referral', { auth: true })
}

// GET /api/referral/leaderboard — top 10 referrer bulan ini (publik)
export function fetchLeaderboardReferral() {
  return apiFetch('/referral/leaderboard')
}
