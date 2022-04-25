<?php

/**
 * 
 * Add action 
 */
add_action( 'woocommerce_admin_order_data_after_order_details',  "demoCheckout_order_details" );
add_filter( 'manage_edit-shop_order_columns',"demoCheckout_order_column", 20 );
add_filter( 'manage_edit-shop_order_columns',"demoCheckout_risk_order_column", 20 );
add_action( 'manage_shop_order_posts_custom_column' , "demoCheckout_orders_list_column_content" , 20, 2 );


/**
 * Customers Woocommerce order details 
 * 
 */
if (!function_exists("demoCheckout_order_details")) {
	function demoCheckout_order_details( $order ){  ?>
		<div class="form-field form-field-wide wc-customer-user">
			<?php 
				$order_id = $order->get_id();
				$demoMode  = get_post_meta( $order_id, 'demo_payment_mode', true );
				$demoCheckoutId = get_post_meta( $order_id, 'demo_payment_id', true );
				if ($demoMode === 'UPI') {
					echo '<h4>'.esc_html("demo Checkout","demoCheckout").'</h4>';
					echo '<p><strong>'.esc_html("demo Checkout Id:","demoCheckout").'</strong>' . esc_attr($demoCheckoutId , 'demoCheckout') . '</p>'; 
				}else if ($demoMode === 'COD') {
					echo '<h4>'.esc_html("demo Checkout",'demoCheckout').'</h4>';
					echo '<p><strong>'.esc_html("demo Checkout Type:","demoCheckout").'</strong>COD</p>'; 
				}
			?>
		</div>
	<?php 
	}
}
        
/***
 * Woocommerce custom shop order 
 * 
 */
if (!function_exists("demoCheckout_order_column")) {

	function demoCheckout_order_column($columns){
		$reordered_columns = array();
		// Inserting columns to a specific location
		foreach( $columns as $key => $column){
			$reordered_columns[$key] = $column;
			if( $key ==  'order_status' ){
				// Inserting after "Status" column
				$reordered_columns['demo-column'] = __( 'demo Payment Id','demoCheckout');
			}
		}
		return $reordered_columns;
	}
}  
/***
 * Woocommerce custom shop order 
 * 
 */
if (!function_exists("demoCheckout_risk_order_column")) {

	function demoCheckout_risk_order_column($columns){
		$reordered_columns = array();
		// Inserting columns to a specific location
		foreach( $columns as $key => $column){
			$reordered_columns[$key] = $column;
			if( $key == 'demo-column' ){
				$reordered_columns['demo-risk'] = __( 'demo Order Risk','demoCheckout');
			}
		}
		return $reordered_columns;
	}
}

/**
 * Woocommerce Order Column Add
 * 
 */
function demoCheckout_orders_list_column_content( $column, $post_id ){
	switch ( $column )
	{
		case 'demo-column' :
			// Get custom post meta data
			$demoTag = get_post_meta( $post_id, 'demo_tags', true );
			$demoMode  = get_post_meta( $post_id, 'demo_payment_mode', true );
			$demoCheckoutId = get_post_meta( $post_id, 'demo_payment_id', true );
			if(!empty($demoMode)){
				if($demoMode === 'COD'){
					echo '-';
				}elseif ($demoMode === 'UPI') {
					echo esc_attr($demoCheckoutId);
				}
			}else{
				echo '-';
			}
		break;
		case 'demo-risk' :
			// Get custom post meta data
			$demoRisk = get_post_meta( $post_id, 'demo_risk', true );
			if(!empty($demoRisk)){
				if($demoRisk == 'GREEN'){
					echo '<div class="demo-green"><span class=" fs-24 font-weight-600 dashicons dashicons-saved demo-text-green"></span></a>';
				}elseif ($demoRisk == 'RED') {
					echo '<div class="demo-red"><span class=" fs-24 font-weight-600 dashicons dashicons-no-alt demo-text-red"></span></a>';
				}
			}else{
				echo '-';
			}
		break;

	}
}

?>