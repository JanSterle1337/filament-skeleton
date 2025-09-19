<div>
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
