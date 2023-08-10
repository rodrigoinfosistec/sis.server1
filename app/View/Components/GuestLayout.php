<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

use App\Models\Begin;

class GuestLayout extends Component
{
    /**
     * View offline.
     */
    public function render(): View
    {
        /**
         * Inicialização obrigatória.
         */
        Begin::initialize();

        return view('layouts.guest');
    }
}
