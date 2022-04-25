<?php 

/**
 * Add Hook
 */
add_action("admin_menu", "demo_settings");
add_action("admin_init", "demo_payment_my_settings_init");

/***
 * Menu settings added
 *
 */

if (!function_exists("demo_settings")) {
    function demo_settings()
    {
        add_menu_page(
            __("demo Checkout Settings", "demoCheckout"),
            __("demo Checkout", "demoCheckout"),
            "manage_options",
            "demo-checkout-settings",
            "demo_menu_settings",
            "dashicons-tagcloud",
            6
        );
    }
}

/***
 * demo menu callback
 *
 */

if (!function_exists("demo_menu_settings")) {
    function demo_menu_settings()
    {
        echo '<h1 class="demo-checkout">'.esc_html('Welcome to the demo Checkout Settings','demoCheckout').'</h1>'; ?>
        <div class="demo-notice">
            <div class="" >
                <div class="demo-size notice notice-success" style="padding:15px;margin:0">
					<h4><?php echo esc_html('Welcome to demo Checkout!','demoCheckout') ?></h4>
					<p><?php echo __('The first step to integrating demo Checkout with your WooCommerce store is to become a seller with demo. Contact to <a herf="mailto:woocommerce@bureau.id" >woocommerce@bureau.id </a>','demoCheckout') ?></p>
                    <a href="https://dashboard.godemo.co/" class="btn btn-primary button button-primary" target="_blank"><?php echo esc_html('demo Dashboard','demoCheckout') ?></a> 
                </div>
            </div>
        </div>
        
			<div class="demo-class-settings">
				<form method="POST" class="form-settings" action="options.php">
					<?php
                        settings_fields("demo-payment-secure-settings");
                        do_settings_sections("demo-payment-secure-settings");
                        submit_button();
                    ?>
				</form>
			</div>

    <?php
    }
}


/**
	 * Blaz Form Fileds
	 */
	if (!function_exists("demo_payment_my_settings_init")) {
		function demo_payment_my_settings_init()
		{
			add_settings_section(
				"demo_payment_secure_settings",
				__("Settings", "demoCheckout"),
				"my_setting_section_callback_function",
				"demo-payment-secure-settings"
			);

			add_settings_field(
				"demo_payment_setting_checkout_key",
				__("demo Test Mode", "demoCheckout"),
				"demo_payment_setting_checkout_key_markup",
				"demo-payment-secure-settings",
				"demo_payment_secure_settings"
			);

			add_settings_field( 
				'demo_checkout_button_placement_option', 
				__("Select Product Button Location", "demoCheckout"), 
				'demo_checkout_button_placement_option_markup', 
				'demo-payment-secure-settings', 
				'demo_payment_secure_settings', 
				array('class' => 'demo-button-placement')
			);

			
			add_settings_field( 
				'demo_checkout_button_placement_option_other', 
				__("Other Button Placement hook", "demoCheckout"), 
				'demo_checkout_button_placement_option_other_markup', 
				'demo-payment-secure-settings', 
				'demo_payment_secure_settings', 
				array('class' => 'demo-other-button')
			
			);

			
			add_settings_field( 
				'demo_checkout_woo_checkout_option', 
				__("Checkout Page Button Placement", "demoCheckout"), 
				'demo_checkout_woo_checkout_option_markup', 
				'demo-payment-secure-settings', 
				'demo_payment_secure_settings'
			
			);
			
			
			add_settings_field( 
				'demo_checkout_cart_option', 
				__("Cart Page Button Placement", "demoCheckout"), 
				'demo_checkout_cart_option_markup', 
				'demo-payment-secure-settings', 
				'demo_payment_secure_settings'
			
			);

			register_setting(
				"demo-payment-secure-settings",
				"demo_pyemnt_security_field"
			);
			register_setting(
				"demo-payment-secure-settings",
				"demo_payment_setting_checkout_key"
			);

			register_setting('demo-payment-secure-settings','demo_checkout_button_placement_option');
			register_setting('demo-payment-secure-settings','demo_checkout_button_placement_option_other');
			register_setting('demo-payment-secure-settings','demo_checkout_woo_checkout_option');
			register_setting('demo-payment-secure-settings','demo_checkout_cart_option');
			register_setting('demo-payment-secure-settings','demo_checkout_button_placement_option_css');

		}
	}
	
	/**
	 * demo Checkout Single Product options 
	 */
	function demo_checkout_button_placement_option_markup() { 
		$buttonPlacement = get_option('demo_checkout_button_placement_option');
		$messageDetails = __( 'Other , is available for users with advanced understanding of WordPress hooks. If Other is selected, a valid WordPress action hook must be entered in the next field, Enter Alternate Product Button Location..', 'demoCheckout' );
		$selected = __( 'selected', 'demoCheckout' );
		?>
		<select id="demo_checkout_button_placement_option" name="demo_checkout_button_placement_option">
			<option value="woocommerce_before_add_to_cart_quantity" <?php if ( $buttonPlacement == 'woocommerce_before_add_to_cart_quantity') { echo $selected ; }?> > <?php echo __('Before Quantity Selection','demoCheckout'); ?> </option>
			<option value="woocommerce_after_add_to_cart_quantity" <?php if ( $buttonPlacement == 'woocommerce_after_add_to_cart_quantity') { echo $selected; } ?> > <?php echo __('After Quantity Selection' , 'demoCheckout'); ?></option>
			<option value="woocommerce_after_add_to_cart_button" <?php if ( $buttonPlacement == 'woocommerce_after_add_to_cart_button') { echo $selected; } ?>  > <?php echo __('After Add to Cart Button' , 'demoCheckout'); ?> </option>
			<option value="other" <?php if (  $buttonPlacement == 'other') { echo $selected; }?> > <?php echo __('Other' , 'demoCheckout'); ?>  </option>
			<option value="disable" <?php if ( $buttonPlacement == 'disable') { echo $selected; }?> > <?php echo __('Disable' , 'demoCheckout'); ?>  </option>
		</select>
		<p><?php echo esc_html( $messageDetails ); ?></p>
		<?php
	}

	/**
	 * demo Checkout Single Product hook Options
	 */
	function demo_checkout_button_placement_option_other_markup() { 
		$buttonOther = get_option("demo_checkout_button_placement_option_other");
		$messageDetails = __( 'Enter an alternative location for displaying the demo Checkout button is available for users with advanced understanding of WordPress hooks.', 'demoCheckout' );
		?>
		<input type="text" name="demo_checkout_button_placement_option_other" class="min-w-300" value="<?php echo esc_attr($buttonOther); ?>" id="demo_checkout_button_placement_option_other" >
		<p><?php echo esc_html( $messageDetails ); ?></p>
		<?php
	}

	
	
	/**
	 * demo Checkout Woo checkout page 
	 */
	function demo_checkout_woo_checkout_option_markup() { 
		$buttonCheckoutPlacement = get_option('demo_checkout_woo_checkout_option');
		$selected = __( 'selected', 'demoCheckout' );
		?>
		
		<select id="demo_checkout_woo_checkout_option" name="demo_checkout_woo_checkout_option">
			<option value="woocommerce_review_order_before_submit" <?php if (   $buttonCheckoutPlacement == 'woocommerce_review_order_before_submit') { echo $selected; } ?> > <?php echo __('Before Checkout Button','demoCheckout'); ?> </option>
			<option value="woocommerce_review_order_after_submit" <?php if (  $buttonCheckoutPlacement == 'woocommerce_review_order_after_submit') { echo $selected; } ?> > <?php echo __('After Checkout Button' , 'demoCheckout'); ?></option>
			<option value="woocommerce_before_checkout_form" <?php if ( $buttonCheckoutPlacement == 'woocommerce_before_checkout_form') { echo $selected; } ?> > <?php echo __('Before Checkout Form' , 'demoCheckout'); ?> </option>
			<option value="disable" <?php if ($buttonCheckoutPlacement == 'disable') { echo $selected; } ?> > <?php echo __('Disable' , 'demoCheckout'); ?>  </option>
		</select>
		<?php
	}


	/**
	 * demo Style Add
	 */
	
	 function demo_checkout_button_placement_option_css_markup(){
		$buttonCss = get_option("demo_checkout_button_placement_option_css");
		$messageDetails = __( 'Add Additional Css for Button Placement.', 'demoCheckout' );
		?>
		<textarea name="demo_checkout_button_placement_option_css" id="" cols="30" rows="10"><?php echo esc_attr($buttonCss); ?></textarea>
		<p><?php echo esc_html( $messageDetails ); ?></p>
		<?php
	 }
	
	/**
	 * demo Checkout Woo checkout page 
	 */
	function demo_checkout_cart_option_markup() { 
		$buttonCartPlacement = get_option('demo_checkout_cart_option');
		$selected = __( 'selected', 'demoCheckout' );

		?>
		<select id="demo_checkout_cart_option" name="demo_checkout_cart_option">
			<option value="woocommerce_proceed_to_checkout" <?php  if ( $buttonCartPlacement == 'woocommerce_proceed_to_checkout') { echo $selected;  } ?> > <?php echo __('Before Cart Button','demoCheckout'); ?> </option>
			<option value="woocommerce_after_cart_totals" <?php if ( $buttonCartPlacement == 'woocommerce_after_cart_totals') { echo $selected;  } ?> > <?php echo __('After Cart Button' , 'demoCheckout'); ?></option>
			<option value="woocommerce_before_cart" <?php if ( $buttonCartPlacement == 'woocommerce_before_cart') { echo $selected;  } ?> > <?php echo __('Before Cart Form' , 'demoCheckout'); ?> </option>
			<option value="disable"  <?php if ( $buttonCartPlacement == 'disable') { echo $selected;  } ?> > <?php echo __('Disable' , 'demoCheckout'); ?>  </option>
		</select>
		<?php
	}

    /**
	 * demo Settings show
	 */

	if (!function_exists("my_setting_section_callback_function")) {
		function my_setting_section_callback_function()
		{
			// echo '<p>Intro text for our settings section</p>';
		}
	}

	/**
	 * demo Settings payment
	 */

	if (!function_exists("demo_payment_setting_checkout_key_markup")) {
		function demo_payment_setting_checkout_key_markup()
		{
            $options = get_option("demo_payment_setting_checkout_key");
            $checked = '';
            if($options == 1){
                $checked = 'checked';
            }
			$messageDetails = __( 'When test mode is enabled, only logged-in admin users will see the demo Checkout button.', 'demoCheckout' ); 
		?>
			<input type="checkbox" name="demo_payment_setting_checkout_key" value="1" id="demo_checkout_test_mode" <?php echo esc_attr($checked)?>>
            <label for="demo_checkout_test_mode">Enable test mode</label>
            <p><?php echo esc_html( $messageDetails ); ?></p>
	<?php
		}
	}