<?php
/**
 * Global site footer.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo    = ar_option( 'footer_logo' );
$about   = ar_option( 'footer_about' );
$socials = ar_rows( 'socials', 'option' );
$phone   = ar_option( 'phone_display', '1800 726 434' );
$tel     = ar_option( 'phone_link', '1800726434' );
$mobile  = ar_option( 'mobile_display' );
$mtel    = ar_option( 'mobile_link' );
$email   = ar_option( 'email' );
$address = ar_option( 'address' );
$addr_u  = ar_option( 'address_url', '#' );
$cta_l   = ar_option( 'cta_primary_label', 'Get a Free Quote' );
$cta_u   = ar_option( 'cta_primary_url', '/contact-us/' );

$columns = array(
	array( 'title' => ar_option( 'footer_col2_title', 'Quick links' ), 'links' => ar_rows( 'footer_col2_links', 'option' ) ),
	array( 'title' => ar_option( 'footer_col3_title', 'Services' ), 'links' => ar_rows( 'footer_col3_links', 'option' ) ),
);
?>
<footer class="ar-site-footer ar-scope">
	<div class="ar-container">
		<div class="ar-footer__grid">
			<div>
				<?php if ( $logo ) : ?>
					<img class="ar-flogo" src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" loading="lazy">
				<?php endif; ?>
				<?php if ( $about ) : ?>
					<p><?php echo esc_html( $about ); ?></p>
				<?php endif; ?>
				<?php if ( $socials ) : ?>
					<div class="ar-socials-row">
						<?php foreach ( $socials as $s ) : ?>
							<a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener"
							   aria-label="<?php echo esc_attr( ucfirst( $s['network'] ) ); ?>"><?php echo ar_icon( $s['network'] ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php foreach ( $columns as $col ) : ?>
				<div>
					<h4><?php echo esc_html( $col['title'] ); ?></h4>
					<ul class="ar-footer-links">
						<?php foreach ( $col['links'] as $link ) : ?>
							<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>

			<div>
				<h4><?php echo esc_html( ar_option( 'footer_col4_title', 'Contact info' ) ); ?></h4>
				<ul class="ar-footer-links">
					<?php if ( $address ) : ?>
						<li><a href="<?php echo esc_url( $addr_u ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $address ); ?></a></li>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
					<?php endif; ?>
					<li><a href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $phone ); ?></a></li>
					<?php if ( $mobile ) : ?>
						<li><a href="tel:<?php echo esc_attr( $mtel ); ?>"><?php echo esc_html( $mobile ); ?></a></li>
					<?php endif; ?>
				</ul>
				<a class="ar-btn ar-btn--sm ar-btn--glow" href="<?php echo esc_url( ar_cta_url( $cta_u ) ); ?>" style="margin-top:20px"><?php echo esc_html( $cta_l ); ?></a>
			</div>
		</div>

		<div class="ar-footer__bottom">
			<span><?php echo esc_html( ar_option( 'footer_copyright', 'Copyright ' . gmdate( 'Y' ) . ' Australian Rangehoods. All rights reserved.' ) ); ?></span>
			<?php if ( ar_option( 'footer_designer' ) ) : ?>
				<span>
					<?php esc_html_e( 'Designed by', 'hello-elementor-child' ); ?>
					<a href="<?php echo esc_url( ar_option( 'footer_designer_url', '#' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( ar_option( 'footer_designer' ) ); ?></a>
				</span>
			<?php endif; ?>
		</div>
	</div>
</footer>

<a class="ar-call-float ar-scope" id="ar-call-float" href="tel:<?php echo esc_attr( $tel ); ?>">
	<?php echo ar_icon( 'phone' ); ?> <?php esc_html_e( 'Call Now', 'hello-elementor-child' ); ?>
</a>

<button class="ar-to-top ar-scope" id="ar-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'hello-elementor-child' ); ?>">
	<?php echo ar_icon( 'up' ); ?>
</button>

<div class="ar-lightbox ar-scope" id="ar-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Project image viewer', 'hello-elementor-child' ); ?>">
	<button class="ar-lightbox__close" id="ar-lb-close" aria-label="<?php esc_attr_e( 'Close', 'hello-elementor-child' ); ?>"><?php echo ar_icon( 'close' ); ?></button>
	<button class="ar-lightbox__nav ar-lightbox__nav--prev" id="ar-lb-prev" aria-label="<?php esc_attr_e( 'Previous image', 'hello-elementor-child' ); ?>"><?php echo ar_icon( 'left' ); ?></button>
	<button class="ar-lightbox__nav ar-lightbox__nav--next" id="ar-lb-next" aria-label="<?php esc_attr_e( 'Next image', 'hello-elementor-child' ); ?>"><?php echo ar_icon( 'right' ); ?></button>
	<img id="ar-lb-img" src="" alt="">
</div>
