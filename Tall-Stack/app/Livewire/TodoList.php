<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
class TodoList extends Component
{
    public $judul;
    public function tambah()
{
  Todo::create(['judul' => $this->judul]);
  $this->judul = '';
}
    public function render()
    {
        return view('livewire.todo-list', ['todos' => Todo::latest()->get()]);
    }
}
