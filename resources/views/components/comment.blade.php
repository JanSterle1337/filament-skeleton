<div class="border-l pl-4 my-2">
    <div>
        <strong>{{ $comment->author->name ?? 'Anonymous' }}</strong>
        <span class="text-gray-500 text-xs">{{$comment->created_at->diffForHumans()}}</span>
    </div>
    <div class="mb-1">{{ $comment->content }}</div>

    <button wire:click="reply({{ $comment->id }})" class="text-blue-600 text-xs">
        Reply
    </button>

    @foreach($comment->replies as $reply)
        <h1 style="font-size:25px; color: red;"></h1>
        <x-comment :comment="$reply"  class="ml-3"/>
    @endforeach
</div>
