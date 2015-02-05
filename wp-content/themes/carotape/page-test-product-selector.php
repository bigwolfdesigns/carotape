<?php
/**
 * Template Name: Product Selector
 */
get_header();
$cats = get_categories();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="DropDownField">
			<div class="searchform-param">
				<label class="searchform-label">Category</label>
				<span class="searchform-input-wrapper">
					<select id="cat">
						<option value="">Please Select...</option>
						<?php
						foreach($cats as $cat_obj){
							?><option value="<?php echo $cat_obj->term_id ?>"><?php echo $cat_obj->name ?></option><?php
						}
						?>
					</select>
				</span>
			</div>
		</div>
		<div class="searchform-params">
			<div class="DropDownField">
				<div class="searchform-param">
					<label class="searchform-label">Adhesive</label>
					<span class="searchform-input-wrapper">
						<select id="adhesive"></select>
					</span>
				</div>
			</div>
			<div class="DropDownField">
				<div class="searchform-param">
					<label class="searchform-label">Substrate</label>
					<span class="searchform-input-wrapper">
						<select id="substrate"></select>
					</span>
				</div>
			</div>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
