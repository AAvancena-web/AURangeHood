<?php
/**
 * The enquiry form card, shared by the homepage banner and the inner pages.
 *
 * When a shortcode is set under AR Design > Enquiry form, the real Elementor
 * form renders inside the styled card. Otherwise the static design is shown so
 * the layout is still complete.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title     = ar_option( 'form_title', 'Request your free quote' );
$intro     = ar_option( 'form_intro', 'Send us a few details and our team will be in touch shortly.' );
$shortcode = trim( (string) ar_option( 'form_shortcode', '' ) );
$note      = ar_option( 'form_note', 'Your details stay private and are only used to prepare your quote.' );
$reveal    = isset( $args['reveal'] ) ? $args['reveal'] : 'right';
?>
<div class="ar-form-card" data-reveal="<?php echo esc_attr( $reveal ); ?>">
	<div class="ar-form-card__head">
		<h3><?php echo esc_html( $title ); ?></h3>
		<?php if ( $intro ) : ?>
			<p><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( $shortcode ) : ?>
		<div class="ar-form-embed"><?php echo do_shortcode( $shortcode ); ?></div>
	<?php else : ?>
		<form class="ar-js-form" novalidate>
			<div class="ar-form-grid">
				<p class="ar-field"><label for="ar-f-first">First name</label><input type="text" id="ar-f-first" name="first_name" placeholder="John" required></p>
				<p class="ar-field"><label for="ar-f-last">Last name</label><input type="text" id="ar-f-last" name="last_name" placeholder="Smith" required></p>
				<p class="ar-field"><label for="ar-f-phone">Phone number</label><input type="tel" id="ar-f-phone" name="phone" placeholder="04XX XXX XXX" required></p>
				<p class="ar-field"><label for="ar-f-email">Email address</label><input type="email" id="ar-f-email" name="email" placeholder="you@example.com" required></p>
				<p class="ar-field"><label for="ar-f-suburb">Suburb</label><input type="text" id="ar-f-suburb" name="suburb" placeholder="e.g. Narre Warren South" required></p>
				<p class="ar-field ar-field--select">
					<label for="ar-f-service">Service required</label>
					<select id="ar-f-service" name="service" required>
						<option value="">Please select a service</option>
						<option>Rangehood Installation</option>
						<option>Custom Rangehoods</option>
						<option>Ducting Installation &amp; Upgrades</option>
						<option>Maintenance &amp; Repairs</option>
					</select>
				</p>
				<p class="ar-field ar-field--full"><label for="ar-f-message">Tell us about your project</label><textarea id="ar-f-message" name="message" placeholder="Cooktop size, kitchen layout, preferred timeline"></textarea></p>
			</div>
			<button class="ar-btn ar-btn--block" type="submit" style="margin-top:16px">
				Send My Enquiry <?php echo ar_icon( 'arrow' ); ?>
			</button>
			<div class="ar-form-foot"><?php echo ar_icon( 'lock' ); ?> <?php echo esc_html( $note ); ?></div>
			<div class="ar-form-note" role="status"></div>
		</form>
	<?php endif; ?>

	<?php if ( $shortcode && $note ) : ?>
		<div class="ar-form-foot"><?php echo ar_icon( 'lock' ); ?> <?php echo esc_html( $note ); ?></div>
	<?php endif; ?>
</div>
