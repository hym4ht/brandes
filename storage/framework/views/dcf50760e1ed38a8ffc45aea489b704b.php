<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['number', 'title', 'text', 'position' => 'left', 'target' => '', 'targetGroup' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['number', 'title', 'text', 'position' => 'left', 'target' => '', 'targetGroup' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="custom-tooltip-info pos-<?php echo e($position); ?>" data-step="<?php echo e($number); ?>" data-target="<?php echo e($target); ?>" data-target-group="<?php echo e($targetGroup); ?>">
    <?php if($position === 'left'): ?>
        <div class="tooltip-box">
            <strong><?php echo e($title); ?></strong>
            <p><?php echo $text; ?></p>
        </div>
        <div class="tooltip-node">
            <span class="tooltip-number"><?php echo e($number); ?></span>
            <div class="tooltip-line"></div>
        </div>
    <?php elseif($position === 'right'): ?>
        <div class="tooltip-node">
            <div class="tooltip-line"></div>
            <span class="tooltip-number"><?php echo e($number); ?></span>
        </div>
        <div class="tooltip-box">
            <strong><?php echo e($title); ?></strong>
            <p><?php echo $text; ?></p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-shared/tooltip-info.blade.php ENDPATH**/ ?>