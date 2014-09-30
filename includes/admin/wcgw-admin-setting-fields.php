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
		add_action('woocommerce_settings_woocommerce_gift_wrap_settings', array($this, 'settings_tab'));
		add_action('woocommerce_update_options_woocommerce_gift_wrap_settings', array($this, 'update_settings'));
	}

	/**
	 * Settings tab.
	 */
	function settings_tab()
	{
		woocommerce_admin_fields($this->get_settings());
	}

	/**
	 * Update the settings values.
	 */
	function update_settings()
	{
		woocommerce_update_options($this->get_settings());
	}

	/**
	 * Gets all of the settings.
	 *
	 * @return mixed The settings
	 */
	private function get_settings()
	{

		$settings['freegift_settings_title'] = array('name' => __('Free Gift Settings', 'woocommerce-gift-wrap'), 'type' => 'title', 'desc' => __('Add your wraps here.', 'woocommerce-gift-wrap'), 'id' => 'wcp_plivo_settings_section_title');

		$fields     = apply_filters( 'wcgw_settings_fields', array(

			array(
				'title'    => __( 'Possible wraps', 'woocommerce-gift-wrap' ),
				'desc'     => __( 'Gift wrap options, one per line.', 'woocommerce-hear-gift-wrap' ),
				'id'       => 'wcgw_options',
				'type'     => 'textarea',
				'default'  => implode( PHP_EOL, array( 'Birthday', 'Congratulations','Back to school' ) ),
				'desc_tip' => true,
			)
		)
		);
		$settings   = array_merge( $settings, $fields );
		$sectionend = array( 'type' => 'sectionend', 'id' => 'wcgw_sectionend' );

		array_push( $settings, $sectionend );


		return apply_filters('woocommerce_gift_wrap_settings', $settings);
	}
}