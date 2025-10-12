<!DOCTYPE html>
<html lang="en" data-scroll="0">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ursa Minor Games - Where Games Are Born Under the Stars</title>
	
	<!-- SEO Meta Tags -->
	<meta name="description" content="Ursa Minor Games: Browser games, F1 predictions, original board games, and world-building. Join our gaming community and follow our journey towards a board game café in New Zealand.">
	<meta name="keywords" content="browser games, sudoku, chess, F1 predictions, board games, game development, New Zealand, gaming community">
	<meta name="author" content="Ursa Minor Games">
	
	<!-- Open Graph / Social Media -->
	<meta property="og:type" content="website">
	<meta property="og:title" content="Ursa Minor Games - Where Games Are Born Under the Stars">
	<meta property="og:description" content="Creating memorable gaming experiences from browser games to board games and beyond.">
	<meta property="og:url" content="{{ config('app.url') }}">
	
	<link rel="stylesheet" href="{{ asset('style.css') }}?v={{ time() }}">
	<script src="{{ asset('script.js') }}?v={{ time() }}"></script>
	<script src="{{ asset('scroll.js') }}?v={{ time() }}"></script>
</head>

	<body onload="stars();" style="background: #000; color: #fff;">
	<header>
		<nav aria-label="Main navigation" class="header-nav">
			<a class="nav left" href="/games">Games</a>
			<a class="nav left" href="/f1">F1</a>
			<a class="nav right" href="/about">About</a>
			<a class="nav right" href="/contact">Contact</a>
                </nav>
		<div class="center-content">
			<span class="ursa">ursa</span>
			<object class="bear" data="{{ asset('bear.svg') }}" type="image/svg+xml"></object>
			<span class="minor">minor</span>
		</div>
        </header>
	<div id="stars">
		<div class="circle blink" id="circle-animate"> </div>
		<div class="circle" id="circle"> </div>
                </div>

	<main>
		<section class="hero-section">
			<article>
				<h1>Welcome to Ursa Minor</h1>
				<p class="tagline">Where games are born under the stars</p>
				
				<p class="intro">
					Ursa Minor is a game development brand focused on creating memorable gaming experiences—from classic 
					browser games to innovative board games and beyond. We believe great games start with great ideas, 
					careful planning, and a community of passionate players.
				</p>
			</article>
		</section>

		<section class="mission-section">
			<article>
				<h2>Our Vision</h2>
				<p>
					One day, we dream of opening a board game café in New Zealand—a place where friends gather, 
					strategies unfold, and new adventures begin. But every great journey starts with a single step.
				</p>
				<p>
					Before that dream becomes reality, we're building something special: a platform where we can 
					test game mechanics, explore world-building, share original board game designs, and create a 
					community around the love of gaming.
				</p>
			</article>
		</section>

		<section class="coming-soon">
			<article>
				<h2>What's Coming</h2>
				
				<div class="feature">
					<h3>🎮 Browser Games</h3>
					<p>
						Classic games like Sudoku, Chess, and more—reimagined for the web. Play for free, 
						challenge yourself, and compete on leaderboards. Coming soon!
					</p>
				</div>

				<div class="feature">
					<h3>🏎️ F1 Predictions</h3>
					<p>
						Join our community to predict race outcomes, earn points, and climb the seasonal leaderboard. 
						Perfect for Formula 1 fans who want to add an extra layer of excitement to race weekends.
					</p>
                </div>

				<div class="feature">
					<h3>🎲 Board Games</h3>
					<p>
						Original board game designs available digitally and as print-and-play downloads. 
						Playtest new mechanics, provide feedback, and be part of the creative process.
					</p>
        </div>

				<div class="feature">
					<h3>🌍 World Building</h3>
					<p>
						Explore the lore, maps, and stories behind our ambitious video game project. 
						A collaborative space for writers, artists, and world-builders to contribute to something epic.
					</p>
				</div>
			</article>
		</section>

		<section class="cta-section">
			<article>
				<h2>Join the Journey</h2>
				<p>
					We're building this platform incrementally, adding features and games step by step. 
					Follow our progress, play our games, and become part of the Ursa Minor community.
				</p>
				<p class="disclaimer">
					<em>Currently in active development. First games coming soon!</em>
				</p>
			</article>
		</section>
	</main>

	<footer>
		<div class="footer-content">
			<p>&copy; {{ date('Y') }} Ursa Minor Games. All rights reserved.</p>
			<p class="footer-tagline">Building games under the Southern Cross</p>
			<div class="footer-links">
				<a href="https://github.com/bearjcc/website" target="_blank" rel="noopener">GitHub</a>
			</div>
		</div>
	</footer>

</body>

</html>
