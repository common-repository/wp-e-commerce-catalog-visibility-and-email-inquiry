<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WPEC EI Default Form Style Settings

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

class WPEC_EI_Popup_Form_Style_Settings extends WPEC_Email_Inquiry_Admin_UI
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
	public $option_name = 'wpec_email_inquiry_customize_email_popup';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wpec_email_inquiry_customize_email_popup';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 2;
	
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
				'success_message'	=> __( 'Default Form Style Settings successfully saved.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'error_message'		=> __( 'Error: Default Form Style Settings can not save.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'reset_message'		=> __( 'Default Form Style Settings successfully reseted.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
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
			'name'				=> 'default-form-style',
			'label'				=> __( 'Default Form Style', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			'callback_function'	=> 'wpec_ei_popup_form_style_settings_form',
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
            	'name' 		=> __( 'Form Background Colour', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Background Colour', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( 'Default', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) . ' [default_value]',
				'id' 		=> 'inquiry_form_bg_colour',
				'type' 		=> 'color',
				'default'	=> '#FFFFFF'
			),
			
			array(
            	'name' 		=> __( 'Form Title', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Header Title', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( "&lt;empty&gt; and the form title will be the Button title", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_heading',
				'type' 		=> 'text',
				'default'	=> ''
			),
			array(  
				'name' 		=> __( 'Title Font', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_heading_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '18px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#000000' )
			),
			
			array(
            	'name' 		=> __( 'Form Send / Submit Button', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Send Button Title', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( "&lt;empty&gt; for default SEND", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_text_button',
				'type' 		=> 'text',
				'default'	=> __( 'SEND', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
			),
			array(  
				'name' 		=> __( 'Button Padding', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( 'Padding from Button text to Button border', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_button_padding',
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array(  'id' 		=> 'inquiry_contact_button_padding_tb',
	 										'name' 		=> __( 'Top/Bottom', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 7 ),
	 
	 								array(  'id' 		=> 'inquiry_contact_button_padding_lr',
	 										'name' 		=> __( 'Left/Right', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 8 ),
	 							)
			),
			array(  
				'name' 		=> __( 'Background Colour', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( 'Default', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) . ' [default_value]',
				'id' 		=> 'inquiry_contact_button_bg_colour',
				'type' 		=> 'color',
				'default'	=> '#EE2B2B'
			),
			array(  
				'name' 		=> __( 'Background Colour Gradient From', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( 'Default', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) . ' [default_value]',
				'id' 		=> 'inquiry_contact_button_bg_colour_from',
				'type' 		=> 'color',
				'default'	=> '#FBCACA'
			),
			array(  
				'name' 		=> __( 'Background Colour Gradient To', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( 'Default', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) . ' [default_value]',
				'id' 		=> 'inquiry_contact_button_bg_colour_to',
				'type' 		=> 'color',
				'default'	=> '#EE2B2B'
			),
			array(  
				'name' 		=> __( 'Button Border', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_button_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '1px', 'style' => 'solid', 'color' => '#EE2B2B', 'corner' => 'rounded' , 'top_left_corner' => 3, 'top_right_corner' => 3, 'bottom_left_corner' => 3, 'bottom_right_corner' => 3 ),
			),
			array(  
				'name' 		=> __( 'Button Font', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_button_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '12px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#FFFFFF' )
			),
			array(  
				'name' => __( 'Button Shadow', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_button_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#999999', 'inset' => '' )
			),
			
			array(
            	'name' 		=> __( 'Style Form With Theme CSS', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Form CSS Class', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( "&lt;empty&gt; for default or enter custom form CSS", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_form_class',
				'type' 		=> 'text',
				'default'	=> ''
			),
			array(  
				'name' 		=> __( 'Button CSS Class', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'desc' 		=> __( 'Enter your own button CSS class', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ),
				'id' 		=> 'inquiry_contact_button_class',
				'type' 		=> 'text',
				'default'	=> ''
			),
			
        ));
	}
	
}

global $wpec_ei_popup_form_style_settings;
$wpec_ei_popup_form_style_settings = new WPEC_EI_Popup_Form_Style_Settings();

/** 
 * wpec_ei_popup_form_style_settings_form()
 * Define the callback function to show subtab content
 */
function wpec_ei_popup_form_style_settings_form() {
	global $wpec_ei_popup_form_style_settings;
	$wpec_ei_popup_form_style_settings->settings_form();
}

?>