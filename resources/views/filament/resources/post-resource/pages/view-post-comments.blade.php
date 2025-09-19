<x-filament::page>
    <h1 class="text-2xl font-bold mb-4">{{$post->title}}</h1>

    <div class="prose mb-6">
        {!! nl2br(e($post->content)) !!}
    </div>

    <livewire:post-comments :post="$post" />
</x-filament::page>
