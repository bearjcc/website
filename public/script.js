function stars() {
	var stars = document.getElementById("stars");
	
	// how many stars
	var iterations = window.innerHeight * window.innerWidth / 10000;
	console.log("Generating " + iterations + "stars...");



	for (i = 0; i < iterations * .6; i++) { //only animate a certain number
		star = document.getElementById("circle-animate").cloneNode(true);
		stars.appendChild(star);
		stars.lastChild.id = "circle-animate" + i;
		randSize = Math.random() * 3;
		randTop = Math.random() * 100;
		randLeft = Math.random() * 100;
		randDelay = Math.random() * 3 - 2;
		randDuration = Math.random() * 3 + 1;
		stars.lastChild.style.height = randSize + "px";
		stars.lastChild.style.width = randSize + "px";
		stars.lastChild.style.top = randTop + "vh";
		stars.lastChild.style.left = randLeft + "vw";
		stars.lastChild.style.animationDelay = randDelay + "s";
		stars.lastChild.style.animationDuration = randDuration + "s";
	}

	for (; i < iterations; i++) {
		star = document.getElementById("circle").cloneNode(true);
		stars.appendChild(star);
		stars.lastChild.id = "circle" + i;
		randSize = Math.random() * 3;
		randTop = Math.random() * 100;
		randLeft = Math.random() * 100;
		stars.lastChild.style.height = randSize + "px";
		stars.lastChild.style.width = randSize + "px";
		stars.lastChild.style.top = randTop + "vh";
		stars.lastChild.style.left = randLeft + "vw";
	}

}