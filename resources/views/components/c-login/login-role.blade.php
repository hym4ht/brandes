<div class="custom-options">
    <div class="custom-option {{ (old('role') == 'superadmin' || !old('role')) ? 'selected' : '' }}"
        data-value="superadmin">Super Admin</div>
    <div class="custom-option {{ old('role') == 'admin' ? 'selected' : '' }}" data-value="admin">Admin</div>
    <div class="custom-option {{ old('role') == 'user' ? 'selected' : '' }}" data-value="user">User</div>
</div>