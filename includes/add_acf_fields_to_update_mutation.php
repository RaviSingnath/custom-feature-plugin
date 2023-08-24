<?php

global $my_cpts;
$my_cpts_post = array_merge($my_cpts, ['post']);

$filters = array();

foreach ($my_cpts_post as $post_type) {
    $filters[] = update_filter_callback($post_type);
}

// Register the filters
foreach ($filters as $filter) {
    add_action('graphql_input_fields', $filter, 20, 3);
}

function update_filter_callback($post_type) {
    return function ($fields, $type_name, $config) use ($post_type) {
        $my_post_type = ucfirst($post_type);
        $my_type_name = "Update" . $my_post_type . "Input";
        if ($type_name === $my_type_name) {
            // Get all ACF group fields
            $arr = acf_get_field_groups();

            // Array to store matching group fields
            $matching_group_fields = array();

            // Loop through each group field
            foreach ($arr as $group) {
                // Check if the rule location is 'post'
                if ($group['location'][0][0]['value'] == $post_type) {
                    // Add the matching group field to the array
                    $matching_group_fields[] = $group;
                }
            }

            $groups = $matching_group_fields;
            foreach ($groups as $group) {
                $groupFields = acf_get_fields($group['key']);
                $fields_arr = [];
                foreach ($groupFields as &$groupField) {
                    $field = $group['graphql_field_name'] . $groupField['name'];
                    //--------------------------------------------------------
                    // Determine the ACF field type based on $field['type']
                    switch ($groupField['type']) {
                        case 'text':
                        case 'textarea':
                        case 'email':
                        case 'password':
                        case 'url':
                        case 'message':
                        case 'wysiwyg':
                            $input_type = 'String';
                            break;
                        case 'number':
                        case 'range':
                            $input_type = 'Int';
                            break;
                        case 'checkbox':
                        case 'radio':
                        case 'button_group':
                        case 'true_false':
                            $input_type = 'Boolean';
                            break;
                        case 'select':
                        case 'taxonomy':
                        case 'page_link':
                        case 'post_object':
                        case 'relationship':
                        case 'user':
                            $input_type = 'ID';
                            break;
                        case 'date_picker':
                        case 'time_picker':
                        case 'date_time_picker':
                            
                            $input_type = 'String';
                            break;

                        case 'color_picker':
                            $input_type = 'String'; // Assuming color codes will be stored as strings
                            break;
                        case 'file':
                        case 'image':
                            $input_type = 'String'; // Assuming file paths or URLs will be stored as strings
                            break;

                        default:
                            $input_type = 'String'; // Default to 'String' if no specific type is found
                            break;
                    }



                    //--------------------------------------------------------
                    $fields_arr[$field] = ['type' => $input_type];
                    
                }

                $fields = array_merge($fields, $fields_arr);
            }

        }

        return $fields;
    };
}