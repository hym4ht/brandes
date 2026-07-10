

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id'      => 'toast_default',
    'title'   => 'Rekap berhasil diunduh',
    'message' => 'File rekap akses telah disimpan ke perangkat anda',
]));

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

foreach (array_filter(([
    'id'      => 'toast_default',
    'title'   => 'Rekap berhasil diunduh',
    'message' => 'File rekap akses telah disimpan ke perangkat anda',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<div class="toast-container" id="toastContainer">
    
</div>


<template id="toastTemplate">
    <div class="toast" id="<?php echo e($id); ?>">
        
        
        <div class="toast-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 13" fill="none">
                <path d="M15.5 1.5L6.69355 11.5L1.5 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        
        
        <div class="toast-body">
            <div class="toast-title"><?php echo e($title); ?></div>
            <div class="toast-text"><?php echo e($message); ?></div>
        </div>

    </div>
</template><?php /**PATH /home/restuaja/Downloads/project - revisi tapi benar (sudah sidang TA)/resources/views/components/c-shared/toast.blade.php ENDPATH**/ ?>