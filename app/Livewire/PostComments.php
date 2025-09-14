<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class PostComments extends Component
{
    public Post $post;
    public $replyContent = '';
    public $replyTo = null;

    public function reply(int $commentId): void
    {
        $this->replyTo = $commentId;
    }

    public function submitReply(): void
    {
        $this->validate([
            'replyContent' => 'required|string|min:2'
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'parent_id' => $this->replyTo,
            'user_id' => auth()->id(),
            'content' => $this->replyContent,
            'status' => 'pending'
        ]);

        $this->replyContent = '';
        $this->replyTo = null;

        $this->post->load('comments.replies.replies.replies');

    }

    public function render()
    {

        $comments = $this->post
            ->comments()
            ->with('author', 'replies.author', 'replies.replies')
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('livewire.post-comments', [
            'comments' => $comments
        ]);
    }
}
