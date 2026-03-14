<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($context) && is_array($context) ? $context : [];
?>
<article class="svdp-event-detail svdp-card">
    <h1><?php echo esc_html((string) ($context['title'] ?? '')); ?></h1>
    <p class="svdp-event-meta">
        <?php echo esc_html((string) ($context['event_type'] ?? '')); ?>
        <?php if (!empty($context['event_status'])) : ?>
            <?php echo esc_html(' | ' . (string) $context['event_status']); ?>
        <?php endif; ?>
    </p>
    <p><?php echo esc_html((string) ($context['event_start'] ?? '')); ?></p>
    <div><?php echo esc_html((string) ($context['content'] ?? '')); ?></div>
</article>
