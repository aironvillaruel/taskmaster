<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TodoModal extends Component
{
    public $showModal = false;

    protected $listeners = ['openModal' => 'openModal'];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.todo-modal');
    }
}
