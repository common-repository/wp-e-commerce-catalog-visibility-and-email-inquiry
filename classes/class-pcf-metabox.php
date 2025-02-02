<?php
/**
 * WPEC PCF MetaBox
 *
 * Table Of Contents
 *
 * add_meta_boxes()
 * the_meta_forms()
 * save_meta_boxes()
 */
class WPEC_PCF_MetaBox
{
	
	public static function add_meta_boxes(){
		global $post;
		$pagename = 'wpsc-product';
		add_meta_box( 'wpec_email_inquiry_meta', __('Email & Cart', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ), array('WPEC_PCF_MetaBox', 'the_meta_forms'), $pagename, 'normal', 'high' );
	}
	
	public static function the_meta_forms() {
		global $post;
		global $wpec_email_inquiry_rules_roles_settings;
		global $wpec_email_inquiry_global_settings;
		global $wpec_email_inquiry_contact_form_settings;
		global $wpec_email_inquiry_customize_email_button;
		add_action('admin_footer', array('WPEC_PCF_Hook_Filter', 'admin_footer_scripts'), 10);
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$roles = $wp_roles->get_names();
		
		
		$wpsc_pcf_custom = get_post_meta( $post->ID, '_wpsc_pcf_custom', true);
		if (is_array($wpsc_pcf_custom)) extract($wpsc_pcf_custom);
		
		if (!isset($hide_addcartbt_before_login)) $hide_addcartbt_before_login = $wpec_email_inquiry_rules_roles_settings['hide_addcartbt_before_login'];
		else $hide_addcartbt_before_login = esc_attr($hide_addcartbt_before_login);
		
		if (!isset($hide_addcartbt_after_login)) $hide_addcartbt_after_login = $wpec_email_inquiry_rules_roles_settings['hide_addcartbt_after_login'];
		else $hide_addcartbt_after_login = esc_attr($hide_addcartbt_after_login);
		
		if (!isset($show_email_inquiry_button_before_login)) $show_email_inquiry_button_before_login = $wpec_email_inquiry_global_settings['show_email_inquiry_button_before_login'];
		else $show_email_inquiry_button_before_login = esc_attr($show_email_inquiry_button_before_login);
		
		if (!isset($show_email_inquiry_button_after_login)) $show_email_inquiry_button_after_login = $wpec_email_inquiry_global_settings['show_email_inquiry_button_after_login'];
		else $show_email_inquiry_button_after_login = esc_attr($show_email_inquiry_button_after_login);
		
		if (!isset($hide_price_before_login)) $hide_price_before_login = $wpec_email_inquiry_rules_roles_settings['hide_price_before_login'];
		else $hide_price_before_login = esc_attr($hide_price_before_login);
		
		if (!isset($hide_price_after_login)) $hide_price_after_login = $wpec_email_inquiry_rules_roles_settings['hide_price_after_login'];
		else $hide_price_after_login = esc_attr($hide_price_after_login);
		
		if (!isset($role_apply_hide_cart)) $role_apply_hide_cart = (array) $wpec_email_inquiry_rules_roles_settings['role_apply_hide_cart'];
		if (!isset($role_apply_hide_price)) $role_apply_hide_price = (array) $wpec_email_inquiry_rules_roles_settings['role_apply_hide_price'];
		if (!isset($role_apply_show_inquiry_button)) $role_apply_show_inquiry_button = (array) $wpec_email_inquiry_global_settings['role_apply_show_inquiry_button'];
		
		if (!isset($inquiry_email_to)) $inquiry_email_to = $wpec_email_inquiry_contact_form_settings['inquiry_email_to'];
		else $inquiry_email_to = esc_attr($inquiry_email_to);
		
		if (!isset($inquiry_email_cc)) $inquiry_email_cc = $wpec_email_inquiry_contact_form_settings['inquiry_email_cc'];
		else $inquiry_email_cc = esc_attr($inquiry_email_cc);
		
		if (!isset($inquiry_button_type)) $inquiry_button_type = $wpec_email_inquiry_customize_email_button['inquiry_button_type'];
		else $inquiry_button_type = esc_attr($inquiry_button_type);
		
		if (!isset($inquiry_text_before)) $inquiry_text_before = $wpec_email_inquiry_customize_email_button['inquiry_text_before'];
		else $inquiry_text_before = esc_attr($inquiry_text_before);
		
		if (!isset($inquiry_hyperlink_text)) $inquiry_hyperlink_text = $wpec_email_inquiry_customize_email_button['inquiry_hyperlink_text'];
		else $inquiry_hyperlink_text = esc_attr($inquiry_hyperlink_text);
		
		if (!isset($inquiry_trailing_text)) $inquiry_trailing_text = $wpec_email_inquiry_customize_email_button['inquiry_trailing_text'];
		else $inquiry_trailing_text = esc_attr($inquiry_trailing_text);
		
		if (!isset($inquiry_button_title)) $inquiry_button_title = $wpec_email_inquiry_customize_email_button['inquiry_button_title'];
		else $inquiry_button_title = esc_attr($inquiry_button_title);
		
		
		
		if (!isset($inquiry_single_only)) $inquiry_single_only = $wpec_email_inquiry_global_settings['inquiry_single_only'];
		else $inquiry_single_only = esc_attr($inquiry_single_only);
		
		?>
        <style>
		#wpec_email_inquiry_upgrade_area_box { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:10px; position:relative; margin:10px auto;}
		#wpec_email_inquiry_upgrade_area_box legend {margin-left:4px; font-weight:bold;}
		.wpec_ei_rule_after_login_container {
			margin-top:10px;
		}
		.wpec_ei_tab_bar .wp-tab-bar li {
			padding:5px 8px !important;	
		}
		.wpec_ei_tab_bar .wp-tab-bar li.wp-tab-active {
		}
		.wpec_ei_tab_bar .wp-tab-panel {
			border-radius: 0 3px 3px 3px !important;
			-moz-border-radius: 0 3px 3px 3px !important;
			-webkit-border-radius: 0 3px 3px 3px !important;
			max-height: inherit !important;
			overflow:visible !important;
		}
		</style>
        <script>
		(function($) {
		$(document).ready(function() {
			$(document).on( "change", "input.wpec_ei_rule_after_login", function() {
				if ( $(this).prop("checked") ) {
					$(this).parent('label').siblings(".wpec_ei_rule_after_login_container").slideDown();
				} else {
					$(this).parent('label').siblings(".wpec_ei_rule_after_login_container").slideUp();
				}
			});
			
			/* Apply Sub tab selected script */
			$('div.wpec_ei_tab_bar ul.wp-tab-bar li a').click(function(){
				var clicked = $(this);
				var section = clicked.closest('.wpec_ei_tab_bar');
				var target  = clicked.attr('href');
			
				section.find('li').removeClass('wp-tab-active');
			
				if ( section.find('.wp-tab-panel:visible').size() > 0 ) {
					section.find('.wp-tab-panel:visible').fadeOut( 100, function() {
						section.find( target ).fadeIn('fast');
					});
				} else {
					section.find( target ).fadeIn('fast');
				}
			
				clicked.parent('li').addClass('wp-tab-active');
			
				return false;
			});
		});
		})(jQuery);
		</script>
        <table cellspacing="0" class="form-table">
			<tbody>
            	<tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_email_inquiry_reset_product_options"><?php _e('Reset Product Options', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp">
                        <fieldset><label><input type="checkbox" value="1" id="wpec_email_inquiry_reset_product_options" name="wpec_email_inquiry_reset_product_options" /> <?php _e('Check to reset this product setting to the Global Settings', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></fieldset>
                    </td>
                </tr>
			</tbody>
        </table>
        <div class="wpec_ei_tab_bar">
        <ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#wpec_ei_cart_price"><?php echo __( 'Cart & Price', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></a></li>
			<li class="hide-if-no-js"><a href="#wpec_ei_email_inquiry"><?php echo __( 'Email Inquiry', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></a></li>
		</ul>
        <div id="wpec_ei_cart_price" class="wp-tab-panel">
        <table cellspacing="0" class="form-table">
			<tbody>
            	<tr valign="top">
                    <th class="titledesc" scope="rpw" colspan="2"><strong><?php _e( "Product Page Rule: Hide 'Add to Cart'", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></strong></th>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="hide_addcartbt_before_login"><?php _e("Apply for all users before log in", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><label><input type="checkbox" name="_wpsc_pcf_custom[hide_addcartbt_before_login]" id="hide_addcartbt_before_login" value="yes" <?php checked( $hide_addcartbt_before_login, 'yes' ); ?> /> <?php _e('ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="hide_addcartbt_after_login"><?php _e('Apply by user role after log in', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp">
                    	<label><input class="wpec_ei_rule_after_login" type="checkbox" name="_wpsc_pcf_custom[hide_addcartbt_after_login]" id="hide_addcartbt_after_login" value="yes" <?php checked( $hide_addcartbt_after_login, 'yes' ); ?> /> <?php _e('ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
                        <div class="wpec_ei_rule_after_login_container" style=" <?php if ( $hide_addcartbt_after_login != 'yes' ) echo 'display: none;'; ?>">
                    	<select multiple="multiple" id="role_apply_hide_cart" name="_wpsc_pcf_custom[role_apply_hide_cart][]" data-placeholder="<?php _e( 'Choose Roles', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?>" style="display:none; width:300px;" class="chzn-select">
						<?php foreach ( $roles as $key => $val ) { ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, (array) $role_apply_hide_cart), true ); ?>><?php echo $val ?></option>
                        <?php } ?>
                        </select>
                        </div>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw" colspan="2"><strong><?php _e( "Product Page Rule: Hide Price", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></strong></th>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="hide_price_before_login"><?php _e("Apply for all users before log in", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><label><input type="checkbox" name="_wpsc_pcf_custom[hide_price_before_login]" id="hide_price_before_login" value="yes"  <?php checked( $hide_price_before_login, 'yes' ); ?> /> <?php _e('ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="hide_price_after_login"><?php _e('Apply by user role after log in', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp">
                    	<label><input class="wpec_ei_rule_after_login" type="checkbox" name="_wpsc_pcf_custom[hide_price_after_login]" id="hide_price_after_login" value="yes" <?php checked( $hide_price_after_login, 'yes' ); ?> /> <?php _e('ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
                        <div class="wpec_ei_rule_after_login_container" style=" <?php if ( $hide_price_after_login != 'yes' ) echo 'display: none;'; ?>">
                    	<select multiple="multiple" id="role_apply_hide_price" name="_wpsc_pcf_custom[role_apply_hide_price][]" data-placeholder="<?php _e( 'Choose Roles', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?>" style="display:none; width:300px;" class="chzn-select">
						<?php foreach ($roles as $key => $val) { ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, (array) $role_apply_hide_price), true ); ?>><?php echo $val ?></option>
                        <?php } ?>
                        </select>
                        </div>
                    </td>
               	</tr>
        	</tbody>
		</table>
        </div>
        <div id="wpec_ei_email_inquiry" class="wp-tab-panel" style="display:none;">
        <table cellspacing="0" class="form-table">
			<tbody>
                <tr valign="top">
                    <th class="titledesc" scope="rpw" colspan="2"><strong><?php _e( "Product Page Rule: Show Email Inquiry Button", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></strong></th>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="show_email_inquiry_button_before_login"><?php _e('Apply for all users before log in', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp">
                    <label><input type="checkbox" name="_wpsc_pcf_custom[show_email_inquiry_button_before_login]" id="show_email_inquiry_button_before_login" value="yes" <?php checked( $show_email_inquiry_button_before_login, 'yes' ); ?> /> <?php _e('ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="show_email_inquiry_button_after_login"><?php _e('Apply by user role after log in', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp">
                    	<label><input class="wpec_ei_rule_after_login" type="checkbox" name="_wpsc_pcf_custom[show_email_inquiry_button_after_login]" id="show_email_inquiry_button_after_login" value="yes" <?php checked( $show_email_inquiry_button_after_login, 'yes' ); ?> /> <?php _e('ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
                        <div class="wpec_ei_rule_after_login_container" style=" <?php if ( $show_email_inquiry_button_after_login != 'yes' ) echo 'display: none;'; ?>">
                    	<select multiple="multiple" id="role_apply_show_inquiry_button" name="_wpsc_pcf_custom[role_apply_show_inquiry_button][]" data-placeholder="<?php _e( 'Choose Roles', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?>" style="display:none; width:300px;" class="chzn-select">
						<?php foreach ($roles as $key => $val) { ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, (array) $role_apply_show_inquiry_button), true ); ?>><?php echo $val ?></option>
                        <?php } ?>
                        </select>
                        </div>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw" colspan="2"><strong><?php _e('Product Card', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></strong></th>
               	</tr>
            	<tr valign="top">
                    <th class="titledesc" scope="rpw"><label><?php _e('Email Inquiry Feature', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><label><input type="radio" name="_wpsc_pcf_custom[inquiry_single_only]" id="inquiry_single_only_no" value="no" <?php checked( $inquiry_single_only, 'no' ); ?> /> <?php _e('ON', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><input type="radio" name="_wpsc_pcf_custom[inquiry_single_only]" id="inquiry_single_only_yes" value="yes" <?php if ($inquiry_single_only != 'no' ) echo 'checked="checked"'; ?> /> <?php _e('OFF', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label> <div><?php _e( "ON to show Button / Link Text on this Product's Card.", 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></div>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw" colspan="2"><strong><?php _e('Email Delivery Options', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></strong></th>
               	</tr>      
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="inquiry_email_to"><?php _e('Inquiry Email goes to', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><input type="text" name="_wpsc_pcf_custom[inquiry_email_to]" id="inquiry_email_to" value="<?php echo $inquiry_email_to;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="inquiry_email_cc"><?php _e('Copy to', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><input type="text" name="_wpsc_pcf_custom[inquiry_email_cc]" id="inquiry_email_cc" value="<?php echo $inquiry_email_cc;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw" colspan="2"><strong><?php _e('Inquiry Button / Hyperlink Options', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></strong></th>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label><?php _e('Button or Hyperlink Text', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp">
                    <label><input type="radio" name="_wpsc_pcf_custom[inquiry_button_type]" id="wpec_inquiry_button_type" class="inquiry_button_type" value="" checked="checked" /> <?php _e('Button', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><input type="radio" name="_wpsc_pcf_custom[inquiry_button_type]" id="pec_email_inquiry_link" class="inquiry_button_type" value="link" <?php checked( $inquiry_button_type, 'link' ); ?> /> <?php _e('Link', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
                    </td>
               	</tr>
			</tbody>
        </table>
        <div class="button_type_link" style=" <?php if($inquiry_button_type != 'link') { echo 'display:none'; } ?>">
        <table cellspacing="0" class="form-table " >
			<tbody>                
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="inquiry_text_before"><?php _e('Text before', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><input type="text" name="_wpsc_pcf_custom[inquiry_text_before]" id="inquiry_text_before" value="<?php echo $inquiry_text_before;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="inquiry_hyperlink_text"><?php _e('Hyperlink text', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><input type="text" name="_wpsc_pcf_custom[inquiry_hyperlink_text]" id="inquiry_hyperlink_text" value="<?php echo $inquiry_hyperlink_text;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="inquiry_trailing_text"><?php _e('Trailing text', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><input type="text" name="_wpsc_pcf_custom[inquiry_trailing_text]" id="inquiry_trailing_text" value="<?php echo $inquiry_trailing_text;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
			</tbody>
        </table>
        </div>
        <div class="button_type_button" style=" <?php if($inquiry_button_type == 'link') { echo 'display:none'; } ?>">
        <table cellspacing="0" class="form-table " >
			<tbody>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="inquiry_button_title"><?php _e('Button Title', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label></th>
                    <td class="forminp"><input type="text" name="_wpsc_pcf_custom[inquiry_button_title]" id="inquiry_button_title" value="<?php echo $inquiry_button_title;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
			</tbody>
		</table>
        </div>        
        </div>
        </div>
		<script type="text/javascript">
			(function($){		
				$(function(){	
					$('.inquiry_button_type').click(function(){
						if ($("input[name='_wpsc_pcf_custom[inquiry_button_type]']:checked").val() == '') {
							$(".button_type_button").slideDown();
							$(".button_type_link").slideUp();
						} else {
							$(".button_type_link").slideDown();
							$(".button_type_button").slideUp();
						}
					});
				});		  
			})(jQuery);
		</script>
		<?php
	}
	
	public static function save_meta_boxes($post_id){
		$post_status = get_post_status($post_id);
		$post_type = get_post_type($post_id);
		if ($post_type == 'wpsc-product' && isset($_REQUEST['_wpsc_pcf_custom']) && $post_status != false  && $post_status != 'inherit') {
			if (isset($_REQUEST['wpec_email_inquiry_reset_product_options']) && $_REQUEST['wpec_email_inquiry_reset_product_options'] == 1) {
				delete_post_meta($post_id, '_wpsc_pcf_custom');
				return;	
			}
			
			$wpsc_pcf_custom = $_REQUEST['_wpsc_pcf_custom'];
			if ( !isset($wpsc_pcf_custom['hide_addcartbt_before_login'] ) ) {
				$wpsc_pcf_custom['hide_addcartbt_before_login'] = 'no' ;
			}
			if ( !isset($wpsc_pcf_custom['hide_addcartbt_after_login'] ) ) {
				$wpsc_pcf_custom['hide_addcartbt_after_login'] = 'no' ;
			}
			if ( !isset($wpsc_pcf_custom['role_apply_hide_cart'] ) ) {
				$wpsc_pcf_custom['role_apply_hide_cart'] = array() ;
			}
			
			if ( !isset($wpsc_pcf_custom['show_email_inquiry_button_before_login'] ) ) {
				$wpsc_pcf_custom['show_email_inquiry_button_before_login'] = 'no' ;
			}
			if ( !isset($wpsc_pcf_custom['show_email_inquiry_button_after_login'] ) ) {
				$wpsc_pcf_custom['show_email_inquiry_button_after_login'] = 'no' ;
			}
			if ( !isset($wpsc_pcf_custom['role_apply_show_inquiry_button'] ) ) {
				$wpsc_pcf_custom['role_apply_show_inquiry_button'] = array() ;
			}
			
			if ( !isset($wpsc_pcf_custom['hide_price_before_login'] ) ) {
				$wpsc_pcf_custom['hide_price_before_login'] = 'no' ;
			}
			if ( !isset($wpsc_pcf_custom['hide_price_after_login'] ) ) {
				$wpsc_pcf_custom['hide_price_after_login'] = 'no' ;
			}
			if ( !isset($wpsc_pcf_custom['role_apply_hide_price'] ) ) {
				$wpsc_pcf_custom['role_apply_hide_price'] = array() ;
			}
			
			if ( !isset($wpsc_pcf_custom['inquiry_button_type'] ) ) {
				$wpsc_pcf_custom['inquiry_button_type'] = 'button' ;
			}
			if ( !isset($wpsc_pcf_custom['inquiry_single_only'] ) ) {
				$wpsc_pcf_custom['inquiry_single_only'] = 'no' ;
			}
			update_post_meta($post_id, '_wpsc_pcf_custom', $wpsc_pcf_custom);
		}
	}
}
?>
