<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class PostForm extends Component
{
    public $postId;
    public $title = '';
    public $content = '';

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required|min:10',
    ];

    public function mount($postId = null)
    {
        if ($postId) {
            $this->postId = $postId;
            $post = Post::findOrFail($postId);
            $this->title = $post->title;
            $this->content = $post->content;
        }
    }

    public function savePost()
    {
        $this->validate();

        if ($this->postId) {
            // Update
            $post = Post::find($this->postId);
            $post->update([
                'title' => $this->title,
                'content' => $this->content,
            ]);
            session()->flash('message', 'Post updated successfully.');
        } else {
            // Create
            Post::create([
                'title' => $this->title,
                'content' => $this->content,
            ]);
            session()->flash('message', 'Post created successfully.');
        }

        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}