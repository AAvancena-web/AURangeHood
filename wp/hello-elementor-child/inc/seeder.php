<?php
/**
 * One time content seeder.
 *
 * Fills the ACF fields on the page that already has the redesign template
 * assigned. It never creates a page, and it never runs twice: the version flag
 * in wp_options stops it. Re-run it deliberately from Tools if you need to.
 *
 * Images are matched against the media library by URL, so the seeder reuses the
 * attachments the site already has rather than importing anything.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AR_SEED_VERSION', '1.0.0' );
define( 'AR_SEED_OPTION', 'ar_redesign_seed_version' );

/**
 * Find the page the seeder should write to.
 *
 * 1. a page using the redesign template
 * 2. otherwise the static front page
 */
function ar_seed_target_id() {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => AR_HOME_TEMPLATE,
		)
	);
	if ( ! empty( $pages ) ) {
		return (int) $pages[0];
	}

	$front = (int) get_option( 'page_on_front' );
	return $front ? $front : 0;
}

/**
 * Resolve a media library attachment from a URL.
 * Falls back to matching the file name when the domain differs.
 */
function ar_attachment_id( $url ) {
	if ( ! $url ) {
		return 0;
	}

	$id = attachment_url_to_postid( $url );
	if ( $id ) {
		return (int) $id;
	}

	// Same file, different domain (for example seeded from a staging URL).
	$path = wp_parse_url( $url, PHP_URL_PATH );
	if ( $path ) {
		$id = attachment_url_to_postid( home_url( $path ) );
		if ( $id ) {
			return (int) $id;
		}
	}

	// Last resort: match on the file name.
	global $wpdb;
	$file = basename( wp_parse_url( $url, PHP_URL_PATH ) );
	$id   = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s LIMIT 1",
			'%' . $wpdb->esc_like( $file )
		)
	);
	return $id ? (int) $id : 0;
}

/** Map a list of URLs to attachment IDs, dropping anything not found. */
function ar_attachment_ids( array $urls ) {
	$ids = array();
	foreach ( $urls as $url ) {
		$id = ar_attachment_id( $url );
		if ( $id ) {
			$ids[] = $id;
		}
	}
	return $ids;
}

/** Base URL the design references. */
function ar_up( $path ) {
	return 'https://australianrangehoods.com.au/wp-content/uploads/' . ltrim( $path, '/' );
}

/* ======================================================================
 * The default content
 * ==================================================================== */

function ar_seed_global_values() {
	return array(
		'header_logo'         => ar_attachment_id( ar_up( '2024/12/site-logo.png' ) ),
		'tagline'             => 'Do it once, Do it right',
		'phone_display'       => '1800 726 434',
		'phone_link'          => '1800726434',
		'mobile_display'      => '0434 086 027',
		'mobile_link'         => '0434086027',
		'email'               => 'admin@australianrangehoods.com.au',
		'address'             => 'Narre Warren South VIC, servicing all of Melbourne',
		'address_url'         => 'https://maps.app.goo.gl/UEbBZTrEFrk1rUP78',
		'hours'               => 'Monday to Friday, 7am to 5pm. Saturday by appointment.',
		'cta_primary_label'   => 'Get a Free Quote',
		'cta_primary_url'     => '#ar-contact',
		'show_cart'           => 1,
		'socials'             => array(
			array( 'network' => 'facebook', 'url' => 'https://www.facebook.com/australianrangehoods' ),
			array( 'network' => 'instagram', 'url' => 'https://www.instagram.com/australian_rangehoods/' ),
		),

		'form_title'          => 'Request your free quote',
		'form_intro'          => 'Send us a few details and our team will be in touch shortly.',
		'form_shortcode'      => '',
		'form_note'           => 'Your details stay private and are only used to prepare your quote.',

		'footer_logo'         => ar_attachment_id( ar_up( '2024/12/footer-logo.png' ) ),
		'footer_about'        => 'At Australian Rangehoods, we specialise in delivering high performance rangehood solutions tailored to the needs of modern homes and outdoor spaces.',
		'footer_col2_title'   => 'Quick links',
		'footer_col2_links'   => array(
			array( 'label' => 'Home', 'url' => '/' ),
			array( 'label' => 'About Us', 'url' => '/about-us/' ),
			array( 'label' => 'Gallery', 'url' => '/gallery/' ),
			array( 'label' => 'Shop', 'url' => '/shop/' ),
			array( 'label' => 'Contact Us', 'url' => '/contact-us/' ),
			array( 'label' => 'Return and Refund Policy', 'url' => '/return-and-refund-policy/' ),
		),
		'footer_col3_title'   => 'Services',
		'footer_col3_links'   => array(
			array( 'label' => 'Rangehood Installation', 'url' => '/rangehood-installation/' ),
			array( 'label' => 'Custom Rangehoods', 'url' => '/custom-rangehoods/' ),
			array( 'label' => 'Ducting Installation & Upgrades', 'url' => '/ducting-installation-upgrades/' ),
			array( 'label' => 'Maintenance & Repairs', 'url' => '/maintenance-repairs/' ),
		),
		'footer_col4_title'   => 'Contact info',
		'footer_copyright'    => 'Copyright ' . gmdate( 'Y' ) . ' Australian Rangehoods. All rights reserved.',
		'footer_designer'     => 'Digital Movement',
		'footer_designer_url' => 'https://www.digitalmovement.com.au/',

		'inner_banner_image'  => ar_attachment_id( ar_up( '2025/10/gallery2.jpg' ) ),
		'inner_banner_form'   => 1,
	);
}

function ar_seed_home_values() {
	return array(
		/* ---- Banner ---- */
		'hero_badge_chip'      => '5.0 rated',
		'hero_badge_text'      => '183 Google reviews',
		'hero_title'           => "Melbourne's go-to service provider for Rangehood installation",
		'hero_title_highlight' => 'Rangehood installation',
		'hero_intro'           => 'Licensed installers of Qasair, Sirius and Whispair. From custom canopies and alfresco hoods to full ducting upgrades, we deliver quiet, powerful ventilation that is measured, fitted and finished properly the first time.',
		'hero_cta2_label'      => 'Call Us Today',
		'hero_cta2_url'        => 'tel:1800726434',
		'hero_cta3_label'      => 'Shop Our Rangehood Products',
		'hero_cta3_url'        => '/shop/',
		'hero_usps'            => array(
			array( 'text' => 'Licensed Qasair, Sirius & Whispair installer' ),
			array( 'text' => "Trusted by Victoria's top builders" ),
			array( 'text' => 'Clean, tidy finish on every job' ),
		),
		'hero_video_url'       => ar_up( '2025/10/Dean-intro-video-new.mp4' ),
		'hero_poster'          => ar_attachment_id( ar_up( '2026/04/video-img.png' ) ),

		/* ---- Reviews ---- */
		'reviews_eyebrow'      => 'Satisfied customers',
		'reviews_title'        => 'Trusted by homeowners across Victoria',
		'reviews_intro'        => 'Our team makes sure every job is done right the first time. That is why homeowners love working with Australian Rangehoods.',
		'reviews_shortcode'    => '[trustindex no-registration=google]',
		'reviews_cta_note'     => 'Join the Melbourne homeowners who rated us 5.0 out of 5.',

		/* ---- Brands ---- */
		'brands_eyebrow'       => 'Top brands',
		'brands_title'         => 'We install the best rangehood brands',
		'brands_intro'         => 'Licensed Qasair, Sirius and Whispair installer. Browse and shop these leading brands online, delivered to your home.',
		'brands_logos'         => ar_attachment_ids( array(
			ar_up( '2025/08/logo-slid.jpg' ),
			ar_up( '2025/08/logo-slid2.jpg' ),
			ar_up( '2025/08/logo-slid3.jpg' ),
			ar_up( '2025/08/logo-slid4.jpg' ),
			ar_up( '2025/10/logo-slid3.jpg' ),
		) ),
		'brands_cta_label'     => 'Shop Our Rangehood Products',
		'brands_cta_url'       => '/shop/',
		'brands_cta_note'      => 'See the full range, prices and product details.',

		/* ---- About ---- */
		'about_eyebrow'        => 'Welcome to Australian Rangehoods',
		'about_title'          => 'Expert ventilation solutions, engineered for your kitchen',
		'about_body'           => '<p>We specialise in high performance rangehood solutions tailored to modern kitchens and outdoor entertaining spaces. Whether you are renovating, building new or upgrading an alfresco area, our installations combine style, airflow and durability so your space stays clean, comfortable and efficient.</p><p>As a licensed installer for leading Australian and international brands, we bring precision to every job. From custom ducting routes to perfectly aligned canopies, we focus on the detail that makes a rangehood quiet, powerful and built to last.</p>',
		'about_image'          => ar_attachment_id( ar_up( '2025/08/welcome-left.jpg' ) ),
		'about_badge_value'    => '5.0',
		'about_badge_label'    => 'Google rating',
		'about_list'           => array(
			array( 'text' => 'Ducted, undermount, canopy, island and alfresco specialists' ),
			array( 'text' => 'Pre duct inspections so your build is ready before fit off' ),
			array( 'text' => 'Clear quotes, punctual arrival and a tidy site when we leave' ),
		),
		'stats'                => array(
			array( 'value' => '183', 'suffix' => '+', 'label' => 'Google reviews' ),
			array( 'value' => '5.0', 'suffix' => '', 'label' => 'Star average rating' ),
			array( 'value' => '4', 'suffix' => '', 'label' => 'Core services' ),
			array( 'value' => 'Melbourne', 'suffix' => '', 'label' => 'Wide service area' ),
		),

		/* ---- Services ---- */
		'services_eyebrow'     => 'What we offer',
		'services_title'       => 'We service all types of rangehoods',
		'services_intro'       => 'Four specialist services covering every stage, from product selection and ducting through to repairs on an existing unit.',
		'services_items'       => array(
			array(
				'image' => ar_attachment_id( ar_up( '2025/08/Services-slide.jpg' ) ),
				'title' => 'Rangehood Installation',
				'text'  => 'We install every type of rangehood, from slim undermounts to statement canopies, with the ducting done properly.',
				'url'   => '/rangehood-installation/',
			),
			array(
				'image' => ar_attachment_id( ar_up( '2025/08/Services-slide2.jpg' ) ),
				'title' => 'Custom Rangehoods',
				'text'  => 'Bespoke hoods shaped, sized and finished to match your cabinetry, joinery and cooking style.',
				'url'   => '/custom-rangehoods/',
			),
			array(
				'image' => ar_attachment_id( ar_up( '2025/08/Services-slide3.jpg' ) ),
				'title' => 'Ducting Installation & Upgrades',
				'text'  => 'Correctly sized ducting for peak efficiency and proper removal of steam, smoke and cooking odours.',
				'url'   => '/ducting-installation-upgrades/',
			),
			array(
				'image' => ar_attachment_id( ar_up( '2025/10/slider.jpg' ) ),
				'title' => 'Maintenance & Repairs',
				'text'  => 'Servicing, filter care and repairs that extend the life and airflow of the rangehood you already own.',
				'url'   => '/maintenance-repairs/',
			),
		),
		'services_cta_note'    => 'Not sure which option suits your kitchen? We will talk it through with you.',

		/* ---- Process ---- */
		'process_eyebrow'      => 'How we work',
		'process_title'        => 'A simple process, done once and done right',
		'process_intro'        => 'Clear communication from the first call through to the final test, so you always know what happens next.',
		'process_items'        => array(
			array( 'title' => 'Free quote', 'text' => 'Tell us about your kitchen, cooktop and layout. We recommend the right unit and send a clear written price.' ),
			array( 'title' => 'Measure & duct plan', 'text' => 'We confirm sizes on site and map the best ducting route before any cutting or cabinetry work begins.' ),
			array( 'title' => 'Precision install', 'text' => 'Your rangehood, motor and ducting are fitted to manufacturer specification and aligned to the millimetre.' ),
			array( 'title' => 'Test & tidy', 'text' => 'We test airflow on every speed, walk you through the controls and leave the space spotless.' ),
		),
		'process_cta_note'     => 'Start with step one. Most quotes come back the same business day.',

		/* ---- Why us ---- */
		'why_eyebrow'          => 'Why choose Australian Rangehoods',
		'why_title'            => 'Precision ventilation, perfectly installed',
		'why_intro'            => "When it comes to custom rangehoods, Australian Rangehoods is Melbourne's trusted expert. We combine precision craftsmanship, attention to detail and a focus on customer satisfaction to deliver exceptional results.",
		'why_items'            => array(
			array(
				'icon'  => ar_attachment_id( ar_up( '2025/08/icon-slider.png' ) ),
				'title' => 'Licensed expertise',
				'text'  => 'Certified to install industry leading brands, so your system is fitted safely and to the highest standard.',
			),
			array(
				'icon'  => ar_attachment_id( ar_up( '2025/08/icon-slider2.png' ) ),
				'title' => 'Comprehensive product range',
				'text'  => 'From Qasair premium rangehoods to Bosch, V-ZUG and Gaggenau, there is an option for every need and style.',
			),
			array(
				'icon'  => ar_attachment_id( ar_up( '2025/08/icon-slider3.png' ) ),
				'title' => 'Customer focused service',
				'text'  => 'We work closely with you to design and implement a solution that fits your space, budget and lifestyle.',
			),
		),
		'why_image'            => ar_attachment_id( ar_up( '2025/08/right-colam.jpg' ) ),

		/* ---- Builders ---- */
		'builders_eyebrow'     => 'Top builders',
		'builders_title'       => "Proudly working with Victoria's top builders",
		'builders_intro'       => "We work alongside some of Victoria's leading residential builders, delivering reliable service and high quality results on premium home projects.",
		'builders_items'       => array(
			array( 'logo' => ar_attachment_id( ar_up( '2025/08/Builders1-1.png' ) ), 'url' => 'https://sherbrookeconstructions.com.au/' ),
			array( 'logo' => ar_attachment_id( ar_up( '2025/08/Builders2.png' ) ), 'url' => 'https://www.l37.com.au/' ),
			array( 'logo' => ar_attachment_id( ar_up( '2025/08/Builders3.png' ) ), 'url' => 'https://informdesign.com.au/' ),
			array( 'logo' => ar_attachment_id( ar_up( '2025/08/Builders4.png' ) ), 'url' => 'https://www.mazzei.com.au/' ),
		),
		'builders_cta_note'    => 'Building or renovating? Bring us in early so the ducting is planned properly.',

		/* ---- Gallery ---- */
		'gallery_eyebrow'      => 'Our recent projects',
		'gallery_title'        => 'Jobs we have completed',
		'gallery_intro'        => '',
		'gallery_images'       => ar_attachment_ids( array(
			ar_up( '2025/10/1.png' ), ar_up( '2025/10/2.png' ), ar_up( '2025/10/3.png' ),
			ar_up( '2025/10/4.png' ), ar_up( '2025/10/5.png' ), ar_up( '2025/10/gallery.jpg' ),
			ar_up( '2025/10/7.png' ), ar_up( '2025/10/8.png' ), ar_up( '2025/10/11.png' ),
			ar_up( '2025/10/12.png' ),
		) ),
		'gallery_cta_label'    => 'View Full Gallery',
		'gallery_cta_url'      => '/gallery/',

		/* ---- Facebook feed ---- */
		'social_eyebrow'       => '#australianrangehoods',
		'social_title'         => 'Recent posts from Australian Rangehoods',
		'social_intro'         => 'Follow the latest installs, pre duct inspections and behind the scenes updates straight from our Facebook page.',
		'social_shortcode'     => '[custom-facebook-feed feed=1]',
		'social_cta_label'     => 'Follow Us On Facebook',
		'social_cta_url'       => 'https://www.facebook.com/australianrangehoods',
		'social_cta_note'      => 'New projects posted every week.',

		/* ---- CTA band ---- */
		'band_eyebrow'         => 'Contact us',
		'band_title'           => 'Call us for your free quote today',
		'band_intro'           => 'Proudly serving Melbourne and the surrounding communities, bringing premium rangehood solutions to you.',
		'band_image'           => ar_attachment_id( ar_up( '2025/10/gallery2.jpg' ) ),

		/* ---- FAQ ---- */
		'faq_eyebrow'          => 'Good to know',
		'faq_title'            => 'Frequently asked questions',
		'faq_intro'            => 'Everything homeowners and builders usually ask us before booking an installation.',
		'faq_items'            => ar_seed_faq_rows(),
		'faq_helper_title'     => 'Still have a question?',
		'faq_helper_text'      => 'Speak with our team for straight answers on sizing, ducting and product selection.',

		/* ---- Contact ---- */
		'contact_eyebrow'      => 'Contact us today',
		'contact_title'        => 'Get your dream rangehood installation',
		'contact_intro'        => 'Fill out the form and a team member will be in touch shortly, or call us now for an immediate answer.',
		'contact_map_src'      => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d804839.6295345018!2d144.47214422456392!3d-38.0026808965019362!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad61b56eb2f2145%3A0xbd476474bdc8bb73!2sAustralian%20Rangehoods!5e0!3m2!1sen!2sau!4v1785109966471!5m2!1sen!2sau',
	);
}

function ar_seed_faq_rows() {
	return array(
		array(
			'question' => 'Why is a quality rangehood important for my kitchen?',
			'answer'   => '<p>During cooking, steam, smoke, grease particles and odours are released into the air. Without effective extraction, these contaminants settle on cabinetry, walls and ceilings, making cleaning harder over time.</p><p>A well designed rangehood improves air quality, supports a more enjoyable cooking experience and helps protect the long term condition of your kitchen finishes and appliances.</p>',
		),
		array(
			'question' => 'How do I choose the right rangehood for my home?',
			'answer'   => '<p>It starts with your cooking habits, kitchen layout and design preferences. Cooktop size, cooking frequency, ceiling height and available installation space all influence which solution performs best.</p><p>Different kitchens suit canopy, undermount, island, alfresco or custom designed options. We also weigh extraction performance, noise levels, filters and maintenance so the unit suits you long term.</p>',
		),
		array(
			'question' => 'Can I customise a rangehood to suit my kitchen design?',
			'answer'   => '<p>Yes. Customisation gives you flexibility in size, shape, finish and overall appearance, so the hood reflects the style of your kitchen instead of settling for a standard product.</p><p>Custom hoods are especially useful with bespoke cabinetry, unusual layouts or specific architectural features, and can be designed to complement the surrounding materials.</p>',
		),
		array(
			'question' => 'How often should rangehood filters be cleaned?',
			'answer'   => '<p>Follow the maintenance guidelines for your model. Households that cook frequently, or prepare foods that generate more grease, will need to clean filters more often than occasional cooks.</p><p>Clean filters maintain efficient ventilation, reduce odours and support the long term performance of the unit.</p>',
		),
		array(
			'question' => 'What is the difference between ducted and recirculating rangehoods?',
			'answer'   => '<p>Ducted rangehoods vent fumes, steam and airborne particles outside the home, which is highly effective because contaminants are physically removed from the indoor environment.</p><p>Recirculating rangehoods filter the air and return it to the kitchen. They suit properties where external ducting is not practical, though performance depends heavily on filter quality and maintenance.</p>',
		),
		array(
			'question' => 'Are rangehoods suitable for outdoor and alfresco kitchens?',
			'answer'   => '<p>Yes. Barbecues and outdoor cooking appliances generate large amounts of smoke, heat, grease and odours that can build up beneath covered entertaining spaces.</p><p>We offer solutions designed to handle changing weather conditions while still delivering reliable extraction, which keeps guests comfortable and surrounding surfaces cleaner.</p>',
		),
		array(
			'question' => 'How long does a rangehood typically last?',
			'answer'   => '<p>Lifespan depends on product quality, installation standards, frequency of use and ongoing maintenance. A well built unit that receives regular care provides reliable performance for many years.</p><p>Proper filter cleaning, routine inspections and prompt attention to any performance issue all help extend the life of the system.</p>',
		),
		array(
			'question' => 'Why should I choose Australian Rangehoods?',
			'answer'   => '<p>We guide you through the whole process, from initial selection to final installation, balancing performance, durability and design for your specific space.</p><p>Whether you are building new, renovating or creating an outdoor entertaining area, you get practical expertise and a customer focused experience from start to finish.</p>',
		),
	);
}

/* ======================================================================
 * Runner
 * ==================================================================== */

/**
 * Write the defaults. Repeaters are written with update_field() using the
 * field key, which is what lets ACF store the row count and sub field meta
 * correctly rather than leaving orphaned rows.
 *
 * @param bool $overwrite Replace values that already have content.
 * @return array Report of what happened.
 */
function ar_run_seeder( $overwrite = false ) {
	$report = array(
		'target'     => 0,
		'written'    => 0,
		'skipped'    => 0,
		'missing'    => array(),
		'errors'     => array(),
	);

	if ( ! function_exists( 'update_field' ) ) {
		$report['errors'][] = 'Advanced Custom Fields is not active.';
		return $report;
	}

	$target = ar_seed_target_id();
	if ( ! $target ) {
		$report['errors'][] = 'No page uses the AR Home Redesign template yet, and no static front page is set. Assign the template to a page, then run the seeder.';
		return $report;
	}
	$report['target'] = $target;

	$sets = array(
		array( 'values' => ar_seed_global_values(), 'post_id' => 'option' ),
		array( 'values' => ar_seed_home_values(), 'post_id' => $target ),
	);

	foreach ( $sets as $set ) {
		foreach ( $set['values'] as $name => $value ) {
			$key = 'field_ar_' . $name;

			if ( ! $overwrite ) {
				$existing = get_field( $name, $set['post_id'] );
				$has      = ! ( null === $existing || '' === $existing || array() === $existing || false === $existing );
				if ( $has ) {
					$report['skipped']++;
					continue;
				}
			}

			// An image field seeded with 0 means the attachment was not found.
			if ( 0 === $value ) {
				$report['missing'][] = $name;
				continue;
			}

			update_field( $key, $value, $set['post_id'] );
			$report['written']++;
		}
	}

	return $report;
}

/**
 * Run once, automatically, the first time the admin is loaded after the
 * template has been assigned.
 */
function ar_maybe_seed() {
	if ( ! is_admin() || wp_doing_ajax() || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	if ( AR_SEED_VERSION === get_option( AR_SEED_OPTION ) ) {
		return;
	}
	if ( ! function_exists( 'update_field' ) ) {
		return; // ACF not ready yet, try again next load.
	}
	if ( ! ar_seed_target_id() ) {
		return; // Nothing to seed into yet, stay quiet until the template is assigned.
	}

	$report = ar_run_seeder( false );
	update_option( AR_SEED_OPTION, AR_SEED_VERSION );
	set_transient( 'ar_seed_report', $report, 60 );
}
add_action( 'admin_init', 'ar_maybe_seed', 20 );

/**
 * Manual re-run, from Tools. Useful after adding the images to the media
 * library, or to push the defaults over edited content.
 */
function ar_seed_tools_page() {
	add_management_page(
		'AR Design Seeder',
		'AR Design Seeder',
		'edit_theme_options',
		'ar-design-seeder',
		'ar_seed_tools_screen'
	);
}
add_action( 'admin_menu', 'ar_seed_tools_page' );

function ar_seed_tools_screen() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( 'Not allowed.' );
	}

	$report = null;
	if ( isset( $_POST['ar_seed_run'] ) && check_admin_referer( 'ar_seed_run' ) ) {
		$overwrite = ! empty( $_POST['ar_seed_overwrite'] );
		$report    = ar_run_seeder( $overwrite );
		update_option( AR_SEED_OPTION, AR_SEED_VERSION );
	}

	$target = ar_seed_target_id();
	?>
	<div class="wrap">
		<h1>Australian Rangehoods design seeder</h1>

		<?php if ( $report ) : ?>
			<div class="notice notice-success"><p>
				<strong><?php echo (int) $report['written']; ?></strong> fields written,
				<strong><?php echo (int) $report['skipped']; ?></strong> left alone.
				<?php if ( ! empty( $report['missing'] ) ) : ?>
					<br>Images not found in the media library:
					<code><?php echo esc_html( implode( ', ', $report['missing'] ) ); ?></code>
				<?php endif; ?>
				<?php foreach ( $report['errors'] as $error ) : ?>
					<br><strong><?php echo esc_html( $error ); ?></strong>
				<?php endforeach; ?>
			</p></div>
		<?php endif; ?>

		<p>
			Target page:
			<?php if ( $target ) : ?>
				<strong><?php echo esc_html( get_the_title( $target ) ); ?></strong>
				(<a href="<?php echo esc_url( get_edit_post_link( $target ) ); ?>">edit</a>)
			<?php else : ?>
				<strong>none found.</strong> Assign the <em>AR Home Redesign</em> template to a page first.
			<?php endif; ?>
		</p>
		<p>Last run: <code><?php echo esc_html( get_option( AR_SEED_OPTION, 'never' ) ); ?></code></p>

		<form method="post">
			<?php wp_nonce_field( 'ar_seed_run' ); ?>
			<p>
				<label>
					<input type="checkbox" name="ar_seed_overwrite" value="1">
					Overwrite fields that already have content
				</label>
			</p>
			<p class="description" style="max-width:640px">
				Without the checkbox the seeder only fills fields that are still empty, so
				anything already edited is preserved.
			</p>
			<p><button class="button button-primary" name="ar_seed_run" value="1">Run the seeder</button></p>
		</form>
	</div>
	<?php
}

/**
 * Tell the user what the automatic run did.
 */
function ar_seed_notice() {
	$report = get_transient( 'ar_seed_report' );
	if ( ! $report ) {
		return;
	}
	delete_transient( 'ar_seed_report' );
	printf(
		'<div class="notice notice-success is-dismissible"><p>Australian Rangehoods design: seeded <strong>%d</strong> fields. <a href="%s">Seeder options</a></p></div>',
		(int) $report['written'],
		esc_url( admin_url( 'tools.php?page=ar-design-seeder' ) )
	);
}
add_action( 'admin_notices', 'ar_seed_notice' );
