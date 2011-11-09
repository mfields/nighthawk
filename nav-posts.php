<?php
/**
 * Navigation Template for Pages of Posts.
 *
 * Prints navigation for all templates that display
 * queries that produce multiple posts. This file
 * should not be included in templates degined to
 * display single posts.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

$older = get_next_posts_link( __( 'Older', 'nighthawk' ) );
if ( ! empty( $older ) )
	$older = '<div class="nav-paged timeline-regress">' . $older . '</div>';

$newer = get_previous_posts_link( __( 'Newer', 'nighthawk' ) );
if ( ! empty( $newer ) )
	$newer =  '<div class="nav-paged timeline-progress">' . $newer . '</div>';
?>

<?php if ( ! empty( $older ) || ! empty( $newer ) ) : ?>
	<nav id="page-footer" class="paged-navigation contain">
		<h1 class="assistive-text"><?php _e( 'Posts navigation', 'pyrmont' ); ?></h1>
		<?php echo $older; ?>
		<?php echo $newer; ?>
	</nav>
<?php endif; ?>