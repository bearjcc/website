<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-star-yellow">Lore Library</h1>
        <a href="{{ route('lore.create') }}" class="btn btn-primary">Create New Page</a>
    </div>
    
    @if($lorePages->count() > 0)
        <div class="space-y-4">
            @foreach($lorePages as $page)
                <x-public-card>
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-semibold text-star-yellow mb-2">{{ $page->title }}</h2>
                            @if($page->excerpt_md)
                                <p class="text-sm text-white/70 mb-2">{{ \Illuminate\Support\Str::limit($page->excerpt_md, 150) }}</p>
                            @endif
                            <p class="text-xs text-white/50">
                                Status: {{ ucfirst($page->status) }} • 
                                Updated: {{ $page->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('lore.show', $page->slug) }}" class="btn btn-secondary">View</a>
                    </div>
                </x-public-card>
            @endforeach
        </div>
    @else
        <x-public-card>
            <p class="text-center text-white/70">No lore pages yet. Create your first one!</p>
        </x-public-card>
    @endif
</div>
