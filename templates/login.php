<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($context) && is_array($context) ? $context : [];
$title = $context['title'] ?? 'Portal Sign In';
$message = $context['message'] ?? '';
$button_label = $context['button_label'] ?? 'Continue';
$button_url = $context['button_url'] ?? '#';
?>
<section class="svdp-gate svdp-login">
    <h1><?php echo esc_html($title); ?></h1>
    <p><?php echo esc_html($message); ?></p>
    <p><a class="button" href="<?php echo esc_url($button_url); ?>"><?php echo esc_html($button_label); ?></a></p>
</section>
