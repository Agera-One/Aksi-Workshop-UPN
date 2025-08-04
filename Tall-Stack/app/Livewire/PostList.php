<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $confirmingPostDeletion = false;
    public $postIdToDelete;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingPostDeletion = true;
        $this->postIdToDelete = $id;
    }

    public function deletePost()
    {
        Post::find($this->postIdToDelete)->delete();
        $this->confirmingPostDeletion = false;
        session()->flash('message', 'Post deleted successfully.');
    }

    public function render()
    {
        $posts = Post::where('title', 'like', '%' . $this->search . '%')
            ->orWhere('content', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.post-list', [
            'posts' => $posts,
        ]);
    }
}