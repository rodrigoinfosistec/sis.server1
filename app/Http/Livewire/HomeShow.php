<?php

namespace App\Http\Livewire;

use Livewire\Component;

class HomeShow extends Component
{
    public $config;

    /**
     * Construtor.
     */
    public function mount($config){
        $this->config = $config;
    }

    /**
     * Renderiza página.
     */
    public function render(){
        return view('livewire.' . $this->config['name'] . '-show', [
            'config' => $this->config
        ]);
    }
}
