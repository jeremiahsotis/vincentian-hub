<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($context) && is_array($context) ? $context : [];
?>
<article class="svdp-document-detail svdp-card">
    <h1><?php echo esc_html((string) ($context['title'] ?? '')); ?></h1>
    <p class="svdp-document-meta">
        <?php echo esc_html((string) ($context['doc_source'] ?? '')); ?>
        <?php if (!empty($context['preview_type'])) : ?>
            <?php echo esc_html(' | ' . (string) $context['preview_type']); ?>
        <?php endif; ?>
    </p>
    <div><?php echo esc_html((string) ($context['content'] ?? '')); ?></div>
</article>
