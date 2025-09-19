<?php

namespace App\Livewire;

use Livewire\Component;

class CompanyProfile extends Component
{
    public function render()
    {
        return view('livewire.company-profile')
            ->layout('layouts.app'); // pakai layout utama
    }
}
