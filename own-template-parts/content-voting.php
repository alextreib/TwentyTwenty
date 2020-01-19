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
<!-- <script type="text/javascript" src="https://stockvoting.net/wp-content/themes/twentytwenty/js/charts.js"> </script> -->
<script type="text/javascript" src="https://stockvoting.net/wp-content/themes/twentytwenty/own-template-parts/third-party/canvas-gauges/gauge.min.js"></script>
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->

<style>
	/* * {
  box-sizing: border-box;
} */

	/* Create two equal columns that floats next to each other */
	.column-voting {
		float: left;
		width: 33%;
		padding: 10px;
		/* border: 1px solid black; */
	}

	/* Clear floats after the columns */
	.row:after {
		content: "";
		display: table;
		clear: both;
	}
</style>


<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<?php

	get_template_part('template-parts/entry-header');

	if (!is_search()) {
		get_template_part('template-parts/featured-image');
	}

	?>


	<div class="post-inner <?php echo is_page_template('templates/template-full-width.php') ? '' : 'thin'; ?> ">

		<div class="entry-content">

			<?php
			if (is_search() || !is_singular() && 'summary' === get_theme_mod('blog_content', 'full')) {
				the_excerpt();
			} else {
				the_content(__('Continue reading', 'twentytwenty'));
			}
			?>

		</div><!-- .entry-content -->

	</div><!-- .post-inner -->

	<div class="section-inner">
		<div class="row">
			<a href="https://stockvoting.net/tag/tesla/">
				<h1>Tesla</h1>
			</a>
			<div class="column-voting">
				<!-- First column=Overview -->
				Overview
				Total prognosis

			</div>


			<div class="column-voting" id="second-column">
				<?php
				include("gauge.html");
				?>

				<script>
					// var myArr = ["Audi", "BMW", "Ford", "Honda", "Jaguar", "Nissan"];

					function showContent() {
						var template = document.getElementById("radial-gauge-template");
						var second_col = document.getElementById("second-column");
						var template_inst = template.content.cloneNode(true);

						second_col.append(template_inst);
						// second_col.parentNode.insertBefore(template_inst, second_col.nextSibling);


						// var temp, item, a, i;
						// // Get the DIV element from the template:
						// item = template_inst.querySelector("div");
						// // For each item in the array:
						// for (i = 0; i < myArr.length; i++) {
						// 	// Create a new node, based on the template:
						// 	a = document.importNode(item, true);
						// 	// Add data from the array:
						// 	// a.textContent += myArr[i];
						// 	// Append the new node wherever you like:
						// 	second_col.parentNode.insertBefore(a, second_col.nextSibling);

						// }
					}
				</script>




			</div>

			<div class="column-voting">
				<!-- Third column = Your vote -->
				<!-- todo: Change to int type and value to default value -->
				<h2>Your vote</h2>

				<form name="vote_form" method="post">
					<input id="voting_input" type="text" name="voting_number" value="123" />
					<!-- <input type="button" name="vote_button" onclick="showContent()" value="Vote" /> -->
					<input type="button" name="vote_button" onclick="buttonfire(voting_input.value)" value="Vote" />
				</form>
			</div>
		</div>
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


<script>
	document.addEventListener("DOMContentLoaded", theDomHasLoaded, false);
	window.addEventListener("load", pageFullyLoaded, false);

	function theDomHasLoaded(e) {
		alert("domloaded");
		// do something
	}

	function pageFullyLoaded(e) {
		buildtemplates();

		// do something again
	}



	if (!Array.prototype.forEach) {
		Array.prototype.forEach = function(cb) {
			var i = 0,
				s = this.length;
			for (; i < s; i++) {
				cb && cb(this[i], i, this);
			}
		}
	}

	document.fonts && document.fonts.forEach(function(font) {
		font.loaded.then(function() {
			if (font.family.match(/Led/)) {
				document.gauges.forEach(function(gauge) {
					gauge.update();
				});
			}
		});
	});

	var timers = [];

	function animateGauges() {
		document.gauges.forEach(function(gauge) {
			timers.push(setInterval(function() {
				gauge.value = Math.random() *
					(gauge.options.maxValue - gauge.options.minValue) / 4 +
					gauge.options.minValue / 4;
			}, gauge.animation.duration + 50));
		});
	}

	function stopGaugesAnimation() {
		timers.forEach(function(timer) {
			clearInterval(timer);
		});
	}
</script>