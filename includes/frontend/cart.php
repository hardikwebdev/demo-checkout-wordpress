<?php

/**Add actions */

add_action( 'init', 'demoCheckout_button_cart_checkout_hook' );

if (!function_exists("demoCheckout_button_cart_checkout_hook")) {
	function demoCheckout_button_cart_checkout_hook()
	{
		$placeButton = get_option('demo_checkout_cart_option');
		$placementCartHook = array (
			'woocommerce_proceed_to_checkout',
			'woocommerce_after_cart_totals',
			'woocommerce_before_cart'
		);
	
		if(($placeButton == '' || !in_array( $placeButton, $placementCartHook) && $placeButton != 'disable' ) ){
			$placeButton = 'woocommerce_proceed_to_checkout';
		}
		if($placeButton != 'disable'){
			add_action($placeButton, "demoCheckout_add_cart_script");
		}

		$placeButtonCheckout = get_option('demo_checkout_woo_checkout_option');
		$placementCheckoutHook = array (
			'woocommerce_review_order_before_submit',
			'woocommerce_review_order_after_submit',
			'woocommerce_before_checkout_form'
		);
	
		if( ($placeButtonCheckout == '' || !in_array( $placeButtonCheckout, $placementCheckoutHook) )  && $placeButton != 'disable' ){
			$placeButtonCheckout = 'woocommerce_review_order_before_submit';
		}
		if($placeButtonCheckout != 'disable'){
			add_action($placeButtonCheckout, "demoCheckout_add_cart_script");
		}
	}
}


/**
 * Cart page Add button
 * 
 */

if (!function_exists("demoCheckout_add_cart_script")) {
function demoCheckout_add_cart_script(){
		global $woocommerce;
		$items = (array) $woocommerce->cart->get_cart();
		if(count($items) > 0){
			echo '<div class="demo-cart">';
				echo '<div id="demo-cart-sibling"></div>';
			echo '</div>';
		}
	}
}


?>