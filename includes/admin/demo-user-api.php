<?php 

/* 
* Function to send user details on plugin activation
*/

add_action( 'admin_notices', 'demo_activation_send_user_details' );
register_activation_hook( __FILE__, 'demo_activation_send_user_details' );

function demo_activation_send_user_details() { 

    global $current_user;
    get_currentuserinfo();
  
    $demo_details = [];
  
    $username = $current_user->user_login; 
    $email = $current_user->user_email;
    $website_title = get_bloginfo( 'name' );
  
    $demo_details[ 'username' ] = $username;
    $demo_details[ 'email' ] = $email;
    $demo_details[ 'website_title' ] = $website_title;
    


    $endpoint = APIURL;
 
    $body = $demo_details;
    
    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];
    
    wp_remote_post( $endpoint, $options );

  
    
}