jQuery(function( $ ) {
	'use strict';

	/* -----------------------------------------
	Responsive Menus Init with mmenu
	----------------------------------------- */
	var $mainNav   = $( '.navigation-main' );
	var $mobileNav = $( '#mobilemenu' );

	$mainNav.clone().removeAttr( 'id' ).removeClass().appendTo( $mobileNav );
	$mobileNav.find( 'li' ).removeAttr( 'id' );

	$mobileNav.mmenu({
		offCanvas: {
			position: 'top',
			zposition: 'front'
		},
		"autoHeight": true,
		"navbars": [
			{
				"position": "top",
				"content": [
					"prev",
					"title",
					"close"
				]
			}
		]
	});

	/* -----------------------------------------
	Responsive Videos with fitVids
	----------------------------------------- */
	$( 'body' ).fitVids();

	/* -----------------------------------------
	Image Lightbox
	----------------------------------------- */
	$( '.ci-lightbox' ).magnificPopup({
		type: 'image',
		mainClass: 'mfp-with-zoom',
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true
		}
	} );

	/* -----------------------------------------
	First letter content styling
	----------------------------------------- */
	(function () {
		var $entries = $('.entry');
		var $content = $('.entry-content');
		var lineHeight;

		if (!$content.length) {
			return;
		}

		lineHeight = window.getComputedStyle($content.get(0), null).getPropertyValue('line-height');

		$entries.each(function () {
			var $this = $(this);
			var $p0 = $this.find('.entry-content').find('p:first-of-type').first();

			// Only apply stylized first letter if the first
			// paragraph spans 4 lines or more.
			if (!$p0.length || $p0.height() < 4 * parseInt(lineHeight, 10)) {
				return;
			}

			var $letter = $('<span />', {
				class: 'letter-stylized',
				text: $p0.text().charAt(0).toUpperCase(),
			});
			$p0.prepend($letter);
		});
	})();

	/* -----------------------------------------
	Sticky Header
	----------------------------------------- */
	if ($(window).width() > 991) {
		$('.header-sticky').stick_in_parent({
			offset_top: -1
		});
	}


	/* -----------------------------------------
	Datepickers
	----------------------------------------- */
	// The datepickers must output the format yy/mm/dd otherwise PHP's checkdate() fails.
	// Makes sure arrival date is not after departure date, and vice versa.


	$( ".datepicker[name='ci-arrive']" ).datepicker({
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        firstDay: 1 ,
		dateFormat: 'dd/mm/yy',
		minDate: new Date(),
        beforeShowDay: function(date){
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [ dates.indexOf(string) == -1 ]
        },
		onSelect: function(dateText, dateObj){
            var minDate = new Date( dateObj.selectedYear, dateObj.selectedMonth, dateObj.selectedDay );
            minDate.setDate(minDate.getDate() + 1);
            $( ".datepicker[name='depart']" ).datepicker('option', 'minDate', minDate );
        }
	});

	$( ".datepicker[name='ci-depart']" ).datepicker({
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        firstDay: 1 ,
		dateFormat: 'dd/mm/yy',
		minDate: new Date(),
        beforeShowDay: function(date){
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [ dates.indexOf(string) == -1 ]
        },
		onSelect: function(dateText, dateObj) {
			//var maxDate = new Date(dateText);
			var maxDate = new Date( dateObj.selectedYear, dateObj.selectedMonth, dateObj.selectedDay );
			maxDate.setDate(maxDate.getDate() - 1);
			$( ".datepicker[name='arrive']" ).datepicker('option', 'maxDate', maxDate );
		}
	});

	/* -----------------------------------------
	Slideshow
	----------------------------------------- */
	var $slideshow = $('.page-slideshow');

	if ($slideshow.length) {
		var animation = $slideshow.data('animation'); // fade or slide
		var autoplay = $slideshow.data('autoplay');
		var autoplaySpeed = $slideshow.data('autoplayspeed'); // slide transition speed
		var speed = $slideshow.data( 'speed' ); // slide/fade animation speed

		$slideshow.slick({
			fade: animation === 'fade',
			autoplay: !!autoplay,
			autoplaySpeed: autoplaySpeed,
			speed: speed,
			dots: true,
			prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
			nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>'
		});
	}

	$( window ).on( 'load', function() {
		/* -----------------------------------------
		MatchHeight
		----------------------------------------- */
		$( '.row-equal' ).find( '[class^="col"]' ).matchHeight();

		/* -----------------------------------------
		 Video Backgrounds
		 ----------------------------------------- */
		var $videoWrap = $('.ci-video-wrap');
		var $videoBg = $('.ci-video-background');

		// YouTube videos
		function onYouTubeAPIReady() {
			if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
				return setTimeout(onYouTubeAPIReady, 333);
			}

			var ytPlayer = new YT.Player('youtube-vid', {
				videoId: $videoBg.data('video-id'),
				playerVars: {
					autoplay: 1,
					controls: 0,
					showinfo: 0,
					modestbranding: 1,
					loop: 1,
					playlist: $videoBg.data('video-id'),
					fs: 0,
					cc_load_policy: 0,
					iv_load_policy: 3,
					autohide: 0
				},
				events: {
					onReady: function (event) {
						event.target.mute();
					},
					onStateChange: function (event) {
						if (YT.PlayerState.PLAYING) {
							$videoWrap.addClass('visible');
						}
					}
				}
			});
		}

		function onVimeoAPIReady() {
			if (typeof Vimeo === 'undefined' || typeof Vimeo.Player === 'undefined') {
				return setTimeout(onVimeoAPIReady, 333);
			}

			var player = new Vimeo.Player($videoBg, {
				id: $videoBg.data('video-id'),
				loop: true,
				autoplay: true,
				byline: false,
				title: false,
			});

			player.setVolume(0);

			// Cuepoints seem to be the best way to determine
			// if the video is actually playing or not
			player.addCuePoint(.1).catch(function () {
				$videoWrap.addClass('visible');
			});

			player.on('cuepoint', function () {
				$videoWrap.addClass('visible');
			});
		}

		var videoType = $videoBg.data('video-type');

		if ($videoBg.length && window.innerWidth > 1080) {
			var tag = document.createElement('script');
			var firstScript = $('script');

			if (videoType === 'youtube') {
				tag.src = 'https://www.youtube.com/player_api';
				firstScript.parent().prepend(tag);
				onYouTubeAPIReady();
			} else if (videoType === 'vimeo') {
				tag.src = 'https://player.vimeo.com/api/player.js';
				firstScript.parent().prepend(tag);
				onVimeoAPIReady();
			}
		}
	});
});
