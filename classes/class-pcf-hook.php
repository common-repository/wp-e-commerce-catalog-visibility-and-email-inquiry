<?php
/**
 * WPEC PCF Hook Filter
 *
 * Table Of Contents
 *
 * hide_addcartbt_or_price()
 * add_contact_button()
 * script_show_contact_button()
 * show_contact_button()
 * pcf_contact_popup()
 * pcf_contact_action()
 * add_style_header()
 * footer_print_scripts()
 * script_contact_popup()
 * admin_footer_scripts()
 * a3_wp_admin()
 * admin_sidebar_menu_css()
 * plugin_extra_links()
 */
class WPEC_PCF_Hook_Filter{
		
	public static function hide_addcartbt_or_price() {
		global $post;
		$product_id = $post->ID;
		
		if ( ( WPEC_PCF_Functions::check_hide_add_cart_button($product_id) || WPEC_PCF_Functions::check_hide_price($product_id) ) && $post->post_type == 'wpsc-product') {
		?>
        <style type="text/css">
		<?php if (  WPEC_PCF_Functions::check_hide_add_cart_button($product_id) ) { ?>
			body input#product_<?php echo $product_id; ?>_submit_button, input#product_<?php echo $product_id; ?>_submit_button { display:none !important; visibility:hidden !important; height:0 !important;}
			body button#product_<?php echo $product_id; ?>_submit_button, button#product_<?php echo $product_id; ?>_submit_button { display:none !important; visibility:hidden !important; height:0 !important;}
			body .product_<?php echo $product_id; ?> .wpsc_buy_button, .product_<?php echo $product_id; ?> .wpsc_buy_button { display:none !important; visibility:hidden !important; height:0 !important; }
		<?php } ?>
		<?php if (  WPEC_PCF_Functions::check_hide_price($product_id) ) { ?>
			body form#product_<?php echo $product_id; ?> .pricedisplay, form#product_<?php echo $product_id; ?> .pricedisplay { display:none !important; visibility:hidden !important; height:0 !important;}
			body .product_view_<?php echo $product_id; ?> .pricedisplay, .product_view_<?php echo $product_id; ?> .pricedisplay { display:none !important; visibility:hidden !important; height:0 !important;}
		<?php } ?>
		</style>
        <?php
		}
	}
	
	public static function hide_price_script() {
		global $post;
		$product_id = $post->ID;
		
		if ( WPEC_PCF_Functions::check_hide_price($product_id) && $post->post_type == 'wpsc-product') {
		?>
        	<script type="text/javascript">
				(function($){		
					$(function(){
						$(".pcf_hide_price_<?php echo $product_id; ?>").remove();
						if($("form#product_<?php echo $product_id; ?>").length > 0){
							$("form#product_<?php echo $product_id; ?>").find(".pricedisplay").remove();
						}
						if($(".product_view_<?php echo $product_id; ?>").length > 0){
							$(".product_view_<?php echo $product_id; ?>").find(".pricedisplay").remove();
						}
					});		  
				})(jQuery);
			</script>
        <?php
		}
	}
	
	public static function hide_price_currency_display( $output = '' ) {		
		$output = '';
		
		remove_filter('wpsc_currency_display', array('WPEC_PCF_Hook_Filter', 'hide_price_currency_display'), 1000 );
		return $output;
	}
	
	public static function hide_price_after_cart_widget_item_name( ) {
		global $wpsc_cart;
   		$product_id = $wpsc_cart->cart_item->product_id;
		$mypost = get_post( $product_id );
		if ( isset($mypost->post_parent) && $mypost->post_parent > 0) $product_id = $mypost->post_parent;
				
		$myproduct = get_post( $product_id );
		if ( WPEC_PCF_Functions::check_hide_price($product_id) && $myproduct->post_type == 'wpsc-product') {
			add_filter('wpsc_currency_display', array('WPEC_PCF_Hook_Filter', 'hide_price_currency_display'), 1000 );
		}
	}
	
	public static function hide_price_currency_display_checkout_page( $output = '' ) {		
		$output = '';
		
		return $output;
	}
	
	public static function hide_price_before_checkout_cart_row() {
		global $wpsc_cart;
   		$product_id = $wpsc_cart->cart_item->product_id;
		$mypost = get_post( $product_id );
		if ( isset($mypost->post_parent) && $mypost->post_parent > 0) $product_id = $mypost->post_parent;
				
		$myproduct = get_post( $product_id );
		if ( WPEC_PCF_Functions::check_hide_price($product_id) && $myproduct->post_type == 'wpsc-product') {
			add_filter('wpsc_currency_display', array('WPEC_PCF_Hook_Filter', 'hide_price_currency_display_checkout_page'), 1000 );
		}
	}
	
	public static function remove_hide_price_after_checkout_cart_row() {
		remove_filter('wpsc_currency_display', array('WPEC_PCF_Hook_Filter', 'hide_price_currency_display_checkout_page'), 1000 );
	}
	
	public static function hide_price_in_refresh_item() {
		if ( wpsc_cart_item_count() > 0 ) {
			while( wpsc_have_cart_items() ) {
				wpsc_the_cart_item();
				global $wpsc_cart;
				$product_id = $wpsc_cart->cart_item->product_id;
				$mypost = get_post( $product_id );
				if ( isset($mypost->post_parent) && $mypost->post_parent > 0) $product_id = $mypost->post_parent;
				
				$myproduct = get_post( $product_id );
				if ( WPEC_PCF_Functions::check_hide_price($product_id) && $myproduct->post_type == 'wpsc-product') {
					$wpsc_cart->cart_item->total_price = 0;
				}
			}
		}
	}
	
	public static function hide_price_refresh_cart_items() {
		global $wpsc_cart;
		
		if ( is_object( $wpsc_cart ) && is_array( $wpsc_cart->cart_items ) && count( $wpsc_cart->cart_items ) > 0 ) {
			foreach ( $wpsc_cart->cart_items as $key => $cart_item ) {
				$cart_item->refresh_item();
			}
			$wpsc_cart->clear_cache();
		}
	}
	
	public static function add_class_to_hide_price( $old_class, $product_id ) {
		if ( WPEC_PCF_Functions::check_hide_price($product_id) ) {
			$old_class = 'pcf_hide_price pcf_hide_price_'.$product_id;
		}
		
		return $old_class;
	}
	
	public static function add_contact_button() {
		WPEC_PCF_Hook_Filter::show_contact_button(false);
	}
	
	public static function script_show_contact_button() {
		WPEC_PCF_Hook_Filter::show_contact_button(true);
	}
	
	public static function show_contact_button($use_script=true) {
		global $post;
		global $wpec_email_inquiry_global_settings;
		global $wpec_email_inquiry_customize_email_button;
		
		$product_id = $post->ID;
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
		
		$email_inquiry_button_class = 'pcf_contact_buton';
		
		if (!isset($wpsc_pcf_custom['inquiry_single_only'])) $inquiry_single_only = $wpec_email_inquiry_global_settings['inquiry_single_only'];
		else $inquiry_single_only = esc_attr($wpsc_pcf_custom['inquiry_single_only']);
		
		if (!isset($wpsc_pcf_custom['inquiry_button_type'])) $inquiry_button_type = $wpec_email_inquiry_customize_email_button['inquiry_button_type'];
		else $inquiry_button_type = esc_attr($wpsc_pcf_custom['inquiry_button_type']);
		
		if (!isset($wpsc_pcf_custom['inquiry_text_before'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_text_before'])) == '') $inquiry_text_before = $wpec_email_inquiry_customize_email_button['inquiry_text_before'];
		else $inquiry_text_before = esc_attr($wpsc_pcf_custom['inquiry_text_before']);
		
		if (!isset($wpsc_pcf_custom['inquiry_hyperlink_text'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_hyperlink_text'])) == '') $inquiry_hyperlink_text = $wpec_email_inquiry_customize_email_button['inquiry_hyperlink_text'];
		else $inquiry_hyperlink_text = esc_attr($wpsc_pcf_custom['inquiry_hyperlink_text']);
		if (trim($inquiry_hyperlink_text) == '') $inquiry_hyperlink_text = 'Click Here';
		
		if (!isset($wpsc_pcf_custom['inquiry_trailing_text'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_trailing_text'])) == '') $inquiry_trailing_text = $wpec_email_inquiry_customize_email_button['inquiry_trailing_text'];
		else $inquiry_trailing_text = esc_attr($wpsc_pcf_custom['inquiry_trailing_text']);
		
		if (!isset($wpsc_pcf_custom['inquiry_button_title'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_button_title'])) == '') $inquiry_button_title = $wpec_email_inquiry_customize_email_button['inquiry_button_title'];
		else $inquiry_button_title = esc_attr($wpsc_pcf_custom['inquiry_button_title']);
		if (trim($inquiry_button_title) == '') $inquiry_button_title = __('Product Enquiry', 'wp-e-commerce-catalog-visibility-and-email-inquiry' );
		
		$inquiry_button_position = $wpec_email_inquiry_customize_email_button['inquiry_button_position'];
		
		$inquiry_button_class = '';
		if ( trim( $wpec_email_inquiry_customize_email_button['inquiry_button_class'] ) != '') $inquiry_button_class = $wpec_email_inquiry_customize_email_button['inquiry_button_class'];
		
		$button_link = '';
		if (trim($inquiry_text_before) != '') $button_link .= '<span class="pcf_text_before pcf_text_before_'.$product_id.'">'.trim($inquiry_text_before).'</span> ';
		$button_link .= '<a class="pcf_hyperlink_text pcf_hyperlink_text_'.$product_id.' '. $email_inquiry_button_class .'" id="pcf_contact_button_'.$product_id.'" product_name="'.addslashes($post->post_title).'" product_id="'.$product_id.'">'.$inquiry_hyperlink_text.'</a>';
		if (trim($inquiry_trailing_text) != '') $button_link .= ' <span class="pcf_trailing_text pcf_trailing_text_'.$product_id.'">'.trim($inquiry_trailing_text).'</span>';
		
		$button_button = '<a class="pcf_email_button pcf_button_'.$product_id.' '. $email_inquiry_button_class .' '.$inquiry_button_class.'" id="pcf_contact_button_'.$product_id.'" product_name="'.addslashes($post->post_title).'" product_id="'.$product_id.'"><span>'.$inquiry_button_title.'</span></a>';
		
		if ( ( is_singular('wpsc-product') || $inquiry_single_only != 'yes' ) && WPEC_PCF_Functions::check_add_email_inquiry_button( $product_id ) && $post->post_type == 'wpsc-product') {
			add_action('wp_footer', array('WPEC_PCF_Hook_Filter', 'footer_print_scripts') );
			$button_ouput = '<span class="pcf_button_container">';
			if ($inquiry_button_type == 'link') $button_ouput .= $button_link;
			else $button_ouput .= $button_button;
			
			$button_ouput .= '</span>';
		?>
       		
        <?php
			if ($use_script) {
				if ($inquiry_button_position == 'above') {
		?>
				<script type="text/javascript">
                    (function($){		
                        $(function(){
                            if($("#pcf_contact_button_<?php echo $product_id; ?>").length <= 0){
								var add_cart_float = '';
                                if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("input#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("input#product_<?php echo $product_id; ?>_submit_button").before('<?php echo $button_ouput; ?><br />');
                                }else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("button#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("button#product_<?php echo $product_id; ?>_submit_button").before('<?php echo $button_ouput; ?><br />');
                                }else if($(".product_view_<?php echo $product_id; ?>").length > 0){
                                    $(".product_view_<?php echo $product_id; ?>").find(".more_details").before('<?php echo $button_ouput; ?><br />');
                                }else{
									add_cart_float = $("input.wpsc_buy_button").css("float");
                                    $("input.wpsc_buy_button").before('<?php echo $button_ouput; ?><br />');
                                }
								$("#pcf_contact_button_<?php echo $product_id; ?>").parent(".pcf_button_container").css("float", add_cart_float);
                            }
                        });		  
                    })(jQuery);
                </script>
        <?php		
				} else {
		?>
				<script type="text/javascript">
                    (function($){		
                        $(function(){
                            if($("#pcf_contact_button_<?php echo $product_id; ?>").length <= 0){
								var add_cart_float = '';
                                if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("input#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("input#product_<?php echo $product_id; ?>_submit_button").after('<br /><?php echo $button_ouput; ?>');
                                }else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("button#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("button#product_<?php echo $product_id; ?>_submit_button").after('<br /><?php echo $button_ouput; ?>');
                                }else if($(".product_view_<?php echo $product_id; ?>").length > 0){
                                    $(".product_view_<?php echo $product_id; ?>").find(".more_details").after('<br /><?php echo $button_ouput; ?>');
                                }else{
									add_cart_float = $("input.wpsc_buy_button").css("float");
                                    $("input.wpsc_buy_button").after('<br /><?php echo $button_ouput; ?>');
                                }
								$("#pcf_contact_button_<?php echo $product_id; ?>").parent(".pcf_button_container").css("float", add_cart_float);
                            }
                        });		  
                    })(jQuery);
                </script>
        <?php
				}
				if ( WPEC_PCF_Functions::check_hide_add_cart_button( $product_id ) && $post->post_type == 'wpsc-product') {
		?>
        		<script type="text/javascript">
                    (function($){		
                        $(function(){
							if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
								$("input#product_<?php echo $product_id; ?>_submit_button").hide();
							} else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
								$("button#product_<?php echo $product_id; ?>_submit_button").hide();
							}
                        });		  
                    })(jQuery);
                </script>
        <?php		
				}
			} else {
				echo $button_ouput;
			}
		}
	}
	
	public static function pcf_contact_popup() {
		
		global $wpec_email_inquiry_contact_form_settings;
		global $wpec_email_inquiry_customize_email_popup;
		global $wpec_email_inquiry_customize_email_button;
		
		$pcf_contact_action = wp_create_nonce("pcf_contact_action");
		$product_id = $_REQUEST['product_id'];
		$product_name = get_the_title($product_id);
		
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
		
		if (!isset($wpsc_pcf_custom['inquiry_button_title'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_button_title'])) == '') $inquiry_button_title = $wpec_email_inquiry_customize_email_button['inquiry_button_title'];
		else $inquiry_button_title = esc_attr($wpsc_pcf_custom['inquiry_button_title']);
		if (trim($inquiry_button_title) == '') $inquiry_button_title = __('Product Enquiry', 'wp-e-commerce-catalog-visibility-and-email-inquiry' );
		
		if (!isset($wpsc_pcf_custom['inquiry_text_before'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_text_before'])) == '') $inquiry_text_before = $wpec_email_inquiry_customize_email_button['inquiry_text_before'];
		else $inquiry_text_before = esc_attr($wpsc_pcf_custom['inquiry_text_before']);
		
		if (!isset($wpsc_pcf_custom['inquiry_hyperlink_text'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_hyperlink_text'])) == '') $inquiry_hyperlink_text = $wpec_email_inquiry_customize_email_button['inquiry_hyperlink_text'];
		else $inquiry_hyperlink_text = esc_attr($wpsc_pcf_custom['inquiry_hyperlink_text']);
		if (trim($inquiry_hyperlink_text) == '') $inquiry_hyperlink_text = __('Click Here', 'wp-e-commerce-catalog-visibility-and-email-inquiry' );
		
		if (!isset($wpsc_pcf_custom['inquiry_trailing_text'])  || trim(esc_attr($wpsc_pcf_custom['inquiry_trailing_text'])) == '') $inquiry_trailing_text = $wpec_email_inquiry_customize_email_button['inquiry_trailing_text'];
		else $inquiry_trailing_text = esc_attr($wpsc_pcf_custom['inquiry_trailing_text']);
		
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_heading'] ) != '') {
			$inquiry_contact_heading = $wpec_email_inquiry_customize_email_popup['inquiry_contact_heading'];
		} else {
			if (!isset($wpsc_pcf_custom['inquiry_button_type'])) $inquiry_button_type = $wpec_email_inquiry_customize_email_button['inquiry_button_type'];
			else $inquiry_button_type = esc_attr($wpsc_pcf_custom['inquiry_button_type']);
			
			if ($inquiry_button_type == 'link') $inquiry_contact_heading = $inquiry_text_before .' '. $inquiry_hyperlink_text .' '.$inquiry_trailing_text;
			else $inquiry_contact_heading = $inquiry_button_title;
		}
		
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_text_button'] ) != '') $inquiry_contact_text_button = $wpec_email_inquiry_customize_email_popup['inquiry_contact_text_button'];
		else $inquiry_contact_text_button = __('SEND', 'wp-e-commerce-catalog-visibility-and-email-inquiry' );
		
		$inquiry_contact_button_class = '';
		$inquiry_contact_form_class = '';
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_button_class'] ) != '') $inquiry_contact_button_class = $wpec_email_inquiry_customize_email_popup['inquiry_contact_button_class'];
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_form_class'] ) != '') $inquiry_contact_form_class = $wpec_email_inquiry_customize_email_popup['inquiry_contact_form_class'];
		
		$wpec_email_inquiry_form_class = 'pcf_contact_form';
		
	?>	
<div class="<?php echo $wpec_email_inquiry_form_class; ?> <?php echo $inquiry_contact_form_class; ?>">
<div style="padding:10px;">
	<h1 class="pcf_result_heading"><?php echo $inquiry_contact_heading; ?></h1>
	<div class="pcf_contact_content" id="pcf_contact_content_<?php echo $product_id; ?>">
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_name_<?php echo $product_id; ?>"><?php _e('Name', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?> <span class="pcf_required">*</span></label> 
			<input type="text" class="your_name" name="your_name" id="your_name_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_email_<?php echo $product_id; ?>"><?php _e('Email Address', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?> <span class="pcf_required">*</span></label>
			<input type="text" class="your_email" name="your_email" id="your_email_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_phone_<?php echo $product_id; ?>"><?php _e('Phone', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?> <span class="pcf_required">*</span></label> 
			<input type="text" class="your_phone" name="your_phone" id="your_phone_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label"><?php _e('Subject', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?> </label> 
			<?php echo $product_name; ?></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_message_<?php echo $product_id; ?>"><?php _e('Message', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label> 
			<textarea class="your_message" name="your_message" id="your_message_<?php echo $product_id; ?>"></textarea></div>
        <div class="pcf_contact_field">
        	<?php if ( $wpec_email_inquiry_contact_form_settings['inquiry_send_copy'] == 'yes' ) { ?>
            <label class="pcf_contact_label">&nbsp;</label>
            <label class="pcf_contact_send_copy"><input type="checkbox" name="send_copy" id="send_copy_<?php echo $product_id; ?>" value="1" checked="checked" /> <?php echo __('Send a copy of this email to myself.', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?></label>
            <?php } ?>
		<a class="pcf_form_button pcf_contact_bt_<?php echo $product_id; ?> <?php echo $inquiry_contact_button_class; ?>" id="pcf_contact_bt_<?php echo $product_id; ?>" product_id="<?php echo $product_id; ?>"><span><?php echo $inquiry_contact_text_button; ?></span></a> <span class="pcf_contact_loading" id="pcf_contact_loading_<?php echo $product_id; ?>"><img src="<?php echo WPEC_PCF_IMAGES_URL; ?>/ajax-loader.gif" /></span>
        </div>
        <div style="clear:both"></div>
	</div>
    <div style="clear:both"></div>
</div>
</div>
	<?php		
		die();
	}
	
	public static function pcf_contact_action() {
		$product_id 	= esc_attr( stripslashes( $_REQUEST['product_id'] ) );
		$your_name 		= esc_attr( stripslashes( $_REQUEST['your_name'] ) );
		$your_email 	= esc_attr( stripslashes( $_REQUEST['your_email'] ) );
		$your_phone 	= esc_attr( stripslashes( $_REQUEST['your_phone'] ) );
		$your_message 	= esc_attr( stripslashes( strip_tags( $_REQUEST['your_message'] ) ) );
		$send_copy_yourself	= esc_attr( stripslashes( $_REQUEST['send_copy'] ) );
		
		$email_result = WPEC_PCF_Functions::email_inquiry($product_id, $your_name, $your_email, $your_phone, $your_message, $send_copy_yourself );
		echo json_encode($email_result );
		die();
	}
		
	public static function add_style_header() {
		wp_enqueue_style('a3_pcf_style', WPEC_PCF_CSS_URL . '/pcf_style.css');
	}
	
	public static function add_google_fonts() {
		global $wpec_ei_fonts_face;
		global $wpec_email_inquiry_customize_email_button, $wpec_email_inquiry_customize_email_popup;
		$google_fonts = array( 
							$wpec_email_inquiry_customize_email_button['inquiry_button_font']['face'], 
							$wpec_email_inquiry_customize_email_button['inquiry_hyperlink_font']['face'], 
							$wpec_email_inquiry_customize_email_popup['inquiry_contact_button_font']['face'], 
							$wpec_email_inquiry_customize_email_popup['inquiry_contact_heading_font']['face'], 
						);
						
		$google_fonts = apply_filters( 'wpec_ei_google_fonts', $google_fonts );
		
		$wpec_ei_fonts_face->generate_google_webfonts( $google_fonts );
	}
	
	public static function include_customized_style() {
		include( WPEC_PCF_DIR. '/templates/customized_style.php' );
	}
	
	public static function footer_print_scripts() {
		wp_enqueue_style( 'woocommerce_fancybox_styles', WPEC_PCF_JS_URL . '/fancybox/fancybox.css' );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'fancybox', WPEC_PCF_JS_URL . '/fancybox/fancybox.min.js');
	}
	
	public static function script_contact_popup() {
		$pcf_contact_popup = wp_create_nonce("pcf_contact_popup");
		$pcf_contact_action = wp_create_nonce("pcf_contact_action");
	?>
<script type="text/javascript">
(function($){
	$(function(){
		var ajax_url = "<?php echo admin_url('admin-ajax.php', 'relative'); ?>";
		$(document).on("click", ".pcf_contact_buton", function(){
			var product_id = $(this).attr("product_id");
			var product_name = $(this).attr("product_name");
			
			var popup_wide = 520;
			if ( ei_getWidth()  <= 568 ) { 
				popup_wide = '90%'; 
			}
			$.fancybox({
				href: ajax_url+"?action=pcf_contact_popup&product_id="+product_id+"&security=<?php echo $pcf_contact_popup; ?>",
				centerOnScroll : true,
				easingIn: 'swing',
				easingOut: 'swing',
				width: popup_wide,
				autoScale: true,
				autoDimensions: true,
				height: 460,
				margin: 0,
				maxWidth: "90%",
				maxHeight: "80%",
				padding: 10,
				showCloseButton : true,
				openEffect	: "none",
				closeEffect	: "none"
			});
		});
		
		$(document).on("click", ".pcf_form_button", function(){
			if ( $(this).hasClass('pcf_email_inquiry_sending') ) {
				return false;
			}
			$(this).addClass('pcf_email_inquiry_sending');
			
			var product_id = $(this).attr("product_id");
			var your_name = $("#your_name_"+product_id).val();
			var your_email = $("#your_email_"+product_id).val();
			var your_phone = $("#your_phone_"+product_id).val();
			var your_message = $("#your_message_"+product_id).val();
			var send_copy = 0;
			if ( $("#send_copy_"+product_id).is(':checked') )
				send_copy = 1;
				
			var pcf_contact_error = "";
			var pcf_have_error = false;
			var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			if (your_name.replace(/^\s+|\s+$/g, '') == "") {
				pcf_contact_error += "<?php _e('Please enter your Name', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?>\n";
				pcf_have_error = true;
			}
			if (your_email == "" || !filter.test(your_email)) {
				pcf_contact_error += "<?php _e('Please enter valid Email address', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?>\n";
				pcf_have_error = true;
			}
			if (your_phone.replace(/^\s+|\s+$/g, '') == "") {
				pcf_contact_error += "<?php _e('Please enter your Phone', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ); ?>\n";
				pcf_have_error = true;
			}
			if (pcf_have_error) {
				$(this).removeClass('pcf_email_inquiry_sending');
				alert(pcf_contact_error);
				return false;
			}
			$("#pcf_contact_loading_"+product_id).show();
			
			var data = {
				action: 		"pcf_contact_action",
				product_id: 	product_id,
				your_name: 		your_name,
				your_email: 	your_email,
				your_phone: 	your_phone,
				your_message: 	your_message,
				send_copy:		send_copy,
				security: 		"<?php echo $pcf_contact_action; ?>"
			};
			$.post( ajax_url, data, function(response) {
				pcf_response = $.parseJSON( response );
				$("#pcf_contact_loading_"+product_id).hide();
				$("#pcf_contact_content_"+product_id).html(pcf_response);
			});
		});
	});
})(jQuery);
function ei_getWidth() {
    xWidth = null;
    if(window.screen != null)
      xWidth = window.screen.availWidth;

    if(window.innerWidth != null)
      xWidth = window.innerWidth;

    if(document.body != null)
      xWidth = document.body.clientWidth;

    return xWidth;
}
</script>
    <?php
	}
	
	public static function admin_footer_scripts() {
		global $wpec_ei_admin_interface;
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'a3rev-chosen-new-style', $wpec_ei_admin_interface->admin_plugin_url() . '/assets/js/chosen/chosen' . $suffix . '.css' );
		wp_enqueue_script( 'a3rev-chosen-new', $wpec_ei_admin_interface->admin_plugin_url() . '/assets/js/chosen/chosen.jquery' . $suffix . '.js', array( 'jquery' ), true, false );
	?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".chzn-select").chosen(); jQuery(".chzn-select-deselect").chosen({allow_single_deselect:true});
});	
</script>
	<?php
	}
	
	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', WPEC_PCF_CSS_URL . '/a3_wp_admin.css' );
	}
	
	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-wpec-ei-admin-sidebar-menu-style', WPEC_PCF_CSS_URL . '/admin_sidebar_menu.css' );
	}
		
	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WPEC_PCF_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Documentation', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</a>';
		$links[] = '<a href="https://a3rev.com/forums/forum/wp-e-commerce-plugins/catalog-visibility-email-inquiry/" target="_blank">'.__('Support', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ).'</a>';
		return $links;
	}

	public static function settings_plugin_links($actions) {
		$actions = array_merge( array( 'settings' => '<a href="admin.php?page=wpec-cart-email">' . __( 'Settings', 'wp-e-commerce-catalog-visibility-and-email-inquiry' ) . '</a>' ), $actions );

		return $actions;
	}
}
?>