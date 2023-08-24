<?php

global $my_cpts;
foreach ($my_cpts as $my_cpt) {

    add_filter('register_post_type_args', function ($args, $post_type) use ($my_cpt) {

        if ( $my_cpt === $post_type ) {
            $args['show_in_graphql'] = true;
            $args['graphql_single_name'] = $my_cpt;
            $args['graphql_plural_name'] = $my_cpt. 's';
            $s_my_cpt = $my_cpt. 's';
            $args['capability_type'] = array($my_cpt, $s_my_cpt);
            $args['map_meta_cap'] = true;

        }

        return $args;
    }, 10, 2);

}



/**
add teachers capability
 */





// Define the function wp_aoutogq_role outside the loop
function wp_aoutogq_role($my_cpt)
{
    $s_my_cpt = $my_cpt . 's';
    $role1 = get_role('administrator');
    $role2 = get_role('editor');
    $capabilities = array(
        'edit_' . $s_my_cpt,
        'edit_others_' . $s_my_cpt,
        'delete_' . $s_my_cpt,
        'publish_' . $s_my_cpt,
        'read_private_' . $s_my_cpt,
        'delete_private_' . $s_my_cpt,
        'delete_published_' . $s_my_cpt,
        'delete_others_' . $s_my_cpt,
        'edit_private_' . $s_my_cpt,
        'edit_published_' . $s_my_cpt,
        'create_' . $s_my_cpt,

    );
    foreach ($capabilities as $cap) {
        $role1->add_cap($cap);
        $role2->add_cap($cap);
    }
}

// Loop through $my_cpts and hook the wp_aoutogq_role function to admin_init with priority 999 for each custom post type
foreach ($my_cpts as $my_cpt) {
    add_action('admin_init', function () use ($my_cpt) {
        wp_aoutogq_role($my_cpt);
    }, 999);
}




/*foreach ($my_cpts as $my_cpt) {

    add_action('admin_init', 'wp_aoutogq_role', 999);

    function wp_aoutogq_role( $my_cpt )
    {
        $s_my_cpt = $my_cpt.'s';
        $role = get_role('administrator');
        $capabilities = array(
            'edit_'. $s_my_cpt,
            'edit_others_'. $s_my_cpt,
            'delete_'.$s_my_cpt,
            'publish_'.$s_my_cpt,
            'read_private_'.$s_my_cpt,
            'delete_private_'.$s_my_cpt,
            'delete_published_'.$s_my_cpt,
            'delete_others_'.$s_my_cpt,
            'edit_private_'.$s_my_cpt,
            'edit_published_'.$s_my_cpt,
        );
        foreach ($capabilities as $cap) {
            $role->add_cap($cap);
        }
    }
}*/