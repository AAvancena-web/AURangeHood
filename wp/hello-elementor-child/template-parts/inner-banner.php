<?php
/**
 * Inner page banner: heading on the left, enquiry form on the right, matching
 * the homepage treatment.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id    = get_queried_object_id();
$title = ar_field( 'banner_title', '', $id );
$intro = ar_field( 'banner_intro', '', $id );
$image = ar_field( 'banner_image', '', $id );

if ( ! $title ) {
	if ( is_singular() ) {
		$title = get_the_title( $id );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = get_the_title( wc_get_page_id( 'shop' ) );
	} elseif ( is_archive() || is_home() || is_search() ) {
		$title = wp_strip_all_tags( get_the_archive_title() );
	} else {
		$title = get_bloginfo( 'name' );
	}
}

if ( ! $image ) {
	$image = ar_option( 'inner_banner_image' );
}

$show_form = ar_field( 'banner_show_form', null, $id );
if ( null === $show_form || '' === $show_form ) {
	$show_form = ar_option( 'inner_banner_form', true );
}

$phone = ar_option( 'phone_display', '1800 726 434' );
$tel   = ar_option( 'phone_link', '1800726434' );
$cta_l = ar_option( 'cta_primary_label', 'Get a Free Quote' );
?>
<section class="ar-hero ar-hero--inner ar-scope">
	<?php if ( $image ) : ?>
		<div class="ar-hero__media" aria-hidden="true">
			<img src="<?php echo esc_url( $image['url'] ); ?>" alt="" loading="eager">
		</div>
	<?php endif; ?>

	<div class="ar-container ar-hero__inner<?php echo $show_form ? '' : ' ar-hero__inner--solo'; ?>">
		<div class="ar-hero__copy">
			<h1 data-reveal><?php echo wp_kses_post( $title ); ?></h1>
			<?php if ( $intro ) : ?>
				<p class="ar-hero__text" data-reveal><?php echo wp_kses_post( $intro ); ?></p>
			<?php endif; ?>
			<div class="ar-hero__ctas" data-reveal>
				<a class="ar-btn ar-btn--glow" href="<?php echo esc_url( ar_cta_url( '#ar-contact' ) ); ?>"><?php echo esc_html( $cta_l ); ?> <?php echo ar_icon( 'arrow' ); ?></a>
				<a class="ar-btn ar-btn--light" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo ar_icon( 'phone' ); ?> <?php echo esc_html( 'Call ' . $phone ); ?></a>
			</div>
		</div>

		<?php if ( $show_form ) : ?>
			<?php get_template_part( 'template-parts/enquiry-form', null, array( 'reveal' => 'right' ) ); ?>
		<?php endif; ?>
	</div>
</section>
