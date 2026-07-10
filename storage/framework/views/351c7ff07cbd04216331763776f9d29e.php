

<?php
    // 1. LOGIKA OTOMATISASI AKSI (Berdasarkan Tipe Admin/User)
    if (isset($type) && isset($item)) {

        // Skenario untuk Data Admin
        if ($type === 'admin') {
            $adminData = json_encode([
                'id' => $item->id,
                'nama' => $item->nama,
                'username' => $item->username,
                'pin' => $item->pin
            ]);
            $editAction = 'window.openEditModalAdmin(' . $adminData . ')';
            $deleteAction = 'window.openDeleteModal("' . $item->id . '", "' . $item->nama . '", "admin")';
        }

        // Skenario untuk Data User
        elseif ($type === 'user') {
            $userData = json_encode([
                'id' => $item->id,
                'nama' => $item->nama,
                'username' => $item->username,
                'fingerprint_id' => $item->fingerprint_id ?? '',
                'kode_fingerprint' => $item->kode_fingerprint ?? '',
                'pin' => $item->pin_asli ?? ''
            ]);
            $editAction = 'window.openEditModalUser(' . $userData . ')';
            $deleteAction = 'window.openDeleteModal("' . $item->id . '", "' . $item->nama . '", "user")';
        }

        // Skenario untuk Data Log
        elseif ($type === 'log') {
            $logData = json_encode([
                'id' => $item->id,
                'judul' => $item->judul,
                'ktp_nik' => $item->ktp_nik ?? '',
                'ktp_nama' => $item->ktp_nama ?? '',
                'kk_no' => $item->kk_no ?? '',
                'kk_nama_kepala' => $item->kk_nama_kepala ?? '',
                'akte_no' => $item->akte_no ?? '',
                'file_ktp' => $item->file_ktp ?? '',
                'file_kk' => $item->file_kk ?? '',
                'file_akte' => $item->file_akte ?? '',
                'status' => $item->status ?? ''
            ]);
            $editAction = 'window.openEditModalLog(' . $logData . ')';
            $deleteAction = 'window.openDeleteModal("' . $item->id . '", "' . $item->judul . '", "log-berkas")';
            $disableDeleteToggle = false;
        }

        // Skenario untuk Data Log User
        elseif ($type === 'log-user') {
            // Aksi akan dikirim via slot
            $disableDeleteToggle = false;
        }
    }
?>




<div class="aksi-wrap">

    
    <button class="btn-aksi" onclick="window.toggleDropdown(this)" aria-label="Aksi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <circle cx="12" cy="12" r="1"></circle>
            <circle cx="12" cy="5" r="1"></circle>
            <circle cx="12" cy="19" r="1"></circle>
        </svg>
    </button>

    
    <div class="dropdown-menu">

        
        <div class="dropdown-items-group">

            <?php 
                                if ($type === 'log' || $type === 'log-user') {
                    $disableEdit = false;
                    $disableDeleteToggle = false;
                } else {
                    // Deteksi apakah ini baris milik diri sendiri
                    $isSelf = ($item->id == session('user.id'));

                    // Deteksi apakah baris ini adalah akun Super Admin
                    $isSuperAdminRow = (bool) ($item->is_superadmin ?? false);

                    // Deteksi apakah user yang sedang login saat ini adalah Super Admin
                    $loggedInAsSuperAdmin = (bool) (session('user.is_superadmin', false));

                    // Logika Proteksi:
                    // 1. Edit mati JIKA ini akun Super Admin TAPI yang login bukan Super Admin
                    $disableEdit = $isSuperAdminRow && !$loggedInAsSuperAdmin;

                    // 2. Delete & Toggle mati JIKA ini akun sendiri ATAU sama seperti aturan Edit di atas
                    $disableDeleteToggle = $isSelf || ($isSuperAdminRow && !$loggedInAsSuperAdmin);
                }
            ?>
        
        <?php if(isset($editAction)): ?>
            <button class="dropdown-item" onclick='<?php echo $disableEdit ? "" : $editAction; ?>' <?php echo $disableEdit ? 'disabled style="opacity: 0.5; cursor: not-allowed; filter: grayscale(1);"' : ""; ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            d="M7 7H6C5.46957 7 4.96086 7.21071 4.58579 7.58579C4.21071 7.96086 4 8.46957 4 9V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H15C15.5304 20 16.0391 19.7893 16.4142 19.4142C16.7893 19.0391 17 18.5304 17 18V17"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                        d="M16 5.00011L19 8.00011M20.385 6.58511C20.7788 6.19126 21.0001 5.65709 21.0001 5.10011C21.0001 4.54312 20.7788 4.00895 20.385 3.61511C19.9912 3.22126 19.457 3 18.9 3C18.343 3 17.8088 3.22126 17.415 3.61511L9 12.0001V15.0001H12L20.385 6.58511Z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Edit</span>

                           </button>
        <?php endif; ?>
            
            <?php if(isset($deleteAction)): ?>
                <button class="dropdown-item danger" onclick='<?php echo $disableDeleteToggle ? "" : $deleteAction; ?>' <?php echo $disableDeleteToggle ? 'disabled style="opacity: 0.5; cursor: not-allowed; filter: grayscale(1);"' : ""; ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>Delete</span>
                </button>
            <?php endif; ?>
        </div>

        
        <?php if(isset($type) && isset($item) && $type !== 'log' && $type !== 'log-user'): ?>
            <div class="dropdown-status-wrap">
                <button type="button" class="badge-status <?php echo e($item->aktif ? 'aktif' : 'nonaktif'); ?>"
                    style="border:none; font-family:inherit; width:100%; <?php echo $disableDeleteToggle ? 'opacity: 0.5; cursor: not-allowed; pointer-events: none;' : 'cursor:pointer;'; ?>"
                    <?php echo $disableDeleteToggle ? 'disabled' : ""; ?> onclick="<?php echo $disableDeleteToggle ? '' : "window.toggleStatus(this, '{$item->id}', '{$type}')"; ?>">
                    <?php echo e($item->aktif ? 'Aktif' : 'Nonaktif'); ?>

                </button>
            </div>
        <?php endif; ?>

        
        <?php if(isset($slot) && $slot->isNotEmpty()): ?>
            <?php echo e($slot); ?>

        <?php endif; ?>

    </div>
</div><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-shared/opsi.blade.php ENDPATH**/ ?>