<?php
/**
 * WPEC PCF Functions
 *
 * Table Of Contents
 *
 * check_hide_add_cart_button()
 * check_hide_price()
 * check_add_email_inquiry_button()
 * check_add_email_inquiry_button_on_shoppage()
 * reset_products_to_global_settings()
 * email_inquiry()
 * upgrade_version_2_0()
 */
class WPEC_PCF_Functions
{	
	public static function check_hide_add_cart_button ($product_id) {
		global $wpec_email_inquiry_rules_roles_settings;
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
			
		if (!isset($wpsc_pcf_custom['hide_addcartbt_before_login'])) $hide_addcartbt_before_login = $wpec_email_inquiry_rules_roles_settings['hide_addcartbt_before_login'] ;
		else $hide_addcartbt_before_login = esc_attr($wpsc_pcf_custom['hide_addcartbt_before_login']);
		
		// dont hide add to cart button if setting is not checked and not logged in users
		if ($hide_addcartbt_before_login == 'no' && !is_user_logged_in() ) return false;
		
		// hide add to cart button if setting is checked and not logged in users
		if ($hide_addcartbt_before_login != 'no' &&  !is_user_logged_in()) return true;
		
		if (!isset($wpsc_pcf_custom['hide_addcartbt_after_login'])) $hide_addcartbt_after_login = $wpec_email_inquiry_rules_roles_settings['hide_addcartbt_after_login'] ;
		else $hide_addcartbt_after_login = esc_attr($wpsc_pcf_custom['hide_addcartbt_after_login']);		

		// don't hide add to cart if for logged in users is deacticated
		if ( $hide_addcartbt_after_login != 'yes' ) return false;
		
		if (!isset($wpsc_pcf_custom['role_apply_hide_cart'])) {
			$role_apply_hide_cart = (array) $wpec_email_inquiry_rules_roles_settings['role_apply_hide_cart'];
		} else {
			$role_apply_hide_cart = (array) $wpsc_pcf_custom['role_apply_hide_cart'];
		}
		
		$user_login = wp_get_current_user();
		if (is_array($user_login->roles) && count($user_login->roles) > 0) {
			$user_role = '';
			foreach ($user_login->roles as $role_name) {
				$user_role = $role_name;
				break;
			}
			// hide add to cart button if current user role in list apply role
			if ( in_array($user_role, $role_apply_hide_cart) ) return true;
		}
		return false;
		
	}
	
	public static function check_hide_price ($product_id) {
		global $wpec_email_inquiry_rules_roles_settings;
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
			
		if (!isset($wpsc_pcf_custom['hide_price_before_login'])) $hide_price_before_login = $wpec_email_inquiry_rules_roles_settings['hide_price_before_login'];
		else $hide_price_before_login = esc_attr($wpsc_pcf_custom['hide_price_before_login']);
			
		// dont hide price if setting is not checked and not logged in users
		if ($hide_price_before_login == 'no' && !is_user_logged_in() ) return false;
		
		// alway hide price if setting is checked and not logged in users
		if ($hide_price_before_login != 'no' && !is_user_logged_in()) return true;
		
		if (!isset($wpsc_pcf_custom['hide_price_after_login'])) $hide_price_after_login = $wpec_email_inquiry_rules_roles_settings['hide_price_after_login'] ;
		else $hide_price_after_login = esc_attr($wpsc_pcf_custom['hide_price_after_login']);		

		// don't hide price if for logged in users is deacticated
		if ( $hide_price_after_login != 'yes' ) return false;
		
		if (!isset($wpsc_pcf_custom['role_apply_hide_price'])) {
			$role_apply_hide_price = (array) $wpec_email_inquiry_rules_roles_settings['role_apply_hide_price'];
		} else {
			$role_apply_hide_price = (array) $wpsc_pcf_custom['role_apply_hide_price'];
		}
		
		$user_login = wp_get_current_user();		
		if (is_array($user_login->roles) && count($user_login->roles) > 0) {
			$user_role = '';
			foreach ($user_login->roles as $role_name) {
				$user_role = $role_name;
				break;
			}
			// hide price if current user role in list apply role
			if ( in_array($user_role, $role_apply_hide_price) ) return true;
		}
		
		return false;
	}
	
	public static function check_add_email_inquiry_button ($product_id) {
		global $wpec_email_inquiry_global_settings;
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
			
		if (!isset($wpsc_pcf_custom['show_email_inquiry_button_before_login'])) $show_email_inquiry_button_before_login = $wpec_email_inquiry_global_settings['show_email_inquiry_button_before_login'];
		else $show_email_inquiry_button_before_login = esc_attr($wpsc_pcf_custom['show_email_inquiry_button_before_login']);
		
		// dont show email inquiry button if setting is not checked and not logged in users
		if ($show_email_inquiry_button_before_login == 'no' && !is_user_logged_in() ) return false;
		
		// alway show email inquiry button if setting is checked and not logged in users
		if ($show_email_inquiry_button_before_login != 'no' && !is_user_logged_in()) return true;
		
		if (!isset($wpsc_pcf_custom['show_email_inquiry_button_after_login'])) $show_email_inquiry_button_after_login = $wpec_email_inquiry_global_settings['show_email_inquiry_button_after_login'] ;
		else $show_email_inquiry_button_after_login = esc_attr($wpsc_pcf_custom['show_email_inquiry_button_after_login']);		

		// don't show email inquiry button if for logged in users is deacticated
		if ( $show_email_inquiry_button_after_login != 'yes' ) return false;
		
		if (!isset($wpsc_pcf_custom['role_apply_show_inquiry_button'])) $role_apply_show_inquiry_button = (array) $wpec_email_inquiry_global_settings['role_apply_show_inquiry_button'];
		else $role_apply_show_inquiry_button = (array) $wpsc_pcf_custom['role_apply_show_inquiry_button'];
		
		
		$user_login = wp_get_current_user();		
		if (is_array($user_login->roles) && count($user_login->roles) > 0) {
			$user_role = '';
			foreach ($user_login->roles as $role_name) {
				$user_role = $role_name;
				break;
			}
			// show email inquiry button if current user role in list apply role
			if ( in_array($user_role, $role_apply_show_inquiry_button) ) return true;
		}
		
		return false;
		
	}
	
	public static function check_add_email_inquiry_button_on_shoppage ($product_id=0) {
		global $wpec_email_inquiry_global_settings;
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
			
		if (!isset($wpsc_pcf_custom['inquiry_single_only'])) $inquiry_single_only = $wpec_email_inquiry_global_settings['inquiry_single_only'];
		else $inquiry_single_only = esc_attr($wpsc_pcf_custom['inquiry_single_only']);
		
		if ($inquiry_single_only == 'yes') return false;
		
		return WPEC_PCF_Functions::check_add_email_inquiry_button($product_id);
		
	}
	
	public static function reset_products_to_global_settings() {
		global $wpdb;
		$wpdb->query( "DELETE FROM ".$wpdb->postmeta." WHERE meta_key='_wpsc_pcf_custom' " );
	}
	
	public static function email_inquiry($product_id, $your_name, $your_email, $your_phone, $your_message, $send_copy_yourself = 1) {
		global $wpec_email_inquiry_contact_form_settings;
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
		
		if ( WPEC_PCF_Functions::check_add_email_inquiry_button( $product_id ) ) {
			
			$wpec_pcf_contact_success = stripslashes( get_option( 'wpec_pcf_contact_success', '' ) );
			if ( trim( $wpec_pcf_contact_success ) != '') $wpec_pcf_contact_success = wpautop(wptexturize(   $wpec_pcf_contact_success ));
			else $wpec_pcf_contact_success = __("Thanks for your inquiry - we'll be in touch with you as soon as possible!", 'wp-e-commerce-catalog-visibility-and-email-inquiry' );
		
			if (!isset($wpsc_pcf_custom['inquiry_email_to']) || trim(esc_attr($wpsc_pcf_custom['inquiry_email_to'])) == '') $to_email = $wpec_email_inquiry_contact_form_settings['inquiry_email_to'];
			else $to_email = esc_attr($wpsc_pcf_custom['inquiry_email_to']);
			if (trim($to_email) == '') $to_email = get_option('admin_email');
			
			if ( $wpec_email_inquiry_contact_form_settings['inquiry_email_from_address'] == '' )
				$from_email = get_option('admin_email');
			else
				$from_email = $wpec_email_inquiry_contact_form_settings['inquiry_email_from_address'];
				
			if ( $wpec_email_inquiry_contact_form_settings['inquiry_email_from_name'] == '' )
				$from_name = ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
			else
				$from_name = $wpec_email_inquiry_contact_form_settings['inquiry_email_from_name'];
			
			if (!isset($wpsc_pcf_custom['inquiry_email_cc']) || trim(esc_attr($wpsc_pcf_custom['inquiry_email_cc'])) == '') $cc_emails = $wpec_email_inquiry_contact_form_settings['inquiry_email_cc'];
			else $cc_emails = esc_attr($wpsc_pcf_custom['inquiry_email_cc']);
			if (trim($cc_emails) == '') $cc_emails = '';
			
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset='. get_option('blog_charset');
			$headers[] = 'From: '.$from_name.' <'.$from_email.'>';
			$headers_yourself = $headers;
			
			if (trim($cc_emails) != '') {
				$cc_emails_a = explode("," , $cc_emails);
				if (is_array($cc_emails_a) && count($cc_emails_a) > 0) {
					foreach ($cc_emails_a as $cc_email) {
						$headers[] = 'Cc: '.$cc_email;
					}
				} else {
					$headers[] = 'Cc: '.$cc_emails;
				}
			}
			
			$product_name = get_the_title($product_id);
			$product_url = get_permalink($product_id);
			$subject = __('Email inquiry for', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).' '.$product_name;
			$subject_yourself = __('[Copy]: Email inquiry for', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).' '.$product_name;
			
			$content = '
	<table width="99%" cellspacing="0" cellpadding="1" border="0" bgcolor="#eaeaea"><tbody>
	  <tr>
		<td>
		  <table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="#ffffff"><tbody>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Name', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_name]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Email Address', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="mailto:[your_email]">[your_email]</a></font> 
			  </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Phone', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_phone]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Product Name', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="[product_url]">[product_name]</a></font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Message', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_message]</font> 
		  </td></tr></tbody></table></td></tr></tbody></table>';
		  
			$content = str_replace('[your_name]', $your_name, $content);
			$content = str_replace('[your_email]', $your_email, $content);
			$content = str_replace('[your_phone]', $your_phone, $content);
			$content = str_replace('[product_name]', $product_name, $content);
			$content = str_replace('[product_url]', $product_url, $content);
			$your_message = str_replace( '://', ':&#173;¬≠//', $your_message );
			$your_message = str_replace( '.com', '&#173;.com', $your_message );
			$your_message = str_replace( '.net', '&#173;.net', $your_message );
			$your_message = str_replace( '.info', '&#173;.info', $your_message );
			$your_message = str_replace( '.org', '&#173;.org', $your_message );
			$your_message = str_replace( '.au', '&#173;.au', $your_message );
			$content = str_replace('[your_message]', wpautop( $your_message ), $content);
			
			$content = apply_filters('pcf_inquiry_content', $content);
			
			// Filters for the email
			add_filter( 'wp_mail_from', array( 'WPEC_PCF_Functions', 'get_from_address' ) );
			add_filter( 'wp_mail_from_name', array( 'WPEC_PCF_Functions', 'get_from_name' ) );
			add_filter( 'wp_mail_content_type', array( 'WPEC_PCF_Functions', 'get_content_type' ) );
			
			wp_mail( $to_email, $subject, $content, $headers, '' );
			
			if ($send_copy_yourself == 1) {
				wp_mail( $your_email, $subject_yourself, $content, $headers_yourself, '' );
			}
			
			// Unhook filters
			remove_filter( 'wp_mail_from', array( 'WPEC_PCF_Functions', 'get_from_address' ) );
			remove_filter( 'wp_mail_from_name', array( 'WPEC_PCF_Functions', 'get_from_name' ) );
			remove_filter( 'wp_mail_content_type', array( 'WPEC_PCF_Functions', 'get_content_type' ) );
			
			return $wpec_pcf_contact_success;
		} else {
			return __("Sorry, this product don't enable email inquiry.", 'wp-e-commerce-catalog-visibility-and-email-inquiry' );
		}
	}
	
	public static function get_from_address() {
		global $wpec_email_inquiry_contact_form_settings;
		if ( $wpec_email_inquiry_contact_form_settings['inquiry_email_from_address'] == '' )
			$from_email = get_option('admin_email');
		else
			$from_email = $wpec_email_inquiry_contact_form_settings['inquiry_email_from_address'];
			
		return $from_email;
	}
	
	public static function get_from_name() {
		global $wpec_email_inquiry_contact_form_settings;
		if ( $wpec_email_inquiry_contact_form_settings['inquiry_email_from_name'] == '' )
			$from_name = ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		else
			$from_name = $wpec_email_inquiry_contact_form_settings['inquiry_email_from_name'];
			
		return $from_name;
	}
	
	public static function get_content_type() {
		return 'text/html';
	}
	
	public static function wpec_ei_yellow_message_dontshow() {
		check_ajax_referer( 'wpec_ei_yellow_message_dontshow', 'security' );
		$option_name   = $_REQUEST['option_name'];
		update_option( $option_name, 1 );
		die();
	}
	
	public static function wpec_ei_yellow_message_dismiss() {
		check_ajax_referer( 'wpec_ei_yellow_message_dismiss', 'security' );
		$session_name   = $_REQUEST['session_name'];
		if ( !isset($_SESSION) ) { @session_start(); } 
		$_SESSION[$session_name] = 1 ;
		die();
	}
	
	public static function upgrade_version_2_0() {
		$have_deactivate_meta = get_posts(array('numberposts' => -1, 'post_type' => array('wpsc-product'), 'post_status' => array('publish', 'private'), 'meta_key' => '_wpsc_deactivate_find_seller'));
		if (is_array($have_deactivate_meta) && count($have_deactivate_meta) > 0) {
			foreach($have_deactivate_meta as $product) {
				$deactivate_find_seller = get_post_meta( $product->ID, '_wpsc_deactivate_find_seller', true );
				$wpsc_pcf_custom = get_post_meta( $product->ID, '_wpsc_pcf_custom', true);
				if (!is_array($wpsc_pcf_custom)) $wpsc_pcf_custom = array();
				if ($deactivate_find_seller == 'no') {
					$wpsc_pcf_custom['wpec_pcf_show_button'] = 0;
					update_post_meta($product->ID, '_wpsc_pcf_custom', $wpsc_pcf_custom);
				}
				if ($deactivate_find_seller == 'no') {
					$wpsc_pcf_custom['wpec_pcf_show_button'] = 1;
					update_post_meta($product->ID, '_wpsc_pcf_custom', $wpsc_pcf_custom);
				}
			}
		}
	}
	
	public static function pro_upgrade_version_2_1_0() {
		$wpec_pcf_hide_addcartbt = get_option( 'wpec_pcf_hide_addcartbt', 1 );
		$wpec_email_inquiry_rules_roles_settings = array(
			'hide_addcartbt_before_login'	=> ( $wpec_pcf_hide_addcartbt == 1 ) ? 'yes' : 'no',
			'hide_addcartbt_after_login'	=> 'yes',
		);	
		update_option( 'wpec_email_inquiry_rules_roles_settings', $wpec_email_inquiry_rules_roles_settings );
		
		$wpec_pcf_show_button = get_option( 'wpec_pcf_show_button', 1 );
		$wpec_pcf_user = get_option( 'wpec_pcf_user', 0 );
		$wpec_email_inquiry_global_settings = array(
			'show_email_inquiry_button_before_login'	=> ( $wpec_pcf_show_button == 1 ) ? 'yes' : 'no',
			'show_email_inquiry_button_after_login'		=> ( $wpec_pcf_user == 1 ) ? 'yes' : 'no',
			'inquiry_single_only'						=> get_option( 'wpec_pcf_single_only', 'no' ),
		);	
		update_option( 'wpec_email_inquiry_global_settings', $wpec_email_inquiry_global_settings );
		
		$wpec_email_inquiry_contact_form_settings = array(
			'inquiry_email_from_name'					=> get_bloginfo('blogname'),
			'inquiry_email_from_address'				=> get_bloginfo('admin_email'),
			'inquiry_send_copy'							=> 'no',
			'inquiry_email_to'							=> get_option( 'wpec_pcf_email_to', get_bloginfo('admin_email') ),
			'inquiry_email_cc'							=> get_option( 'wpec_pcf_email_cc', '' ),
		);	
		update_option( 'wpec_email_inquiry_contact_form_settings', $wpec_email_inquiry_contact_form_settings );
		
		$wpec_email_inquiry_customize_email_popup = array(
			'inquiry_form_bg_colour'					=> '#FFFFFF',
			'inquiry_contact_heading'					=> get_option( 'wpec_pcf_contact_heading', '' ),
			'inquiry_contact_heading_font'				=> array( 'size' => '18px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#000000' ),
			'inquiry_contact_text_button'				=> get_option( 'wpec_pcf_contact_text_button', __( 'SEND', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) ),
			'inquiry_contact_button_padding_tb'			=> 7,
			'inquiry_contact_button_padding_lr'			=> 8,
			'inquiry_contact_button_bg_colour'			=> get_option( 'wpec_pcf_contact_button_bg_colour', '#EE2B2B' ),
			'inquiry_contact_button_bg_colour_from'		=> get_option( 'wpec_pcf_contact_button_bg_colour', '#FBCACA' ),
			'inquiry_contact_button_bg_colour_to'		=> get_option( 'wpec_pcf_contact_button_bg_colour', '#EE2B2B' ),
			'inquiry_contact_button_border'				=> array( 'width' => '1px', 'style' => 'solid', 'color' => get_option( 'wpec_pcf_contact_button_border_colour', '#EE2B2B' ), 'corner' => 'rounded' , 'top_left_corner' => 3, 'top_right_corner' => 3, 'bottom_left_corner' => 3, 'bottom_right_corner' => 3 ),
			'inquiry_contact_button_font'				=> array( 'size' => '12px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#FFFFFF' ),
			'inquiry_contact_button_shadow'				=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#999999', 'inset' => '' ),
			'inquiry_contact_form_class'				=> get_option( 'wpec_pcf_contact_form_class', '' ),
			'inquiry_contact_button_class'				=> get_option( 'wpec_pcf_contact_button_class', '' ),
		);	
		update_option( 'wpec_email_inquiry_customize_email_popup', $wpec_email_inquiry_customize_email_popup );
		
		$wpec_pcf_button_type = get_option( 'wpec_pcf_button_type', 'button' );
		$wpec_email_inquiry_customize_email_button = array(
			'inquiry_button_type'						=> ( $wpec_pcf_button_type == 'link' ) ? 'link' : 'button',
			'inquiry_button_position'					=> ( get_option( 'wpec_pcf_button_position', 'below' ) == 'above' ) ? 'above' : 'below',
			'inquiry_button_margin_top'					=> get_option( 'wpec_pcf_button_padding', 5 ),
			'inquiry_button_margin_bottom'				=> get_option( 'wpec_pcf_button_padding', 5 ),
			'inquiry_button_margin_left'				=> get_option( 'wpec_pcf_button_padding', 5 ),
			'inquiry_button_margin_right'				=> get_option( 'wpec_pcf_button_padding', 5 ),
			'inquiry_button_title'						=> get_option( 'wpec_pcf_button_title', __( 'Product Enquiry', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) ),
			'inquiry_button_padding_tb'					=> 7,
			'inquiry_button_padding_lr'					=> 8,
			'inquiry_button_bg_colour'					=> get_option( 'wpec_pcf_button_bg_colour', '#EE2B2B' ),
			'inquiry_button_bg_colour_from'				=> get_option( 'wpec_pcf_button_bg_colour', '#FBCACA' ),
			'inquiry_button_bg_colour_to'				=> get_option( 'wpec_pcf_button_bg_colour', '#EE2B2B' ),
			'inquiry_button_border'						=> array( 'width' => '1px', 'style' => 'solid', 'color' => get_option( 'wpec_pcf_button_border_colour', '#EE2B2B' ), 'corner' => 'rounded' , 'top_left_corner' => 3, 'top_right_corner' => 3, 'bottom_left_corner' => 3, 'bottom_right_corner' => 3 ),
			'inquiry_button_font'						=> array( 'size' => '12px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#FFFFFF' ),
			'inquiry_button_shadow'						=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#999999', 'inset' => '' ),
			'inquiry_button_class'						=> get_option( 'wpec_pcf_button_class', '' ),
			'inquiry_text_before'						=> get_option( 'wpec_pcf_text_before', '' ),
			'inquiry_hyperlink_text'					=> get_option( 'wpec_pcf_hyperlink_text', __( 'Click Here', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) ),
			'inquiry_trailing_text'						=> get_option( 'wpec_pcf_trailing_text', '' ),
			'inquiry_hyperlink_font'					=> array( 'size' => '12px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#000000' ),
			'inquiry_hyperlink_hover_color'				=> '#999999',
		);	
		update_option( 'wpec_email_inquiry_customize_email_button', $wpec_email_inquiry_customize_email_button );
	}
}
?>