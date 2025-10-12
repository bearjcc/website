<div>
    <h1 class="text-4xl font-bold text-star-yellow mb-8">Manage Feature Blocks</h1>
    
    @if(session()->has('message'))
        <div class="card bg-green-500/20 border-green-500/50 mb-6">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="grid md:grid-cols-2 gap-6">
        <div class="card">
            <h2 class="text-2xl font-semibold text-star-yellow mb-4">Add Feature Block</h2>
            
            <form wire:submit="addFeature">
                <div class="mb-4">
                    <label for="kind" class="block text-sm font-medium mb-2">Type</label>
                    <select id="kind" wire:model="kind" class="input w-full">
                        <option value="game">Game</option>
                        <option value="post">Post</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="ref_id" class="block text-sm font-medium mb-2">Reference</label>
                    <select id="ref_id" wire:model="ref_id" class="input w-full" required>
                        <option value="">Select...</option>
                        @if($kind === 'game')
                            @foreach($games as $game)
                                <option value="{{ $game->id }}">{{ $game->title }}</option>
                            @endforeach
                        @else
                            @foreach($posts as $post)
                                <option value="{{ $post->id }}">{{ $post->title }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('ref_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="order" class="block text-sm font-medium mb-2">Order</label>
                    <input 
                        type="number" 
                        id="order" 
                        wire:model="order" 
                        class="input w-full"
                        required
                    >
                    @error('order') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    Add Feature Block
                </button>
            </form>
        </div>

        <div class="card">
            <h2 class="text-2xl font-semibold text-star-yellow mb-4">Current Features</h2>
            
            <div class="space-y-3">
                @foreach($featureBlocks as $block)
                    @php
                        $ref = $block->getReference();
                    @endphp
                    <div class="flex justify-between items-center bg-black/30 p-3 rounded">
                        <div>
                            <span class="text-xs text-star-yellow">{{ ucfirst($block->kind) }} #{{ $block->order }}</span>
                            <p class="text-sm">{{ $ref?->title ?? 'Unknown' }}</p>
                        </div>
                        <button 
                            wire:click="removeFeature({{ $block->id }})" 
                            class="text-red-400 hover:text-red-300 text-sm"
                        >
                            Remove
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
