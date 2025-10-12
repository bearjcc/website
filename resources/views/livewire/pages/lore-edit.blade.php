<div class="max-w-2xl mx-auto">
    <h1 class="text-4xl font-bold text-star-yellow mb-8">
        {{ $lorePage ? 'Edit' : 'Create' }} Lore Page
    </h1>
    
    @if(session()->has('message'))
        <div class="card bg-green-500/20 border-green-500/50 mb-6">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="card">
        <form wire:submit="save">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-2">Title</label>
                <input 
                    type="text" 
                    id="title" 
                    wire:model="title" 
                    class="input w-full"
                    required
                >
                @error('title') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium mb-2">Slug</label>
                <input 
                    type="text" 
                    id="slug" 
                    wire:model="slug" 
                    class="input w-full"
                    placeholder="Leave empty to auto-generate"
                >
                @error('slug') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium mb-2">Status</label>
                <select id="status" wire:model="status" class="input w-full">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="excerpt_md" class="block text-sm font-medium mb-2">Excerpt</label>
                <textarea 
                    id="excerpt_md" 
                    wire:model="excerpt_md" 
                    class="input w-full"
                    rows="2"
                ></textarea>
            </div>

            <div class="mb-6">
                <label for="body_md" class="block text-sm font-medium mb-2">Content (Markdown)</label>
                <textarea 
                    id="body_md" 
                    wire:model="body_md" 
                    class="input w-full font-mono text-sm"
                    rows="15"
                    required
                ></textarea>
                @error('body_md') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn btn-primary">
                    Save Page
                </button>
                <a href="{{ route('lore.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
