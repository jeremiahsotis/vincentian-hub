<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($context) && is_array($context) ? $context : [];
$items = isset($context['items']) && is_array($context['items']) ? $context['items'] : [];
?>
<section class="svdp-dashboard svdp-dashboard-district">
    <h1>District Dashboard</h1>
    <ul class="svdp-dashboard-items">
        <?php foreach ($items as $item) : ?>
            <li class="svdp-dashboard-item svdp-card">
                <h2><?php echo esc_html((string) ($item['title'] ?? '')); ?></h2>
                <div><?php echo esc_html((string) ($item['content'] ?? '')); ?></div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
