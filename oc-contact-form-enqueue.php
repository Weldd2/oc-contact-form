<?php
function enqueue_custom_plugin_script() {
    wp_enqueue_script('email-sender', plugin_dir_url( __FILE__ ) . 'js/email-sender.js', array('jquery'), '1.0', true);
    wp_localize_script('email-sender', 'my_send_email_vars', array(
        'nonce' => wp_create_nonce('my_send_email_nonce')
    ));
    
    wp_enqueue_style('custom-plugin-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
}

function ccf_enqueue_admin_scripts()
{
    wp_enqueue_style('custom-admin-plugin-style', plugin_dir_url( __FILE__ ) . 'css/admin.css');
}
add_action('admin_enqueue_scripts', 'ccf_enqueue_admin_scripts');

add_action('wp_enqueue_scripts', 'enqueue_custom_plugin_script');
?>
