<?php

/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<div class="post-inner ">
	<div class="category-heading h2">Performance Overview </div>
		<div class="category-performance">
			<h2>
				<?php 
				// echo single_cat_title( '', false );
				echo do_shortcode('[stock_ticker symbols="' . single_cat_title( '', false ) . '" show="name" static="1"]');
				echo "<br>"
				?> 
			</h2>
		</div><!-- .entry-content -->

	</div><!-- .post-inner -->

	<div class="section-inner">

	</div><!-- .section-inner -->

	<?php

	if (is_single()) {

		get_template_part('template-parts/navigation');
	}

	/**
	 *  Output comments wrapper if it's a post, or if comments are open,
	 * or if there's a comment number – and check for password.
	 * */
	if ((is_single() || is_page()) && (comments_open() || get_comments_number()) && !post_password_required()) {
	?>

		<div class="comments-wrapper section-inner">

			<?php comments_template(); ?>

		</div><!-- .comments-wrapper -->

	<?php
	}
	?>

</article><!-- .post -->