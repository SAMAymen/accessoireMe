=== AddToAny Share Buttons ===
Contributors: micropat, addtoany
Tags: share, social, share buttons, share icons, social media
License: GPLv2 or later
Requires at least: 4.5
Tested up to: 6.7
Requires PHP: 5.6
Stable tag: 1.8.13

Share buttons for WordPress including the AddToAny button, Facebook, Mastodon, WhatsApp, Pinterest, Reddit, Threads, many more, and follow icons too.

== Description ==

The AddToAny Share Buttons plugin for WordPress increases traffic & engagement by helping people share your posts and pages to any service. Services include Facebook, Mastodon, Pinterest, WhatsApp, LinkedIn, Threads, Bluesky, Tumblr, Reddit, X, WeChat, and many more sharing and social media sites & apps.

AddToAny is the home of universal sharing, and the AddToAny plugin is the most popular share plugin for WordPress, making sites social media ready since 2006.

= Share Buttons =

* [**Standard**](https://www.addtoany.com/buttons/customize/wordpress/standalone_services) share buttons — share each piece of content
* [**Floating**](https://www.addtoany.com/buttons/customize/wordpress/floating_share_buttons) share buttons — responsive & customizable, vertical & horizontal
* **Counters** — fast & official [share counts](https://www.addtoany.com/buttons/customize/wordpress/share_counters) in the same style
* **Follow** buttons — [social media links](https://www.addtoany.com/buttons/customize/wordpress/follow_buttons) to your Instagram, YouTube, Discord, Snapchat
* **Image** sharing buttons - share buttons for [sharing images](https://www.addtoany.com/buttons/customize/wordpress/image_sharing)
* **Vector** share buttons & follow buttons — [custom color](https://www.addtoany.com/buttons/customize/wordpress/icon_color) SVG icons
* **Custom** share icons — use your own if you prefer
* Official buttons including the Facebook Like Button, Pinterest Save Button, and LinkedIn Share Button
* Universal email sharing makes it easy to share via Gmail, Yahoo Mail, Outlook.com (Hotmail), AOL Mail, and any other web or native apps

= Custom Placement & Appearance =
* Before content, after content, or before & after content
* Vertical Floating Share Bar, and Horizontal Floating Share Bar
* As a shortcode, or a widget within a theme's layout
* Programmatically with template tags

= Analytics Integration =

* Google Analytics integration (<a href="https://www.addtoany.com/ext/google_analytics/">access guide</a>) for sharing analytics
* Track shared links with Bitly and custom URL shorteners
* Display share counts on posts and pages

= WordPress Optimized =

* Loads asynchronously so your content always loads before or in parallel with AddToAny
* Supports theme features such as HTML5, widgets, infinite scroll, post formats
* Supports WooCommerce, multilingual sites, multisite networks, and accessibility standards
* AddToAny is free — no signup, no login, no accounts to manage

= Mobile Optimized & Retina Ready =

* AddToAny gives users the choice in sharing from a service's native app or from a web app
* Responsive Floating Share Buttons are mobile ready by default, and configurable breakpoints make floating buttons work with any theme
* AddToAny's SVG icons are super-lightweight and pixel-perfect at any size, and AddToAny's responsive share menu fits on all displays
* Automatic <a href="https://wordpress.org/plugins/amp/">AMP</a> (Accelerated Mobile Pages) support for social share buttons on AMP pages

= Customizable & Extensible =

* Choose exactly where you want AddToAny to appear
* Easily <a href="https://www.addtoany.com/buttons/customize/wordpress">customize sharing</a> on your WordPress site
* <a href="https://wordpress.org/plugins/add-to-any/#faq">Highly extensible</a> for developers and designers
* Custom icons let you use any icons from any location (media uploads directory, CDN, etc.)
* Many more publisher and user features

= Wide Support =

* Over 10 years of development
* Over 17 million downloads
* Translated into dozens of languages
* Ongoing support from the community

This plugin always strives to be the best WordPress plugin for sharing. Development is fueled by your kind words and feedback.

<a href="https://www.addtoany.com/share#url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fadd-to-any%2F&title=AddToAny%20Sharing%20Plugin%20for%20WordPress" title="Share">Share</a> this plugin

See also:

* The <a href="https://www.addtoany.com/buttons/">share buttons</a> for all platforms
* The <a href="https://www.addtoany.com/buttons/for/wordpress_com">share buttons for WordPress.com</a>

<a href="https://www.addtoany.com/blog/">AddToAny Blog</a> | <a href="https://www.addtoany.com/privacy">Privacy Policy</a>

== Installation ==

In WordPress:

1. Go to Plugins > Add New > search for `addtoany`
1. Press "Install Now" for the AddToAny plugin
1. Press "Activate Plugin"

Manual installation:

1. Upload the `add-to-any` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the "Plugins" menu in WordPress

WP-CLI installation:

1. `wp plugin install add-to-any --activate`

== Frequently Asked Questions ==

= Where are the options, and how can I customize the sharing plugin? =

In WordPress, go to Settings > AddToAny.

Setup Follow buttons (like Instagram, YouTube, etc.) using the AddToAny Follow widget in Appearance > Widgets (or Appearance > Customize).

To further customize AddToAny, see the <a href="https://www.addtoany.com/buttons/customize/wordpress">WordPress sharing documentation</a> for the AddToAny plugin. Many customizations will have you copy & paste one or more lines of code into your "Additional JavaScript" or "Additional CSS" box. Those boxes are in Settings > AddToAny.

= Something is wrong. What should I try first? =

Try temporarily switching themes and deactivating other plugins to identify a potential conflict. If you find a conflict, try contacting that theme or plugin author. If an issue persists on a default theme with all other plugins deactivated, search the WordPress plugin's <a href="https://wordpress.org/support/plugin/add-to-any/">support forum</a>.

Feel free to <a href="https://wordpress.org/support/plugin/add-to-any">post here</a>, where the community can hopefully help you. Describe the issue, what troubleshooting you have already done, provide a link to your site, and any other potentially relevant information.

= The share buttons are not displaying for me. Why, and what should I try? =

Something on your own device/browser/connection is likely filtering out major social buttons.

Try another web browser, device, and/or Internet connection to see if the buttons appear. Tools like browserling.com will give you an idea of what other people are seeing. The usual cause for this uncommon issue is 3rd party browser add-on software that blocks ads and optionally filters out major social buttons. Some security apps and Internet connections have an option to filter social buttons. Usually a social filter option is disabled by default, but if you find that some software is inappropriately filtering AddToAny buttons, <a href="https://www.addtoany.com/contact/">let AddToAny know</a>.

= What is the shortcode for sharing? =

You can place your share buttons exactly where you want them by inserting the following shortcode:

`[addtoany]`

Customize the shared URL like so:

`[addtoany url="https://www.example.com/page.html" title="Some Example Page"]`

Display specific share buttons by specifying comma-separated <a href="https://www.addtoany.com/services/">service codes</a>:

`[addtoany buttons="facebook,mastodon,email"]`

Share a specific image or video to certain services that accept arbitrary media (Pinterest, Yummly, Houzz):

`[addtoany buttons="pinterest,yummly,houzz" media="https://www.example.com/media/picture.jpg"]`

= For Facebook sharing, how can I set the thumbnail image and description Facebook uses? =

Facebook expects the Title, Description, and preview image of a shared page to be defined in the Open Graph <a href="https://www.addtoany.com/ext/meta-tags/" target="_blank">meta tags</a> of a shared page.

Use Facebook's <a href="https://developers.facebook.com/tools/debug/sharing/" target="_blank">Sharing Debugger</a> on your pages to see how Facebook reads your site. "Scrape Again" to test site changes and clear Facebook's cache of a page, or use the <a href="https://developers.facebook.com/tools/debug/sharing/batch/" target="_blank">Batch Invalidator</a> to purge Facebook's cache of multiple URLs.

To change the title, description and/or image on Facebook, your theme's header file should be modified according to <a href="https://developers.facebook.com/docs/sharing/opengraph" target="_blank">Facebook's OpenGraph specification</a>. With WordPress, this can be accomplished with plugins such as <a href="https://wordpress.org/plugins/wordpress-seo/">Yoast SEO</a> or the Social Meta feature of the <a href="https://wordpress.org/plugins/all-in-one-seo-pack/">All in One SEO Pack</a>. Please see those plugins for details, and post in the WordPress or plugin author's forums for more support.

For more technical information on setting your pages up for Facebook sharing, see "Sharing Best Practices for Websites" in <a href="https://developers.facebook.com/docs/sharing/best-practices">Facebook's documentation</a>.

= Why do share links route through AddToAny? =

AddToAny routing enables publisher customization, visitor personalization, and keeps the AddToAny plugin remarkably lightweight without the need for constant plugin updates. In AddToAny menus, visitors see the services they actually use. On mobile, AddToAny presents the choice of sharing to a service's native app or mobile site and the preference is used on the next share. Publishers take advantage of AddToAny services such as <a href="https://www.addtoany.com/buttons/customize/wordpress/email_template">email templates</a>, <a href="https://www.addtoany.com/buttons/customize/wordpress/templates">custom parameters</a>, <a href="https://www.addtoany.com/buttons/customize/wordpress/link_tracking">URL shorteners</a>, localization, and more. Just as service icons change, service endpoints change too, and AddToAny is updated daily to reflect service endpoint and API changes.

= Where are buttons such as Instagram, YouTube, Snapchat? =

Configure your social media profile links by adding the "AddToAny Follow" widget in Appearance > Customize or Appearance > Widgets.

= How can I use custom icons? =

Upload sharing icons in a single directory to a public location, and make sure the icon filenames match the icon filenames packaged in the AddToAny plugin. In WordPress, go to Settings > AddToAny > Advanced Options > check the "Use custom icons" checkbox and specify the URL to your custom icons directory (including the trailing `/`). For AddToAny's universal button, go to Universal Button, select "Image URL" and specify the exact location of your AddToAny universal share icon (including the filename).

= How can I place the share buttons in a specific area of my site? =

In the Theme Editor (or another code editor), place this code block where you want the button and individual icons to appear in your theme:

`<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>`

You can specify [AddToAny service code(s)](https://www.addtoany.com/services/) to show specific share buttons, for example:

`<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { 
	ADDTOANY_SHARE_SAVE_KIT( array( 
		'buttons' => array( 'facebook', 'mastodon', 'email', 'whatsapp' ),
	) );
} ?>`

To customize the shared URL and title:

`<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { 
	ADDTOANY_SHARE_SAVE_KIT( array( 
		'linkname' => 'Example Page',
		'linkurl'  => 'https://example.com/page.html',
	) );
} ?>`

To share the current URL and title (detected on the client-side):

`<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { 
	ADDTOANY_SHARE_SAVE_KIT( array( 'use_current_page' => true ) );
} ?>`

To hardcode the shared current URL and modify the title (server-side):

`<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { 
	ADDTOANY_SHARE_SAVE_KIT( array( 
		'linkname' => is_home() ? get_bloginfo( 'description' ) : wp_title( '', false ),
		'linkurl'  => esc_url_raw( home_url( $_SERVER['REQUEST_URI'] ) ),
	) );
} ?>`

To share a specific image or video to certain services that accept arbitrary media (Pinterest, Yummly):

`<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { 
	ADDTOANY_SHARE_SAVE_KIT( array( 
		'buttons'   => array( 'pinterest', 'yummly' ),
		'linkmedia' => 'https://www.example.com/media/picture.jpg',
		'linkname'  => 'Example Page',
		'linkurl'   => 'https://www.example.com/page.html',
	) );
} ?>`

= How can I place the follow buttons in a specific area of my site? =

See the [supported follow services](https://www.addtoany.com/buttons/customize/follow_buttons) for service code names, then place this example code in your theme's file(s) where you want the follow buttons to appear:

`<?php if ( function_exists( 'ADDTOANY_FOLLOW_KIT' ) ) {
	ADDTOANY_FOLLOW_KIT( array(
		'buttons' => array(
			'facebook'  => array( 'id' => 'zuck' ),
			'instagram' => array( 'id' => 'kevin' ),
			'tumblr'   => array( 'id' => 'photomatt' ),
		),
	) );
} ?>`

= How can I add a custom share button? =
You can add the following example PHP code using a "functionality" plugin such as the [Code Snippets](https://wordpress.org/plugins/code-snippets/) plugin:

`function addtoany_add_share_services( $services ) {
	$services['example_share_service'] = array(
		'name'        => 'Example Share Service',
		'icon_url'    => 'https://www.example.com/my-icon.svg',
		'icon_width'  => 32,
		'icon_height' => 32,
		'href'        => 'https://www.example.com/share?url=A2A_LINKURL&title=A2A_LINKNAME',
	);
	return $services;
}
add_filter( 'A2A_SHARE_SAVE_services', 'addtoany_add_share_services', 10, 1 );`

= How can I add a custom follow button? =
You can customize the following example PHP code and add it to a "functionality" plugin such as the [Code Snippets](https://wordpress.org/plugins/code-snippets/) plugin:

`function addtoany_add_follow_services( $services ) {
	$services['example_follow_service'] = array(
		'name'        => 'Example Follow Service',
		'icon_url'    => 'https://www.example.com/my-icon.svg',
		'icon_width'  => 32,
		'icon_height' => 32,
		'href'        => 'https://www.example.com/${id}',
	);
	return $services;
}
add_filter( 'A2A_FOLLOW_services', 'addtoany_add_follow_services', 10, 1 );`

= How can I align the standard sharing buttons to the center or to the right side of posts? =
It depends on your theme, but you can try adding the following CSS code to your Additional CSS box in Settings > AddToAny.

To align right:

`.addtoany_content { text-align:right; }`

To align center:

`.addtoany_content { text-align:center; }`

= How can I remove the button(s) from individual posts and pages? =

When editing a post or page, uncheck "Show sharing buttons", which is located next to the WordPress editor. Be sure to update or publish to save your changes.

An older method was to insert the following tag into the page or post (HTML tab) that you do not want the button(s) to appear in: `<!--nosharesave-->`

= How can I force the button(s) to appear in individual posts and pages? =

When editing a post or page, check the "Show sharing buttons" checkbox, which is located next to the WordPress editor. Be sure to update or publish to save your changes. Note that, by default, AddToAny is setup to display on all posts and pages.

An older method was to insert the following tag into the page or post (HTML tab) that you want the button(s) to appear in: `<!--sharesave-->`

= How can I remove the button(s) from category pages, or tag/author/date/search pages? =

Go to Settings > AddToAny > uncheck "Display at the top or bottom of posts on archive pages". Archive pages include Category, Tag, Author, Date, and also Search pages.

= How can I programmatically remove the button(s)? =

You can disable AddToAny sharing using a [filter](https://developer.wordpress.org/plugins/hooks/filters/) (PHP code) that you can add to a "functionality" plugin such as the [Code Snippets](https://wordpress.org/plugins/code-snippets/) plugin.

Disable AddToAny sharing in specific categories, for example:

`function addtoany_disable_sharing_in_some_categories() {
	// Examples of in_category usage: https://codex.wordpress.org/Function_Reference/in_category
	if ( in_category( array( 'my_category_1_slug', 'my_category_2_slug' ) ) ) {
		return true;
	}
}
add_filter( 'addtoany_sharing_disabled', 'addtoany_disable_sharing_in_some_categories' );`

Disable AddToAny sharing on a custom post type, for example:

`function addtoany_disable_sharing_on_my_custom_post_type() {
	if ( 'my_custom_post_type' == get_post_type() ) {
		return true;
	}
}
add_filter( 'addtoany_sharing_disabled', 'addtoany_disable_sharing_on_my_custom_post_type' );`

= How can I position a vertical floating share buttons bar relative to content? =

Go to Settings > AddToAny > Floating > select "Attach to content" then input the CSS selector(s) that match the HTML element you want to attach to.

= Is AddToAny GDPR compatible? =

Yes, AddToAny is [GDPR compatible by default](https://www.addtoany.com/buttons/faq/#gdpr).

= How can I load the buttons after content insertion with Ajax and infinite scroll? =

AddToAny supports the <a href="https://codex.wordpress.org/AJAX_in_Plugins#The_post-load_JavaScript_Event">standard `post-load` event</a>.

Ajax and infinite scroll plugins/themes should always fire the `post-load` event after content insertion, so request <a href="https://codex.wordpress.org/AJAX_in_Plugins#The_post-load_JavaScript_Event">standard `post-load` support</a> from plugin & theme authors as needed.

Use the following line to dispatch the `post-load` event for AddToAny and other plugins:

`jQuery( 'body' ).trigger( 'post-load' );`

= How can I set the plugin as a "Must-Use" plugin that is autoloaded and activated for all sites? =

Upload (or move) the `add-to-any` plugin directory into the `/wp-content/mu-plugins/` directory. Then create a proxy PHP loader file (such as `load.php`) in your `mu-plugins` directory, for example:

`<?php require WPMU_PLUGIN_DIR . '/add-to-any/add-to-any.php';`

== Screenshots ==

1. AddToAny vector share buttons (SVG icons) are pixel-perfect and customizable
2. Mini share menu that drops down when visitors use the universal share button
3. Full universal share menu modal that includes all services
4. Settings for Standard Share Buttons
5. Settings for Floating Share Bars

== Changelog ==

= 1.8.13 =
* Fix defaults in settings

See `changelog.txt` in the plugin's directory for the full changelog.