

<div class="calendar-dropdown <?php echo e($class ?? ''); ?>" id="<?php echo e($id ?? 'calendar-dropdown'); ?>" data-target="<?php echo e($target ?? ''); ?>">

    
    <div class="calendar-header">
        
        
        <h3 class="month-year-label">...</h3>
        
        
        <div class="header-nav">
            
            
            <button type="button" class="nav-btn prev-month" title="Bulan Sebelumnya">
                <svg viewBox="0 0 22 22" fill="none">
                    <path d="M13.293 5.5L7.79297 11L13.293 16.5" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            
            
            <button type="button" class="nav-btn next-month" title="Bulan Berikutnya">
                <svg viewBox="0 0 22 22" fill="none">
                    <path d="M8.70703 5.5L14.207 11L8.70703 16.5" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

        </div>
    </div>

    
    <div class="calendar-grid">
        <div class="calendar-grid-wrapper">
            
            <div class="day-names">
                <span>MG</span>
                <span>SN</span>
                <span>SL</span>
                <span>RB</span>
                <span>KM</span>
                <span>JM</span>
                <span>SB</span>
            </div>
            
            
            <div class="date-grid calendar-days-container">
                
            </div>
        </div>
    </div>

    
    <div class="calendar-footer">
        
        <button type="button" class="footer-btn btn-clear">Bersihkan</button>
        
        
        <button type="button" class="footer-btn btn-today">Hari ini</button>
    </div>

</div><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-shared/calendar.blade.php ENDPATH**/ ?>