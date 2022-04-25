<?php


/**
 * Action Add
 */
add_action( 'manage_posts_extra_tablenav', 'demo_payment_orders_export_button', 20, 1 );
add_action( 'init', 'demo_payment_orders_export' );
	
/***
 * Place order button place Export
 * 
 */
function demo_payment_orders_export_button( $which ) {
	global $typenow;
	if ( 'shop_order' === $typenow && 'top' === $which ) {
		?>
		<div class="tablenav top">
			<div class="alignleft actions">
				
				<input type="text" name="start_date" id="start_date" class="" readonly placeholder="<?php _e('End date'); ?>" value="">
				<input type="text" name="end_date" id="end_date" class="" readonly placeholder="<?php _e('End date'); ?> " value="">
				<input type="submit" name="export_all_posts" class="button button-primary" value="<?php _e('Export Orders'); ?>" />

			</div>
		</div>
		<?php
	}
}

/**
 * Validdations form
 */
function validation($data)
{
	$error = [];
	

	$startDateArray  = explode('-', $data['start_date']);
	$endDateArray  = explode('-', $data['end_date']);
	$validDate = 1;
	if($startDateArray == '' || $endDateArray == ''){
		$error['errors']['start_date'] = 'Date is not valid';
		return $error;
	}
	if(is_numeric($startDateArray[0] ) && is_numeric($startDateArray[1] ) && is_numeric($startDateArray[2] )){
		$validDate= 0;
	}
	if(is_numeric($endDateArray[0] ) && is_numeric($endDateArray[1] ) && is_numeric($endDateArray[2] )){
		$validDate = 0;
	}
	try {
		//code...
		if ( $validDate === 0 && !wp_checkdate($startDateArray[1]  ,$startDateArray[0] , $startDateArray[2] , $data['start_date'])) {
			$error['errors']['start_date'] = ' Date is not valid';
		}
		if ( $validDate === 0 && !wp_checkdate($endDateArray[1]  ,$endDateArray[0] , $endDateArray[2] , $data['start_date'])) {
			$error['errors']['end_date'] = ' Date is not valid';
		}
		if ( $validDate === 1 ){
			$error['errors'] = 'Date is not valid';
		}
	} catch (\Exception $e) {
		//throw $th;
		$error['errors']= 'Date is not valid';
	}
  return $error;
}

/**
 * Show Error .
 */
function demo_error()
{
	echo '<div class="error">';
	echo "<p>" .__("Date is not valid.", "demoCheckout" ) . "</p>";
	echo "</div>";
}

/***
 * Order Export 
 * 
 */
function demo_payment_orders_export(){
	if(isset($_GET['export_all_posts'])) {
		$start_date    = sanitize_text_field( $_GET["start_date"] );
		$end_date      = sanitize_text_field( $_GET["end_date"] );

		$data = array(
			'start_date' => $start_date,
			'end_date' => $end_date,
		);
		$validation = validation($data);
	
		if(is_array($validation) && $validation['errors'] ){
			add_action("admin_notices", "demo_error");
			return;
		}
		$before_date = (isset($start_date)) ? $start_date .' 00:00:00': '';
		$after_date = (isset($end_date)) ? $end_date .' 23:59:59': '';
		
		$dateArray = array();
		if($before_date != ''){
			$dateArray =array(
				'after'     => $before_date,
				'before'    => $after_date,
				'inclusive' => true,
			);
		}
		
		$loop = new WP_Query( array(
			'post_type'         => 'shop_order',
			'post_status'       =>  array_keys( wc_get_order_statuses() ),
			'posts_per_page'    => 2000,
			'date_query' => array(
				$dateArray
			), 
		) );
	
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="demo-order.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');

		$file = fopen('php://output', 'w');

		fputcsv($file, array('Order Id', 'Order Date', 'Order Status', 'Transaction ID','Payment Method' ,'Item Sub Total','Voucher Amount','demo Discount','Shipping Total','Paid Amount','Refund Amount','Tags','Customer Email'));

		if ( $loop->have_posts() ): 
			while ( $loop->have_posts() ) : $loop->the_post();
			$orderArray= array();
			// The order ID
			$order_id = $loop->post->ID;
			$order = wc_get_order($loop->post->ID);
			
			$demoMode  = get_post_meta( $order_id, 'demo_payment_mode', true );
			$paymentMode = $demoCheckoutId = "";
			$demoDiscount  = get_post_meta( $order_id, 'demo_discount_amount', true );
			$paidAmount = $order->get_total();
			if($demoMode === 'UPI'){
				$demoCheckoutId = get_post_meta( $order_id, 'demo_payment_id', true );
				$paymentMode = "demo UPI";
				if(is_numeric($demoDiscount) && $demoDiscount != ''){
					$paidAmount = $order->get_total() - $demoDiscount; 
				}
			}else if ($demoMode === 'COD') {
				$paymentMode = "demo COD";
			}
			$tags = get_post_meta( $order_id, 'demo_tags', true );
			$couponAmount = 0;
			$orderdata = $order->get_data();
			foreach( $order->get_used_coupons() as $coupon_code ){
					// Get the WC_Coupon object
					$coupon = new WC_Coupon($coupon_code);
					$discount_type = $coupon->get_discount_type(); // Get coupon discount type
					$couponAmount = $coupon->get_amount(); // Get coupon amount
			}
			$couponAmount = $orderdata['discount_total'];
			$orerdate = $order->get_date_created()->date("Y-m-d h:i:s");
			$line_subtotal     = $order->get_subtotal();
			$line_total        = $order->get_total();
		
			$orderTotalRefunded = wc_format_decimal($order->get_total_refunded()); // Get the refunded amount for a line item.
			$paymentMethod = ( $paymentMode != '' ) ? $paymentMode : $order->get_payment_method();
			// Get an instance of the WC_Order Object
			$customerEmail = $order->get_billing_email();
			$shipping_total = $order->get_shipping_total();
			$shipping_tax   = $order->get_shipping_tax();
			

			$orderArray = array(
				$order->get_id(),
				$orerdate,
				$order->get_status(),
				$demoCheckoutId,
				$paymentMethod,
				$line_subtotal,
				$couponAmount,
				$demoDiscount,
				$shipping_total,
				$paidAmount,
				$orderTotalRefunded,
				$tags,
				$customerEmail
			);
		
			fputcsv($file, $orderArray);
			endwhile;
		
		wp_reset_postdata();
		
		endif;

		exit;
	
	}
}
?>