<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WPEC EI Contact Form Settings

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

class WPEC_EI_Contact_Form_Settings extends WPEC_Email_Inquiry_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'default-contact-form';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'wpec_email_inquiry_contact_form_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wpec_email_inquiry_contact_form_settings';
	
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
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->init_form_fields();
		$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Default Form Settings successfully saved.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'error_message'		=> __( 'Error: Default Form Settings can not save.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'reset_message'		=> __( 'Default Form Settings successfully reseted.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			);
		
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
				
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
			'name'				=> 'settings',
			'label'				=> __( 'Settings', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			'callback_function'	=> 'wpec_ei_contact_form_settings_form',
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
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
            	'name' 		=> __( 'Built-in Contact Form', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(
				'name' 		=> __( "Email 'From' Settings", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc'		=> __( 'The following options affect the sender (email address and name) used in WPEC Product Email Inquiries.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'class'		=> 'default_contact_form_options',
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( '"From" Name', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc'		=> __( '&lt;empty&gt; defaults to Site Title', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_email_from_name',
				'type' 		=> 'text',
				'default'	=> get_bloginfo('blogname'),
			),
			array(  
				'name' 		=> __( '"From" Email Address', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc'		=> __( '&lt;empty&gt; defaults to WordPress admin email address', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_email_from_address',
				'type' 		=> 'text',
				'default'	=> get_bloginfo('admin_email'),
			),
			array(
				'name' 		=> __( "Sender 'Request A Copy'", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Send Copy to Sender', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( "Gives users a checkbox option to send a copy of the Inquiry email to themselves", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_send_copy',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'YES', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'unchecked_label' 	=> __( 'NO', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			
			array(
				'name' 		=> __( 'Email Delivery', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'class'		=> 'default_contact_form_options',
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Inquiry Email goes to', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc'		=> __( '&lt;empty&gt; defaults to WordPress admin email address', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_email_to',
				'type' 		=> 'text',
				'default'	=> get_bloginfo('admin_email'),
			),
			array(  
				'name' 		=> __( 'CC', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc'		=> __( "&lt;empty&gt; defaults to 'no copy sent' or add an email address", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_email_cc',
				'type' 		=> 'text',
				'default'	=> '',
			),
			
        ));
	}
	
}

global $wpec_ei_contact_form_settings;
$wpec_ei_contact_form_settings = new WPEC_EI_Contact_Form_Settings();

/** 
 * wpec_ei_contact_form_settings_form()
 * Define the callback function to show subtab content
 */
function wpec_ei_contact_form_settings_form() {
	global $wpec_ei_contact_form_settings;
	$wpec_ei_contact_form_settings->settings_form();
}

?>