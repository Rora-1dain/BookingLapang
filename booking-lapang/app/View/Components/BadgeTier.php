<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BadgeTier extends Component
{
    public function __construct(public string $tier = 'Silver')
    {
    }

    public function render()
    {
        return view('components.badge-tier');
    }
}