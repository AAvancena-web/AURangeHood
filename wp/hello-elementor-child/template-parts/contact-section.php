<?php
/**
 * Contact block: details, enquiry form and the map. Sits above the footer.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone   = ar_option( 'phone_display', '1800 726 434' );
$tel     = ar_option( 'phone_link', '1800726434' );
$mobile  = ar_option( 'mobile_display' );
$email   = ar_option( 'email' );
$address = ar_option( 'address' );
$addr_u  = ar_option( 'address_url', '#' );
$hours   = ar_option( 'hours' );
$socials = ar_rows( 'socials', 'option' );
$map     = ar_field( 'contact_map_src' );
?>
<section class="ar-section ar-contact" id="ar-contact">
	<div class="ar-container">
		<div class="ar-contact__inner">
			<div data-reveal="left">
				<?php ar_section_head( ar_field( 'contact_eyebrow' ), ar_field( 'contact_title' ), ar_field( 'contact_intro' ), false ); ?>

				<ul class="ar-contact-info">
					<?php if ( $address ) : ?>
						<li>
							<a href="<?php echo esc_url( $addr_u ); ?>" target="_blank" rel="noopener">
								<span class="ar-contact-info__icon"><?php echo ar_icon( 'pin' ); ?></span>
								<span><span class="ar-k"><?php esc_html_e( 'Office address', 'hello-elementor-child' ); ?></span><span class="ar-v"><?php echo esc_html( $address ); ?></span></span>
							</a>
						</li>
					<?php endif; ?>
					<li>
						<a href="tel:<?php echo esc_attr( $tel ); ?>">
							<span class="ar-contact-info__icon"><?php echo ar_icon( 'phone' ); ?></span>
							<span><span class="ar-k"><?php esc_html_e( 'Phone number', 'hello-elementor-child' ); ?></span><span class="ar-v"><?php echo esc_html( $phone ); ?><?php echo $mobile ? ' &nbsp;|&nbsp; Mobile ' . esc_html( $mobile ) : ''; ?></span></span>
						</a>
					</li>
					<?php if ( $email ) : ?>
						<li>
							<a href="mailto:<?php echo esc_attr( $email ); ?>">
								<span class="ar-contact-info__icon"><?php echo ar_icon( 'mail' ); ?></span>
								<span><span class="ar-k"><?php esc_html_e( 'Email address', 'hello-elementor-child' ); ?></span><span class="ar-v"><?php echo esc_html( $email ); ?></span></span>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( $hours ) : ?>
						<li>
							<div>
								<span class="ar-contact-info__icon"><?php echo ar_icon( 'clock' ); ?></span>
								<span><span class="ar-k"><?php esc_html_e( 'Trading hours', 'hello-elementor-child' ); ?></span><span class="ar-v"><?php echo esc_html( $hours ); ?></span></span>
							</div>
						</li>
					<?php endif; ?>
				</ul>

				<?php if ( $socials ) : ?>
					<div class="ar-socials-row">
						<?php foreach ( $socials as $s ) : ?>
							<a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener"
							   aria-label="<?php echo esc_attr( ucfirst( $s['network'] ) ); ?>"><?php echo ar_icon( $s['network'] ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php get_template_part( 'template-parts/enquiry-form', null, array( 'reveal' => 'right' ) ); ?>
		</div>

		<?php if ( $map ) : ?>
			<div class="ar-map" data-reveal>
				<div class="ar-map__pill">
					<?php echo ar_icon( 'pin' ); ?>
					<span>
						<b><?php bloginfo( 'name' ); ?></b>
						<small><?php echo esc_html( $address ); ?></small>
					</span>
				</div>
				<iframe title="<?php esc_attr_e( 'Map of our service area', 'hello-elementor-child' ); ?>"
						src="<?php echo esc_url( $map ); ?>"
						loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		<?php endif; ?>
	</div>
</section>
