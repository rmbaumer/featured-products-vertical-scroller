=== Featured Products Vertical Scroller ===
Contributors: tv360
Tags: woocommerce, featured products, scroller, carousel
Requires at least: 3.0.1
Tested up to: 4.8.2
Requires PHP: 5.2.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use widgets or shortcodes to display WooCommerce featured products in a vertical carousel scroller.

== Description ==
You can integrate the Vertical Featured Products Scroller into your site by using a widget or a shortcode.

To mark a product as featured, go to: Products > Products and select the Star in the featured column. Alternatively, select Quick Edit and then the Featured option.

The shortcode is:

[wcfps]

Available shortcode options are:

* id (unique ids are required if you want to use more than one scroller on the same page)
* num_slides (the number of visible products)
* speed (scroller speed in miliseconds, default is 5000)
* category_slug (limits products by category slug)

Example usage:

[wcfps id="scroller-one" num_slides="3" speed="6000" category_slug="shoes"]

== Installation ==
Upload the plugin to your blog then activate it.