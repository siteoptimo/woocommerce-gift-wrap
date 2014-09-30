<?php

class WCGW_Frontend {

	public function __construct() {
		add_filter('woocommerce_cart_item_name', array($this, 'add_to_cart'), 1, 3);
	}

	public function add_to_cart($product_name, $product, $b) {
		$select = '<br><label><strong>' . _x('Wrapping', 'In cart', 'woocommerce-gift-wrap') . ': </strong>';
		$select .= '<select name="wrapping[' . $product['product_id'] . ']"><option value="">' . _x('Wrapping', 'in selectbox', 'woocommerce-gift-wrap') . '</option>';

		// new options

		$select .= '</select></label>';

		return $product_name . $select;
	}

	public static function prepare_options( $options ) {

		$options = explode( PHP_EOL, $options );

		$return = array();

		$return['empty'] = __( '-- Choose an option --', 'woocommerce-gift-wrap' );

		foreach ( $options as $option ) {
			$return[ self::slugify( $option ) ] = $option;
		}

		return $return;
	}


	private function get_options() {
		return self::prepare_options( wcgw_get_option( 'wcgw_options' ) );
	}

	public static function slugify( $in ) {
		return sanitize_title_with_dashes( $in );
	}
}