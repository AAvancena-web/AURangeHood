<?php
/**
 * ACF field groups, registered in PHP.
 *
 * Defining the fields in code rather than the admin UI keeps them in version
 * control, so staging and live always agree and nothing has to be exported or
 * re-created by hand.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field builder. Keys are derived from the name so they stay stable.
 */
function ar_f( $type, $name, $label, $extra = array() ) {
	return array_merge(
		array(
			'key'   => 'field_ar_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		),
		$extra
	);
}

/** Eyebrow, title and intro trio used by most sections. */
function ar_head_fields( $p, $labels = 'Section' ) {
	return array(
		ar_f( 'text', $p . '_eyebrow', $labels . ' eyebrow' ),
		ar_f( 'text', $p . '_title', $labels . ' title' ),
		ar_f( 'textarea', $p . '_intro', $labels . ' intro', array( 'rows' => 3 ) ),
	);
}

/**
 * Flatten one level, so helpers that return several fields can be dropped
 * straight into a fields list.
 */
function ar_flat( $items ) {
	$out = array();
	foreach ( $items as $item ) {
		if ( is_array( $item ) && ! isset( $item['key'] ) ) {
			foreach ( $item as $sub ) {
				$out[] = $sub;
			}
			continue;
		}
		$out[] = $item;
	}
	return $out;
}

function ar_tab( $label ) {
	return array(
		'key'   => 'field_ar_tab_' . sanitize_title( $label ),
		'label' => $label,
		'name'  => '',
		'type'  => 'tab',
	);
}

/**
 * Options page for everything that is shared by the header, footer and the
 * enquiry form, so those are edited in one place rather than per page.
 */
function ar_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}
	acf_add_options_page(
		array(
			'page_title' => 'Rangehoods Design Settings',
			'menu_title' => 'AR Design',
			'menu_slug'  => 'ar-design-settings',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-customizer',
			'position'   => 59,
			'redirect'   => false,
		)
	);
}
add_action( 'acf/init', 'ar_register_options_page' );

function ar_register_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ------------------------------------------------------------------
	 * Global: header, footer and the enquiry form
	 * ---------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_ar_global',
			'title'    => 'AR Global (header, footer, form)',
			'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'ar-design-settings' ) ) ),
			'fields'   => ar_flat( array(
				ar_tab( 'Header' ),
				ar_f( 'image', 'header_logo', 'Logo', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				ar_f( 'text', 'tagline', 'Top bar tagline' ),
				ar_f( 'text', 'phone_display', 'Phone (shown)' ),
				ar_f( 'text', 'phone_link', 'Phone (tel link, digits only)' ),
				ar_f( 'text', 'mobile_display', 'Mobile (shown)' ),
				ar_f( 'text', 'mobile_link', 'Mobile (tel link, digits only)' ),
				ar_f( 'text', 'email', 'Email address' ),
				ar_f( 'text', 'address', 'Address line' ),
				ar_f( 'url', 'address_url', 'Address map link' ),
				ar_f( 'text', 'hours', 'Trading hours' ),
				ar_f( 'text', 'cta_primary_label', 'Primary CTA label' ),
				ar_f( 'text', 'cta_primary_url', 'Primary CTA link' ),
				ar_f( 'true_false', 'show_cart', 'Show the cart icon', array( 'ui' => 1, 'default_value' => 1 ) ),
				ar_f( 'repeater', 'socials', 'Social links', array(
					'layout'       => 'table',
					'button_label' => 'Add social link',
					'sub_fields'   => array(
						ar_f( 'select', 'network', 'Network', array( 'choices' => array( 'facebook' => 'Facebook', 'instagram' => 'Instagram' ) ) ),
						ar_f( 'url', 'url', 'URL' ),
					),
				) ),

				ar_tab( 'Enquiry form' ),
				ar_f( 'message', 'form_help', '', array(
					'message' => 'Paste the Elementor form shortcode so the real form renders inside the styled card. Leave it empty to show the static design instead.',
				) ),
				ar_f( 'text', 'form_title', 'Form title' ),
				ar_f( 'textarea', 'form_intro', 'Form intro', array( 'rows' => 2 ) ),
				ar_f( 'textarea', 'form_shortcode', 'Form shortcode', array( 'rows' => 2 ) ),
				ar_f( 'text', 'form_note', 'Reassurance line under the form' ),

				ar_tab( 'Footer' ),
				ar_f( 'image', 'footer_logo', 'Footer logo', array( 'return_format' => 'array' ) ),
				ar_f( 'textarea', 'footer_about', 'Footer intro', array( 'rows' => 4 ) ),
				ar_f( 'text', 'footer_col2_title', 'Column 2 title' ),
				ar_f( 'repeater', 'footer_col2_links', 'Column 2 links', array(
					'layout' => 'table', 'button_label' => 'Add link',
					'sub_fields' => array( ar_f( 'text', 'label', 'Label' ), ar_f( 'text', 'url', 'URL' ) ),
				) ),
				ar_f( 'text', 'footer_col3_title', 'Column 3 title' ),
				ar_f( 'repeater', 'footer_col3_links', 'Column 3 links', array(
					'layout' => 'table', 'button_label' => 'Add link',
					'sub_fields' => array( ar_f( 'text', 'label', 'Label' ), ar_f( 'text', 'url', 'URL' ) ),
				) ),
				ar_f( 'text', 'footer_col4_title', 'Column 4 title' ),
				ar_f( 'text', 'footer_copyright', 'Copyright line' ),
				ar_f( 'text', 'footer_designer', 'Designed by' ),
				ar_f( 'url', 'footer_designer_url', 'Designer link' ),

				ar_tab( 'Inner page banner' ),
				ar_f( 'image', 'inner_banner_image', 'Default banner background', array( 'return_format' => 'array' ) ),
				ar_f( 'true_false', 'inner_banner_form', 'Show the enquiry form on inner pages', array( 'ui' => 1, 'default_value' => 1 ) ),
			) ),
		)
	);

	/* ------------------------------------------------------------------
	 * Inner page banner, per page
	 * ---------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_ar_banner',
			'title'    => 'AR Page Banner',
			'location' => array(
				array(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
					array( 'param' => 'page_template', 'operator' => '!=', 'value' => AR_HOME_TEMPLATE ),
				),
			),
			'menu_order' => 1,
			'fields'   => ar_flat( array(
				ar_f( 'true_false', 'banner_enabled', 'Show the banner', array( 'ui' => 1, 'default_value' => 1 ) ),
				ar_f( 'text', 'banner_title', 'Banner heading', array( 'instructions' => 'Leave empty to use the page title.' ) ),
				ar_f( 'textarea', 'banner_intro', 'Banner intro', array( 'rows' => 3 ) ),
				ar_f( 'image', 'banner_image', 'Banner background', array( 'return_format' => 'array' ) ),
				ar_f( 'true_false', 'banner_show_form', 'Show the enquiry form', array( 'ui' => 1, 'default_value' => 1 ) ),
			) ),
		)
	);

	/* ------------------------------------------------------------------
	 * Homepage
	 * ---------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_ar_home',
			'title'    => 'AR Home Redesign',
			'location' => array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => AR_HOME_TEMPLATE ) ) ),
			'fields'   => ar_flat( array(
				ar_tab( 'Banner' ),
				ar_f( 'text', 'hero_badge_chip', 'Rating chip' ),
				ar_f( 'text', 'hero_badge_text', 'Rating text' ),
				ar_f( 'text', 'hero_title', 'Heading' ),
				ar_f( 'text', 'hero_title_highlight', 'Heading highlight', array( 'instructions' => 'The part shown in orange. Must appear inside the heading above.' ) ),
				ar_f( 'textarea', 'hero_intro', 'Intro', array( 'rows' => 4 ) ),
				ar_f( 'text', 'hero_cta2_label', 'Second button label' ),
				ar_f( 'text', 'hero_cta2_url', 'Second button link' ),
				ar_f( 'text', 'hero_cta3_label', 'Third button label' ),
				ar_f( 'text', 'hero_cta3_url', 'Third button link' ),
				ar_f( 'repeater', 'hero_usps', 'Banner bullet points', array(
					'layout' => 'table', 'button_label' => 'Add bullet',
					'sub_fields' => array( ar_f( 'text', 'text', 'Text' ) ),
				) ),
				ar_f( 'url', 'hero_video_url', 'Background video URL (mp4)' ),
				ar_f( 'image', 'hero_poster', 'Video poster image', array( 'return_format' => 'array' ) ),

				ar_tab( 'Reviews' ),
				ar_head_fields( 'reviews', 'Reviews' ),
				ar_f( 'textarea', 'reviews_shortcode', 'Reviews shortcode', array( 'rows' => 2 ) ),
				ar_f( 'text', 'reviews_cta_note', 'CTA supporting line' ),

				ar_tab( 'Brands' ),
				ar_head_fields( 'brands', 'Brands' ),
				ar_f( 'gallery', 'brands_logos', 'Brand logos', array( 'return_format' => 'array' ) ),
				ar_f( 'text', 'brands_cta_label', 'Shop button label' ),
				ar_f( 'text', 'brands_cta_url', 'Shop button link' ),
				ar_f( 'text', 'brands_cta_note', 'Line under the button' ),

				ar_tab( 'About' ),
				ar_head_fields( 'about', 'About' ),
				ar_f( 'wysiwyg', 'about_body', 'About copy', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
				ar_f( 'image', 'about_image', 'About image', array( 'return_format' => 'array' ) ),
				ar_f( 'text', 'about_badge_value', 'Badge value' ),
				ar_f( 'text', 'about_badge_label', 'Badge label' ),
				ar_f( 'repeater', 'about_list', 'Tick list', array(
					'layout' => 'table', 'button_label' => 'Add item',
					'sub_fields' => array( ar_f( 'text', 'text', 'Text' ) ),
				) ),
				ar_f( 'repeater', 'stats', 'Stat strip', array(
					'layout' => 'table', 'button_label' => 'Add stat', 'max' => 4,
					'sub_fields' => array(
						ar_f( 'text', 'value', 'Value', array( 'instructions' => 'A number animates, text is shown as is.' ) ),
						ar_f( 'text', 'suffix', 'Suffix' ),
						ar_f( 'text', 'label', 'Label' ),
					),
				) ),

				ar_tab( 'Services' ),
				ar_head_fields( 'services', 'Services' ),
				ar_f( 'repeater', 'services_items', 'Services', array(
					'layout' => 'block', 'button_label' => 'Add service',
					'sub_fields' => array(
						ar_f( 'image', 'image', 'Image', array( 'return_format' => 'array' ) ),
						ar_f( 'text', 'title', 'Title' ),
						ar_f( 'textarea', 'text', 'Text', array( 'rows' => 2 ) ),
						ar_f( 'text', 'url', 'Link' ),
					),
				) ),
				ar_f( 'text', 'services_cta_note', 'CTA supporting line' ),

				ar_tab( 'Process' ),
				ar_head_fields( 'process', 'Process' ),
				ar_f( 'repeater', 'process_items', 'Steps', array(
					'layout' => 'block', 'button_label' => 'Add step',
					'sub_fields' => array(
						ar_f( 'text', 'title', 'Title' ),
						ar_f( 'textarea', 'text', 'Text', array( 'rows' => 2 ) ),
					),
				) ),
				ar_f( 'text', 'process_cta_note', 'CTA supporting line' ),

				ar_tab( 'Why us' ),
				ar_head_fields( 'why', 'Why us' ),
				ar_f( 'repeater', 'why_items', 'Reasons', array(
					'layout' => 'block', 'button_label' => 'Add reason',
					'sub_fields' => array(
						ar_f( 'image', 'icon', 'Icon', array( 'return_format' => 'array' ) ),
						ar_f( 'text', 'title', 'Title' ),
						ar_f( 'textarea', 'text', 'Text', array( 'rows' => 2 ) ),
					),
				) ),
				ar_f( 'image', 'why_image', 'Section image', array( 'return_format' => 'array' ) ),

				ar_tab( 'Builders' ),
				ar_head_fields( 'builders', 'Builders' ),
				ar_f( 'repeater', 'builders_items', 'Builders', array(
					'layout' => 'table', 'button_label' => 'Add builder',
					'sub_fields' => array(
						ar_f( 'image', 'logo', 'Logo', array( 'return_format' => 'array' ) ),
						ar_f( 'url', 'url', 'Website' ),
					),
				) ),
				ar_f( 'text', 'builders_cta_note', 'CTA supporting line' ),

				ar_tab( 'Gallery' ),
				ar_head_fields( 'gallery', 'Gallery' ),
				ar_f( 'gallery', 'gallery_images', 'Images', array( 'return_format' => 'array' ) ),
				ar_f( 'text', 'gallery_cta_label', 'Button label' ),
				ar_f( 'text', 'gallery_cta_url', 'Button link' ),

				ar_tab( 'Facebook feed' ),
				ar_head_fields( 'social', 'Facebook feed' ),
				ar_f( 'textarea', 'social_shortcode', 'Feed shortcode', array( 'rows' => 2 ) ),
				ar_f( 'text', 'social_cta_label', 'Button label' ),
				ar_f( 'text', 'social_cta_url', 'Button link' ),
				ar_f( 'text', 'social_cta_note', 'Line under the button' ),

				ar_tab( 'CTA band' ),
				ar_head_fields( 'band', 'CTA band' ),
				ar_f( 'image', 'band_image', 'Background image', array( 'return_format' => 'array' ) ),

				ar_tab( 'FAQ' ),
				ar_head_fields( 'faq', 'FAQ' ),
				ar_f( 'repeater', 'faq_items', 'Questions', array(
					'layout' => 'block', 'button_label' => 'Add question',
					'sub_fields' => array(
						ar_f( 'text', 'question', 'Question' ),
						ar_f( 'wysiwyg', 'answer', 'Answer', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
					),
				) ),
				ar_f( 'text', 'faq_helper_title', 'Helper box title' ),
				ar_f( 'textarea', 'faq_helper_text', 'Helper box text', array( 'rows' => 2 ) ),

				ar_tab( 'Contact' ),
				ar_head_fields( 'contact', 'Contact' ),
				ar_f( 'url', 'contact_map_src', 'Google map embed URL', array( 'instructions' => 'The src from the Google Maps embed code.' ) ),
			) ),
		)
	);
}
add_action( 'acf/init', 'ar_register_field_groups' );
