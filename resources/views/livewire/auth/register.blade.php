<div class="max-w-md mx-auto">
    <div class="card">
        <h2 class="text-2xl font-semibold mb-6 text-center">Register</h2>
        
        <form wire:submit="register">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    wire:model="name" 
                    class="input w-full"
                    required
                >
                @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    wire:model="email" 
                    class="input w-full"
                    required
                >
                @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    wire:model="password" 
                    class="input w-full"
                    required
                >
                @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    wire:model="password_confirmation" 
                    class="input w-full"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-full mb-4">
                Register
            </button>

            <div class="text-center text-sm">
                <a href="{{ route('login') }}" class="text-star-yellow hover:underline">
                    Already have an account? Login
                </a>
            </div>
        </form>
    </div>
</div>
