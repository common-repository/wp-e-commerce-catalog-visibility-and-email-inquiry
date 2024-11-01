<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WPEC EI Rules & Roles Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class WPEC_EI_Rules_Roles_Settings extends WPEC_Email_Inquiry_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'rules-roles';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'wpec_email_inquiry_rules_roles_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wpec_email_inquiry_rules_roles_settings';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;
	
	/**
	 * @var array
	 */
	public $form_fields = array();
	
	/**
	 * @var array
	 */
	public $form_messages = array();
	
	public function custom_types() {
		$custom_type = array( 'hide_addtocart_yellow_message', 'hide_price_yellow_message' );
		
		return $custom_type;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		// add custom type
		foreach ( $this->custom_types() as $custom_type ) {
			add_action( $this->plugin_name . '_admin_field_' . $custom_type, array( $this, $custom_type ) );
		}
		
		$this->init_form_fields();
		//$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Rules & Roles Settings successfully saved.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'error_message'		=> __( 'Error: Rules & Roles Settings can not save.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'reset_message'		=> __( 'Rules & Roles Settings successfully reseted.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			);
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . trim( $this->form_key ) . '_settings_init', array( $this, 'after_settings_save' ) );
		
		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $wpec_ei_admin_interface;
		
		$wpec_ei_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* after_settings_save()
	/* Process after settings is saved */
	/*-----------------------------------------------------------------------------------*/
	public function after_settings_save() {
		if ( isset( $_POST['bt_save_settings'] ) && isset( $_POST['wpec_email_inquiry_reset_products_options'] ) ) {
			delete_option( 'wpec_email_inquiry_reset_products_options' );
			WPEC_PCF_Functions::reset_products_to_global_settings();				
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $wpec_ei_admin_interface;
		
		$wpec_ei_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}
	
	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {
		
		$subtab_data = array( 
			'name'				=> 'rules-roles',
			'label'				=> __( 'Settings', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			'callback_function'	=> 'wpec_ei_rules_roles_settings_form',
		);
		
		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {
	
		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();
		
		return $subtabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $wpec_ei_admin_interface;
		
		$output = '';
		$output .= $wpec_ei_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
		return $output;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$roles = $wp_roles->get_names();
		$roles_hide_cart = $roles;
		$roles_hide_price = $roles_hide_cart;
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
            	'name' 		=> __( 'Trouble Shooting', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
				'class'		=> 'trouble_shooting_container',
           	),
			array(  
				'name' 		=> __( "WPEC Theme Compatibility", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'class'		=> 'rules_roles_explanation',
				'id' 		=> 'rules_roles_explanation',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'show',
				'checked_value'		=> 'show',
				'unchecked_value' 	=> 'hide',
				'checked_label'		=> __( 'SHOW', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'unchecked_label' 	=> __( 'HIDE', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			array(
				'desc'		=> '<table class="form-table"><tbody><tr valign="top"><td class="titledesc" scope="row" colspan="2" ><div>'.__( "Product Page Rules 'Hide Cart' , 'Hide Price' cannot work if the bespoke theme you are using removes or replaces (with a custom function) 2 core WP e-Commerce functions and does not use the WP e-Commerce template structure and hierarchy. The 2 core functions required are:", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</div><p>'.esc_attr("do_action('wpsc_product_form_fields_begin')").'</p><p>'.esc_attr("do_action('wpsc_product_form_fields_end')").'</p></td></tr><tr valign="top"><td class="titledesc" scope="row" colspan="2" ><div>'.__( "<strong>Note:</strong> All Product Page Rules work just fine in the Default WordPress theme. You will only have an issue where a theme dev has made customized WP e-Commerce templates and functions and not followed the WP e-Commerce Codex. If this is the case you should consider using another theme.", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</div></td></tr></tbody></table>',
                'type' 		=> 'heading',
				'class'		=> 'rules_roles_explanation_container',
           	),
			
			array(
            	'name' 		=> __( 'Product Page Rules:', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc'		=> __( "Product Page Rules apply a single action Rule to all product pages which can be filtered on a per User Role basis. These Rules can also be varied on a product by product basis from each product edit page.", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			
			array(
            	'name' 		=> __( "Product Page Rule: Hide 'Add to Cart'", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( "Apply for all users before log in", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'class'		=> 'hide_addcartbt_before_login',
				'id' 		=> 'hide_addcartbt_before_login',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'unchecked_label' 	=> __( 'OFF', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			array(  
				'name' 		=> __( "Apply by user role after log in", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'class'		=> 'hide_addcartbt_after_login',
				'id' 		=> 'hide_addcartbt_after_login',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'unchecked_label' 	=> __( 'OFF', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			
			array(
				'class'		=> 'hide_addcartbt_after_login_container',
                'type' 		=> 'heading',
           	),
			array(  
				'class' 	=> 'chzn-select role_apply_hide_cart',
				'id' 		=> 'role_apply_hide_cart',
				'type' 		=> 'multiselect',
				'placeholder' => __( 'Choose Roles', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'css'		=> 'width:450px; min-height:80px; max-width:100%;',
				'options'	=> $roles_hide_cart,
			),
			array(
                'type' 		=> 'heading',
				'class'		=> 'yellow_message_container hide_addtocart_yellow_message_container',
           	),
			array(
                'type' 		=> 'hide_addtocart_yellow_message',
           	),
			
			array(
				'name' 		=> __( "Product Page Rule: Hide Price", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( "Apply for all users before log in", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'class'		=> 'hide_price_before_login',
				'id' 		=> 'hide_price_before_login',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'unchecked_label' 	=> __( 'OFF', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			array(  
				'name' 		=> __( "Apply by user role after log in", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'class'		=> 'hide_price_after_login',
				'id' 		=> 'hide_price_after_login',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'unchecked_label' 	=> __( 'OFF', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			array(
				'class'		=> 'hide_price_after_login_container',
                'type' 		=> 'heading',
           	),
			array(  
				'class' 	=> 'chzn-select role_apply_hide_price',
				'id' 		=> 'role_apply_hide_price',
				'type' 		=> 'multiselect',
				'placeholder' => __( 'Choose Roles', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'css'		=> 'width:450px; min-height:80px; max-width:100%;',
				'options'	=> $roles_hide_price,
			),
			array(
                'type' 		=> 'heading',
				'class'		=> 'yellow_message_container hide_price_yellow_message_container',
           	),
			array(
                'type' 		=> 'hide_price_yellow_message',
           	),
			
			array(
				'name'		=> __( 'Product Page Rules Reset:', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( "Reset All Products", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( "<strong>Warning:</strong> Set to Yes and Save Changes will reset ALL custom Product Page and Product Card Rules and Roles on ALL products back to the admin panels Global settings.", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'wpec_email_inquiry_reset_products_options',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'separate_option'	=> true,
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'unchecked_label' 	=> __( 'OFF', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			
        ));
	}
	
	public function hide_addtocart_yellow_message( $value ) {
		$customized_settings = get_option( $this->option_name, array() );
	?>
    	<tr valign="top" class="hide_addtocart_yellow_message_tr" style=" ">
			<th scope="row" class="titledesc">&nbsp;</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
            <?php 
				$hide_addtocart_blue_message = '<div><strong>'.__( 'Note', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).':</strong> '.__( "If you do not apply Rules to your role i.e. 'administrator' you will need to either log out or open the site in another browser where you are not logged in to see the Rule feature is activated.", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</div>
                <div style="clear:both"></div>
                <a class="hide_addtocart_yellow_message_dontshow" style="float:left;" href="javascript:void(0);">'.__( "Don't show again", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</a>
                <a class="hide_addtocart_yellow_message_dismiss" style="float:right;" href="javascript:void(0);">'.__( "Dismiss", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</a>
                <div style="clear:both"></div>';
            	echo $this->blue_message_box( $hide_addtocart_blue_message, '450px' ); 
			?>
<style>
.a3rev_panel_container .hide_addtocart_yellow_message_container {
<?php if ( $customized_settings['hide_addcartbt_before_login'] == 'no' && $customized_settings['hide_addcartbt_after_login'] == 'no' ) echo 'display: none;'; ?>
<?php if ( get_option( 'wpec_ei_hide_addtocart_message_dontshow', 0 ) == 1 ) echo 'display: none !important;'; ?>
<?php if ( !isset($_SESSION) ) { @session_start(); } if ( isset( $_SESSION['wpec_ei_hide_addtocart_message_dismiss'] ) ) echo 'display: none !important;'; ?>
}
</style>
<script>
(function($) {
$(document).ready(function() {
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.hide_addcartbt_after_login', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".hide_addtocart_yellow_message_container").slideDown();
		} else if( $("input.hide_addcartbt_before_login").prop( "checked" ) == false ) {
			$(".hide_addtocart_yellow_message_container").slideUp();
		}
	});
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.hide_addcartbt_before_login', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".hide_addtocart_yellow_message_container").slideDown();
		} else if( $("input.hide_addcartbt_after_login").prop( "checked" ) == false ) {
			$(".hide_addtocart_yellow_message_container").slideUp();
		}
	});
	
	$(document).on( "click", ".hide_addtocart_yellow_message_dontshow", function(){
		$(".hide_addtocart_yellow_message_tr").slideUp();
		$(".hide_addtocart_yellow_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dontshow",
				option_name: 	"wpec_ei_hide_addtocart_message_dontshow",
				security: 		"<?php echo wp_create_nonce("wpec_ei_yellow_message_dontshow"); ?>"
			};
		$.post( "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>", data);
	});
	
	$(document).on( "click", ".hide_addtocart_yellow_message_dismiss", function(){
		$(".hide_addtocart_yellow_message_tr").slideUp();
		$(".hide_addtocart_yellow_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dismiss",
				session_name: 	"wpec_ei_hide_addtocart_message_dismiss",
				security: 		"<?php echo wp_create_nonce("wpec_ei_yellow_message_dismiss"); ?>"
			};
		$.post( "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>", data);
	});
});
})(jQuery);
</script>
			</td>
		</tr>
    <?php
	
	}
	
	public function hide_price_yellow_message( $value ) {
		$customized_settings = get_option( $this->option_name, array() );
	?>
    	<tr valign="top" class="hide_price_yellow_message_tr" style=" ">
			<th scope="row" class="titledesc">&nbsp;</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
            <?php 
				$hide_inquiry_button_blue_message = '<div><strong>'.__( 'Note', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).':</strong> '.__( "If you do not apply Rules to your role i.e. 'administrator' you will need to either log out or open the site in another browser where you are not logged in to see the Rule feature is activated.", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</div>
                <div style="clear:both"></div>
                <a class="hide_price_yellow_message_dontshow" style="float:left;" href="javascript:void(0);">'.__( "Don't show again", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</a>
                <a class="hide_price_yellow_message_dismiss" style="float:right;" href="javascript:void(0);">'.__( "Dismiss", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</a>
                <div style="clear:both"></div>';
            	echo $this->blue_message_box( $hide_inquiry_button_blue_message, '450px' ); 
			?>
<style>
.a3rev_panel_container .hide_price_yellow_message_container {
<?php if ( $customized_settings['hide_price_before_login'] == 'no' && $customized_settings['hide_price_after_login'] == 'no' ) echo 'display: none;'; ?>
<?php if ( get_option( 'wpec_ei_hide_price_message_dontshow', 0 ) == 1 ) echo 'display: none !important;'; ?>
<?php if ( !isset($_SESSION) ) { @session_start(); } if ( isset( $_SESSION['wpec_ei_hide_price_message_dontshow'] ) ) echo 'display: none !important;'; ?>
}
</style>
<script>
(function($) {
$(document).ready(function() {
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.hide_price_after_login', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".hide_price_yellow_message_container").slideDown();
		} else if( $("input.hide_price_before_login").prop( "checked" ) == false ) {
			$(".hide_price_yellow_message_container").slideUp();
		}
	});
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.hide_price_before_login', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".hide_price_yellow_message_container").slideDown();
		} else if( $("input.hide_price_after_login").prop( "checked" ) == false ) {
			$(".hide_price_yellow_message_container").slideUp();
		}
	});
	
	$(document).on( "click", ".hide_price_yellow_message_dontshow", function(){
		$(".hide_price_yellow_message_tr").slideUp();
		$(".hide_price_yellow_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dontshow",
				option_name: 	"wpec_ei_hide_price_message_dontshow",
				security: 		"<?php echo wp_create_nonce("wpec_ei_yellow_message_dontshow"); ?>"
			};
		$.post( "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>", data);
	});
	
	$(document).on( "click", ".hide_price_yellow_message_dismiss", function(){
		$(".hide_price_yellow_message_tr").slideUp();
		$(".hide_price_yellow_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dismiss",
				session_name: 	"wpec_ei_hide_price_message_dontshow",
				security: 		"<?php echo wp_create_nonce("wpec_ei_yellow_message_dismiss"); ?>"
			};
		$.post( "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>", data);
	});
});
})(jQuery);
</script>
			</td>
		</tr>
    <?php
	
	}
	
	public function include_script() {
	?>
<style>
#a3_plugin_panel_extensions {
	position:absolute;
	bottom:50px;	
}
.conditional_logic_container table th {
	padding-left:0px;
	padding-right:20px;	
}
.conditional_logic_container label {
	font-weight:bold;
	font-size: 1.17em;
}
.yellow_message_container {
	margin-top: -15px;	
}
.yellow_message_container a {
	text-decoration:none;	
}
.yellow_message_container th, .yellow_message_container td, .hide_addcartbt_after_login_container th, .hide_addcartbt_after_login_container td, .hide_price_after_login_container th, .hide_price_after_login_container td, .role_apply_activate_order_logged_in_container th, .role_apply_activate_order_logged_in_container td {
	padding-top: 0 !important;
	padding-bottom: 0 !important;
}
</style>
<script>
(function($) {
	
	a3revEIRulesRoles = {
		
		initRulesRoles: function () {
			
			if ( $("input.rules_roles_explanation").is(':checked') == false ) {
				$(".rules_roles_explanation_container").hide();
			}
			
			/* 
			 * Condition logic for activate apply rule to logged in users
			 * Show Roles dropdown for : Hide Add to Cart, Hide Price
			 * Apply when page is loaded
			 */
			if ( $("input.hide_addcartbt_after_login:checked").val() == 'yes' ) {
				$('.hide_addcartbt_after_login_container').css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
			} else {
				$('.hide_addcartbt_after_login_container').css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
			}
			if ( $("input.hide_price_after_login:checked").val() == 'yes') {
				$('.hide_price_after_login_container').css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
			} else {
				$('.hide_price_after_login_container').css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
			}

		},
		
		conditionLogicEvent: function () {
			
			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.rules_roles_explanation', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".rules_roles_explanation_container").slideDown();
				} else {
					$(".rules_roles_explanation_container").slideUp();
				}
			});
			
			/* 
			 * Condition logic for activate apply rule to logged in users
			 * Show Roles dropdown for : Hide Add to Cart Hide Price
			 */
			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.hide_addcartbt_after_login', function( event, value, status ) {
				$('.hide_addcartbt_after_login_container').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
				if ( status == 'true' ) {
					$(".hide_addcartbt_after_login_container").slideDown();
				} else {
					$(".hide_addcartbt_after_login_container").slideUp();
				}
			});
			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.hide_price_after_login', function( event, value, status ) {
				$('.hide_price_after_login_container').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
				if ( status == 'true' ) {
					$(".hide_price_after_login_container").slideDown();
				} else {
					$(".hide_price_after_login_container").slideUp();
				}
			});
			
		}
		
	}
	
	$(document).ready(function() {
		
		a3revEIRulesRoles.initRulesRoles();
		a3revEIRulesRoles.conditionLogicEvent();
		
	});
	
})(jQuery);
</script>
    <?php	
	}
}

global $wpec_ei_rules_roles_settings;
$wpec_ei_rules_roles_settings = new WPEC_EI_Rules_Roles_Settings();

/** 
 * wpec_ei_rules_roles_settings_form()
 * Define the callback function to show subtab content
 */
function wpec_ei_rules_roles_settings_form() {
	global $wpec_ei_rules_roles_settings;
	$wpec_ei_rules_roles_settings->settings_form();
}

?>