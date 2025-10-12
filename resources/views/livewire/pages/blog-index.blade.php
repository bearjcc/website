<div>
    <h1 class="text-4xl font-bold text-star-yellow mb-8">Blog</h1>
    
    @if($posts->count() > 0)
        <div class="space-y-6">
            @foreach($posts as $post)
                <x-public-card>
                    <h2 class="text-2xl font-semibold text-star-yellow mb-2">{{ $post->title }}</h2>
                    <p class="text-sm text-white/50 mb-4">{{ $post->created_at->format('F j, Y') }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-secondary">
                        Read More
                    </a>
                </x-public-card>
            @endforeach
        </div>
    @else
        <x-public-card>
            <p class="text-center text-white/70">No blog posts yet. Check back soon!</p>
        </x-public-card>
    @endif
</div>
