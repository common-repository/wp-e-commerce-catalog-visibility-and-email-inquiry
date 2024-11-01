<?php
/**
 * Plugin Uninstall
 *
 * Uninstalling deletes options, tables, and pages.
 *
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

if (get_option('wpec_email_inquiry_pro_clean_on_deletion') == 1) {

    delete_option('wpec_email_inquiry_rules_roles_settings');
    delete_option('wpec_email_inquiry_global_settings');
    delete_option('wpec_email_inquiry_contact_form_settings');
    delete_option('wpec_email_inquiry_customize_email_popup');
    delete_option('wpec_pcf_contact_success');
    delete_option('wpec_email_inquiry_customize_email_button');

    delete_post_meta_by_key('_wpsc_pcf_custom');

    delete_option('wpec_email_inquiry_pro_clean_on_deletion');
}