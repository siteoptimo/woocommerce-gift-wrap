<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WCGW_Admin_Setting_Fields
 *
 * Handles the WooCommerce Plivo settings. This uses the WooCommerce Settings API.
 *
 * @package WooCommerce_GiftWrap
 * @class WCGW_Admin_Setting_Fields
 * @author Pieter Carette <pieter@siteoptimo.com>
 */
class WCGW_Admin_Setting_Fields {
	/**
	 * Construct the class.
	 */
	public function __construct() {

		add_filter( 'woocommerce_account_settings', array( $this, 'add_settings' ) );
	}

	public function add_settings( $settings ) {

		$title = array(
			'title' => __( 'Where did you hear about us', 'woocommerce-gift-wrap' ),
			'type'  => 'title',
			'desc'  => 'Manage the "where did you hear about us" options.',
			'id'    => 'wcgw_title'
		);
		array_push( $settings, $title );

		$required = array(
			'title'   => __( 'Make it required', 'woocommerce-gift-wrap' ),
			'id'      => 'wcgw_required',
			'type'    => 'checkbox',
			'default' => 'yes',
		);
		array_push( $settings, $required );

		$fields     = apply_filters( 'wcgw_settings_fields', array(

				array(
					'title'    => __( 'Label', 'woocommerce-gift-wrap' ),
					'desc'     => __( 'Customize the "where did you hear about us" label.', 'woocommerce-gift-wrap' ),
					'id'       => 'wcgw_label',
					'type'     => 'text',
					'default'  => __( 'Where did you hear about us?', 'woocommerce-gift-wrap' ),
					'desc_tip' => true,
				),
				array(
					'title'    => __( 'Possible answers', 'woocommerce-gift-wrap' ),
					'desc'     => __( 'List all of the possible answers, one answer per line.', 'woocommerce-gift-wrap' ),
					'id'       => 'wcgw_options',
					'type'     => 'textarea',
					'default'  => implode( PHP_EOL, array( 'Google', 'Facebook', 'Twitter', 'A friend', 'Other' ) ),
					'desc_tip' => true,
				)
			)
		);
		$settings   = array_merge( $settings, $fields );
		$sectionend = array( 'type' => 'sectionend', 'id' => 'wcgw_sectionend' );

		array_push( $settings, $sectionend );

		return $settings;
	}
} 