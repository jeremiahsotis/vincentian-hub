<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_announcement_types() {
    return [
        'update',
        'alert',
        'reminder',
        'event',
        'grant',
    ];
}

function get_announcement_display_placements() {
    return [
        'top_banner',
        'dashboard_card',
        'whats_new',
        'announcements_page',
    ];
}

function get_announcement_posts() {
    return get_posts([
        'post_type' => 'svdp_announcement',
        'post_status' => 'publish',
    ]);
}

function get_announcement_targeting_meta($post_id) {
    return [
        'svdp_scope' => get_post_meta($post_id, 'svdp_scope', true),
        'svdp_audience_profiles' => get_post_meta($post_id, 'svdp_audience_profiles', true),
        'svdp_target_conference_mode' => get_post_meta($post_id, 'svdp_target_conference_mode', true),
        'svdp_target_conference_ids' => get_post_meta($post_id, 'svdp_target_conference_ids', true),
        'svdp_target_group_flags' => get_post_meta($post_id, 'svdp_target_group_flags', true),
        'svdp_is_active' => get_post_meta($post_id, 'svdp_is_active', true),
        'svdp_publish_start' => get_post_meta($post_id, 'svdp_publish_start', true),
        'svdp_publish_end' => get_post_meta($post_id, 'svdp_publish_end', true),
    ];
}

function normalize_announcement_value($value, array $allowed_values) {
    $value = (string) $value;
    return in_array($value, $allowed_values, true) ? $value : '';
}

function get_visible_announcements_for_dashboard($user) {
    $visibility_context = build_visibility_context_for_user($user);
    $announcements = [];

    foreach (get_announcement_posts() as $announcement) {
        if (!isset($announcement->ID)) {
            continue;
        }

        if (!user_can_view_targeted_object($visibility_context, get_announcement_targeting_meta((int) $announcement->ID))) {
            continue;
        }

        $announcements[] = [
            'id' => (int) $announcement->ID,
            'title' => (string) ($announcement->post_title ?? ''),
            'content' => (string) ($announcement->post_content ?? ''),
            'announcement_type' => normalize_announcement_value(
                get_post_meta((int) $announcement->ID, 'svdp_announcement_type', true),
                get_announcement_types()
            ),
            'display_placement' => normalize_announcement_value(
                get_post_meta((int) $announcement->ID, 'svdp_display_placement', true),
                get_announcement_display_placements()
            ),
        ];
    }

    return $announcements;
}

function render_dashboard_announcements(array $announcements) {
    if ($announcements === []) {
        return '';
    }

    ob_start();
    ?>
    <section class="svdp-announcements">
        <ul class="svdp-dashboard-items">
            <?php foreach ($announcements as $announcement) : ?>
                <li class="svdp-dashboard-item svdp-card">
                    <h2><?php echo esc_html((string) ($announcement['title'] ?? '')); ?></h2>
                    <p class="svdp-announcement-meta">
                        <?php echo esc_html((string) ($announcement['announcement_type'] ?? '')); ?>
                        <?php if (!empty($announcement['display_placement'])) : ?>
                            <?php echo esc_html(' | ' . (string) $announcement['display_placement']); ?>
                        <?php endif; ?>
                    </p>
                    <div><?php echo esc_html((string) ($announcement['content'] ?? '')); ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php
    return (string) ob_get_clean();
}
