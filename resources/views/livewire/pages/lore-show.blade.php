<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-bold text-star-yellow">{{ $lorePage->title }}</h1>
        <a href="{{ route('lore.edit', $lorePage->slug) }}" class="btn btn-secondary">Edit</a>
    </div>
    
    <div class="mb-4 text-sm text-white/50">
        Status: {{ ucfirst($lorePage->status) }} • 
        Last updated: {{ $lorePage->updated_at->format('F j, Y') }}
        @if($lorePage->author)
            • By {{ $lorePage->author->name }}
        @endif
    </div>
    
    @if($lorePage->tags_json && count($lorePage->tags_json) > 0)
        <div class="mb-6 flex gap-2">
            @foreach($lorePage->tags_json as $tag)
                <span class="px-3 py-1 bg-star-yellow/20 text-star-yellow text-xs rounded-full">{{ $tag }}</span>
            @endforeach
        </div>
    @endif
    
    <div class="card prose">
        {!! \Illuminate\Support\Str::markdown($lorePage->body_md) !!}
    </div>
    
    <div class="mt-8">
        <a href="{{ route('lore.index') }}" class="text-star-yellow hover:underline">
            ← Back to Lore Library
        </a>
    </div>
</div>
