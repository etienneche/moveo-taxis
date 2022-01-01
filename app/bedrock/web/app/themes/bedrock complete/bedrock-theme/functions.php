<?php
/**
 * bedrock-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package bedrock-theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'bedrock_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function bedrock_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on bedrock-theme, use a find and replace
		 * to change 'bedrock-theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'bedrock-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'bedrock-theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'bedrock_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'bedrock_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bedrock_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bedrock_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'bedrock_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bedrock_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'bedrock-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'bedrock-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'bedrock_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 * AIzaSyD8wUVi2XH1xP8EwqvsTDX-LhDqlGlE_EY
 */
function bedrock_theme_scripts() {
	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/assets/css/style.css', array(), _S_VERSION );
	wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css', array(), _S_VERSION );
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), _S_VERSION );
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins&display=swap', false);
	wp_style_add_data( 'bedrock-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'bedrock-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'bedrock-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), _S_VERSION, true );

	$args = array(
		'post_type'      => 'taxi',
		'posts_per_page' => -1,
	);

	$query  = new WP_Query( $args );
	$taxies = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$colors                 = get_the_terms( get_the_ID(), 'color' );
			$taxies[ get_the_id() ] = array(
				'id'    => get_the_ID(),
				'title' => get_the_title(),
				'lat'   => get_field( 'lat' ),
				'lng'   => get_field( 'lng' ),
				'color' => $colors ? $colors[0] : '',
				'model' => get_field( 'model' ),
				'year'  => get_field( 'year' ),
			);

		}

		wp_reset_query();
	}

	$siteObject = array(
		'ajaxurl'  => admin_url( 'admin-ajax.php' ),
		'homeurl'  => get_site_url(),
		'themeurl' => get_template_directory_uri(),
		'taxies'   => $taxies,
	);

	// add php array to be available in js file scripts.js
	wp_localize_script( 'bedrock-scripts', 'siteObject', $siteObject );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bedrock_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


// on init register post type - taxi
function taxi_setup_post_type() {
	$args = array(
		'public' => true,
		'label'  => __( 'Taxi' ),
	);
	register_post_type( 'taxi', $args );

	// create color taxonomy
	register_taxonomy(
		'color',
		'taxi',
		array(
			'label'        => __( 'Color', 'textdomain' ),
			'hierarchical' => true,
		)
	);
}
add_action( 'init', 'taxi_setup_post_type' );


// check if file exists and then create new records
add_action( 'init', 'check_csv_file' );


function check_csv_file() {
	$file_path = get_template_directory() . '/data/taxi_db.csv';


	if ( file_exists( $file_path ) ) {
		// csv to php array
		$csv = array_map( 'str_getcsv', file( $file_path ) );

		if ( $csv ) {

			// loop through array
			foreach ( $csv as $key => $taxi ) {

				// if its not header row
				if ( $key > 0 ) {

					$id    = $taxi['0'];
					$title = $taxi['2'] . ' ' . $taxi['1'];

					// check if taxi is exists in db
					$exists = check_taxi_exists( $id );

					if ( ! $exists ) {
						$args = array(
							'post_title'   => $title,
							'post_content' => '',
							'post_type'    => 'taxi',
							'post_status'  => 'publish',
							'post_author'  => 1,
						);

						// Insert the post into the database.
						$post_id = wp_insert_post( $args );

						if ( $post_id ) {

							// set color taxonomy
							wp_set_object_terms( $post_id, $taxi['2'], 'color' );

							// set acf fields
							update_field( 'id', $id, $post_id );
							update_field( 'model', $taxi['1'], $post_id );
							update_field( 'year', $taxi['3'], $post_id );
							update_field( 'lat', $taxi['4'], $post_id );
							update_field( 'lng', $taxi['5'], $post_id );
						}
					}
				}
			}
		}
	}
}


function check_taxi_exists( $id ) {
	$args = array(
		'post_type'      => 'taxi',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'   => 'id',
				'value' => $id,
			),
		),
	);

	$query = new WP_Query( $args );

	return $query->have_posts();
}

/*
Ajax Requests
 */
add_action( 'wp_ajax_nopriv_update_form', 'update_form' );
add_action( 'wp_ajax_update_form', 'update_form' );


// on form update
function update_form() {

	parse_str( $_POST['data'], $data );

	$response = array(
		'ok' => false,
	);

	$taxi_id = sanitize_text_field( $data['taxi_id'] );

	$args = array(
		'post_type'      => 'taxi',
		'p'              => $taxi_id,
		'posts_per_page' => 1,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			// update data
			foreach ( $data as $key => $value ) {

				// if its title $key
				if ( 'title' == $key ) {
					$my_post = array(
						'post_title' => sanitize_text_field( $value ),
					);

					// Update the post into the database
					 wp_update_post( $my_post );

				} elseif ( 'color' == $key ) {
						// if its taxonomy $key
						// get current taxi colors
						$colors_arr = get_the_terms( get_the_ID(), 'color' );
						// remove current colors
						wp_remove_object_terms( get_the_ID(), $colors_arr, 'color' );

						// set new color taxonomy to chosen
						wp_set_object_terms( get_the_ID(), array( $value ), 'color' );

				} else {

					// update acf field
					update_field( $key, sanitize_text_field( $value ), get_the_ID() );

				}
			}
		}

		$response = array(
			'ok' => true,
		);

		wp_reset_query();
	}

	 wp_send_json( $response );
}


add_action( 'wp_ajax_nopriv_register_form', 'register_form' );
add_action( 'wp_ajax_register_form', 'register_form' );

/*
	Create new taxi
 */
function register_form() {

	parse_str( $_POST['data'], $data );

	$response = array(
		'ok' => false,
	);

	$args = array(
		'post_title'   => sanitize_text_field( $data['title'] ),
		'post_content' => '',
		'post_type'    => 'taxi',
		'post_status'  => 'publish',
	);

	// Insert new post into the database.
	$post_id = wp_insert_post( $args );

	if ( $post_id ) {

		// add color taxonomy to new post
		wp_set_object_terms( $post_id, array( sanitize_text_field( $data['color'] ) ), 'color' );

		// update acf fields to new post
		update_field( 'id', sanitize_text_field( $data['id'] ), $post_id );
		update_field( 'model', sanitize_text_field( $data['model'] ), $post_id );
		update_field( 'year', sanitize_text_field( $data['year'] ), $post_id );
		update_field( 'lat', sanitize_text_field( $data['lat'] ), $post_id );
		update_field( 'lng', sanitize_text_field( $data['lng'] ), $post_id );

		$response = array(
			'ok' => true,
		);
	}

	// send ajax response back to js
	 wp_send_json( $response );

}

add_action( 'wp_ajax_nopriv_remove_taxi', 'remove_taxi_callback' );
add_action( 'wp_ajax_remove_taxi', 'remove_taxi_callback' );

function remove_taxi_callback(){

	$response = array(
		'ok' => false,
	);

	$id = sanitize_text_field( $_POST['taxi_id'] );

	if ( $id ) {
		$args = array(
			'post_type' => 'taxi',
			'p'         => $id,
			'posts_per_page' => 1,
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {

			// delete post
			wp_delete_post( $id );

			$response = array(
				'ok' => true,
			);
		}
	}
	wp_send_json( $response );
}
