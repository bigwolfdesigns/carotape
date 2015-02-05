<?php
/**
 * SASSembly functions and definitions
 *
 * @package SASSembly
 */

/**
 * Update Checker
 */

require 'inc/theme-update-checker.php';
$example_update_checker = new ThemeUpdateChecker(
    'carotape',
    'http://themes.bigwolfdesigns.com/carotape/info.json'
);

if(!function_exists('sassembly_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function sassembly_setup(){

        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on SASSembly, use a find and replace
         * to change 'sassembly' to the name of your theme in all the template files
         */
        load_theme_textdomain('sassembly', get_template_directory().'/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
        * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in 3 locations.

        register_nav_menus(array(
            'primary' => __('Primary Menu', 'sassembly'),
            'footer' => __('Footer Menu', 'sassembly'),
            'sidebar' => __('Sidebar Menu', 'sassembly'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */

        add_theme_support('html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ));

    }

endif; // sassembly_setup

add_action('after_setup_theme', 'sassembly_setup');

/**
 * Enqueue scripts and styles.
 */
function sassembly_scripts(){
    wp_enqueue_style('sassembly-style', get_stylesheet_uri());
    wp_enqueue_script( 'jquery' );
} 

add_action('wp_enqueue_scripts', 'sassembly_scripts');

function ajaxurl(){
    wp_enqueue_script('product-selector', get_template_directory_uri().'/js/ajax.js', array('jquery'));
    wp_localize_script('product-selector', 'MyAjax', array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url('admin-ajax.php'),
            // generate a nonce with a unique ID "myajax-post-comment-nonce"
            // so that you can check it later when an AJAX request is sent
            //'postCommentNonce' => wp_create_nonce( 'myajax-post-comment-nonce' ),
            )
    );
}

add_action('wp_head', 'ajaxurl');

/**
 * Custom template tags for this theme.
 */
require get_template_directory().'/inc/template-tags.php';

/* Custom fields for tape */
function render_custom($post){
    // $post is already set, and contains an object: the WordPress post
    global $post;

    $values = get_post_custom($post->ID);
    $model = isset($values['model_box'])?esc_attr($values['model_box'][0]):'';
    $milThickness = isset($values['milThickness_box'])?esc_attr($values['milThickness_box'][0]):'';
    $adhesion = isset($values['adhesion_box'])?esc_attr($values['adhesion_box'][0]):'';
    $substrate = isset($values['substrate_box'])?esc_attr($values['substrate_box'][0]):'';
    $elongation = isset($values['elongation_box'])?esc_attr($values['elongation_box'][0]):'';

    // We'll use this nonce field later on when saving.
    wp_nonce_field('my_custom_box_nonce', 'custom_box_nonce');
    ?>
    <p>
        <label for="model_box" style="float:left;">Model Number</label>
        <input type="text" name="model_box" id="model_box" value="<?php echo $model; ?>" />
   </p>
    <p>
        <label for="milThickness_box" style="float:left;">Mil Thickness</label>
        <input type="text" name="milThickness_box" id="milThickness_box" value="<?php echo $milThickness; ?>" />
    </p>
    <p>
        <label for="adhesion_box" style="float:left;">Cohesion&nbsp;&nbsp;&nbsp;</label>
        <input type="text" name="adhesion_box" id="adhesion_box" value="<?php echo $adhesion; ?>" />
    </p>
    <p>
        <label for="substrate_box" style="float:left;">Substrate&nbsp;&nbsp;&nbsp;</label>
        <input type="text" name="substrate_box" id="substrate_box" value="<?php echo $substrate; ?>" />
    </p>
    <p>
        <label for="elongation_box" style="float:left;">Elongation</label>
        <input type="text" name="elongation_box" id="elongation_box" value="<?php echo $elongation; ?>" />
    </p>
    <?php
}

add_action('add_meta_boxes', 'custom_box');

function custom_box(){
    add_meta_box('custom-box', 'Tape Attributes', 'render_custom', 'post', 'normal', 'high');
}

add_action('save_post', 'custom_box_save');

function custom_box_save($post_id){
    // Bail if we're doing an auto save
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
       return;

    // if our nonce isn't there, or we can't verify it, bail
    if(!isset($_POST['custom_box_nonce']) || !wp_verify_nonce($_POST['custom_box_nonce'], 'my_custom_box_nonce'))
       return;

    // if our current user can't edit this post, bail
    if(!current_user_can('edit_post'))
       return;

    // now we can actually save the data
    $allowed = array(
        'a' => array(// on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );

    // Make sure your data is set before trying to save it
    if(isset($_POST['model_box']))
        update_post_meta($post_id, 'model_box', wp_kses($_POST['model_box'], $allowed));
    if(isset($_POST['milThickness_box']))
        update_post_meta($post_id, 'milThickness_box', wp_kses($_POST['milThickness_box'], $allowed));
    if(isset($_POST['adhesion_box']))
        update_post_meta($post_id, 'adhesion_box', wp_kses($_POST['adhesion_box'], $allowed));
    if(isset($_POST['substrate_box']))
        update_post_meta($post_id, 'substrate_box', wp_kses($_POST['substrate_box'], $allowed));
    if(isset($_POST['elongation_box']))
        update_post_meta($post_id, 'elongation_box', wp_kses($_POST['elongation_box'], $allowed));
}

/* excerpt length */

function custom_excerpt_length($length){
    return 20;
}

add_filter('excerpt_length', 'custom_excerpt_length', 999);

function ajax_callback(){
    global $wpdb; // this is how you get access to the database
    // Declare our variable.

    $return          = array();
    $return['error'] = "You must POST to this script."; //Somehow empty.
    if(isset($_POST) && $post = $_POST){ // Is the form sent?
        $return = array(); 
            $cat   = $post['cat'];
            //get adhesions for category
							$query = $wpdb->get_results(
				"SELECT p.`ID`, pm.`meta_value` FROM {$wpdb->postmeta} pm
		        LEFT JOIN {$wpdb->posts} `p` ON `p`.`ID` = pm.`post_id`
		        LEFT JOIN {$wpdb->term_relationships} `tr` ON `p`.`ID` = `tr`.`object_id`
		        LEFT JOIN {$wpdb->term_taxonomy} `tt` ON `tr`.`term_taxonomy_id` = `tt`.`term_taxonomy_id`
		        WHERE `pm`.`meta_key` = 'adhesion_box'
		        AND `p`.`post_status` = 'publish'
        		AND `p`.`post_type` = 'post'
		        AND `tt`.`taxonomy` = 'category'
		        AND `tt`.`term_id` = $cat
		        ");
		if(is_array($query)){
                foreach($query as $obj){
                    $return[$obj->ID] = $obj->meta_value;
			}
            }else{
                $return['error'] = "We're sorry but that category is not listed.";
            }

            wp_reset_postdata();
    }

    $return = array_unique($return); //Make sure our array has unique values

    echo json_encode($return); // Json encode the array.
    die(); // WordPress requires this.
}

add_action('wp_ajax_nopriv_ajax_callback', 'ajax_callback');
add_action('wp_ajax_ajax_callback', 'ajax_callback');

function adhesion_ajax(){
    global $wpdb; // this is how you get access to the database
    // Declare our variable.

    $return          = array();
    $return['error'] = "You must POST to this script."; //Somehow empty.
    if(isset($_POST) && $post = $_POST){ // Is the form sent?
        $return = array(); 
            $cat   = $post['cat'];
            $adh   = $post['adhesion'];
            //get adhesions for category
					$query	 = $wpdb->get_results("
											SELECT pm.`post_id` FROM {$wpdb->postmeta} pm
		        LEFT JOIN {$wpdb->posts} `p` ON `p`.`ID` = pm.`post_id`
		        LEFT JOIN {$wpdb->term_relationships} `tr` ON `p`.`ID` = `tr`.`object_id`
		        LEFT JOIN {$wpdb->term_taxonomy} `tt` ON `tr`.`term_taxonomy_id` = `tt`.`term_taxonomy_id`
		        WHERE `pm`.`meta_key` = 'adhesion_box'
				AND `pm`.`meta_value` = '$adh'
		        AND `p`.`post_status` = 'publish'
        		AND `p`.`post_type` = 'post'
		        AND `tt`.`taxonomy` = 'category'
		        AND `tt`.`term_id` = $cat
							");
		if(is_array($query)){
			foreach($query as $obj){
				$tmps = $wpdb->get_results("
						SELECT p.`ID` as post_id, pm.`meta_value` FROM {$wpdb->postmeta} pm
						 LEFT JOIN {$wpdb->posts} `p` ON `p`.`ID` = pm.`post_id`
						  WHERE pm.`post_id` = {$obj->post_id}
							  AND pm.`meta_key` = 'substrate_box'
		        ");
				if(is_array($tmps) && count($tmps) > 0){
					foreach($tmps as $tmp){
						$return[$tmp->post_id] = $tmp->meta_value;
					}
				}
			}
		}

		wp_reset_postdata();
    }

    $return = array_unique($return); //Make sure our array has unique values

    echo json_encode($return); // Json encode the array.
    die(); // WordPress requires this.
}

add_action('wp_ajax_nopriv_adhesion_ajax', 'adhesion_ajax');
add_action('wp_ajax_adhesion_ajax', 'adhesion_ajax');