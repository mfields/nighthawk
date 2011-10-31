jQuery( document ) .ready( function( $ ) {

	var config = {
		container   : '#dropdown-widgets',
		dropdowns   : '.dropdown',
		triggers    : '.widget-title',
	//	maxBoxWidth : 765
		maxBoxWidth : 400
	};

	var widgets = $( config.container );
	var parent  = widgets.parent();
	var titles  = widgets.find( config.triggers );
	var boxen   = widgets.find( config.dropdowns );
	var active  = null;
	var height  = 0;
	var origin  = {
		top  : -1599980,
		left : -1599980
	};

	var container = $( document.createElement( 'div' ) ).attr( 'id', 'menu' );
	var dropdowns = $( document.createElement( 'div' ) ).attr( 'id', 'dropdowns' );
	var triggers  = $( document.createElement( 'div' ) ).attr( 'id', 'triggers' );

	hideAllBoxen();

	$( document ).bind( 'dropdownsLoaded', function() {
		widgets.remove();
		parent.prepend( container );
		container.append( dropdowns ).append( triggers );
		height = container.outerHeight() - parseInt( container.css( 'border-top-width' ) );
	} );

	titles.each( function ( i, title ) {
		var title = $( title );

		if ( '' === $.trim( title.text() ) ) {
			return;
		}

		var boxId = 'dropdown-' + i;

		var link = $( document.createElement( 'a' ) )
			.text( title.text() )
			.attr( 'href', '' )
			.attr( 'data-for', boxId )
			.addClass( 'trigger' );

		$( document.createElement( 'li' ) ).html( link ).appendTo( triggers );

		title.parent().find( 'a' ).each( function( i, e ) {
			$( e ).removeAttr( 'title' );
		} );

		title.parent()
			.attr( 'id', boxId )
			.appendTo( dropdowns )
			.find( config.triggers )
			.remove();

		if ( i + 1 === titles.length ) {
			$( document ).trigger( 'dropdownsLoaded' );
		}
	} );

	$( window ).bind( 'resize', function ( e ) {
		height = container.outerHeight() - parseInt( container.css( 'border-top-width' ) );

		if ( null === active ) {
			return;
		}

		/*
		 * Stick the active box to the bottom of the container.
		 */
		active.box.css( { top : height } );

		/*
		 * The active box should not be cut-off when browser window shrinks.
		 */
		var boxPos = active.box.position();
		var triggerPos = active.trigger.position();
		var rightEdge = boxPos.left + active.box.outerWidth();

		if ( rightEdge > parent.innerWidth() ) {
			active.box.css( {
				left  : 'auto',
				right : 0
			} );
		}
		else if ( Math.floor( triggerPos.left ) < boxPos.left || ( 0 == boxPos.left && 0 != triggerPos.left ) ) {
			active.box.css( {
				left  : Math.floor( triggerPos.left ),
				right : 'auto'
			} );
		}
	} );

	$( document ).click( function( e ) {

		/*
		 * Primary mouse button pressed.
		 * @todo See how this works with touch screen devices.
		 */
		if ( 1 != e.which ) {
			return;
		}

		var link = $( e.target );

		if ( link.hasClass( 'trigger' ) ) {
			var box = $( '#' + link.attr( 'data-for' ) );
			var boxPosition = box.position();
			var linkPosition = link.position();

			/*
			 * Each time a link is clicked we need to
			 * reposition all of the boxen. This enables
			 * the box currently on display to be hidden
			 * when a user is clicking from link to link.
			 */
			hideAllBoxen();

			/*
			 * Toggle effect.
			 * This enables a single link to be clicked
			 * once to show a box and then clicked again
			 * to hide the box.
			 */
			if ( origin.top !== boxPosition.top && origin.left !== boxPosition.left ) {
				hideAllBoxen();
				return false;
			}

			var css = {
				top : height
			};

			box.rightEdge = Math.ceil( box.outerWidth() + linkPosition.left );

			if ( box.rightEdge > dropdowns.innerWidth() ) {
				css.left = Math.ceil( dropdowns.outerWidth() - box.outerWidth() );
			}
			else {
				css.left = Math.ceil( parseInt( linkPosition.left ) );
			}

			/*
			 * Show box.
			 * Positions the requested box directly under
			 * the dropdowns div, left-aligned with the link.
			 */
			box.css( css );
			link.addClass( 'active' );

			if ( box.hasClass( 'widget_search' ) ) {
				box.find( '[name]=s' ).focus();
			}


			active = {
				box     : box,
				trigger : link
			};

			return false;
		}

		/*
		 * All boxen are hidden.
		 * There is no need to continue.
		 */
		if ( null === active ) {
			return;
		}

		/*
		 * A box is being displayed.
		 * Somewhere outside of the box was clicked.
		 * We need to hide all of the boxen.
		 */
		if ( 0 === link.closest( config.dropdowns ).length ) {
			hideAllBoxen();
			return;
		}

	} );

	function hideAllBoxen() {
		boxen.each( function( i, e ) {
			$( e ).css( origin );
		} );

		if ( null !== active ) {
			active.trigger.removeClass( 'active' );
		}

		active = null;
	}
} );