<?php


function register_acf_fields_in_graphql(  $my_post_types ) {

    global  $my_post_types;
    // Loop through the post types
    foreach ($my_post_types as $post_type) {
        $groups = acf_get_field_groups(array('post_type' => $post_type));



        // Loop through the field groups
        foreach ($groups as $group) {
            $group_key = $group['key'];
            $fields = acf_get_fields($group_key);

            // Prepare fields array for ACF field group registration
            $acf_fields = array();
            foreach ($fields as $field) {
                $acf_fields[] = array(
                    'key' => $field['key'],
                    'label' => $field['label'],
                    'name' => $field['name'],
                    'type' => $field['type'],

                );


            }

            // Generate the lowercase version of the group title for the GraphQL field name
            $graphql_field_name = strtolower(str_replace(' ', '_', $group['title']));

            // Register ACF field group
            acf_add_local_field_group(array(
                'key' => $group_key,
                'title' => $group['title'],
                'show_in_graphql' => true,
                'graphql_field_name' => $graphql_field_name,
                'fields' => $acf_fields,
                'location' => $group['location'],

            ));

        }
    }
}

add_action('acf/init', 'register_acf_fields_in_graphql');




//--------------------------------------------------------------------------------------------------------



// Define the modification function
function my_acf_load_field($field)
{
    $object = new stdClass();

    $object->type = $field['type'];
    $object->label = $field['label'];
    $object->name = $field['name'];

    if (isset($field['default_value']) && $field['default_value'] != "") {
        $object->defaultValue = $field['default_value'];
    }

    if (isset($field['required']) && $field['required'] != 0 ) {
        $object->required = $field['required'];
    }

    if (isset($field['maxlength']) && $field['maxlength'] != "") {
        $object->characterLimit = $field['maxlength'];
    }

    if (isset($field['min']) && $field['min'] != "") {
        $object->minimum = $field['min'];
    }

    if (isset($field['max']) && $field['max'] != "") {
        $object->maximum = $field['max'];
    }

    if (isset($field['multiple']) && $field['multiple'] != 0 ) {
        $object->selectMultiple = $field['multiple'];
    }

    if (isset($field['allow_null']) && $field['allow_null'] != 0 ) {
        $object->allowNull = $field['allow_null'];
    }

    if (isset($field['min_size']) && $field['min_size'] != "" ) {
        $object->minimumSize = $field['min_size'];
    }

    if (isset($field['max_size']) && $field['max_size'] != "" ) {
        $object->maximumSize = $field['max_size'];
    }

    if (isset($field['mime_types']) && $field['mime_types'] != "" ) {
        $object->allowedTypes = $field['mime_types'];
    }

    if (isset($field['choices']) && $field['choices'] != null ) {
        $object->choices = $field['choices'];
    }

    if (isset($field['other_choice']) && $field['other_choice'] != 0 ) {
        $object->allowOtherChoices = $field['other_choice'];
    }

    if (isset($field['save_other_choice']) && $field['save_other_choice'] != 0 ) {
        $object->saveOtherChoices = $field['save_other_choice'];
    }

    if (isset($field['allow_custom']) && $field['allow_custom'] != 0 ) {
        $object->allowCustomValues = $field['allow_custom'];
    }

    if (isset($field['save_custom']) && $field['save_custom'] != 0 ) {
        $object->saveCustomValues = $field['save_custom'];
    }

    $field['instructions'] = json_encode($object);
    return $field;
}

// Loop through the post types
global $my_post_types;
foreach ($my_post_types as $post_type) {
    $groups = acf_get_field_groups(array('post_type' => $post_type));

    // Loop through the field groups
    foreach ($groups as $group) {
        $group_key = $group['key'];
        $fields = acf_get_fields($group_key);

        // Apply the filter to each field in this group
        foreach ($fields as &$field) {
            $field = my_acf_load_field($field);
        }

    }
}

// Apply the filter
add_filter('acf/load_field', 'my_acf_load_field');


