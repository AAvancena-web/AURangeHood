<?php
/**
 * Global site header.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo    = ar_option( 'header_logo' );
$phone   = ar_option( 'phone_display', '1800 726 434' );
$tel     = ar_option( 'phone_link', '1800726434' );
$email   = ar_option( 'email', 'admin@australianrangehoods.com.au' );
$address = ar_option( 'address' );
$tagline = ar_option( 'tagline', 'Do it once, Do it right' );
$socials = ar_rows( 'socials', 'option' );
$cta_l   = ar_option( 'cta_primary_label', 'Get a Free Quote' );
$cta_u   = ar_option( 'cta_primary_url', '/contact-us/' );
?>

<div class="ar-progress ar-scope" id="ar-progress" aria-hidden="true"></div>

<div class="ar-topbar ar-scope">
	<div class="ar-container ar-topbar__inner">
		<div class="ar-topbar__left">
			<?php if ( $email ) : ?>
				<a class="ar-topbar__item ar-topbar__item--mail" href="mailto:<?php echo esc_attr( $email ); ?>">
					<?php echo ar_icon( 'mail' ); ?><span><?php echo esc_html( $email ); ?></span>
				</a>
			<?php endif; ?>
			<?php if ( $address ) : ?>
				<span class="ar-topbar__item ar-topbar__item--addr">
					<?php echo ar_icon( 'pin' ); ?><?php echo esc_html( $address ); ?>
				</span>
			<?php endif; ?>
		</div>
		<div class="ar-topbar__left">
			<?php if ( $tagline ) : ?>
				<span class="ar-tagline"><?php echo esc_html( $tagline ); ?></span>
			<?php endif; ?>
			<?php if ( $socials ) : ?>
				<div class="ar-topbar__socials">
					<?php foreach ( $socials as $s ) : ?>
						<a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener"
						   aria-label="<?php echo esc_attr( ucfirst( $s['network'] ) ); ?>">
							<?php echo ar_icon( $s['network'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<header class="ar-site-header ar-scope" id="ar-site-header">
	<div class="ar-container ar-site-header__inner">
		<a class="ar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
			<?php if ( $logo ) : ?>
				<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ? $logo['alt'] : get_bloginfo( 'name' ) ); ?>">
			<?php else : ?>
				<span style="color:#fff;font-weight:700"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>

		<nav class="ar-nav" aria-label="<?php esc_attr_e( 'Primary', 'hello-elementor-child' ); ?>">
			<?php ar_nav_menu(); ?>
		</nav>

		<div class="ar-header__actions">
			<a class="ar-header-phone" href="tel:<?php echo esc_attr( $tel ); ?>">
				<span class="ar-header-phone__icon"><?php echo ar_icon( 'phone' ); ?></span>
				<span>
					<span class="ar-header-phone__label"><?php esc_html_e( 'Call us today', 'hello-elementor-child' ); ?></span>
					<span class="ar-header-phone__num"><?php echo esc_html( $phone ); ?></span>
				</span>
			</a>

			<?php if ( ar_option( 'show_cart', true ) && function_exists( 'WC' ) && WC()->cart ) : ?>
				<a class="ar-icon-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'hello-elementor-child' ); ?>">
					<?php echo ar_icon( 'cart' ); ?>
					<span class="ar-icon-btn__count"><?php echo (int) WC()->cart->get_cart_contents_count(); ?></span>
				</a>
			<?php endif; ?>

			<a class="ar-btn ar-btn--sm ar-btn--glow" href="<?php echo esc_url( ar_cta_url( $cta_u ) ); ?>"><?php echo esc_html( $cta_l ); ?></a>
		</div>

		<div class="ar-mobile-tools">
			<a class="ar-phone-circle" href="tel:<?php echo esc_attr( $tel ); ?>" aria-label="<?php echo esc_attr( 'Call ' . $phone ); ?>">
				<?php echo ar_icon( 'phone' ); ?>
			</a>
			<button class="ar-burger" id="ar-burger" aria-label="<?php esc_attr_e( 'Open menu', 'hello-elementor-child' ); ?>" aria-expanded="false" aria-controls="ar-drawer">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<div class="ar-scrim ar-scope" id="ar-scrim" hidden></div>
<aside class="ar-drawer ar-scope" id="ar-drawer" aria-label="<?php esc_attr_e( 'Mobile menu', 'hello-elementor-child' ); ?>" aria-hidden="true">
	<div class="ar-drawer__top">
		<?php if ( $logo ) : ?>
			<span class="ar-brand"><img src="<?php echo esc_url( $logo['url'] ); ?>" alt=""></span>
		<?php endif; ?>
		<button class="ar-burger ar-is-open" id="ar-drawer-close" aria-label="<?php esc_attr_e( 'Close menu', 'hello-elementor-child' ); ?>">
			<span></span><span></span><span></span>
		</button>
	</div>
	<?php ar_nav_menu( 'ar-drawer__link' ); ?>
	<div class="ar-drawer__cta">
		<a class="ar-btn ar-btn--block" href="<?php echo esc_url( $cta_u ); ?>"><?php echo esc_html( $cta_l ); ?></a>
		<a class="ar-btn ar-btn--dark ar-btn--block" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( 'Call ' . $phone ); ?></a>
	</div>
</aside>
