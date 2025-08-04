<?php
namespace App\Livewire;
use Livewire\Component;

class Counter extends Component
{
    public $count = 100;
    public $nama = 'ABC';
public $isOpen = false;
public function mount()
{
    $this->count = $this->count * 200;
}
    public function increment()
    {
        $this->count= $this->count + 2;
    }
    public function decrement()
    {
        $this->count--;
    }
    public function render()
    {
        return view('livewire.counter');
    }
}

