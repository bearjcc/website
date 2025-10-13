@php
    $title = 'Contact - Ursa Minor Games';
    $description = 'Get in touch with Ursa Minor Games. Find us on GitHub, follow our development, and join our gaming community.';
@endphp

<x-layouts.app :title="$title" :description="$description">
    <section class="hero-section">
        <article>
            <h1>Get In Touch</h1>
            <p class="tagline">Connect with Ursa Minor Games</p>
        </article>
    </section>

    <section class="mission-section">
        <article>
            <h2>Where to Find Us</h2>
            <p>
                We're in active development mode, building out the platform incrementally. 
                The best way to follow our progress and get involved is through our development channels.
            </p>
        </article>
    </section>

    <section class="coming-soon">
        <article>
            <h2>Development & Updates</h2>
            
            <div class="feature">
                <h3>GitHub</h3>
                <p>
                    Follow our development progress, report issues, and see what we're working on.
                </p>
                <p>
                    <a href="https://github.com/bearjcc/website" target="_blank" rel="noopener" 
                       style="color: var(--star-yellow); text-decoration: underline;">
                        github.com/bearjcc/website
                    </a>
                </p>
            </div>

            <div class="feature">
                <h3>Project Roadmap</h3>
                <p>
                    Want to know what's coming next? Check out our comprehensive roadmap in the repository's 
                    <code>docs/MASTER_ROADMAP.md</code> file. It outlines every phase from browser games to 
                    the board game café dream.
                </p>
            </div>

            <div class="feature">
                <h3>Future Channels</h3>
                <p>
                    As the community grows, we'll add more ways to connect:
                </p>
                <ul style="text-align: left; max-width: 600px; margin: 1rem auto;">
                    <li>Discord server for real-time chat</li>
                    <li>Twitter/X for updates and announcements</li>
                    <li>Reddit community for discussions</li>
                    <li>Email newsletter for major milestones</li>
                </ul>
                <p>
                    <em>These channels will be announced when we're ready to support them properly.</em>
                </p>
            </div>
        </article>
    </section>

    <section class="mission-section">
        <article>
            <h2>Feedback & Suggestions</h2>
            <p>
                Have ideas for games we should add? Found a bug? Want to contribute to the world-building wiki 
                when it launches? We'd love to hear from you!
            </p>
            <p>
                For now, the best place for feedback is through GitHub Issues on our repository. 
                As the platform grows, we'll add more formal feedback channels.
            </p>
        </article>
    </section>

    <section class="cta-section">
        <article>
            <h2>Stay Tuned</h2>
            <p>
                We're building something special here, and we're excited to share it with you. 
                Bookmark this site, check back regularly, and watch as Ursa Minor Games comes to life.
            </p>
            <p>
                <em>First browser games launching soon!</em>
            </p>
        </article>
    </section>
</x-layout>

