<?php

/**
 * Plugin Name: Demo Checkout Wordpress
 * Plugin URI: aipxperts.com
 * Description:Install it in minutes so your customers can check out in seconds.Simply install & configure the Demo Checkout Wordpress for WooCommerce WordPress plugin to start seeing fewer abandoned carts and more sales for your business. Our top sellers have seen a 20-35% uplift in conversion within one week of installing Demo Checkout Wordpress.
  *Author:      Aipxperts Themes
  *Author URI:  http://aipxperts.com
  *Text Domain: plugin-scroll-demo
 **/

/**
 * 
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
 */

if (!defined("ABSPATH")) {
  exit();
}

/**
 *
 * demoCheckout main class
 */

if (!class_exists("demoCheckout")) {
  class demoCheckout
  {
    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @var string
     */
    public $version = "1.0.1";

    /**
     * Unique identifier for the plugin.
     *
     * The variable name is used as the text domain when internationalizing strings of text.
     *
     * @var string
     */
    public $plugin_slug;

    /**
     * A reference to an instance of this class.
     *
     * @var demoCheckout
     */
    private static $_instance = null;

    /**
     * Initialize the plugin.
     */
    public function __construct()
    {
      add_action("plugins_loaded", [$this, "init"]);
    }

    /**
     * Returns an instance of this class.
     *
     * @return demoCheckout
     */
    public static function instance()
    {
      if (!isset(self::$_instance)) {
        self::$_instance = new demoCheckout();
      }

      return self::$_instance;
    }

    /**
     * Init this plugin when WordPress Initializes.
     */
    public function init()
    {
      $this->plugin_slug = "demo-checkout";
      // If woocommerce class exists and woocommerce version is greater than required version.
      if (class_exists("woocommerce") && WC()->version >= 2.1) {
        $this->defineConstants();
        $this->includes();
        $this->actionHook();

        add_filter("plugin_action_links_" . plugin_basename(__FILE__), [
          $this,
          "pluginActionLinks",
        ]);
      }
      // If woocommerce class exists but woocommerce version is older than required version.
      elseif (class_exists("woocommerce")) {
        add_action("admin_notices", [$this, "updateWoocommerce"]);
      }
      // If woocommerce plugin not found.
      else {
        add_action("admin_notices", [$this, "needWoocommerce"]);
      }
    }
    /**
     * 
     */
    static function install() {
      // do not generate any output here

    }

    /**
     * Rest Api hook
     */
    public function actionHook()
    {
      // add_action("rest_api_init", [$this, "woo_callback_url"]);
      add_action( 'wp_enqueue_scripts',   [$this, "demoCheckout_frontendStyle"] );
      add_action( 'admin_enqueue_scripts',  [$this, "demoCheckout_backendStyle"] );
    }


    /**
     * Call back Url Called For the 
     *  
     */
    
    public function woo_callback_url()
    {
      register_rest_route("wc_cart/v2", "callback", [
        "methods" => "POST",
        "callback" => [$this, "wc_callback_save"],
      ]);
    }
    /**
     * reset api call back function 
     */
    public function wc_callback_save(WP_REST_Request $request){
        global $wpdb;
        $request = $request;
        update_option("demo_callback_all", $request);
        $demo_callback_all = get_option("demo_callback_all");
        $json = 
          [
            "code" => 200,
            'data' => $demo_callback_all
          ];
      return  $json;
    }


    /**
     * Defind constants for this plugin.
     */
    public function defineConstants()
    {
      $this->define("demoCheckout_LOCALE", $this->plugin_slug);
      $this->define("demoCheckout_PATH", $this->pluginPath());
      $this->define("demoCheckout_ASSETS_PATH", $this->assetsPath());
    }

    
    /**
     * Register and enqueue frontend Style.
     *
     * @return mixed
     */
    public function demoCheckout_frontendStyle()
      {
          wp_enqueue_style('demo-style',demoCheckout_ASSETS_PATH . "/css/demo-styles.css", array(), false, 'all');
          wp_enqueue_script( "demo-script",demoCheckout_ASSETS_PATH . "/js/custom.js", ["jquery"], "20120206", true
        );
      }


  /**
   * Register and enqueue backend Style.
   *
   * @return mixed
   */
  public function demoCheckout_backendStyle(){
      wp_enqueue_script( 'demo-admin-script',demoCheckout_ASSETS_PATH . '/js/admin-custom.js', array('jquery') , time());  
      wp_register_style( 'demo_admin_css', demoCheckout_ASSETS_PATH . '/css/admin-demo-styles.css', false, '1.0.0' );
      wp_enqueue_style( 'demo_admin_css' );
    }
    

    /**
     * Include required core files.
     */
    public function includes()
    {
      require_once "includes/admin/customer-settings.php";
      require_once "includes/admin/demo-user-api.php";
      require_once "includes/admin/menu.php";
      require_once "includes/admin/order.php";
      require_once "includes/admin/orderExport.php";
      require_once "includes/frontend/frontendScript.php";
      require_once "includes/frontend/cart.php";
      require_once "includes/frontend/singleProduct.php";
    }

    /**
     * Define constants if not already defined.
     *
     * @param  string $name
     * @param  string|bool $value
     */
    public function define($name, $value)
    {
      if (!defined($name)) {
        define($name, $value);
      }
    }

    /**
     * Get Plugin Path
     */
    public function pluginPath()
    {
      return untrailingslashit(plugin_dir_url(__FILE__));
    }

    /**
     * Get the plugin assets path.
     *
     * @return string
     */
    public function assetsPath()
    {
      return trailingslashit(plugin_dir_url(__FILE__) . "assets/");
    }
    /**
     * Show admin notice if woocommerce plugin version is older than required version.
     */
    public function updateWoocommerce()
    {
      echo '<div class="error">';
      echo "<p>" .__("To use demo Checkout update your WooCommerce plugin.", "demoCheckout" ) . "</p>";
      echo "</div>";
    }

    /**
     * Show admin notice if woocommerce plugin not found.
     */
    public function needWoocommerce()
    {
      echo '<div class="error">';
      echo "<p>" .
        __(
          "demo Checkout need woocommerce plugin",
          "demoCheckout"
        ) .
        "</p>";
      echo "</div>";
    }

    /**
     * Show action links on the plugins page.
     *
     * @param  array $links
     * @return array
     */
    public function pluginActionLinks($links)
    {
      $links[] =
        '<a href="' .
        admin_url("options-general.php?page=demo-checkout-settings") .
        '">' .
        __("Settings", "demoCheckout") .
        "</a>";
      return $links;
    }
  }
}

/**
 * Instantiate this class globally.
 */

register_activation_hook( __FILE__, array( 'demoCheckout', 'install' ) );

$GLOBALS["demoCheckout"] = demoCheckout::instance();
