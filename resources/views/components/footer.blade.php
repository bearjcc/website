<footer>
    <div class="footer-content">
        <p>&copy; {{ date('Y') }} Ursa Minor Games. All rights reserved.</p>
        <p class="footer-tagline">Building games under the Southern Cross</p>
        <div class="footer-links">
            <a href="https://github.com/bearjcc/website" target="_blank" rel="noopener">GitHub</a>
            @auth
                <span>|</span>
                <a href="{{ route('dashboard') }}">Dashboard</a>
            @else
                <span>|</span>
                <a href="{{ route('about') }}">About</a>
                <span>|</span>
                <a href="{{ route('contact') }}">Contact</a>
            @endauth
        </div>
    </div>
</footer>

