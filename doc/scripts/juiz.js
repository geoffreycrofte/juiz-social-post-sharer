/**
 * Global Document adjustment
 */
(function(window, document) {
	/**
	 * JS HTML adjustements.
	 */
	
	if ( document.querySelector('.tutorial-content') ) {
		document.querySelector('.tutorial-content').closest('#main').classList.add('tutorial-page');
	}
	
	/**
	 * Menu button
	 * for mobile purpose
	 */
	let body = document.querySelector('body'),
		nav  = document.querySelector('nav'),
		btn  = document.createElement('button'),
		doOnResize = function(){
			if ( window.matchMedia("(max-width: 840px)").matches ) {
				body.classList.add('off-canvas-menu');
				nav.setAttribute('aria-hidden', 'true');

				nav.prepend( btn );
				btn.addEventListener('click', toggleMenu );
			} else {
				body.classList.remove('off-canvas-menu');
				nav.removeAttribute('aria-hidden');

				if ( btn ) {
					btn.removeEventListener('click', toggleMenu );
					nav.querySelector('.menu-btn').remove();
				}
			}
		},
		toggleMenu = function() {
			if ( btn.classList.contains('is-open') ) {
				btn.classList.remove('is-open');
				btn.setAttribute('aria-expanded', 'false');
				nav.classList.remove('is-open');
				nav.setAttribute('aria-hidden', 'true');
				body.classList.remove('menu-is-open');
				// close	
			} else {
				// open
				btn.classList.add('is-open');
				btn.setAttribute('aria-expanded', 'true');
				nav.classList.add('is-open');
				nav.setAttribute('aria-hidden', 'false');
				body.classList.add('menu-is-open');
			}
		};

	btn.type = 'button';
	btn.className = 'menu-btn';
	btn.setAttribute('aria-controls', 'navigation');
	btn.setAttribute('aria-label', 'Open/Close Menu');
	btn.innerHTML = '<span class="menu-icon"></span><span class="btn-text">Menu</span>';

	window.addEventListener('resize', function() {
		window.requestAnimationFrame( doOnResize );
	});
	doOnResize();
})(window, document);
