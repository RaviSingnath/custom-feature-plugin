<?php

function m_activate_plugin(){
    if ( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ){
        wp_die( __("You must update WordPress to use this plugin.", 'auto_graphql') );
    }
}

function check_required_plugins() {
    // Check if WPGraphQL plugin is active
    if ( ! is_plugin_active( 'wp-graphql/wp-graphql.php' ) ) {
        add_action( 'admin_notices', 'required_plugins_notice' );
    }

    // Check if Advanced Custom Fields plugin is active
    if ( ! is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
        add_action( 'admin_notices', 'required_plugins_notice' );
    }
}

function required_plugins_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php esc_html_e( 'The "auto-graphql" plugin requires WPGraphQL (v0.4.0+) and Advanced Custom Fields (v5.7+) to work.', 'auto_graphql' ); ?></p>
    </div>
    <?php
}
add_action( 'admin_init', 'check_required_plugins' );

