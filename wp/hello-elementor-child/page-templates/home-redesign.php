<?php
/**
 * Template Name: AR Home Redesign
 *
 * Assign this template to the homepage under Page Attributes. All content is
 * read from ACF, so nothing here needs editing to change copy or images.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$phone = ar_option( 'phone_display', '1800 726 434' );
$tel   = ar_option( 'phone_link', '1800726434' );
$cta_l = ar_option( 'cta_primary_label', 'Get a Free Quote' );
$cta_u = ar_option( 'cta_primary_url', '#ar-contact' );

/** Wrap the highlighted words of the heading in the orange span. */
function ar_hero_heading( $title, $highlight ) {
	$title = esc_html( $title );
	if ( $highlight ) {
		$highlight = esc_html( $highlight );
		$title     = str_replace( $highlight, '<em>' . $highlight . '</em>', $title );
	}
	return $title;
}
?>

<main id="content" class="ar-scope ar-home">

	<?php
	/* ==============================================================
	 * Banner
	 * ============================================================ */
	$video  = ar_field( 'hero_video_url' );
	$poster = ar_field( 'hero_poster' );
	?>
	<section class="ar-hero">
		<?php if ( $video || $poster ) : ?>
			<div class="ar-hero__media" aria-hidden="true">
				<?php if ( $video ) : ?>
					<video autoplay muted loop playsinline<?php echo $poster ? ' poster="' . esc_url( $poster['url'] ) . '"' : ''; ?>>
						<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
					</video>
				<?php elseif ( $poster ) : ?>
					<img src="<?php echo esc_url( $poster['url'] ); ?>" alt="">
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="ar-container ar-hero__inner">
			<div class="ar-hero__copy">
				<?php if ( ar_field( 'hero_badge_text' ) ) : ?>
					<span class="ar-hero__badge" data-reveal>
						<?php if ( ar_field( 'hero_badge_chip' ) ) : ?>
							<span class="ar-chip"><?php echo esc_html( ar_field( 'hero_badge_chip' ) ); ?></span>
						<?php endif; ?>
						<span class="ar-stars" aria-hidden="true"><?php echo str_repeat( ar_icon( 'star' ), 5 ); ?></span>
						<b><?php echo esc_html( ar_field( 'hero_badge_text' ) ); ?></b>
					</span>
				<?php endif; ?>

				<h1 data-reveal><?php echo wp_kses_post( ar_hero_heading( ar_field( 'hero_title', get_the_title() ), ar_field( 'hero_title_highlight' ) ) ); ?></h1>

				<?php if ( ar_field( 'hero_intro' ) ) : ?>
					<p class="ar-hero__text" data-reveal><?php echo esc_html( ar_field( 'hero_intro' ) ); ?></p>
				<?php endif; ?>

				<div class="ar-hero__ctas" data-reveal>
					<a class="ar-btn ar-btn--glow" href="<?php echo esc_url( $cta_u ); ?>"><?php echo esc_html( $cta_l ); ?> <?php echo ar_icon( 'arrow' ); ?></a>
					<?php if ( ar_field( 'hero_cta2_label' ) ) : ?>
						<a class="ar-btn ar-btn--dark" href="<?php echo esc_url( ar_field( 'hero_cta2_url', 'tel:' . $tel ) ); ?>"><?php echo ar_icon( 'phone' ); ?> <?php echo esc_html( ar_field( 'hero_cta2_label' ) ); ?></a>
					<?php endif; ?>
					<?php if ( ar_field( 'hero_cta3_label' ) ) : ?>
						<a class="ar-btn ar-btn--light" href="<?php echo esc_url( ar_field( 'hero_cta3_url', '/shop/' ) ); ?>"><?php echo ar_icon( 'cart' ); ?> <?php echo esc_html( ar_field( 'hero_cta3_label' ) ); ?></a>
					<?php endif; ?>
				</div>

				<?php $usps = ar_rows( 'hero_usps' ); ?>
				<?php if ( $usps ) : ?>
					<ul class="ar-hero__usps" data-reveal>
						<?php foreach ( $usps as $u ) : ?>
							<li><?php echo ar_icon( 'check' ); ?> <?php echo esc_html( $u['text'] ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php get_template_part( 'template-parts/enquiry-form', null, array( 'reveal' => 'right' ) ); ?>
		</div>
	</section>

	<?php /* ============ Reviews ============ */ ?>
	<section class="ar-section ar-reviews">
		<div class="ar-container">
			<?php ar_section_head( ar_field( 'reviews_eyebrow' ), ar_field( 'reviews_title' ), ar_field( 'reviews_intro' ) ); ?>
			<?php ar_shortcode_slot( ar_field( 'reviews_shortcode' ), 'Google reviews widget', 'Add the Trustindex shortcode under AR Design to render the live reviews here.' ); ?>
			<?php ar_cta_pair( array( 'note' => ar_field( 'reviews_cta_note' ) ) ); ?>
		</div>
	</section>

	<?php /* ============ Brands ============ */ ?>
	<?php $brand_logos = ar_field( 'brands_logos', array() ); ?>
	<section class="ar-section ar-brands">
		<div class="ar-container ar-center ar-brands__head" data-reveal>
			<?php ar_section_head( ar_field( 'brands_eyebrow' ), ar_field( 'brands_title' ), ar_field( 'brands_intro' ) ); ?>
		</div>

		<?php if ( $brand_logos ) : ?>
			<div class="ar-marquee" data-reveal>
				<div class="ar-marquee__track">
					<?php foreach ( array( 1, 2 ) as $pass ) : ?>
						<?php foreach ( $brand_logos as $logo ) : ?>
							<div class="ar-brand-card"<?php echo 2 === $pass ? ' aria-hidden="true"' : ''; ?>>
								<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo 2 === $pass ? '' : esc_attr( $logo['alt'] ); ?>" loading="lazy">
							</div>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="ar-container ar-brands__foot" data-reveal>
			<?php if ( ar_field( 'brands_cta_label' ) ) : ?>
				<a class="ar-btn ar-btn--dark" href="<?php echo esc_url( ar_field( 'brands_cta_url', '/shop/' ) ); ?>">
					<?php echo ar_icon( 'cart' ); ?> <?php echo esc_html( ar_field( 'brands_cta_label' ) ); ?>
				</a>
			<?php endif; ?>
			<?php if ( ar_field( 'brands_cta_note' ) ) : ?>
				<small><?php echo esc_html( ar_field( 'brands_cta_note' ) ); ?></small>
			<?php endif; ?>
		</div>
	</section>

	<?php /* ============ About ============ */ ?>
	<?php $about_img = ar_field( 'about_image' ); ?>
	<section class="ar-section ar-about" id="ar-about">
		<div class="ar-container">
			<div class="ar-about__inner">
				<?php if ( $about_img ) : ?>
					<div class="ar-media-frame" data-reveal="left">
						<img src="<?php echo esc_url( $about_img['url'] ); ?>" alt="<?php echo esc_attr( $about_img['alt'] ); ?>" loading="lazy">
						<?php if ( ar_field( 'about_badge_value' ) ) : ?>
							<div class="ar-media-badge">
								<strong><?php echo esc_html( ar_field( 'about_badge_value' ) ); ?></strong>
								<span><?php echo esc_html( ar_field( 'about_badge_label' ) ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div data-reveal="right">
					<?php ar_section_head( ar_field( 'about_eyebrow' ), ar_field( 'about_title' ), '', false ); ?>
					<?php echo wp_kses_post( ar_field( 'about_body' ) ); ?>

					<?php $list = ar_rows( 'about_list' ); ?>
					<?php if ( $list ) : ?>
						<ul class="ar-about__list">
							<?php foreach ( $list as $item ) : ?>
								<li><?php echo ar_icon( 'check-ring' ); ?> <?php echo esc_html( $item['text'] ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php ar_cta_pair( array( 'class' => 'ar-about__cta' ) ); ?>
				</div>
			</div>

			<?php $stats = ar_rows( 'stats' ); ?>
			<?php if ( $stats ) : ?>
				<div class="ar-stats" data-reveal>
					<?php foreach ( $stats as $stat ) : ?>
						<div class="ar-stat">
							<?php if ( is_numeric( $stat['value'] ) ) : ?>
								<strong data-count="<?php echo esc_attr( $stat['value'] ); ?>"
									<?php echo $stat['suffix'] ? 'data-suffix="' . esc_attr( $stat['suffix'] ) . '"' : ''; ?>
									<?php echo false !== strpos( $stat['value'], '.' ) ? 'data-decimals="1"' : ''; ?>>0</strong>
							<?php else : ?>
								<strong><?php echo esc_html( $stat['value'] ); ?><i>.</i></strong>
							<?php endif; ?>
							<span><?php echo esc_html( $stat['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php /* ============ Services ============ */ ?>
	<?php $services = ar_rows( 'services_items' ); ?>
	<section class="ar-section ar-services" id="ar-services">
		<div class="ar-container">
			<?php ar_section_head( ar_field( 'services_eyebrow' ), ar_field( 'services_title' ), ar_field( 'services_intro' ) ); ?>

			<?php if ( $services ) : ?>
				<div class="ar-svc-grid">
					<?php foreach ( $services as $i => $svc ) : ?>
						<article class="ar-svc" data-reveal>
							<div class="ar-svc__img">
								<span class="ar-svc__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
								<?php if ( $svc['image'] ) : ?>
									<img src="<?php echo esc_url( $svc['image']['url'] ); ?>" alt="<?php echo esc_attr( $svc['image']['alt'] ? $svc['image']['alt'] : $svc['title'] ); ?>" loading="lazy">
								<?php endif; ?>
							</div>
							<div class="ar-svc__body">
								<h3><?php echo esc_html( $svc['title'] ); ?></h3>
								<p><?php echo esc_html( $svc['text'] ); ?></p>
								<a class="ar-link-arrow" href="<?php echo esc_url( $svc['url'] ); ?>">
									<?php esc_html_e( 'Learn more', 'hello-elementor-child' ); ?> <span></span>
								</a>
							</div>
							<div class="ar-svc__bar"></div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php ar_cta_pair( array( 'note' => ar_field( 'services_cta_note' ), 'on_dark' => true ) ); ?>
		</div>
	</section>

	<?php /* ============ Process ============ */ ?>
	<?php $steps = ar_rows( 'process_items' ); ?>
	<section class="ar-section ar-process">
		<div class="ar-container">
			<?php ar_section_head( ar_field( 'process_eyebrow' ), ar_field( 'process_title' ), ar_field( 'process_intro' ) ); ?>
			<?php if ( $steps ) : ?>
				<div class="ar-steps">
					<?php foreach ( $steps as $i => $step ) : ?>
						<div class="ar-step" data-reveal>
							<div class="ar-step__n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></div>
							<h4><?php echo esc_html( $step['title'] ); ?></h4>
							<p><?php echo esc_html( $step['text'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php ar_cta_pair( array( 'note' => ar_field( 'process_cta_note' ) ) ); ?>
		</div>
	</section>

	<?php /* ============ Why us ============ */ ?>
	<?php
	$why_items = ar_rows( 'why_items' );
	$why_img   = ar_field( 'why_image' );
	?>
	<section class="ar-section ar-why">
		<div class="ar-container ar-why__inner">
			<div data-reveal="left">
				<?php ar_section_head( ar_field( 'why_eyebrow' ), ar_field( 'why_title' ), ar_field( 'why_intro' ), false ); ?>
				<?php if ( $why_items ) : ?>
					<div class="ar-why-list">
						<?php foreach ( $why_items as $item ) : ?>
							<div class="ar-why-item">
								<div class="ar-why-item__icon">
									<?php if ( $item['icon'] ) : ?>
										<img src="<?php echo esc_url( $item['icon']['url'] ); ?>" alt="" loading="lazy">
									<?php endif; ?>
								</div>
								<div>
									<h4><?php echo esc_html( $item['title'] ); ?></h4>
									<p><?php echo esc_html( $item['text'] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php ar_cta_pair( array( 'class' => 'ar-about__cta' ) ); ?>
			</div>

			<?php if ( $why_img ) : ?>
				<div class="ar-media-frame" data-reveal="right">
					<img src="<?php echo esc_url( $why_img['url'] ); ?>" alt="<?php echo esc_attr( $why_img['alt'] ); ?>" loading="lazy">
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php /* ============ Builders ============ */ ?>
	<?php $builders = ar_rows( 'builders_items' ); ?>
	<section class="ar-section ar-builders">
		<div class="ar-container">
			<?php ar_section_head( ar_field( 'builders_eyebrow' ), ar_field( 'builders_title' ), ar_field( 'builders_intro' ) ); ?>
			<?php if ( $builders ) : ?>
				<div class="ar-builders__grid">
					<?php foreach ( $builders as $b ) : ?>
						<a class="ar-builder" href="<?php echo esc_url( $b['url'] ); ?>" target="_blank" rel="noopener" data-reveal="zoom">
							<?php if ( $b['logo'] ) : ?>
								<img src="<?php echo esc_url( $b['logo']['url'] ); ?>" alt="<?php echo esc_attr( $b['logo']['alt'] ); ?>" loading="lazy">
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php ar_cta_pair( array( 'note' => ar_field( 'builders_cta_note' ) ) ); ?>
		</div>
	</section>

	<?php /* ============ Gallery ============ */ ?>
	<?php $shots = ar_field( 'gallery_images', array() ); ?>
	<?php if ( $shots ) : ?>
		<section class="ar-section ar-gallery" id="ar-gallery">
			<div class="ar-container">
				<div class="ar-gallery__head">
					<div data-reveal>
						<?php if ( ar_field( 'gallery_eyebrow' ) ) : ?>
							<span class="ar-eyebrow"><?php echo esc_html( ar_field( 'gallery_eyebrow' ) ); ?></span>
						<?php endif; ?>
						<h2 style="margin-bottom:0"><?php echo esc_html( ar_field( 'gallery_title' ) ); ?></h2>
					</div>
					<?php if ( ar_field( 'gallery_cta_label' ) ) : ?>
						<a class="ar-btn ar-btn--light" href="<?php echo esc_url( ar_field( 'gallery_cta_url', '/gallery/' ) ); ?>" data-reveal="right">
							<?php echo esc_html( ar_field( 'gallery_cta_label' ) ); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="ar-gallery__grid" id="ar-gallery-grid">
					<?php foreach ( $shots as $shot ) : ?>
						<button class="ar-shot" data-reveal="zoom" data-full="<?php echo esc_url( $shot['url'] ); ?>"
								aria-label="<?php esc_attr_e( 'Open project image', 'hello-elementor-child' ); ?>">
							<img src="<?php echo esc_url( isset( $shot['sizes']['large'] ) ? $shot['sizes']['large'] : $shot['url'] ); ?>"
								 alt="<?php echo esc_attr( $shot['alt'] ); ?>" loading="lazy">
							<span class="ar-shot__zoom"><?php echo ar_icon( 'zoom' ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ============ Facebook feed ============ */ ?>
	<section class="ar-section ar-social-feed">
		<div class="ar-container">
			<?php ar_section_head( ar_field( 'social_eyebrow' ), ar_field( 'social_title' ), ar_field( 'social_intro' ) ); ?>
			<?php ar_shortcode_slot( ar_field( 'social_shortcode' ), 'Facebook feed widget', 'Add the Custom Facebook Feed shortcode under AR Design to render the live timeline here.' ); ?>
			<div class="ar-social-feed__foot" data-reveal>
				<?php if ( ar_field( 'social_cta_label' ) ) : ?>
					<a class="ar-btn ar-btn--dark" href="<?php echo esc_url( ar_field( 'social_cta_url' ) ); ?>" target="_blank" rel="noopener">
						<?php echo ar_icon( 'facebook' ); ?> <?php echo esc_html( ar_field( 'social_cta_label' ) ); ?>
					</a>
				<?php endif; ?>
				<?php if ( ar_field( 'social_cta_note' ) ) : ?>
					<small><?php echo esc_html( ar_field( 'social_cta_note' ) ); ?></small>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php /* ============ CTA band ============ */ ?>
	<?php $band_img = ar_field( 'band_image' ); ?>
	<section class="ar-cta-band">
		<?php if ( $band_img ) : ?>
			<div class="ar-cta-band__bg" aria-hidden="true">
				<img src="<?php echo esc_url( $band_img['url'] ); ?>" alt="" loading="lazy">
			</div>
		<?php endif; ?>
		<div class="ar-container ar-cta-band__inner ar-on-dark">
			<?php if ( ar_field( 'band_eyebrow' ) ) : ?>
				<span class="ar-eyebrow" data-reveal><?php echo esc_html( ar_field( 'band_eyebrow' ) ); ?></span>
			<?php endif; ?>
			<h2 data-reveal><?php echo esc_html( ar_field( 'band_title' ) ); ?></h2>
			<?php if ( ar_field( 'band_intro' ) ) : ?>
				<p class="ar-lead" data-reveal><?php echo esc_html( ar_field( 'band_intro' ) ); ?></p>
			<?php endif; ?>
			<?php ar_cta_pair( array( 'class' => 'ar-btns', 'on_dark' => true ) ); ?>
		</div>
	</section>

	<?php /* ============ FAQ ============ */ ?>
	<?php $faqs = ar_rows( 'faq_items' ); ?>
	<?php if ( $faqs ) : ?>
		<section class="ar-section ar-faq">
			<div class="ar-container ar-faq__inner">
				<div class="ar-faq__aside" data-reveal="left">
					<?php ar_section_head( ar_field( 'faq_eyebrow' ), ar_field( 'faq_title' ), ar_field( 'faq_intro' ), false ); ?>
					<?php if ( ar_field( 'faq_helper_title' ) ) : ?>
						<div class="ar-helper">
							<h4><?php echo esc_html( ar_field( 'faq_helper_title' ) ); ?></h4>
							<p><?php echo esc_html( ar_field( 'faq_helper_text' ) ); ?></p>
							<a class="ar-btn ar-btn--dark ar-btn--sm" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( 'Call ' . $phone ); ?></a>
						</div>
					<?php endif; ?>
				</div>

				<div class="ar-acc" id="ar-acc">
					<?php foreach ( $faqs as $faq ) : ?>
						<div class="ar-acc__item">
							<button class="ar-acc__btn" aria-expanded="false">
								<?php echo esc_html( $faq['question'] ); ?><span class="ar-acc__icon" aria-hidden="true"></span>
							</button>
							<div class="ar-acc__panel"><div><?php echo wp_kses_post( $faq['answer'] ); ?></div></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ============ Contact ============ */ ?>
	<?php get_template_part( 'template-parts/contact-section' ); ?>

</main>

<?php
get_footer();
