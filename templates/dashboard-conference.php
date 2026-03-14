<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($context) && is_array($context) ? $context : [];
$conference_context = isset($context['conference_context']) && is_array($context['conference_context']) ? $context['conference_context'] : [];
$items = isset($context['items']) && is_array($context['items']) ? $context['items'] : [];
?>
<section class="svdp-dashboard svdp-dashboard-conference">
    <h1>Conference Dashboard</h1>
    <p class="svdp-dashboard-route-token"><?php echo esc_html((string) ($conference_context['page_slug'] ?? '')); ?></p>
    <ul class="svdp-dashboard-items">
        <?php foreach ($items as $item) : ?>
            <li class="svdp-dashboard-item svdp-card">
                <h2><?php echo esc_html((string) ($item['title'] ?? '')); ?></h2>
                <div><?php echo esc_html((string) ($item['content'] ?? '')); ?></div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
