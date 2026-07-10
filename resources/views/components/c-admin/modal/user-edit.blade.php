{{-- ======================================================================
KOMPONEN: Modal Edit User
Deskripsi: Digunakan pada halaman list user untuk mengubah data
(nama, username, fingerprint ID, pin) dari user biasa.
====================================================================== --}}
<div class="modal-overlay user-edit-overlay" id="userEditModalOverlay"
    onclick="window.closeEditModalOutsideUser(event)">
    <div class="modal modal-edit">

        {{-- 1. HEADER MODAL --}}
        <div class="modal-header">
            <span class="modal-title">Edit User</span>
            <button class="modal-close" onclick="window.closeEditModalUser()" title="Tutup Modal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- 2. FORM EDIT USER --}}
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="role" value="user">

            <div class="form-inputs-wrapper">

                {{-- Input: Nama Lengkap --}}
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M12 12.125C14.3312 12.125 16.484 12.6545 18.0859 13.5547C19.6586 14.4406 20.875 15.8005 20.875 17.5V17.6016C20.876 18.757 20.8833 20.2773 19.5527 21.3613C18.9827 21.8245 18.2211 22.1658 17.2354 22.4062L16.7988 22.5039C15.5935 22.7482 14.0302 22.875 12 22.875C9.96977 22.875 8.40552 22.7482 7.20215 22.5039C6.00149 22.2617 5.09877 21.8908 4.44824 21.3613C3.11773 20.2774 3.12401 18.7571 3.125 17.6016V17.5C3.125 15.8005 4.34141 14.4406 5.91504 13.5547L6.22168 13.3906C7.78595 12.5907 9.81539 12.125 12 12.125ZM12.001 13.875C9.91248 13.875 8.06651 14.3534 6.77246 15.0811C5.45199 15.8236 4.875 16.7139 4.875 17.5C4.875 18.8195 4.92197 19.4901 5.55273 20.0039C5.90118 20.2874 6.49971 20.5763 7.54883 20.7881L7.95898 20.8633C8.96079 21.029 10.2763 21.125 12 21.125C13.9697 21.125 15.4055 20.9998 16.4512 20.7881C17.5003 20.5763 18.0989 20.2873 18.4473 20.0029L18.5586 19.9053C19.0839 19.4042 19.125 18.7375 19.125 17.5C19.125 16.7138 18.5489 15.8236 17.2275 15.0811C15.9335 14.3534 14.0875 13.875 12.001 13.875ZM12 1.125C13.2929 1.125 14.533 1.63851 15.4473 2.55273C16.3615 3.46697 16.875 4.70707 16.875 6C16.875 7.29293 16.3615 8.53303 15.4473 9.44727C14.533 10.3615 13.2929 10.875 12 10.875C10.7071 10.875 9.46696 10.3615 8.55273 9.44727C7.63851 8.53303 7.125 7.29292 7.125 6C7.125 4.70708 7.63851 3.46697 8.55273 2.55273C9.46696 1.63851 10.7071 1.12501 12 1.125ZM12 2.875C11.1712 2.87501 10.3761 3.204 9.79004 3.79004C9.20401 4.37609 8.875 5.17121 8.875 6C8.875 6.82879 9.20401 7.62391 9.79004 8.20996C10.3761 8.796 11.1712 9.12499 12 9.125C12.8288 9.125 13.6239 8.79599 14.21 8.20996C14.796 7.62391 15.125 6.8288 15.125 6C15.125 5.1712 14.796 4.37609 14.21 3.79004C13.6239 3.20401 12.8288 2.875 12 2.875Z"
                                    fill="currentColor" stroke="currentColor" stroke-width="0.25" />
                            </svg>
                        </span>
                        <input type="text" name="nama" id="editNamaUser" class="form-control with-icon"
                            placeholder="Masukan nama lengkap user" required>
                    </div>
                </div>

                {{-- Input: Username --}}
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M12 12.125C14.3312 12.125 16.484 12.6545 18.0859 13.5547C19.6586 14.4406 20.875 15.8005 20.875 17.5V17.6016C20.876 18.757 20.8833 20.2773 19.5527 21.3613C18.9827 21.8245 18.2211 22.1658 17.2354 22.4062L16.7988 22.5039C15.5935 22.7482 14.0302 22.875 12 22.875C9.96977 22.875 8.40552 22.7482 7.20215 22.5039C6.00149 22.2617 5.09877 21.8908 4.44824 21.3613C3.11773 20.2774 3.12401 18.7571 3.125 17.6016V17.5C3.125 15.8005 4.34141 14.4406 5.91504 13.5547L6.22168 13.3906C7.78595 12.5907 9.81539 12.125 12 12.125ZM12.001 13.875C9.91248 13.875 8.06651 14.3534 6.77246 15.0811C5.45199 15.8236 4.875 16.7139 4.875 17.5C4.875 18.8195 4.92197 19.4901 5.55273 20.0039C5.90118 20.2874 6.49971 20.5763 7.54883 20.7881L7.95898 20.8633C8.96079 21.029 10.2763 21.125 12 21.125C13.9697 21.125 15.4055 20.9998 16.4512 20.7881C17.5003 20.5763 18.0989 20.2873 18.4473 20.0029L18.5586 19.9053C19.0839 19.4042 19.125 18.7375 19.125 17.5C19.125 16.7138 18.5489 15.8236 17.2275 15.0811C15.9335 14.3534 14.0875 13.875 12.001 13.875ZM12 1.125C13.2929 1.125 14.533 1.63851 15.4473 2.55273C16.3615 3.46697 16.875 4.70707 16.875 6C16.875 7.29293 16.3615 8.53303 15.4473 9.44727C14.533 10.3615 13.2929 10.875 12 10.875C10.7071 10.875 9.46696 10.3615 8.55273 9.44727C7.63851 8.53303 7.125 7.29292 7.125 6C7.125 4.70708 7.63851 3.46697 8.55273 2.55273C9.46696 1.63851 10.7071 1.12501 12 1.125ZM12 2.875C11.1712 2.87501 10.3761 3.204 9.79004 3.79004C9.20401 4.37609 8.875 5.17121 8.875 6C8.875 6.82879 9.20401 7.62391 9.79004 8.20996C10.3761 8.796 11.1712 9.12499 12 9.125C12.8288 9.125 13.6239 8.79599 14.21 8.20996C14.796 7.62391 15.125 6.8288 15.125 6C15.125 5.1712 14.796 4.37609 14.21 3.79004C13.6239 3.20401 12.8288 2.875 12 2.875Z"
                                    fill="currentColor" stroke="currentColor" stroke-width="0.25" />
                            </svg>
                        </span>
                        <input type="text" name="username" id="editUsernameUser" class="form-control with-icon"
                            placeholder="Masukan username user" required autocomplete="off">
                    </div>
                </div>

                {{-- Input: Fingerprint ID --}}
                <div class="form-group">
                    <label>Fingerprint ID</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path
                                    d="M11.9983 10C11.4678 10 10.9591 10.2107 10.5841 10.5858C10.209 10.9609 9.99828 11.4696 9.99828 12C9.99828 13.02 9.89828 14.51 9.73828 16"
                                    stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M14 13.1201C14 15.5001 14 19.5001 13 22.0001" stroke-width="1.66667"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17.2891 21.02C17.4091 20.42 17.7191 18.72 17.7891 18" stroke-width="1.66667"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M2 12C2 9.90118 2.66037 7.85555 3.88758 6.1529C5.11478 4.45024 6.8466 3.17687 8.83772 2.51317C10.8288 1.84946 12.9783 1.82906 14.9817 2.45486C16.985 3.08067 18.7407 4.32094 20 6"
                                    stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2 16H2.01" stroke-width="1.66667" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M21.8008 16C22.0008 14 21.9318 10.646 21.8008 10" stroke-width="1.66667"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5 19.5C5.5 18 6 15 6 12C5.99899 11.3189 6.11397 10.6425 6.34 10"
                                    stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.64844 22C8.85844 21.34 9.09844 20.68 9.21844 20" stroke-width="1.66667"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M9 6.79994C9.9124 6.27317 10.9474 5.99593 12.001 5.99609C13.0545 5.99626 14.0894 6.27384 15.0017 6.8009C15.9139 7.32797 16.6713 8.08594 17.1976 8.99859C17.7239 9.91124 18.0007 10.9464 18 11.9999V13.9999"
                                    stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        {{-- Tampilan Kode Unik (Terkunci Total) --}}
                        <input type="text" id="editDisplayFingerprint" class="form-control with-icon readonly-iot"
                            readonly tabindex="-1">
                        {{-- Data Asli ke Database --}}
                        <input type="hidden" name="fingerprint_id" id="editHiddenFingerprintId">
                    </div>
                    <p class="form-hint">ID</p>
                </div>

                {{-- Input: PIN (Security) --}}
                <div class="form-group">
                    <label>PIN</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M10.3008 10.4581L20.7169 0L22.5933 1.88402L20.7169 3.76938L24 7.06575L22.1235 8.95111L18.8391 5.6534L16.9626 7.53742L19.7773 10.3635L17.9008 12.2488L15.0862 9.42145L12.1772 12.3421C13.0789 13.7198 13.44 15.3842 13.1907 17.0137C12.9415 18.6431 12.0996 20.122 10.8277 21.1646C9.55582 22.2072 7.94421 22.7395 6.30424 22.6586C4.66428 22.5778 3.11234 21.8896 1.94828 20.7269C0.783455 19.5598 0.0921409 17.9997 0.00856829 16.3496C-0.0750043 14.6995 0.455127 13.0771 1.49603 11.7973C2.53694 10.5176 4.01435 9.6719 5.6414 9.42442C7.26845 9.17693 8.92906 9.54532 10.3008 10.4581ZM9.45545 18.8429C9.84204 18.4758 10.1514 18.0345 10.3653 17.5453C10.5791 17.0561 10.6931 16.5287 10.7005 15.9945C10.7079 15.4603 10.6085 14.93 10.4083 14.435C10.2081 13.94 9.91109 13.4903 9.53482 13.1125C9.15855 12.7347 8.71066 12.4365 8.21762 12.2354C7.72459 12.0344 7.19643 11.9347 6.66435 11.9421C6.13226 11.9495 5.60707 12.064 5.11979 12.2787C4.63252 12.4934 4.19306 12.804 3.8274 13.1922C3.1022 13.946 2.70092 14.9558 2.70999 16.0038C2.71906 17.0519 3.13775 18.0544 3.87589 18.7955C4.61404 19.5367 5.61256 19.957 6.65641 19.9661C7.70026 19.9753 8.70592 19.5724 9.45678 18.8442"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <input type="password" name="pin" id="editPinInputUser" class="form-control with-icon"
                            placeholder="Masukan PIN user" maxlength="6" inputmode="numeric">

                        {{-- Toggle Visibility Eye (Lihat PIN) --}}
                        <button type="button" class="toggle-eye" onclick="window.toggleEditPinUser()"
                            title="Tampilkan PIN">
                            <svg id="editEyeIconUser" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    <p class="form-hint">PIN yang akan digunakan pada keypad IoT untuk verifikasi akses</p>
                </div>

            </div>{{-- /.form-inputs-wrapper --}}
        </form>

        {{-- 3. TOMBOL SUBMIT --}}
        <div class="form-footer">
            <button type="submit" class="btn-submit" form="editUserForm">Simpan Perubahan</button>
        </div>

    </div>{{-- /.modal --}}
</div>{{-- /.modal-overlay --}}