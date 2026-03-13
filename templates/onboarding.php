<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($context) && is_array($context) ? $context : [];
$title = $context['title'] ?? 'Finish Setup';
$message = $context['message'] ?? '';
$account_scope = $context['account_scope'] ?? '';
$conference_id = (int) ($context['conference_id'] ?? 0);
$requires_conference_assignment = !empty($context['requires_conference_assignment']);
$errors = isset($context['errors']) && is_array($context['errors']) ? $context['errors'] : [];
?>
<section class="svdp-gate svdp-onboarding">
    <h1><?php echo esc_html($title); ?></h1>
    <p><?php echo esc_html($message); ?></p>
    <dl>
        <dt>Account scope</dt>
        <dd><?php echo esc_html($account_scope === '' ? 'unassigned' : $account_scope); ?></dd>
        <dt>Conference assignment required</dt>
        <dd><?php echo esc_html($requires_conference_assignment ? 'yes' : 'no'); ?></dd>
        <dt>Conference ID</dt>
        <dd><?php echo esc_html((string) $conference_id); ?></dd>
    </dl>
    <?php if ($errors !== []) : ?>
        <ul class="svdp-onboarding-errors">
            <?php foreach ($errors as $error) : ?>
                <li><?php echo esc_html((string) $error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
