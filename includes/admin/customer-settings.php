<?php 

/**Woocomerece Order And customer Hook */
add_action('show_user_profile', 'demoCheckout_user_profile_fields');

// Hooks near the bottom of the profile page (if not current user) 
add_action('edit_user_profile', 'demoCheckout_user_profile_fields');

/**
 * Customer Custom filed data added
 */

if (!function_exists("demoCheckout_user_profile_fields")) {
    function demoCheckout_user_profile_fields($user){
    ?>
        <h3>Customer demo info</h>
        <table class="form-table">
            <tr>
                <th>
                    <label for="demo_phone"><?php _e( 'Phone Number','demoCheckout' ); ?></label>
                </th>
                <td>
                    <input type="number" name="" disabled="disabled" readonly id="demo_phone" placeholder="<?php echo esc_attr( get_the_author_meta( 'demo_phone', $user->ID ),'demoCheckout' ); ?>" class="regular-text" /> <span class="description "> <?php _e( 'Phone Number cannot be changed','demoCheckout' ); ?> .</span>
                </td>
            </tr>
        </table>
    <?php 
    }
}

?>