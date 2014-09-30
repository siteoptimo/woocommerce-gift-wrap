<?php

class WCGW_Frontend {

	public function __construct() {
		add_filter('woocommerce_cart_item_name', array($this, 'add_to_cart'), 1, 3);
		add_filter('woocommerce_update_cart_action_cart_updated', array($this, 'cart_updated'));
	}

	public function cart_updated($cart_updated) {


		$wrappings = array();
		$options = self::get_options();
		foreach($_POST['cart'] as $item_key => $cart_item) {
			if(isset($cart_item['wrapping']) && array_key_exists($cart_item['wrapping'], $options)) {
				$wrappings[$item_key] = $cart_item['wrapping'];
			}
		}

		$_SESSION['wcgw_wrappings'] = $wrappings;

		return $cart_updated;
	}

	public function add_to_cart($product_name, $product, $id) {
		$select = '<br><label><strong>' . _x('Wrapping', 'In cart', 'woocommerce-gift-wrap') . ': </strong>';
		$select .= '<select name="cart[' . $id . '][wrapping]">';

		$selected = 'none';

		if(isset($_SESSION, $_SESSION['wcgw_wrappings'], $_SESSION['wcgw_wrappings'][$id])) {
			$selected = $_SESSION['wcgw_wrappings'][$id];
		}


		$options = self::get_options();
		foreach($options as $k => $v) {

			$select .= '<option value="' . $k . '"' . selected($k, $selected, false) . '>' . $v . '</option>';
		}




		$select .= '</select></label>';

		return $product_name . $select;
	}

	public static function prepare_options( $options ) {

		$options = explode( PHP_EOL, $options );

		$return = array();

		$return['none'] = _x( 'None', 'No wrapping in selectbox', 'woocommerce-gift-wrap' );

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