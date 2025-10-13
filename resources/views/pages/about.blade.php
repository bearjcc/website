@php
    $title = 'About Us - Ursa Minor Games';
    $description = 'Learn about Ursa Minor Games, our journey from web platform to board game café in New Zealand, and our passion for creating memorable gaming experiences.';
@endphp

<x-layouts.app :title="$title" :description="$description">
    <section class="hero-section">
        <article>
            <h1>About Ursa Minor Games</h1>
            <p class="tagline">From constellation to café</p>
        </article>
    </section>

    <section class="mission-section">
        <article>
            <h2>Our Story</h2>
            <p>
                Ursa Minor—the Little Bear—is a constellation that has guided travelers for centuries. 
                Just as that constellation contains the North Star, we aim to be a guiding light in the gaming community, 
                creating experiences that bring people together and inspire joy.
            </p>
            <p>
                Our ultimate dream is to open a board game café in New Zealand—a physical space where friends gather, 
                strangers become allies across a game board, and every night brings new adventures. But dreams this big 
                require preparation, and that's what we're building here.
            </p>
        </article>
    </section>

    <section class="coming-soon">
        <article>
            <h2>The Journey</h2>
            
            <div class="feature">
                <h3>Phase 1: Building Reputation</h3>
                <p>
                    Before we can run a successful café, we need to establish ourselves in the gaming community. 
                    We're starting with browser games—classic titles that everyone knows and loves, available for free. 
                    This lets us build an audience, get feedback, and hone our craft.
                </p>
            </div>

            <div class="feature">
                <h3>Phase 2: Community Building</h3>
                <p>
                    The F1 Predictions system started as a spreadsheet and Discord bot serving a small community. 
                    We're bringing it here to create a proper platform where race fans can compete, strategize, 
                    and connect over the thrill of Formula 1.
                </p>
            </div>

            <div class="feature">
                <h3>Phase 3: Original Designs</h3>
                <p>
                    We're designing original board games, but we don't have storage space for physical prototypes yet. 
                    Digital versions let us playtest mechanics, gather feedback, and iterate on designs before 
                    considering physical production.
                </p>
            </div>

            <div class="feature">
                <h3>Phase 4: The Big Project</h3>
                <p>
                    There's an ambitious video game in our future—a project that will need years of development, 
                    extensive world-building, and a team of creative contributors. We're building the wiki and 
                    collaboration tools now, laying the groundwork for something epic.
                </p>
            </div>
        </article>
    </section>

    <section class="mission-section">
        <article>
            <h2>Why "Under the Stars"?</h2>
            <p>
                Our tagline—"Where games are born under the stars"—reflects both our brand name (a constellation) 
                and our location in the Southern Hemisphere. The night sky in New Zealand is spectacular, 
                and it reminds us to dream big while staying grounded in the work required to reach those dreams.
            </p>
            <p>
                Every game we create, every feature we build, is a step toward that physical café where people 
                can gather around a table, roll dice, play cards, and create memories together. Until then, 
                we're building the digital foundation that will make that dream a reality.
            </p>
        </article>
    </section>

    <section class="cta-section">
        <article>
            <h2>Join Us on This Journey</h2>
            <p>
                We're not asking for money, subscriptions, or commitments. We're simply inviting you to play our games, 
                share your feedback, and be part of this adventure. If you enjoy what we're building, tell your friends. 
                If you have ideas, we'd love to hear them.
            </p>
            <p>
                Together, we're building something special—one game, one player, one step at a time.
            </p>
        </article>
    </section>
</x-layouts.app>

