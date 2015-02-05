<?php
/**
 * Template Name: Home Page
 * @package SASSembly
 */

get_header(); ?>

	<div id="home" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php foreach (get_categories(array('order' => 'DESC')) as $cat) : ?>
				<article class="home-box">
					<header class="home-title">
						<a href="<?php echo get_category_link($cat->term_id); ?>">
							<h2 style="padding:5px;color:#fff;font-size:1em;margin:0;"><?php echo $cat->cat_name; ?></h2>
						</a>
					</header>

					<div class="entry-content">
						<img src="<?php echo get_template_directory_uri(); ?>/img/<?php echo $cat->term_id; ?>.jpg" class="alignright" align="right">
						<?php echo category_description($cat->term_id); ?>
					</div><!-- .entry-content -->
				 </article>
			<?php endforeach; ?>
				<article class="home-box">
					<header class="home-title">
						<a href="/tape-services/">
							<h2 style="padding:5px;color:#fff;font-size:1em;margin:0;">
								Specialty Tape &amp; Printing Services
							</h2>
						</a>
					</header>
					<div class="entry-content">
						<img src="<?php echo get_template_directory_uri(); ?>/img/specialty_tape_services.gif" class="alignright" align="right">
						More than just a distributor of adhesive tape products, Carolina Tape and Supply Corporation offers a wide variety of Specialty Tape Services, and Printed Tapes. We can Print in 1 to 3 colors on tapes widths 1/4" to 6" x up to 1000 yds.
						<ul>
							<li>Die Cut Tapes</li>
							<li>Kiss / Butt Cutting</li>
							<li>Tape Slitting</li>
							<li>Laminating</li>
							<li>1-3 Color Tape Printing</li>
						</ul>
					</div>
				</article>
				<article class="home-box">
					<header class="home-title">
						<a href="/manufacturing-printing-supplies/">
							<h2 style="padding:5px;color:#fff;font-size:1em;margin:0;">
								Furniture Manufacturing &amp; Packaging Supplies
							</h2>
						</a>
					</header>
					<div class="entry-content">
						<img src="<?php echo get_template_directory_uri(); ?>/img/mps.jpg" class="alignright" align="right">
						Carolina Tape and Supply Corporation offers a wide variety of Furniture Manufacturer's Supplies, Packaging Products and Shipping Room Supplies.
						<ul>
							<li>Grommets</li>
							<li>Swatch Rings (2" to 10")</li>
							<li>Swatch Handles</li>
							<li>Klinch Its and Tools</li>
							<li>Nails</li>
							<li>etc...</li>
						</ul>
					</div>
				</article>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>