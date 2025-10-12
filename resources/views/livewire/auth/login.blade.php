<div class="max-w-md mx-auto">
    <div class="card">
        <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>
        
        <form wire:submit="login">
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
                <label class="flex items-center">
                    <input type="checkbox" wire:model="remember" class="mr-2">
                    <span class="text-sm">Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-full mb-4">
                Login
            </button>

            <div class="text-center text-sm">
                <a href="{{ route('register') }}" class="text-star-yellow hover:underline">
                    Don't have an account? Register
                </a>
            </div>
        </form>
    </div>
</div>
