<?php
/**
 * Template Name: Product Selector
 */
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

<?php if(function_exists('wp_custom_fields_search')) 
	wp_custom_fields_search(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>