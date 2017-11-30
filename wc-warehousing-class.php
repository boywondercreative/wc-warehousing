<?php

class WC_Warehousing extends WC_Shipping_Method {

  public function __construct(){
    $this->id = 'warehousing';
    $this->method_title = __( "Warehousing", 'wc-warehousing' );

    $this->init_form_fields();
    $this->init_settings();

    $this->enabled  = $this->get_option( 'enabled' );
    $this->title    = $this->get_option( 'title' );
    $this->mode     = $this->get_option( 'mode' );
    $this->price    = $this->get_option( 'price' );
    
    
    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
  }

  public function init_form_fields() {
    $this->form_fields = array(
      'enabled' => array(
        'title'       => __( "Enable/Disable", 'woocommerce' ),
        'type'        => 'checkbox',
        'label'       => __( "Enable Warehousing", 'wc-warehousing' ),
        'default'     => 'yes'
      ),
      
      'title' => array(
        'title'       => __( "Title", 'woocommerce' ),
        'type'        => 'text',
        'description' => __( "This controls the title which the user sees during checkout.", 'woocommerce' ),
        'default'     => __( "Warehousing", 'wc-warehousing' )
      ),
      
      'mode' => array(
        'title'       => __( "Mode", 'wc-warehousing' ),
        'type'        => 'select',
        'options'     => array(
            'everyone'  => __( "Everyone", 'wc-warehousing' ),
            'selected'  => __( "Selected Customers", 'wc-warehousing' )
        )
      ),
      
      'price' => array(
        'title'       => __( 'Price', 'woocommerce' ),
        'type'        => 'text'
      )

    );
  }

  public function is_available( $package ){
    global $current_user;
    return $this->enabled == "yes" && ( $this->mode == "everyone" || get_user_meta( $current_user->ID, 'warehousing_enabled', true ) == "yes" );
  }

  public function calculate_shipping($package){
    $this->add_rate( array(
      'id'   => $this->id,
      'label' => $this->title,
      'cost'   => (double)$this->price
    ));
  }

}