<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Clockregistry;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClockregistryShow extends Component
{
    public function render()
    {
        return view('livewire.clockregistry-show');
    }
}
