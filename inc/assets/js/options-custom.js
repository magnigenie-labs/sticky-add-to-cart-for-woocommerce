/**
 * Custom scripts needed for the colorpicker, image button selectors,
 * and navigation tabs.
 */

jQuery(document).ready(function($) {

	$('.wsac_multiselect').find('select').select2();

	$(".ajax_products").find('.wsac-select2').select2({
		minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '3',
    ajax: {
    	url					: wsacOption.ajaxurl,
     	dataType		: 'json',
     	quietMillis : 250,
     	data : function (params) {
      	return {
        	q: params.term, // search query
        	action: 'wsac_ajax_products' // AJAX action for admin-ajax.php
      	};
    	},
        
      processResults: function( data ) {
				var terms = [];
					if ( data ) {
						$.each( data, function( id, text ) {
							terms.push( { id: id, text: text } );
						});
					}
					return {
						results: terms
					};
			},
			cache: true
		},
	});

	// Loads the color pickers
	$('.of-color').wpColorPicker();

	$('[data-toggle="tooltip"]').tooltip();

	// Image Options
	$('.of-radio-img-img').click( function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	if( $('#wsac_optionsframework').length ) {
		var SettingsHeight = $('#wsac_optionsframework').find('#options-group-1').innerHeight();
		if( SettingsHeight > 0 ) {
			//$('#wsac_optionsframework-wrap').find('.queries-holder').height(SettingsHeight+'px');
		}
	}

	// Loads tabbed sections if they exist
	if ( $( '.nav-tab-wrapper').length > 0 ) {
		options_framework_tabs();
	}

	function options_framework_tabs() {
		var $group = $('.group'),
				$navtabs = $('.nav-tab-wrapper a'),
				active_tab = '';

		// Hides all the .group sections to start
		$group.hide();

		// Find if a selected tab is saved in localStorage
		if (  typeof( localStorage ) != 'undefined'  ) {
			active_tab = localStorage.getItem( 'active_tab' );
		}

		// If active tab is saved and exists, load it's .group
		if ( active_tab != '' && $( active_tab ).length ) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').addClass('nav-tab-active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		// Bind tabs clicks
		$navtabs.click( function( e ) {
			
			e.preventDefault();

			// Remove active class from all tabs
			$navtabs.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();

			if (typeof( localStorage ) != 'undefined') {
				localStorage.setItem('active_tab', $(this).attr('href'));
			}

			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();
		});
	}

	var ProHtml = '<div class="wsac-pro-block"><span><a target="_blank" href="'+wsacOption.pro_link+'">Upgrade to PRO to use this option</a></span></div>';
	$('#wsac_optionsframework .pro-feature').append(ProHtml);
	
	$('.pro-feature').hover(function() {
		$(this).find('.wsac-pro-block').toggleClass('show');
	});

	

});