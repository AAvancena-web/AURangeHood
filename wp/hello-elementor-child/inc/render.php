<?php
/**
 * Small render helpers shared by the templates.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a field with a fallback, from the page or from the options page.
 *
 * @param string   $name    Field name.
 * @param mixed    $default Value used when the field is empty.
 * @param int|null $post_id Post to read from, or 'option'.
 */
function ar_field( $name, $default = '', $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = ( null === $post_id ) ? get_field( $name ) : get_field( $name, $post_id );
	if ( null === $value || '' === $value || array() === $value ) {
		return $default;
	}
	return $value;
}

/**
 * Read a global setting from the ACF options page.
 */
function ar_option( $name, $default = '' ) {
	return ar_field( $name, $default, 'option' );
}

/**
 * Repeater rows, always an array.
 */
function ar_rows( $name, $post_id = null ) {
	$rows = ar_field( $name, array(), $post_id );
	return is_array( $rows ) ? $rows : array();
}

/**
 * An SVG icon from the design set.
 */
function ar_icon( $name, $class = '' ) {
	$stroke = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
	$icons  = array(
		'arrow'     => '<path d="M5 12h13"/><path d="m12 5 7 7-7 7"/>',
		'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/>',
		'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>',
		'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
		'cart'      => '<circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h2.2l2.4 11.2a2 2 0 0 0 2 1.6h8.6a2 2 0 0 0 2-1.55L21 7H6"/>',
		'check'     => '<path d="M20 6 9 17l-5-5"/>',
		'check-ring'=> '<circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.4 2.4 4.6-4.8"/>',
		'lock'      => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
		'shield'    => '<path d="M12 3l8 3v6c0 5-3.4 8.2-8 9-4.6-.8-8-4-8-9V6Z"/><path d="m9 12 2.2 2.2L15.5 10"/>',
		'zoom'      => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6M11 8v6M8 11h6"/>',
		'up'        => '<path d="M12 19V5"/><path d="m5 12 7-7 7 7"/>',
		'close'     => '<path d="M18 6 6 18M6 6l12 12"/>',
		'left'      => '<path d="m15 18-6-6 6-6"/>',
		'right'     => '<path d="m9 6 6 6-6 6"/>',
		'widget'    => '<rect x="3" y="4" width="18" height="16" rx="3"/><path d="M3 9h18M8 14h8"/>',
	);
	$solid = array(
		'star'      => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1Z"/>',
		'facebook'  => '<path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.9c0-.9.25-1.5 1.55-1.5h1.65V4.6c-.3 0-1.3-.1-2.45-.1-2.4 0-4.05 1.45-4.05 4.15v2.25H7.5V14h2.2v8h3.8Z"/>',
	);

	$class = $class ? ' class="' . esc_attr( $class ) . '"' : '';

	if ( isset( $solid[ $name ] ) ) {
		return '<svg' . $class . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' . $solid[ $name ] . '</svg>';
	}
	if ( 'instagram' === $name ) {
		return '<svg' . $class . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="3.6"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg>';
	}
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg' . $class . ' viewBox="0 0 24 24" ' . $stroke . ' aria-hidden="true">' . $icons[ $name ] . '</svg>';
}

/**
 * Resolve a quote link.
 *
 * The contact block only exists on the homepage template, so a bare #anchor
 * would go nowhere on an inner page. Off the homepage the anchor is rewritten
 * onto the contact page instead.
 */
function ar_cta_url( $url ) {
	$url = (string) $url;
	if ( '' === $url || '#' !== $url[0] ) {
		return $url;
	}
	if ( function_exists( 'ar_is_home_template' ) && ar_is_home_template() ) {
		return $url;
	}

	$contact = get_page_by_path( 'contact-us' );
	if ( $contact ) {
		return get_permalink( $contact ) . $url;
	}
	return home_url( '/contact-us/' ) . $url;
}

/**
 * The standard call to action pair: orange primary, solid secondary.
 *
 * @param array $args note, primary_label, primary_url, secondary_label,
 *                    secondary_url, on_dark, class.
 */
function ar_cta_pair( $args = array() ) {
	$a = wp_parse_args(
		$args,
		array(
			'note'            => '',
			'primary_label'   => ar_option( 'cta_primary_label', 'Get a Free Quote' ),
			'primary_url'     => ar_option( 'cta_primary_url', '#ar-contact' ),
			'secondary_label' => '',
			'secondary_url'   => '',
			'on_dark'         => false,
			'class'           => 'ar-section-cta',
		)
	);

	if ( '' === $a['secondary_label'] ) {
		$a['secondary_label'] = 'Call ' . ar_option( 'phone_display', '1800 726 434' );
	}
	if ( '' === $a['secondary_url'] ) {
		$a['secondary_url'] = 'tel:' . ar_option( 'phone_link', '1800726434' );
	}

	// White secondary on the dark sections, black on the light ones.
	$secondary_class = $a['on_dark'] ? 'ar-btn ar-btn--light' : 'ar-btn ar-btn--dark';
	?>
	<div class="<?php echo esc_attr( $a['class'] ); ?>" data-reveal>
		<?php if ( $a['note'] ) : ?>
			<p><?php echo esc_html( $a['note'] ); ?></p>
		<?php endif; ?>
		<a class="ar-btn ar-btn--glow" href="<?php echo esc_url( ar_cta_url( $a['primary_url'] ) ); ?>">
			<?php echo esc_html( $a['primary_label'] ); ?> <?php echo ar_icon( 'arrow' ); ?>
		</a>
		<?php if ( $a['secondary_label'] ) : ?>
			<a class="<?php echo esc_attr( $secondary_class ); ?>" href="<?php echo esc_url( $a['secondary_url'] ); ?>">
				<?php echo ar_icon( 'phone' ); ?> <?php echo esc_html( $a['secondary_label'] ); ?>
			</a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Section heading block.
 */
function ar_section_head( $eyebrow, $title, $intro = '', $center = true ) {
	if ( ! $eyebrow && ! $title && ! $intro ) {
		return;
	}
	?>
	<div class="<?php echo $center ? 'ar-center' : ''; ?>" data-reveal>
		<?php if ( $eyebrow ) : ?>
			<span class="ar-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h2><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>
		<?php if ( $intro ) : ?>
			<p class="ar-lead"><?php echo wp_kses_post( $intro ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * A plugin shortcode slot. Renders the shortcode when one is set, and the
 * dashed placeholder when it is not, so an empty section never looks broken.
 */
function ar_shortcode_slot( $shortcode, $title, $note ) {
	$shortcode = trim( (string) $shortcode );

	if ( $shortcode ) {
		echo '<div class="ar-shortcode-output" data-reveal>' . do_shortcode( $shortcode ) . '</div>';
		return;
	}
	?>
	<div class="ar-shortcode-slot" data-reveal>
		<?php echo ar_icon( 'widget' ); ?>
		<b><?php echo esc_html( $title ); ?></b>
		<span><?php echo esc_html( $note ); ?></span>
	</div>
	<?php
}

/**
 * Menu walker that prints plain anchors.
 *
 * The design styles `.ar-nav a` and `.ar-drawer__link` directly, so the default
 * ul/li markup is not wanted here.
 */
class AR_Plain_Menu_Walker extends Walker_Nav_Menu {

	/** @var string Extra class for each link. */
	protected $link_class;

	public function __construct( $link_class = '' ) {
		$this->link_class = $link_class;
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item    = $data_object;
		$classes = (array) $item->classes;
		$current = in_array( 'current-menu-item', $classes, true )
			|| in_array( 'current_page_item', $classes, true )
			|| in_array( 'current-menu-ancestor', $classes, true );

		$class = trim( $this->link_class . ( $depth > 0 ? ' ar-drawer__link--sub' : '' ) );

		$output .= '<a href="' . esc_url( $item->url ) . '"';
		if ( $class ) {
			$output .= ' class="' . esc_attr( $class ) . '"';
		}
		if ( $current ) {
			$output .= ' aria-current="page"';
		}
		$output .= '>' . esc_html( $item->title ) . '</a>';
	}
}

/**
 * Nav menu fallback.
 *
 * The header uses the `menu-1` location that Hello Elementor registers. If no
 * menu is assigned there yet, fall back to any menu that exists, then to the
 * top level pages, so the header is never delivered empty.
 */
function ar_nav_menu( $link_class = '' ) {
	$args = array(
		'container'   => false,
		'items_wrap'  => '%3$s',
		'depth'       => $link_class ? 2 : 1,
		'fallback_cb' => false,
		'walker'      => new AR_Plain_Menu_Walker( $link_class ),
		'echo'        => false,
	);

	$html = '';
	if ( has_nav_menu( 'menu-1' ) ) {
		$html = wp_nav_menu( array_merge( $args, array( 'theme_location' => 'menu-1' ) ) );
	}

	if ( ! $html ) {
		$menus = wp_get_nav_menus();
		if ( ! empty( $menus ) ) {
			$html = wp_nav_menu( array_merge( $args, array( 'menu' => $menus[0]->term_id ) ) );
		}
	}

	if ( ! $html ) {
		$pages = get_pages( array( 'sort_column' => 'menu_order,post_title', 'parent' => 0, 'number' => 7 ) );
		foreach ( $pages as $page ) {
			$class = $link_class ? ' class="' . esc_attr( $link_class ) . '"' : '';
			$html .= '<a href="' . esc_url( get_permalink( $page ) ) . '"' . $class . '>' . esc_html( $page->post_title ) . '</a>';
		}
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
}
