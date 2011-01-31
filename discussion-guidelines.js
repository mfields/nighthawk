var toggle  = document.getElementById( 'discussion-guidelines-toggle' );
var widget  = document.getElementById( 'discussion-guidelines-widgets' );
var comment = document.getElementById( 'comment' );
if ( 'object' == typeof toggle && 'object' == typeof widget && 'object' == typeof comment ) {
	widget.style.display = 'none';
	toggle.onmouseover = function() {
		toggle.style.cursor = 'pointer';
	};
	toggle.onclick = function() {
		widget.style.display = ( 'none' == widget.style.display ) ? 'block' : 'none';
	};
	comment.onfocus = function() {
		widget.style.display = 'block';
	};
}