<?php
/**
 * Default template
 *
 * This file is responsible for creating all public views.
 * It uses WordPress core functions as well as custom actions
 * defined in functions.php and includes various other template
 * parts from this theme. Here's a breakdown:
 * 
 * <ol>
 * <li>Includes header.php.</li>
 * <li>Prints view title if available.</li>
 * <li>Prints view description if available.</li>
 * <li>Runs the WordPress Loop.</li>
 * <li>Includes loop.php.</li>
 * <li>Includes comments.php.</li>
 * <li>Includes sidebar.php</li>
 * <li>Includes footer.php</li>
 * </ol>
 * 
 * If you notice, Ghostbird contains no other complete template
 * files, only parts. This is by design and will hopefully make
 * customizing a bit easier. If you find that it is necessary to
 * create a custom template it is suggested that you copy the code
 * below into a new file (ideally in a child theme), give it a name
 * that is recoginized by WordPress template hierarchy and then make
 * the necessary modifications.
 * 
 * This file closes all html tags that it opens.
 * 
 * @package      Ghostbird
 * @subpackage   Templates
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

get_header();

?>

<div id="content">

<div id="intro"><?php
	ghostbird_title( '<h1>', '</h1>' );
	ghostbird_byline( '<p id="byline">', '</p>' );
	ghostbird_description( '<div id="description">', '</div>' );
	ghostbird_intro_meta( '<div id="intro-meta">', '</div>' );
?>

</div>


<?php

if ( ! have_posts() && ! is_search() ) {
	print '<div class="entry">' . __( 'Sorry, no posts were found', 'ghostbird' ) . '</div>';
}
else {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'loop' );
	}
}

do_action( 'ghostbird_author' );

comments_template( '', true );

?>
</div><!--content-->

<div class="clear"></div>

<?php get_footer(); ?>