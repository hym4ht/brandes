<!DOCTYPE html>
<html lang="id">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login — Brandes</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/shared/colors.css', 'resources/css/login/login.css', 'resources/css/components/c-login/login-role.css']); ?>

    
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/login/login.js'); ?>

    
    <style>
        :root {
            --login-bg-image: url('<?php echo e(asset("images/bg_form login.png")); ?>');
        }

        .left-panel::before {
            background-image: var(--login-bg-image);
        }
    </style>
</head>

<body>

    
    <div class="left-panel">

        
        <div class="logo">
            <svg viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-img">
                <path
                    d="M0 16C0 7.16344 7.16344 0 16 0H78C86.8366 0 94 7.16344 94 16V78C94 86.8366 86.8366 94 78 94H16C7.16344 94 0 86.8366 0 78V16Z"
                    fill="#00A63E" />
                <path
                    d="M23 75.4682V34.504L30.7308 30.061L44.3263 37.9695V15.8435L38.9058 19.0424V32.2825L36.6844 30.9496V17.5318L44.3263 13L58.0106 20.9973V30.061L71.6061 37.8806V50.7653L64.4973 55.0305L71.6061 59.2958V72.0027L58.0106 79.8223V30.061L44.3263 37.9695V72.0915L30.7308 80V32.8156L25.3103 35.9257V74.1353L23 75.4682Z"
                    fill="white" />
            </svg>
            <span class="logo-text">BRANDES</span>
        </div>

        
        <div class="hero-text">
            <h1>
                Sistem Monitoring<br>
                <span>Keamanan Brankas</span><br>
                Balai Desa Bengle
            </h1>
            <p>Sistem keamanan brankas arsip Desa Bengle dengan akses terenkripsi, autentikasi berlapis, dan pemantauan
                real-time.</p>
        </div>

    </div>

    
    <div class="right-panel">
        <div class="form-container">

            
            <div class="form-header">
                <h2>Selamat Datang</h2>
                <p>Silahkan masukan data anda yang sudah terdaftar di sistem agar bisa mengakses.</p>
            </div>

            
            <?php echo $__env->make('components.c-login.alert.invalid-input', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-inputs-wrapper">

                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M12 12.125C14.3312 12.125 16.484 12.6545 18.0859 13.5547C19.6586 14.4406 20.875 15.8005 20.875 17.5V17.6016C20.876 18.757 20.8833 20.2773 19.5527 21.3613C18.9827 21.8245 18.2211 22.1658 17.2354 22.4062L16.7988 22.5039C15.5935 22.7482 14.0302 22.875 12 22.875C9.96977 22.875 8.40552 22.7482 7.20215 22.5039C6.00149 22.2617 5.09877 21.8908 4.44824 21.3613C3.11773 20.2774 3.12401 18.7571 3.125 17.6016V17.5C3.125 15.8005 4.34141 14.4406 5.91504 13.5547L6.22168 13.3906C7.78595 12.5907 9.81539 12.125 12 12.125ZM12.001 13.875C9.91248 13.875 8.06651 14.3534 6.77246 15.0811C5.45199 15.8236 4.875 16.7139 4.875 17.5C4.875 18.8195 4.92197 19.4901 5.55273 20.0039C5.90118 20.2874 6.49971 20.5763 7.54883 20.7881L7.95898 20.8633C8.96079 21.029 10.2763 21.125 12 21.125C13.9697 21.125 15.4055 20.9998 16.4512 20.7881C17.5003 20.5763 18.0989 20.2873 18.4473 20.0029L18.5586 19.9053C19.0839 19.4042 19.125 18.7375 19.125 17.5C19.125 16.7138 18.5489 15.8236 17.2275 15.0811C15.9335 14.3534 14.0875 13.875 12.001 13.875ZM12 1.125C13.2929 1.125 14.533 1.63851 15.4473 2.55273C16.3615 3.46697 16.875 4.70707 16.875 6C16.875 7.29293 16.3615 8.53303 15.4473 9.44727C14.533 10.3615 13.2929 10.875 12 10.875C10.7071 10.875 9.46696 10.3615 8.55273 9.44727C7.63851 8.53303 7.125 7.29292 7.125 6C7.125 4.70708 7.63851 3.46697 8.55273 2.55273C9.46696 1.63851 10.7071 1.12501 12 1.125ZM12 2.875C11.1712 2.87501 10.3761 3.204 9.79004 3.79004C9.20401 4.37609 8.875 5.17121 8.875 6C8.875 6.82879 9.20401 7.62391 9.79004 8.20996C10.3761 8.796 11.1712 9.12499 12 9.125C12.8288 9.125 13.6239 8.79599 14.21 8.20996C14.796 7.62391 15.125 6.8288 15.125 6C15.125 5.1712 14.796 4.37609 14.21 3.79004C13.6239 3.20401 12.8288 2.875 12 2.875Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.25" />
                                </svg>
                            </span>
                            <input type="text" id="username" name="username" class="form-control"
                                placeholder="Masukan nama anda" value="<?php echo e(old('username')); ?>" required
                                autocomplete="off">
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="pin">PIN</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M10.3008 10.4581L20.7169 0L22.5933 1.88402L20.7169 3.76938L24 7.06575L22.1235 8.95111L18.8391 5.6534L16.9626 7.53743L19.7773 10.3635L17.9008 12.2488L15.0862 9.42145L12.1772 12.3421C13.0789 13.7198 13.44 15.3842 13.1907 17.0137C12.9415 18.6431 12.0996 20.122 10.8277 21.1646C9.55582 22.2072 7.94421 22.7395 6.30424 22.6586C4.66428 22.5778 3.11234 21.8896 1.94828 20.7269C0.783455 19.5598 0.0921409 17.9997 0.00856829 16.3496C-0.0750043 14.6995 0.455127 13.0771 1.49603 11.7973C2.53694 10.5176 4.01435 9.67191 5.6414 9.42442C7.26845 9.17693 8.92906 9.54532 10.3008 10.4581ZM9.45545 18.8429C9.84204 18.4758 10.1514 18.0345 10.3653 17.5453C10.5791 17.0561 10.6931 16.5287 10.7005 15.9945C10.7079 15.4603 10.6085 14.93 10.4083 14.435C10.2081 13.94 9.91109 13.4903 9.53482 13.1125C9.15855 12.7347 8.71066 12.4365 8.21762 12.2354C7.72459 12.0344 7.19643 11.9347 6.66435 11.9421C6.13226 11.9495 5.60707 12.064 5.11979 12.2787C4.63252 12.4934 4.19306 12.804 3.8274 13.1922C3.1022 13.946 2.70092 14.9558 2.70999 16.0038C2.71906 17.0519 3.13775 18.0544 3.87589 18.7955C4.61404 19.5367 5.61256 19.957 6.65641 19.9661C7.70026 19.9753 8.70592 19.5724 9.45678 18.8442"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <input type="password" id="pin" name="pin" class="form-control"
                                placeholder="Masukan PIN anda" required autocomplete="off" inputmode="numeric"
                                maxlength="6">

                            
                            <button type="button" class="toggle-eye" onclick="togglePin()" id="eyeBtn"
                                title="Tampilkan / sembunyikan PIN">
                                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="role">Role</label>
                        <div class="input-wrapper select-wrapper">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 1.125C13.2929 1.125 14.533 1.63849 15.4473 2.55273C16.3615 3.46697 16.875 4.70707 16.875 6C16.875 7.29293 16.3615 8.53303 15.4473 9.44727C14.533 10.3615 13.2929 10.875 12 10.875C10.7071 10.875 9.46697 10.3615 8.55273 9.44727C7.63849 8.53303 7.125 7.29293 7.125 6C7.125 4.70707 7.63849 3.46697 8.55273 2.55273C9.46697 1.63849 10.7071 1.125 12 1.125ZM12 2.875C11.1712 2.875 10.3761 3.20399 9.79004 3.79004C9.20399 4.37609 8.875 5.1712 8.875 6C8.875 6.8288 9.20399 7.62391 9.79004 8.20996C10.3761 8.79601 11.1712 9.125 12 9.125C12.8288 9.125 13.6239 8.79601 14.21 8.20996C14.796 7.62391 15.125 6.8288 15.125 6C15.125 5.1712 14.796 4.37609 14.21 3.79004C13.6239 3.20399 12.8288 2.875 12 2.875Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.25" />
                                    <path
                                        d="M12 12.125C12.975 12.125 13.9183 12.2172 14.8027 12.3906L14.9062 12.4111L14.9043 12.5166L14.8711 14.042L14.8682 14.1934L14.7197 14.1611C13.8856 13.9779 12.9686 13.875 12.001 13.875C9.91248 13.875 8.06651 14.3534 6.77246 15.0811C5.45199 15.8236 4.875 16.7139 4.875 17.5C4.875 18.8195 4.92197 19.4901 5.55273 20.0039C5.90118 20.2874 6.49971 20.5763 7.54883 20.7881L7.95898 20.8633C8.96079 21.029 10.2763 21.125 12 21.125C13.9697 21.125 15.4055 20.9998 16.4512 20.7881C17.5003 20.5763 18.0989 20.2873 18.4473 20.0029L18.54 19.9229C18.7449 19.733 18.8759 19.5211 18.9619 19.2686L18.9912 19.1826L19.082 19.1836L20.6289 19.1953L20.7812 19.1973L20.75 19.3467C20.6004 20.0623 20.2713 20.7759 19.5527 21.3613C18.9827 21.8245 18.2211 22.1658 17.2354 22.4062L16.7988 22.5039C15.5935 22.7482 14.0302 22.875 12 22.875C9.96977 22.875 8.40552 22.7482 7.20215 22.5039C6.00149 22.2617 5.09877 21.8908 4.44824 21.3613C3.11773 20.2774 3.12401 18.7571 3.125 17.6016V17.5C3.125 15.8005 4.34141 14.4406 5.91504 13.5547L6.22168 13.3906C7.78595 12.5907 9.81539 12.125 12 12.125Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.25" />
                                    <path
                                        d="M14.8027 12.3906C16.0339 12.632 17.1531 13.0305 18.0859 13.5547C19.6586 14.4406 20.875 15.8005 20.875 17.5V17.6016C20.8755 18.1331 20.8761 18.7418 20.75 19.3457L20.7295 19.4463L20.627 19.4453L19.0801 19.4336L18.9062 19.4326L18.9619 19.2686C19.1092 18.8362 19.125 18.2825 19.125 17.5C19.125 16.7138 18.5489 15.8236 17.2275 15.0811C16.5347 14.6915 15.6831 14.3728 14.7197 14.1611L14.6191 14.1387L14.6211 14.0361L14.6543 12.5107L14.6572 12.3623L14.8027 12.3906Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.25" />
                                    <rect x="12" y="12" width="12" height="12" rx="6" fill="white" />
                                    <path
                                        d="M17.125 17.125V14.5H18.875V17.125H21.5V18.875H18.875V21.5H17.125V18.875H14.5V17.125H17.125Z"
                                        fill="currentColor" />
                                </svg>
                            </span>

                            
                            <div class="form-control select-trigger"
                                style="display: flex; align-items: center; cursor: pointer;">
                                <span id="role-label">
                                    <?php if(old('role') == 'user'): ?>
                                        User
                                    <?php elseif(old('role') == 'admin'): ?>
                                        Admin
                                    <?php else: ?>
                                        Super Admin
                                    <?php endif; ?>
                                </span>
                            </div>

                            
                            <select id="role" name="role" style="display: none;">
                                <option value="superadmin" <?php echo e((old('role') == 'superadmin' || !old('role')) ? 'selected' : ''); ?>>Super Admin</option>
                                <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <option value="user" <?php echo e(old('role') == 'user' ? 'selected' : ''); ?>>User</option>
                            </select>

                            <span class="select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_829_4365)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12.7068 15.7073C12.5193 15.8948 12.265 16.0001 11.9998 16.0001C11.7347 16.0001 11.4803 15.8948 11.2928 15.7073L5.63582 10.0503C5.54031 9.9581 5.46413 9.84775 5.41172 9.72575C5.35931 9.60374 5.33172 9.47252 5.33057 9.33974C5.32942 9.20696 5.35472 9.07529 5.405 8.95239C5.45528 8.82949 5.52953 8.71784 5.62343 8.62395C5.71732 8.53006 5.82897 8.4558 5.95187 8.40552C6.07476 8.35524 6.20644 8.32994 6.33922 8.33109C6.472 8.33225 6.60322 8.35983 6.72522 8.41224C6.84723 8.46465 6.95757 8.54083 7.04982 8.63634L11.9998 13.5863L16.9498 8.63634C17.1384 8.45418 17.391 8.35339 17.6532 8.35567C17.9154 8.35795 18.1662 8.46312 18.3516 8.64852C18.537 8.83393 18.6422 9.08474 18.6445 9.34694C18.6468 9.60914 18.546 9.86174 18.3638 10.0503L12.7068 15.7073Z"
                                            fill="currentColor" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_829_4365">
                                            <rect width="24" height="24" fill="white"
                                                transform="matrix(0 1 -1 0 24 0)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>

                            
                            <?php echo $__env->make('components.c-login.login-role', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>

                </div>

                
                <button type="submit" class="btn-login">Masuk ke Dashboard</button>

            </form>

            
            <div class="form-footer">
                &copy; <?php echo e(date('Y')); ?>. Sistem Monitoring Keamanan Brankas X Universitas Harkat Negeri
            </div>
        </div>
    </div>



</body>

</html><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/login/login.blade.php ENDPATH**/ ?>