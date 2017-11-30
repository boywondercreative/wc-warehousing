<?php

/*
Plugin Name: WooCommerce Warehousing
Version: 1.0
Description: Allow warehousing as shipping method.
Author: Mattia Malonni
Author URI: https://www.mattiamalonni.com
Text Domain: wc-warehousing
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins) ) {

  add_action( 'woocommerce_shipping_init', 'init_warehousing' );

  function init_warehousing(){
    require_once('wc-warehousing-class.php');
  }

  add_filter( 'woocommerce_shipping_methods', 'add_warehousing' );

  function add_warehousing( $methods ) {
    $methods[] = 'WC_Warehousing';
    return $methods;
  }

  add_action( 'show_user_profile', 'add_warehousing_fields_to_user_profile' );
  add_action( 'edit_user_profile', 'add_warehousing_fields_to_user_profile' );

  function add_warehousing_fields_to_user_profile( $user ) { 
    global  $woocommerce;
    ?>
      <h3><?php echo __("Warehousing", 'wc-warehousing'); ?></h3>
      <table class="form-table">
        <tr>
          <th><?php echo __('Enable/Disable', 'woocommerce'); ?></th>
          <td>
            <fieldset>
              <label for="warehousing_enabled">
                <input type="checkbox" name="warehousing_enabled" id="warehousing_enabled" value="yes" <?php echo get_user_meta( $user->ID, 'warehousing_enabled', true ) == "yes" ? "checked=\"checked\"" : ""; ?>>
                <?php echo __("Enable warehousing for this user", 'wc-warehousing') ?>
              </label>
            </fieldset>
          </td>
        </tr>
      </table>
    <?php
  }

  add_action( 'personal_options_update', 'save_warehousing_fields_from_user_profile' );
  add_action( 'edit_user_profile_update', 'save_warehousing_fields_from_user_profile' );

  function save_warehousing_fields_from_user_profile( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
      return false; 
    }
    update_user_meta( $user_id, 'warehousing_enabled', $_POST['warehousing_enabled'] );
  }

}