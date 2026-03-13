<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($context) && is_array($context) ? $context : [];
$title = $context['title'] ?? 'Access Pending';
$message = $context['message'] ?? '';
$status = $context['status'] ?? 'pending';
?>
<section class="svdp-gate svdp-pending-access">
    <h1><?php echo esc_html($title); ?></h1>
    <p><?php echo esc_html($message); ?></p>
    <p class="svdp-access-status"><?php echo esc_html((string) $status); ?></p>
</section>
