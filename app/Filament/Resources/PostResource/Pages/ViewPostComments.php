<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPostComments extends ViewRecord
{
    protected static string $resource = PostResource::class;
    protected static string $view = 'filament.resources.post-resource.pages.view-post-comments';

    public Post $post;

    public function mount( $record): void
    {
        parent::mount($record);
        $this->post = Post::with('comments.replies.replies')->findOrFail($record);
    }

    protected static bool $shouldRegisterNavigation = false;
}
