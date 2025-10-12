<div class="max-w-2xl mx-auto">
    <h1 class="text-4xl font-bold text-star-yellow mb-4">{{ $post->title }}</h1>
    <p class="text-sm text-white/50 mb-8">{{ $post->created_at->format('F j, Y') }}</p>
    
    <div class="card prose">
        {!! \Illuminate\Support\Str::markdown($post->body_md) !!}
    </div>
    
    <div class="mt-8">
        <a href="{{ route('blog.index') }}" class="text-star-yellow hover:underline">
            ← Back to Blog
        </a>
    </div>
</div>
