# Australian Rangehoods redesign, WordPress build

Drop-in files for the `hello-elementor-child` theme. Nothing in the parent
theme is touched and no existing page has to be rebuilt.

## What is here

```
hello-elementor-child/
├── functions-append.php        one line to paste into your functions.php
├── header.php                  replaces the Elementor header site wide
├── footer.php                  replaces the Elementor footer site wide
├── inc/
│   ├── bootstrap.php           assets, template registration, banner hook
│   ├── render.php              helpers: icons, CTA pair, menu walker
│   ├── acf-fields.php          all 133 fields, registered in PHP
│   └── seeder.php              one time content seeder + Tools screen
├── page-templates/
│   └── home-redesign.php       "AR Home Redesign", assign it to the homepage
├── template-parts/
│   ├── site-header.php         global header
│   ├── site-footer.php         global footer
│   ├── inner-banner.php        inner page banner with the form on the right
│   ├── enquiry-form.php        the form card, shared by both
│   └── contact-section.php     contact details, form and map
└── assets/
    ├── css/ar-redesign.css     every rule scoped to .ar-scope
    └── js/ar-redesign.js
```

## Install

1. **Back up the site**, or do this on staging first.
2. Copy the folders above into `wp-content/themes/hello-elementor-child/`.
   `header.php` and `footer.php` replace the existing ones, so keep a copy.
3. Paste the single `require_once` line from `functions-append.php` at the end
   of your existing `functions.php`.
4. Make sure **Advanced Custom Fields PRO** is active. PRO is required: the
   build uses repeater, gallery and options page fields.
5. Edit the homepage, and under **Page Attributes > Template** choose
   **AR Home Redesign**. Update.
6. Load any admin page. The seeder runs once and fills every field, including
   all 11 repeaters, with the approved content. You will see a notice telling
   you how many fields were written.
7. Check **Tools > AR Design Seeder** if you want to re-run it or overwrite.

## Where the content lives

* Per page copy: the **AR Home Redesign** field tabs on the homepage.
* Header, footer, phone, email, socials and the enquiry form: **AR Design** in
  the admin sidebar.
* Each inner page has an **AR Page Banner** box to set its own heading, intro,
  background and whether the form shows.

## The enquiry form

The form card is styled markup with a slot in the middle. Whatever shortcode
you put into **AR Design > Enquiry form > Form shortcode** renders inside it,
and that one setting drives the homepage banner, every inner page banner and
the contact block. Until a shortcode is set, a static version of the design
shows so the layout is complete, but it does not submit anywhere.

Both plugins are styled to match the design.

### Contact Form 7

1. Install Contact Form 7, then **Contact > Add New**.
2. Paste the contents of `docs/contact-form-7-template.html` into the Form tab,
   replacing what is there. The wrapper divs and labels in that file are what
   the two column grid and the stacked labels hook onto, so keep the classes.
3. Set the Mail tab from `docs/contact-form-7-mail.txt`.
4. Copy the shortcode CF7 gives you into the Form shortcode field, for example
   `[contact-form-7 id="a1b2c3d" title="Rangehood Enquiry"]`.

CF7 normally injects `<p>` and `<br>` tags that would break the grid. The theme
turns that off, but only for the form whose shortcode is in that field, so any
other CF7 form on the site is untouched.

Worth knowing before you commit to CF7:

* CF7 does not store submissions. Install **Flamingo** (same author) or every
  enquiry exists only as an email, and a mail failure loses the lead silently.
* Spam protection is not built in. Wire up reCAPTCHA or Akismet in CF7 itself.
* Use a From address on your own domain, never the visitor's, or SPF and DMARC
  will reject the mail.

### Elementor Forms

Paste the template shortcode instead, for example
`[elementor-template id="1234"]`. Nothing else to do: the field, label, select
and submit styling is already mapped.

## Plugin shortcodes already seeded

| Section | Shortcode |
| --- | --- |
| Reviews | `[trustindex no-registration=google]` |
| Facebook feed | `[custom-facebook-feed feed=1]` |

## Notes worth knowing

* **The CSS cannot leak.** Every rule is prefixed with `.ar-scope`, and the
  custom properties are renamed `--ar-*`, so Elementor, WooCommerce and the
  existing pages are untouched. The accent reads Elementor's own
  `--e-global-color-accent` with the brand value as a fallback rather than
  overwriting it.
* **The header and footer take over site wide.** That is deliberate, so inner
  pages match. To hand either back to Elementor:
  `add_filter( 'ar_use_custom_header_footer', '__return_false' );`
* **The seeder never creates a page** and never runs twice. It writes into the
  page that already has the template assigned, or the static front page.
* **Images are reused, not imported.** The seeder matches the media library by
  URL, so the existing uploads are linked. Any image it cannot find is listed
  by name on the Tools screen and can be set by hand.
* The header menu uses the `menu-1` location. If nothing is assigned there it
  falls back to your first menu, then to top level pages.
