<?php

class WCGW_Frontend {

	public function __construct() {
		if ( ! isset( $_SESSION ) ) {
			session_start();
		}
		add_filter( 'woocommerce_cart_item_name', array( $this, 'add_to_cart' ), 1, 3 );
		add_filter( 'woocommerce_update_cart_action_cart_updated', array( $this, 'cart_updated' ) );
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_wrapping_to_order_item' ), 1, 3);
	}

	public function add_wrapping_to_order_item($item_id, $values, $cart_item_key) {
		if ( !isset( $_SESSION['wcgw_wrappings'], $_SESSION['wcgw_wrappings'][ $cart_item_key ] ) ) {
			return;
		}

		$wrapping = $_SESSION['wcgw_wrappings'][ $cart_item_key ];
		$options  = self::get_options();

		$wrapping = $options[$wrapping];

		wc_add_order_item_meta($item_id, 'wrapping', $wrapping, true);

	}

	public function cart_updated( $cart_updated ) {

		$wrappings = array();
		$options   = self::get_options();
		foreach ( $_POST['cart'] as $item_key => $cart_item ) {
			if ( isset( $cart_item['wrapping'] ) && array_key_exists( $cart_item['wrapping'], $options ) ) {
				$wrappings[ $item_key ] = $cart_item['wrapping'];
			}
		}

		$_SESSION['wcgw_wrappings'] = $wrappings;

		return $cart_updated;
	}

	public function add_to_cart( $product_name, $product, $id ) {
		if ( ! is_cart() && isset( $_SESSION['wcgw_wrappings'] ) ) {
			if ( isset( $_SESSION['wcgw_wrappings'][ $id ] ) ) {
				$wrapping = $_SESSION['wcgw_wrappings'][ $id ];
				$options  = self::get_options();

				if ( isset( $options[ $wrapping ] ) ) {
					return $product_name . ' <small>(' . $options[ $wrapping ] . ' ' . _x( 'wrapping', 'in product overview on checkout', 'woocommerce-gift-wrap' ) . ')</small>';
				}
			}
		}
		$select = '<br><label><strong>' . _x( 'Wrapping', 'In cart', 'woocommerce-gift-wrap' ) . ': </strong>';
		$select .= '<select name="cart[' . $id . '][wrapping]">';

		$selected = 'none';

		if ( isset( $_SESSION, $_SESSION['wcgw_wrappings'], $_SESSION['wcgw_wrappings'][ $id ] ) ) {
			$selected = $_SESSION['wcgw_wrappings'][ $id ];
		}

		$options = self::get_options();
		foreach ( $options as $k => $v ) {

			$select .= '<option value="' . $k . '"' . selected( $k, $selected, false ) . '>' . $v . '</option>';
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