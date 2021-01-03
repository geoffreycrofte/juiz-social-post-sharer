;
/**
 * Admin.js
 *
 * Admin JS file for Nobs • Share Buttons
 * 
 * @since 2.0.0
 * @author Geoffrey Crofte
 */

/**
 * LMDD Drag & Drop Script
 * @see: https://supraniti.github.io/Lean-Mean-Drag-and-Drop/
 */
var lmdd=function(){function x(a,b,l,c){l?a.classList.add(b):a.classList.remove(b);c&&p[c].push(function(){l?a.classList.remove(b):a.classList.add(b)})}function D(a,b,l,c,d){a.addEventListener(b,l,c);p[d].push(function(){a.removeEventListener(b,l,c)})}function S(a,b){var l={};Object.keys(a).forEach(function(c){l[c]=Object.prototype.hasOwnProperty.call(b,c)?b[c]:a[c]});return l}function K(a){for(var b=0;b<a.childNodes.length;b++){var c=a.childNodes[b];3!==c.nodeType||/\S/.test(c.nodeValue)?1===c.nodeType&&
K(c):(a.removeChild(c),b--)}}function T(a,b){var c=a.cloneRef.rectRef,d=b.cloneRef.rectRef;return{x:c.left-d.left-b.cloneRef.styleRef.left.border,y:c.top-d.top-b.cloneRef.styleRef.top.border}}function L(a,b){for(var c=[],e=!1;a&&a!==document;a=a.parentNode)c.unshift(a),a.classList.contains(b)&&!e&&(e=a);return-1<c.indexOf(d)?e:!1}function v(a){if(!(1<a.touches.length)){var b=new MouseEvent("touchstart"===a.type?"mousedown":"touchend"===a.type?"mouseup":"mousemove",{view:window,bubbles:!0,cancelable:!0,
screenX:a.touches[0]?a.touches[0].screenX:0,screenY:a.touches[0]?a.touches[0].screenY:0,clientX:a.touches[0]?a.touches[0].clientX:0,clientY:a.touches[0]?a.touches[0].clientY:0,button:0,buttons:1}),c="touchmove"===a.type?document.elementFromPoint(b.clientX,b.clientY)||document.body:a.target;"dragStart"===m&&a.preventDefault();c.dispatchEvent(b)}}function M(a){return new CustomEvent(a,{bubbles:!0,detail:{dragType:n?"clone":"move",draggedElement:n?h.elref:g,from:{container:c.originalContainer,index:c.originalIndex},
to:t?{container:c.currentContainer,index:c.currentIndex}:!1}})}function y(a){a.preventDefault();a.stopPropagation();return!1}function N(a){var b=[];Array.prototype.forEach.call(a.childNodes,function(a,c){if(1===a.nodeType){var l=a.getBoundingClientRect();l.index=c;a.classList.contains(d.lmddOptions.draggableItemClass)&&b.push(l)}});return b}function E(a){c.originalContainer=a.parentNode;c.originalNextSibling=a.nextSibling;c.originalIndex=Array.prototype.indexOf.call(a.parentNode.childNodes,a)}function O(){c.previousContainer=
c.currentContainer;c.currentTarget!==k.target&&(c.currentTarget=k.target,c.currentContainer=L(k.target,d.lmddOptions.containerClass))}function z(){c.currentCoordinates=c.currentContainer?N(c.currentContainer):N(c.originalContainer)}function P(){c.previousPosition=c.currentPosition;if(c.currentContainer){var a=c.currentCoordinates;var b=k.clientY,d=a.length;if(0===d)a=null;else{for(var e=0,f=0,g=-1;e<=d;e++)if(e===d){e--;break}else if(a[e].bottom>b){e--;break}for(;f<=d&&f!==d&&!(a[f].top>b);f++);for(b=
e+1;b<=f;b++)if(b===f){g=b;break}else if(a[b].left>k.clientX){g=b;break}a=g===d?a[g-1].index+1:a[g].index}c.currentPosition=a}else c.currentPosition=!1}function F(){var a;if(a=c.currentContainer){var b=c.currentContainer;a=g;a.contains(b)||b.classList.contains("lmdd-dispatcher")?a=!1:d.lmddOptions.matchObject?(b=b.dataset.containerType||!1,a=a.dataset.itemType||!1,a=b?a?d.lmddOptions.matchObject[b][a]:d.lmddOptions.matchObject[b]["default"]:d.lmddOptions.matchObject["default"]):a=!0}a?(c.currentContainer.insertBefore(g,
c.currentContainer.childNodes[c.currentPosition]),c.currentIndex=Array.prototype.indexOf.call(g.parentNode.childNodes,g),n&&!t&&(h.elref.classList.remove("no-display"),h.elref.cloneRef.classList.remove("no-display"),h.elref.cloneRef.classList.add("no-transition"),E(g)),t=!0):d.lmddOptions.revert&&(c.originalContainer.insertBefore(g,c.originalNextSibling),c.currentIndex=c.originalIndex);z();A=k}function G(a){a.cloneRef.rectRef=a.getBoundingClientRect();var b=window.getComputedStyle?getComputedStyle(a,
null):a.currentStyle;a.cloneRef.styleRef={top:{padding:parseInt(b.paddingTop,10),margin:parseInt(b.marginTop,10),border:parseInt(b.borderTopWidth,10)},left:{padding:parseInt(b.paddingLeft,10),margin:parseInt(b.marginLeft,10),border:parseInt(b.borderLeftWidth,10)}};a.classList.contains("lmdd-block")||Array.prototype.forEach.call(a.childNodes,function(a){1===a.nodeType&&G(a)})}function U(a){var b=a.cloneNode(!0);b.id+="-lmddClone";var c=[],d=[],e=function(a,b){b.push(a);Array.prototype.forEach.call(a.childNodes,
function(a){e(a,b)})};e(a,c);e(b,d);for(a=0;a<c.length;a++)c[a].cloneRef=d[a]}function Q(a){delete a.cloneRef;Array.prototype.forEach.call(a.childNodes,function(a){Q(a)})}function H(a){V(a);a.classList.contains("lmdd-block")||Array.prototype.forEach.call(a.childNodes,function(a){1===a.nodeType&&H(a)})}function V(a){var b=a.cloneRef,e=a.parentNode.cloneRef;b.style.position="absolute";b.style.webkitBackfaceVisibility="hidden";b.style.width=b.rectRef.width+"px";b.style.height=b.rectRef.height+"px";b.style.display=
"block";a===d?(b.style.top=b.rectRef.top+window.pageYOffset+"px",b.style.left=b.rectRef.left+window.pageXOffset+"px"):(a=n?c.referenceContainer.cloneRef:c.originalContainer.cloneRef,b.style.transform="translate3d("+(b.rectRef.left-(b===f?a.rectRef.left:e.rectRef.left)-(b===f?a.styleRef.left.border+a.styleRef.left.padding+f.offsetFix.left:e.styleRef.left.border+e.styleRef.left.padding+b.styleRef.left.margin))+"px, "+(b.rectRef.top-(b===f?a.rectRef.top:e.rectRef.top)-(b===f?a.styleRef.top.border+a.styleRef.top.padding+
f.offsetFix.top:e.styleRef.top.border+e.styleRef.top.padding+b.styleRef.top.margin))+"px,0px)")}function I(){e.style.top=k.pageY-parseInt(e.parentNode.style.top,10)+q.y-r.y+"px";e.style.left=k.pageX-parseInt(e.parentNode.style.left,10)+q.x-r.x+"px"}function w(a){switch(m){case "waitDragStart":"mousedown"===a.type&&0===a.button&&(d=this,k=a,D(window,"mouseup",w,!1,"onDragEnd"),D(document,"mousemove",w,!1,"onDragEnd"),D(document,"scroll",w,!1,"onDragEnd"),m="dragStartTimeout",window.setTimeout(function(){if("dragStartTimeout"===
m)if(d.lmddOptions.handleClass&&!a.target.classList.contains(d.lmddOptions.handleClass))u();else{var b=L(a.target,d.lmddOptions.draggableItemClass);b?(d.dispatchEvent(new CustomEvent("lmddbeforestart",{bubbles:!0})),r.x=a.clientX-b.getBoundingClientRect().left,r.y=a.clientY-b.getBoundingClientRect().top,W(b),document.body.setCapture&&(document.body.setCapture(!1),p.onDragEnd.push(function(){document.releaseCapture()})),clearInterval(B),B=window.setInterval(X,d.lmddOptions.calcInterval),R.observe(d,
{childList:!0,subtree:!0}),d.lmddOptions.nativeScroll||a.preventDefault(),d.dispatchEvent(M("lmddstart")),m="dragStart",C.active=!0):u()}},d.lmddOptions.dragstartTimeout));break;case "dragStartTimeout":if(0===J){J++;break}u();break;case "dragStart":if("mousedown"===a.type||"mouseup"===a.type||"mousemove"===a.type&&0===a.buttons){if(!g){u();break}C.active=!1;e.classList.add("gf-transition");var b=e.getBoundingClientRect(),c=b.left-f.rectRef.left;b=b.top-f.rectRef.top;var h=T(g,d);e.style.width=f.rectRef.width+
"px";e.style.height=f.rectRef.height+"px";e.style.transform="scale(1,1)";e.style.top=h.y+"px";e.style.left=h.x+"px";if(3<Math.abs(c)+Math.abs(b))m="waitDragEnd",p.onTransitionEnd.push(function(){u()});else{u();break}}"mousemove"===a.type&&(k=a,d.lmddOptions.nativeScroll||a.preventDefault(),q.lastX=window.pageXOffset,q.lastY=window.pageYOffset,I());"scroll"===a.type&&(I(),z());break;case "waitDragEnd":"transitionend"===a.type&&p.executeTask("onTransitionEnd"),"mouseup"===a.type&&u()}}function W(a){a.classList.contains("lmdd-clonner")&&
(n=!0,h=a.parentNode.insertBefore(a.cloneNode(!0),a),h.classList.remove("lmdd-clonner"),a.classList.add("no-display"),h.elref=a);U(d);g=n?h:a;f=g.cloneRef;e=f.cloneNode(!0);"LI"===e.tagName&&(a=document.createElement("ul"),a.appendChild(e),a.style.padding=0,e.style.margin=0,e=a,e.wrapped=!0);x(g,"lmdd-hidden",!0,"onDragEnd");f.classList.add("lmdd-shadow");E(g);c.referenceContainer=c.originalContainer;O();z();window.getSelection().removeAllRanges();x(document.body,"unselectable",!0,"onDragEnd");d.parentNode.appendChild(d.cloneRef);
p.onDragEnd.push(function(){d.parentNode.removeChild(d.cloneRef);Q(d)});for(a=f;d.cloneRef.contains(a);)a.style.zIndex=0,a=a.parentNode;var b=window.getComputedStyle?getComputedStyle(f,null):f.currentStyle;f.offsetFix={left:parseInt(b.marginLeft,10),top:parseInt(b.marginTop,10),parent:g.parentNode.cloneRef};G(d);H(d);e.classList.add("lmdd-mirror");"width height padding paddingTop paddingBottom paddingLeft paddingRight lineHeight".split(" ").forEach(function(a){e.wrapped?e.childNodes[0].style[a]=b[a]:
e.style[a]=b[a]});a=d.lmddOptions.mirrorMaxWidth/f.getBoundingClientRect().width;var k=d.lmddOptions.mirrorMinHeight/f.getBoundingClientRect().height;a=Math.min(1,Math.max(a,k));r.x*=a;r.y*=a;e.style.transform="scale("+a+","+a+")";e.style.transformOrigin="0 0";d.cloneRef.appendChild(e);e.addEventListener("transitionend",w,!1);q.lastX=window.pageXOffset;q.lastY=window.pageYOffset;I();x(d,"hidden-layer",!0,"onDragEnd");x(d.cloneRef,"visible-layer",!0,!1)}function X(){d.lmddOptions.nativeScroll||C.updateEvent(k);
if(A===k||A&&d.lmddOptions.positionDelay&&k.timeStamp-A.timeStamp<d.lmddOptions.calcInterval||"dragStart"!==m)return!1;O();c.currentContainer?c.currentContainer!==c.previousContainer?(z(),P(),F()):(P(),c.currentPosition!==c.previousPosition&&F()):c.previousContainer&&d.lmddOptions.revert&&F()}function u(){R.disconnect();clearInterval(B);B=null;J=0;C.active=!1;n&&!t&&(h.elref.classList.remove("no-display"),h.parentNode.removeChild(h));n&&E(h.elref);p.executeTask("onDragEnd");"dragStartTimeout"!==m&&
"waitDragStart"!==m&&d.dispatchEvent(M("lmddend"));d.lmddOptions.dataMode&&(t&&n?g.parentNode.removeChild(g):t&&c.originalContainer.insertBefore(g,c.originalNextSibling));n=t=!1;m="waitDragStart"}var Y={containerClass:"lmdd-container",draggableItemClass:"lmdd-draggable",handleClass:!1,dragstartTimeout:50,calcInterval:200,revert:!0,nativeScroll:!1,mirrorMinHeight:100,mirrorMaxWidth:500,matchObject:!1,positionDelay:!1,dataMode:!1},d=null,g=null,f=null,e=null,h=null,n=!1,t=!1,m="waitDragStart",k=null,
A=null,B=null,q={lastX:window.pageXOffset,lastY:window.pageYOffset,get x(){return window.pageXOffset-q.lastX},get y(){return window.pageYOffset-q.lastY}},r={x:0,y:0},c={currentTarget:!1,originalContainer:!1,originalNextSibling:!1,originalIndex:!1,currentContainer:!1,currentIndex:!1,previousContainer:!1,currentCoordinates:!1,currentPosition:!1,previousPosition:!1,referenceContainer:!1},J=0,R=new MutationObserver(function(a){a.forEach(function(a){0<a.addedNodes.length&&(G(d),H(d))})}),p={executeTask:function(a){p[a].forEach(function(a){a()});
p[a]=[]},onDragEnd:[],onTransitionEnd:[]},C={event:null,active:!0,sm:20,el:document.documentElement,scrollInvoked:{top:!1,left:!1,bottom:!1,right:!1},get willScroll(){return{top:this.event.clientY<=this.sm&&0<window.pageYOffset,left:this.event.clientX<=this.sm&&0<window.pageXOffset,bottom:this.event.clientY>=this.el.clientHeight-this.sm&&window.pageYOffset<this.el.scrollHeight-this.el.clientHeight,right:this.event.clientX>=this.el.clientWidth-this.sm&&window.pageXOffset<this.el.scrollWidth-this.el.clientWidth}},
get speed(){return Math.max(0,this.sm+Math.max(0-this.event.clientY,0-this.event.clientX,this.event.clientY-this.el.clientHeight,this.event.clientX-this.el.clientWidth))},updateEvent:function(a){this.event=a;for(var b in this.willScroll)this.willScroll[b]&&!this.scrollInvoked[b]&&this.move(b)},move:function(a){var b=this;this.scrollInvoked[a]=window.setInterval(function(){switch(a){case "top":window.scrollTo(window.pageXOffset,window.pageYOffset-b.speed);break;case "left":window.scrollTo(window.pageXOffset-
b.speed,window.pageYOffset);break;case "bottom":window.scrollTo(window.pageXOffset,window.pageYOffset+b.speed);break;case "right":window.scrollTo(window.pageXOffset+b.speed,window.pageYOffset)}b.willScroll[a]&&b.active||(clearInterval(b.scrollInvoked[a]),b.scrollInvoked[a]=!1)},16)}};return{set:function(a,b){a.lmdd||(K(a),a.lmdd=!0,a.lmddOptions=S(Y,b),a.addEventListener("mousedown",w,!1),document.addEventListener("drag",y,!1),document.addEventListener("dragstart",y,!1),window.addEventListener("touchstart",
v),window.addEventListener("touchmove",v,{passive:!1}),window.addEventListener("touchend",v))},unset:function(a){a.lmdd&&(a.removeEventListener("mousedown",w,!1),a.lmdd=!1,delete a.lmddOptions)},kill:function(){document.removeEventListener("drag",y,!1);document.removeEventListener("dragstart",y,!1);window.removeEventListener("touchstart",v);window.removeEventListener("touchmove",v,{passive:!1});window.removeEventListener("touchend",v)}}}();

/**
 * jQuery Part (structure)
 */
jQuery( document ).ready( function( $ ){
	/**
	 * OCreate tabs for the options.
	 * @since 2.0.0
	 * @author Geoffrey Crofte
	 */
	function juiz_maybe_build_tabs() {
		var $titles = $('.jsps-main-body h2'),
			i = 0,
			tabs = '';

		// Check if tabs don't exist.
		if ( $('#jsps-all-tabs').length === 0 ) {
			$titles.each(function(){
				var $siblings = $(this).nextUntil('h2'),
					curri = jsps_get_current_tab_selected();

				i++;
				
				// Add a new future tab.
				tabs = tabs + '<li><a data-index="' + i + '" role="tab" href="#jsps-tab-container-' + i + '" aria-controls="jsps-tab-container-' + i + '" aria-selected="' + ( i ===curri ? 'true' : 'false' ) + '" tabindex="-1">' + $(this).text() + '</a></li>';

				// Put the h2 into a tab container.
				$(this).wrap('<div role="tabpanel" tabindex="0" id="jsps-tab-container-' + i + '" class="jsps-tab-container"' + ( i ===curri ? '' : ' hidden="hidden"' ) + '>');
				// Put its siblings into that container.
				$('#jsps-tab-container-' + i ).append( $siblings );
			});

			// Regroup all the tab containers
			$('[id^="jsps-tab-container-"]').wrapAll('<div id="jsps-all-tabs">');

			// Build the tabs menu
			$('#jsps-all-tabs').before('<div class="jsps-tabs"><ul role="tablist"></ul></div>');
			$('.jsps-tabs ul').append( tabs );

			// Bind all tabs.
			$('.jsps-tabs a').on('click', function(){
				var $_this = $(this),
					tindex = $_this.data('index');

				// Attributes the right current class.
				$('.jsps-tabs a').removeClass('jsps-is-current').attr('aria-selected', 'false');
				$_this.addClass('jsps-is-current').attr('aria-selected', 'true');

				// Show the right tab content
				$('[id^="jsps-tab-container-"]').removeClass('jsps-is-current').attr('hidden', 'hidden');
				$('#jsps-tab-container-' + tindex).addClass('jsps-is-current').removeAttr('hidden')

				jsps_set_current_tab( tindex );

				// Do something for the hidden togglable things
				if ( $('#jsps-tab-container-' + tindex + ' #jsps-nw-list').length ) { 
					show_hide_incoming_networks();
				}

				return false;
			});

			// Init draggable stuff
			let draggableElement = document.getElementById('jsps-draggable-networks');
			var networkOrder = [];

			draggableElement.querySelectorAll('.juiz_sps_options_p').forEach(function(el){
				networkOrder.push(el.dataset.network);
			});

			var lastOrder = networkOrder;

			lmdd.set(draggableElement, {
				containerClass: 'juiz-sps-squared-options',
				draggableItemClass: 'juiz_sps_options_p',
				handleClass: 'juiz-sps-handle',
				positionDelay: false,
			});

			// Observer to check when dragging happens.
			var observer = new MutationObserver(function (event) {

				// When hidden-layer is removed, dragEvent just ended
				if ( event[0].target.classList.contains('hidden-layer') === false ) {
					var isDiff = false;
					networkOrder = [];

					draggableElement.querySelectorAll('.juiz_sps_options_p').forEach(function(el){
						networkOrder.push(el.dataset.network);
					});

					// Check if there is any change of order.
					for (i = 0; i < lastOrder.length; i++) {
						if ( lastOrder[i] !== networkOrder[i] ) {
							isDiff = true;
							break;
						}
					}

					// If there is, do the AJAX request.
					if ( isDiff ) {

						// To avoid resquet being made is no change.
						lastOrder = networkOrder;

						// AJAX Post actions on ordering stuff
						let orderdata = {
							'action': jsps.networkOrderAction,
							'nonce' : jsps.networkOrderNonce,
							'order' : networkOrder,
						};

						$.post(jsps.ajaxurl, orderdata, function(response) {
							var notif = document.querySelector('.juiz-sps-notif'),
								icon = document.querySelector('.juiz-sps-notif-icon i'),
								text = document.querySelector('.juiz-sps-notif-text'),
								delay = 3000;

							notif.classList.add('is-visible');
							notif.setAttribute('aria-live', 'assertive');
							notif.setAttribute('role', 'alert');

							if ( response.success === true ) {
								notif.classList.remove('is-error');
								notif.classList.add('is-success');
								icon.classList.remove('dashicons-warning');
								icon.classList.add('dashicons-yes-alt');
								text.innerHTML = response.data.message;
							} else {
								notif.classList.remove('is-success');
								notif.classList.add('is-error');
								icon.classList.remove('dashicons-yes-alt');
								icon.classList.add('dashicons-warning');
								text.innerHTML = response.data.message;
								delay = 6000;
							}

							// Remove notification after a delay
							setTimeout(function(){
								notif.classList.remove('is-visible');
								notif.removeAttribute('aria-live');
								notif.removeAttribute('role');
							}, delay);

						});
					} else {
						console.info('JuizSPS: No change baby!');
					}
				}
			});

			observer.observe(draggableElement, {
				attributes: true, 
				attributeFilter: ['class'],
				childList: false, 
				characterData: false
			});

		} else {
			console.log('unbuild tabs');
		}
	}

	/**
	 * Return the current tab index.
	 * @since  2.0.0
	 */
	function jsps_get_current_tab_selected() {
		if ( typeof( Storage ) === "undefined") {
			return 1;
		}

		if ( window.localStorage.getItem('jsps-current-tab') ) {
			return parseInt( window.localStorage.getItem('jsps-current-tab') );
		}

		return 1;
	}

	/**
	 * Set the current tab index.
	 * @since  2.0.0
	 */
	function jsps_set_current_tab( value ) {
		if ( typeof( Storage ) !== "undefined") {
			window.localStorage.setItem('jsps-current-tab', value);
		}
	}

	/**
	 * False Placeholder removal on Promo banner
	 */
	if ( document.querySelector('#mce-EMAIL') ) {
		var label = document.querySelector('[for="mce-EMAIL"] span'),
			input = document.querySelector('#mce-EMAIL');

		input.addEventListener('keyup', function(e){
			if ( input.value.length === 0 ) {
				label.style.display = 'block';
			}
			if ( input.value.length > 0 ) {
				label.style.display = 'none';
			}
		});
	}

	/**
	 * ShareAPI Alert. Modal.
	 */
	let shareapi = document.getElementById('jsps_network_selection_shareapi');
	let lastFocused;
	let close_button = document.getElementById('nobs-modal-close');

	if ( close_button ) {
		close_button.addEventListener('click', function(){
			nobs_close_modal();
		});
	}

	const nobs_close_modal = function() {
		modal = document.querySelector('.nobs-modal-container');

		modal.classList.remove('is-modal-visible');
		modal.setAttribute('aria-hidden', 'true');
		lastFocused.focus();
	};

	if ( shareapi ) {
		let modal = document.querySelector('.nobs-modal-container');
		shareapi.addEventListener('change', function(){
			if ( this.checked ) {
				lastFocused = document.activeElement;
				modal.querySelector('.nobs-modal-icon').innerHTML = jsps.modalShareAPIIcon;
				modal.querySelector('.nobs-modal-title').innerHTML = jsps.modalShareAPITitle;
				modal.querySelector('.nobs-modal-content').innerHTML = jsps.modalShareAPIContent;
				modal.classList.add('is-modal-visible');
				modal.classList.add('has-modal-actions');
				modal.classList.add('has-close-action');
				modal.setAttribute('aria-hidden', 'false');
				modal.focus();
			}
		});
	}

	document.addEventListener('keydown', function(e){
		if ( e.key === 'Escape' ) {
			if ( document.querySelector('.nobs-modal-container') && document.querySelector('.nobs-modal-container').classList.contains('is-modal-visible') ) {
				nobs_close_modal();
			}
		}
	})
	

	/**
	 * Show/Hide incoming list of networks.
	 */
	const show_btn = document.querySelector('.jsps-show-networks');
	const showing_list = document.getElementById('jsps-nw-list');

	const show_hide_incoming_networks = function() {
		if ( show_btn ) {
			showing_list.classList.remove( 'is-hidden' );

			let sl_height = showing_list.offsetHeight;

			showing_list.classList.add( 'is-hidden' );
			
			let open_close_list = function(e) {
				e.preventDefault();

				if ( showing_list.classList.contains( 'is-hidden' ) ) {
					show_btn.setAttribute( 'aria-expanded', 'true');
					setTimeout(function(){
						showing_list.classList.remove( 'is-hidden' );
						showing_list.scrollIntoView({behavior: "smooth", block: "center"})
					}, 275);
				} else {
					show_btn.setAttribute( 'aria-expanded', 'false');
					showing_list.classList.add( 'is-hidden' );;
				}
				return false;
			};

			show_btn.setAttribute( 'aria-controls', show_btn.href.split('#')[1] );
			show_btn.setAttribute( 'aria-expanded', 'false' );

			showing_list.setAttribute( 'style', '--height:' + sl_height + 'px' );


			if ( ! show_btn.getAttribute('haslistener') ) {
				show_btn.addEventListener( 'click', open_close_list );
				show_btn.setAttribute( 'haslistener', 'true' );
			}
		}
	}

	// Init stuff
	juiz_maybe_build_tabs();
	show_hide_incoming_networks();

	$(window).on('resize', function(){
		window.requestAnimationFrame( juiz_maybe_build_tabs );
	});

	/**
	 * Display Skin List
	 */
	if ( document.querySelector('.jsps-tabs') ) {
		const skin_shop_tab = document.querySelector('.jsps-tabs li:last-child');
		const do_stuff = function(){
			// AJAX Post action.
			let orderdata = {
				'action': jsps.skinLoadingAction,
				'nonce' : jsps.skinLoadingNonce
			};

			$.post(jsps.ajaxurl, orderdata, function(response) {
				if ( response.success === true && response.data.html ) {
					document.getElementById('jsps-skin-list-drop').innerHTML = response.data.html;
					console.info(response.data);
				}
			});
		};

		// if current tab, do the request directly.
		if ( skin_shop_tab.querySelector('[aria-selected="true"]') ) {
			do_stuff();
		} else {
			// else do the request on click.
			skin_shop_tab.querySelector('a').addEventListener('click', do_stuff);
		}

	}

	/**
	 * Other scripts I don't remember what I do with.
	 * @since  1.0
	 * @lastupdate 2.0.0
	 */

	$('input[disabled]').closest('tr').addClass('juiz_disabled');
	$('#jsps_counter_both').closest('tr').addClass('juiz_hide juiz_counter_option');

	$('input[name="juiz_SPS_settings[juiz_sps_counter]"]').on('change', juiz_on_change_visibility);

	function juiz_on_change_visibility() {
		if ( $('#jsps_counter_y').filter(':checked').length == 1) {
			$('.juiz_counter_option').fadeIn(300);
		}
		else {
			$('.juiz_counter_option').fadeOut(300);
		}
	}
	juiz_on_change_visibility();
});
