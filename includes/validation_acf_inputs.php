<?php
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WP_Post_Type;
use WPGraphQL\AppContext;
use WPGraphQL\Data\PostObjectMutation;
use WPGraphQL\Utils\Utils;

add_action( 'graphql_post_object_mutation_update_additional_data', function ( $post_id, $input, $mutation_name, $context, $info ) {

    $post=get_post( $post_id );
    $groups = acf_get_field_groups(['post_type' => $post->post_type]);
    foreach ($groups as $group) {
        $groupFields = acf_get_fields($group['key']);
        foreach ($groupFields as &$groupField) {

            $field = $group['graphql_field_name'] . $groupField['name'];
            if ((strpos($context, 'update') === 0) && ($input[$field] === null)) {
                continue;
            }
            $is_valid = true;
            if (
                (bool)$groupField['required'] === true &&
                (
                    empty($input[$field]) &&
                    !is_numeric($input[$field])
                )
            ) {
                $is_valid = false;
            }
            $is_valid = apply_filters('acf/validate_value/type=' . $groupField['type'], $is_valid, $input[$field], $groupField, null);
            $is_valid = apply_filters('acf/validate_value/name=' . $groupField['_name'], $is_valid, $input[$field], $groupField, null);
            $is_valid = apply_filters('acf/validate_value/key=' . $groupField['key'], $is_valid, $input[$field], $groupField, null);
            $is_valid = apply_filters('acf/validate_value', $is_valid, $input[$field], $groupField, null);

            if (
                !empty($is_valid) &&
                is_string($is_valid)
            ) {
                throw new UserError( $is_valid );

            } elseif ($is_valid === false) {
                throw new UserError(esc_html( $field . ' is required field.' ));

            }

            if (isset($input[$field])) {
                update_field($groupField['key'], $input[$field], $post_id);
            }

        }
    }


}, 10, 5 );