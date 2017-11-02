# Featured Products Vertical Scroller

Use widgets or shortcodes to display WooCommerce featured products in a vertical carousel scroller.

https://wordpress.org/plugins/featured-products-vertical-scroller/

See it in use: https://www.easternglove.com

You can integrate the Featured Products Vertical Scroller into your site by using a widget or a shortcode.

To mark a product as featured, go to: Products > Products and select the Star in the featured column. Alternatively, select Quick Edit and then the Featured option.

The shortcode is:

[wcfps]

Available shortcode options are:

* **id** (unique ids are required if you want to use more than one scroller on the same page)
* **num_slides** (the number of visible products)
* **speed** (scroller speed in miliseconds, default is 5000)
* **category_slug** (limits products by category slug)

Example usage:

[wcfps id="scroller-one" num_slides="3" speed="6000" category_slug="shoes"]
