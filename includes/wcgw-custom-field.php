<?php

class WCGW_Custom_Field {

	public function __construct() {
		add_action( 'woocommerce_after_order_notes', array( $this, 'display_field' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'process_checkout_fields' ) );
		add_action( 'woocommerce_checkout_update_user_meta', array( $this, 'save_custom_checkout' ) );
		add_filter( 'woocommerce_customer_meta_fields', array( $this, 'user_profile' ) );
	}

	function display_field( $checkout ) {
		woocommerce_form_field( 'wcgw_source', array(
			'type'     => 'select',
			'class'    => array( 'wcgw-source form-row-wide' ),
			'label'    => wcgw_get_option( 'wcgw_label' ),
			'options'  => $this->get_options(),
			'required' => $this->is_field_required(),
		), $checkout->get_value( 'wcgw_source' ) );

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

	public function process_checkout_fields() {
		// Check if set, if its not set add an error.
		if ( ! isset( $_POST['wcgw_source'] ) || empty( $_POST['wcgw_source'] ) || $_POST['wcgw_source'] == 'empty' ) {
			wc_add_notice( __( 'Please enter where you found us.', 'woocommerce-gift-wrap' ), 'error' );
		}
	}

	function save_custom_checkout( $user_id ) {
		if ( ! empty( $_POST['wcgw_source'] ) ) {

			$options = $this->get_options();

			$source = $_POST['wcgw_source'];

			update_user_meta( $user_id, '_wcgw_source', sanitize_text_field( isset( $options[ $source ] ) ? $options[ $source ] : '' ) );
		}
	}

	public function user_profile( $fields ) {
		$fields['wcgw_source'] = array(
			'title'  => __( 'Where did you hear about us', 'woocommerce-gift-wrap' ),
			'fields' => array(
				'_wcgw_source' => array(
					'label'       => __( 'Source', 'woocommerce-gift-wrap' ),
					'description' => ''
				),
			)
		);

		return $fields;
	}


	private function get_options() {
		return self::prepare_options( wcgw_get_option( 'wcgw_options' ) );
	}

	public static function slugify( $in ) {
		return sanitize_title_with_dashes( $in );
	}

	private function is_field_required() {
		return get_option( 'wcgw_required', 'yes' ) == 'yes';
	}
}