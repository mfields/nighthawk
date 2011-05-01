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
 * <li>Prints summary if available.</li>
 * <li>Runs the WordPress Loop.</li>
 * <li>Includes loop.php.</li>
 * <li>Includes comments.php.</li>
 * <li>Includes footer.php</li>
 * </ol>
 * 
 * If you notice, Nighthawk contains no other complete template
 * files, only parts. This is by design and will hopefully make
 * customizing a bit easier. If you find that it is necessary to
 * create a custom template it is suggested that you copy the code
 * below into a new file (ideally in a child theme), give it a name
 * that is recoginized by WordPress template hierarchy and then make
 * the necessary modifications.
 * 
 * This file closes all html tags that it opens.
 * 
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404' );
}

get_header();

?>

<div id="content" class="contain">

<div id="intro">
	<?php nighthawk_summary( '<div id="summary">', '</div>' ); ?>
	<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ); ?>
</div>

<?php get_template_part( 'loop' ); ?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer(); ?>