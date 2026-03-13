<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function build_visibility_context_for_user($user) {
    return build_normalized_user_context($user);
}

function user_can_access_targeted_object($user, array $object_meta) {
    return user_can_view_targeted_object(build_visibility_context_for_user($user), $object_meta);
}
