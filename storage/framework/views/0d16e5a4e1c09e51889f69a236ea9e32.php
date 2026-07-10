

<div class="history-card history-item <?php echo e($type); ?>" data-name="<?php echo e(strtolower($name)); ?>" data-date="<?php echo e($date); ?>">

    
    <div class="history-card-top">
        <div class="history-card-user">
            
            
            <div class="history-card-icon <?php echo e($type); ?>">
                <?php if($type === 'success'): ?>
                    
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                <?php else: ?>
                    
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                <?php endif; ?>
            </div>

            
            <div class="history-card-info">
                <span class="history-card-name"><?php echo e($name); ?></span>
                <span class="history-card-action"><?php echo e($action); ?></span>
            </div>
        </div>

        
        <span class="history-card-badge"><?php echo e($badge); ?></span>
    </div>

    
    <div class="history-card-divider"></div>

    
    <div class="history-card-footer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <span><?php echo e($time); ?></span>
    </div>

</div><?php /**PATH /home/restuaja/Downloads/project - revisi tapi benar (sudah sidang TA)/resources/views/components/c-shared/history-card.blade.php ENDPATH**/ ?>