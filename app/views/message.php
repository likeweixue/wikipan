<?php
/** @var string $heading */
/** @var string $message */
/** @var array|null $action */
?>
<section class="message-page container">
    <div class="message-card">
        <span class="message-mark">◍</span>
        <h1><?= e($heading) ?></h1>
        <p><?= e($message) ?></p>
        <?php if (!empty($action)): ?>
            <a class="btn btn-primary" href="<?= e($action['href']) ?>"><?= e($action['label']) ?></a>
        <?php endif; ?>
    </div>
</section>
