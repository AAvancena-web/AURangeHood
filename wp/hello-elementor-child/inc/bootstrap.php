<?php
/**
 * Australian Rangehoods redesign: bootstrap.
 *
 * Loaded from functions.php. Registers the page template, enqueues the scoped
 * assets, and takes over the header and footer so the new design is used on
 * every page, not only the homepage.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AR_REDESIGN_VERSION', '1.0.0' );
define( 'AR_REDESIGN_DIR', get_stylesheet_directory() );
define( 'AR_REDESIGN_URI', get_stylesheet_directory_uri() );
define( 'AR_HOME_TEMPLATE', 'page-templates/home-redesign.php' );

require_once AR_REDESIGN_DIR . '/inc/render.php';
require_once AR_REDESIGN_DIR . '/inc/acf-fields.php';
require_once AR_REDESIGN_DIR . '/inc/seeder.php';

/**
 * Assets. Scoped CSS, so it is safe to load site wide for the header and footer.
 */
function ar_redesign_assets() {
	wp_enqueue_style(
		'ar-redesign',
		AR_REDESIGN_URI . '/assets/css/ar-redesign.css',
		array( 'chld_thm_cfg_child' ),
		AR_REDESIGN_VERSION
	);

	wp_enqueue_script(
		'ar-redesign',
		AR_REDESIGN_URI . '/assets/js/ar-redesign.js',
		array(),
		AR_REDESIGN_VERSION,
		true
	);

	// Google Fonts used by the design. Remove if the fonts are already loaded.
	wp_enqueue_style(
		'ar-redesign-fonts',
		'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'ar_redesign_assets', 100 );

/**
 * Body classes so CSS and QA can target the redesign.
 */
function ar_redesign_body_class( $classes ) {
	$classes[] = 'ar-redesign-active';
	if ( ar_is_home_template() ) {
		$classes[] = 'ar-home-template';
	}
	return $classes;
}
add_filter( 'body_class', 'ar_redesign_body_class' );

/**
 * True when the current page uses the redesign homepage template.
 */
function ar_is_home_template() {
	if ( ! is_page() ) {
		return false;
	}
	return AR_HOME_TEMPLATE === get_page_template_slug( get_queried_object_id() );
}

/**
 * Should the theme render our header and footer rather than the Elementor ones?
 * Filterable so a single template can opt out if it ever needs to.
 */
function ar_use_custom_header_footer() {
	return (bool) apply_filters( 'ar_use_custom_header_footer', true );
}

/**
 * Register the page template for classic page editing. Hello Elementor already
 * exposes templates in the Page Attributes box, this covers block editor use.
 */
function ar_register_page_templates( $templates ) {
	$templates[ AR_HOME_TEMPLATE ] = __( 'AR Home Redesign', 'hello-elementor-child' );
	return $templates;
}
add_filter( 'theme_page_templates', 'ar_register_page_templates' );

/**
 * The inner page banner. Rendered after the header on every page except the
 * homepage template, which draws its own hero.
 */
function ar_render_inner_banner() {
	if ( ar_is_home_template() ) {
		return;
	}
	if ( is_404() || is_admin() ) {
		return;
	}
	// Leave WooCommerce cart, checkout and account pages alone.
	if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		return;
	}

	$enabled = get_field( 'banner_enabled', get_queried_object_id() );
	if ( is_page() && false === $enabled && null !== $enabled ) {
		return;
	}

	get_template_part( 'template-parts/inner-banner' );
}

/**
 * The build needs ACF PRO for repeaters, galleries and the options page.
 * Without it the templates still render, but every field falls back to empty,
 * so say so rather than leaving someone to wonder why the page looks bare.
 */
function ar_acf_dependency_notice() {
	if ( function_exists( 'get_field' ) && function_exists( 'acf_add_options_page' ) ) {
		return;
	}
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	$missing = ! function_exists( 'get_field' )
		? 'Advanced Custom Fields is not active'
		: 'Advanced Custom Fields PRO is required (the free version has no repeater, gallery or options page)';
	printf(
		'<div class="notice notice-error"><p><strong>Australian Rangehoods design:</strong> %s, so the homepage and banners will render without content.</p></div>',
		esc_html( $missing )
	);
}
add_action( 'admin_notices', 'ar_acf_dependency_notice' );
