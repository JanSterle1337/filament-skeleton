<div>
    <h1 style="font-size: 40px;">post-comments.blade.php</h1>
   <h2 class="text-lg font-bold mb-3">Comments</h2>

    @foreach($comments as $comment)
        <x-comment :comment="$comment" />
    @endforeach

    @if ($replyTo)
        <div class="mt-3">
            <textarea wire:model="replyContent" class="w-full border rounded p-2" placeholder="Write your reply"></textarea>
            <button wire:click="submitReply" class="mt-2 bg-blue-500 text-white px-3 py-1 rounded">
                Button reply
            </button>
        </div>
    @endif
</div>
