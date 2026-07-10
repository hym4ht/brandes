<div class="custom-options">
    <div class="custom-option <?php echo e((old('role') == 'superadmin' || !old('role')) ? 'selected' : ''); ?>"
        data-value="superadmin">Super Admin</div>
    <div class="custom-option <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>" data-value="admin">Admin</div>
    <div class="custom-option <?php echo e(old('role') == 'user' ? 'selected' : ''); ?>" data-value="user">User</div>
</div><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-login/login-role.blade.php ENDPATH**/ ?>