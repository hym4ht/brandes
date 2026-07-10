


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'user', // Tipe target: 'user' atau 'admin'
    'title' => null,
    'description' => null,
    'routePrefix' => null
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
    'type' => 'user', // Tipe target: 'user' atau 'admin'
    'title' => null,
    'description' => null,
    'routePrefix' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Nilai default jika properti tidak diberikan
    $modalTitle = $title ?? 'Hapus ' . ucfirst($type);
    $modalRoutePrefix = $routePrefix ?? $type;
    
    if (!$description) {
        if ($type === 'admin') {
            $description = 'Apakah Anda yakin ingin menghapus <strong id="deleteTargetName"></strong>? Data yang sudah dihapus tidak dapat memonitoring Brankas.';
        } elseif ($type === 'user') {
            $description = 'Apakah Anda yakin ingin menghapus <strong id="deleteTargetName"></strong>? Data yang sudah dihapus tidak dapat mengakses Brankas kembali.';
        } elseif ($type === 'log-berkas') {
            $modalTitle = 'Hapus Log Berkas';
            $description = 'Apakah Anda yakin ingin menghapus log berkas <strong id="deleteTargetName"></strong>? Data log yang sudah dihapus tidak dapat dikembalikan.';
        }
    }
?>




<div class="user-delete-overlay" id="deleteModalOverlay" onclick="closeDeleteModalOutside(event)">
    <div class="modal modal-user-delete">
        
        
        <div class="modal-header">
            <div class="modal-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
            </div>
            <span class="modal-title"><?php echo e($modalTitle); ?></span>
        </div>

        
        <p class="modal-desc">
            <?php echo $description; ?>

        </p>

        
        <div class="modal-actions">
            
            
            <button type="button" class="btn-batal" onclick="closeDeleteModal()">Batal</button>
            
            
            <form id="deleteForm" method="POST" style="display:contents;" onsubmit="handleDeleteAJAX(event)">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn-confirm">Hapus</button>
            </form>

        </div>

    </div>
</div><?php /**PATH /home/restuaja/Downloads/project - revisi tapi benar (sudah sidang TA)/resources/views/components/c-shared/delete.blade.php ENDPATH**/ ?>