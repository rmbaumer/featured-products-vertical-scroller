<?php
/*
 * Plugin Name: Featured Products Vertical Scroller
 * Description: Use widgets or shortcodes to display WooCommerce featured products in a vertical carousel scroller.
 * Author: TrueVision360
 * Author URI: http://truevision360.com
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.1
 * Version: 1.0.1
*/

// Get the featured products
function wcfps_featured_products($category_slug) {
	$category_slug = '';
	$meta_query  = WC()->query->get_meta_query();
	$tax_query   = WC()->query->get_tax_query();
	$tax_query[] = array(
	    'taxonomy' => 'product_visibility',
	    'field'    => 'name',
	    'terms'    => 'featured',
	    'operator' => 'IN',
	);

	$query_args = array(
	    'post_type'           => 'product',
	    'post_status'         => 'publish',
	    'ignore_sticky_posts' => 1,
	    'posts_per_page'      => $atts['per_page'],
	    'orderby'             => $atts['orderby'],
	    'order'               => $atts['order'],
	    'meta_query'          => $meta_query,
	    'tax_query'           => $tax_query,
      'product_cat' => $category_slug,
	);

	$loop = new WP_Query( $query_args );
	echo '<div class="wcfps">';
	while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>

	    <div>    
	        <?php 
	            if ( has_post_thumbnail( $loop->post->ID ) ) 
	                echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail( $loop->post->ID, 'shop_catalog', array( 'alt' => get_the_title() ) ).'</a>'; 
	            else 
	                echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'"><img src="' . woocommerce_placeholder_img_src() . '" alt="Placeholder" width="65px" height="115px" /></a>'; 
	        ?>
	        <!-- <h3><?php echo '<a href="'.get_the_permalink().'">'.$product->get_sku().'</a>'; ?></h3> -->
	    </div>

	<?php 
  endwhile;
  echo '</div><!-- /.wcfps -->';
  wp_reset_query(); 	
}

// The shortcode function
function wcfps_shortcode($atts = []) {
	ob_start();	
	$wcfps_settings = shortcode_atts( array(
	        'id' => 'wcpfs_shortcode',
	        'num_slides' => 3,
	        'speed' => 5000,
	        'category_slug' => '',
	    ), $atts );
	echo '<div id="'.$wcfps_settings['id'].'">';
	wcfps_featured_products();
	echo '</div>';
	// Initialize the js
	wp_enqueue_script( 'slick_js', plugin_dir_url( __FILE__ ) . 'slick/slick.min.js', array('jquery'), '1.8.0' );
	wp_add_inline_script( 'slick_js', 
		'jQuery(function($){
			$("#'.$wcfps_settings['id'].' .wcfps").slick({
        vertical: true,
        autoplay: true,
        autoplaySpeed: 0,
        speed: '.$wcfps_settings['speed'].',
        easing: "linear",
        cssEase: "linear",
        slidesToShow: '.$wcfps_settings['num_slides'].',
        slidesToScroll: 1,
        arrows: false,
        dots: false });
		});' 
	);	
	$ReturnString = ob_get_contents(); ob_end_clean(); return $ReturnString;
}

// Register the shortcode
add_shortcode('wcfps', 'wcfps_shortcode');

// Register and load the widget
function wcfps_load_widget() {
  register_widget( 'wcfps' );
}
add_action( 'widgets_init', 'wcfps_load_widget' );
 
// Creating the widget 
class wcfps extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
			// Base ID of the widget
			'wcfps', 
			 
			// Widget name will appear in UI
			__('WC Featured Products Scroller', 'wcfps_domain'), 
			 
			// Widget description
			array( 'description' => __( 'A vertical carousel scroller that displays WooCommerce featured products.', 'wcfps_domain' ), ) 

		);

	}
 
	// Creating widget front-end	 
	public function widget( $args, $instance ) {

		// Set the variables...
		$title = apply_filters( 'widget_title', $instance['title'] );
		$slidesToShow = empty($instance['slidesToShow']) ? 3 : $instance['slidesToShow'];
		$widget_id = $this->id;
		$speed = empty($instance['speed']) ? 5000 : $instance['speed'];

		// Initialize the js
  	wp_enqueue_script( 'slick_js', plugin_dir_url( __FILE__ ) . 'slick/slick.min.js', array('jquery'), '1.8.0' );
		wp_add_inline_script( 'slick_js', 
			'jQuery(function($){
  			$("#'.$widget_id.' .wcfps").slick({
	        vertical: true,
	        autoplay: true,
	        autoplaySpeed: 0,
	        speed: '.$speed.',
	        easing: "linear",
	        cssEase: "linear",
	        slidesToShow: '.$slidesToShow.',
	        arrows: false,
	        dots: false,
          pauseOnHover: true,
          });
			});' 
		);
		 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
	 
		// This is where you run the code and display the output
		wcfps_featured_products();

		echo $args['after_widget'];

	}
         
	// Widget Backend 
	public function form( $instance ) {

		// Widget title
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'wcfps_domain' );
		}

		// Number of slides to show
		if ( isset( $instance[ 'slidesToShow' ] ) ) {
			$slidesToShow = $instance[ 'slidesToShow' ];
		}
		else {
			$slidesToShow = 3;
		}

		// Speed
		if ( isset( $instance[ 'speed' ] ) ) {
			$speed = $instance[ 'speed' ];
		}
		else {
			$speed = 5000;
		}

		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'slidesToShow' ); ?>"><?php _e( 'slidesToShow:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'slidesToShow' ); ?>" name="<?php echo $this->get_field_name( 'slidesToShow' ); ?>" type="number" value="<?php echo esc_attr( $slidesToShow ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'speed' ); ?>"><?php _e( 'speed (ms):' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'speed' ); ?>" name="<?php echo $this->get_field_name( 'speed' ); ?>" type="number" value="<?php echo esc_attr( $speed ); ?>" />
		</p>
	<?php 
	}
     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['slidesToShow'] = ( ! empty( $new_instance['slidesToShow'] ) ) ? strip_tags( $new_instance['slidesToShow'] ) : '';
		$instance['speed'] = ( ! empty( $new_instance['speed'] ) ) ? strip_tags( $new_instance['speed'] ) : '';
		return $instance;
	}

} // Class wcfps ends here



// Add the css and js used by the plugin (for widget and shortcode)
function wcfps_enqueue_script() {   
  wp_register_style( 'slick_css', plugin_dir_url( __FILE__ ) . 'slick/slick.css' );
  wp_enqueue_style( 'slick_css' );
  wp_register_style( 'slick_theme_css', plugin_dir_url( __FILE__ ) . 'slick/slick-theme.css' );
  wp_enqueue_style( 'slick_theme_css' );
}
add_action('wp_enqueue_scripts', 'wcfps_enqueue_script');