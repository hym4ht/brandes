
<aside class="sidebar" id="sidebar">

    
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-img">
                <path
                    d="M0 16C0 7.16344 7.16344 0 16 0H78C86.8366 0 94 7.16344 94 16V78C94 86.8366 86.8366 94 78 94H16C7.16344 94 0 86.8366 0 78V16Z"
                    fill="#00A63E" />
                <path
                    d="M23 75.4682V34.504L30.7308 30.061L44.3263 37.9695V15.8435L38.9058 19.0424V32.2825L36.6844 30.9496V17.5318L44.3263 13L58.0106 20.9973V30.061L71.6061 37.8806V50.7653L64.4973 55.0305L71.6061 59.2958V72.0027L58.0106 79.8223V30.061L44.3263 37.9695V72.0915L30.7308 80V32.8156L25.3103 35.9257V74.1353L23 75.4682Z"
                    fill="white" />
            </svg>
        </div>
        <div class="brand-info">
            <strong>BRANDES</strong>
            <span>Admin Panel</span>
        </div>

        
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M18 17L13 12L18 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 17L6 12L11 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    
    <nav class="sidebar-nav">

        
        <a href="<?php echo e(session('user.role') === 'superadmin' ? route('dashboard.superadmin') : route('dashboard.admin')); ?>"
            class="nav-item <?php echo e(request()->routeIs('dashboard.admin') || request()->routeIs('dashboard.superadmin') ? 'active' : ''); ?>" data-title="Dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                    d="M9 3H4C3.44772 3 3 3.44772 3 4V11C3 11.5523 3.44772 12 4 12H9C9.55228 12 10 11.5523 10 11V4C10 3.44772 9.55228 3 9 3Z" />
                <path
                    d="M20 3H15C14.4477 3 14 3.44772 14 4V7C14 7.55228 14.4477 8 15 8H20C20.5523 8 21 7.55228 21 7V4C21 3.44772 20.5523 3 20 3Z" />
                <path
                    d="M20 12H15C14.4477 12 14 12.4477 14 13V20C14 20.5523 14.4477 21 15 21H20C20.5523 21 21 20.5523 21 20V13C21 12.4477 20.5523 12 20 12Z" />
                <path
                    d="M9 16H4C3.44772 16 3 16.4477 3 17V20C3 20.5523 3.44772 21 4 21H9C9.55228 21 10 20.5523 10 20V17C10 16.4477 9.55228 16 9 16Z" />
            </svg>
            <span class="nav-label">Dashboard</span>
        </a>

        
        <a href="<?php echo e(route('admin.list')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.list') ? 'active' : ''); ?>"
            data-title="List Admin">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                    d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H6C4.93913 15 3.92172 15.4214 3.17157 16.1716C2.42143 16.9217 2 17.9391 2 19V21" />
                <path
                    d="M16 3.12793C16.8578 3.3503 17.6174 3.85119 18.1597 4.55199C18.702 5.25279 18.9962 6.11382 18.9962 6.99993C18.9962 7.88604 18.702 8.74707 18.1597 9.44787C17.6174 10.1487 16.8578 10.6496 16 10.8719" />
                <path
                    d="M22 20.9999V18.9999C21.9993 18.1136 21.7044 17.2527 21.1614 16.5522C20.6184 15.8517 19.8581 15.3515 19 15.1299" />
                <path
                    d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" />
            </svg>
            <span class="nav-label">List Admin</span>
        </a>

        
        <a href="<?php echo e(route('user.list')); ?>" class="nav-item <?php echo e(request()->routeIs('user.list') ? 'active' : ''); ?>"
            data-title="List User">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                    d="M11.9983 10C11.4678 10 10.9591 10.2107 10.5841 10.5858C10.209 10.9609 9.99828 11.4696 9.99828 12C9.99828 13.02 9.89828 14.51 9.73828 16" />
                <path d="M14 13.1201C14 15.5001 14 19.5001 13 22.0001" />
                <path d="M17.2891 21.02C17.4091 20.42 17.7191 18.72 17.7891 18" />
                <path
                    d="M2 12C2 9.90118 2.66037 7.85555 3.88758 6.1529C5.11478 4.45024 6.8466 3.17687 8.83772 2.51317C10.8288 1.84946 12.9783 1.82906 14.9817 2.45486C16.985 3.08067 18.7407 4.32094 20 6" />
                <path d="M2 16H2.01" />
                <path d="M21.8008 16C22.0008 14 21.9318 10.646 21.8008 10" />
                <path d="M5 19.5C5.5 18 6 15 6 12C5.99899 11.3189 6.11397 10.6425 6.34 10" />
                <path d="M8.64844 22C8.85844 21.34 9.09844 20.68 9.21844 20" />
                <path
                    d="M9 6.79994C9.9124 6.27317 10.9474 5.99593 12.001 5.99609C13.0545 5.99626 14.0894 6.27384 15.0017 6.8009C15.9139 7.32797 16.6713 8.08594 17.1976 8.99859C17.7239 9.91124 18.0007 10.9464 18 11.9999V13.9999" />
            </svg>
            <span class="nav-label">List User</span>
        </a>

        
        <a href="<?php echo e(route('history.akses')); ?>"
            class="nav-item <?php echo e(request()->routeIs('history.akses') ? 'active' : ''); ?>" data-title="History Akses">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                    d="M3 12C3 13.78 3.52784 15.5201 4.51677 17.0001C5.50571 18.4802 6.91131 19.6337 8.55585 20.3149C10.2004 20.9961 12.01 21.1743 13.7558 20.8271C15.5016 20.4798 17.1053 19.6226 18.364 18.364C19.6226 17.1053 20.4798 15.5016 20.8271 13.7558C21.1743 12.01 20.9961 10.2004 20.3149 8.55585C19.6337 6.91131 18.4802 5.50571 17.0001 4.51677C15.5201 3.52784 13.78 3 12 3C9.48395 3.00947 7.06897 3.99122 5.26 5.74L3 8" />
                <path d="M3 3V8H8" />
                <path d="M12 7V12L16 14" />
            </svg>
            <span class="nav-label">History Akses</span>
        </a>

        
        <a href="<?php echo e(route('log.berkas')); ?>" class="nav-item <?php echo e(request()->routeIs('log.berkas') ? 'active' : ''); ?>"
            data-title="Log Berkas">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                <path d="M9 14h6" />
                <path d="M9 18h6" />
                <path d="M9 10h6" />
            </svg>
            <span class="nav-label">Log Berkas</span>
        </a>

        
        <a href="<?php echo e(route('notifikasi.keamanan')); ?>"
            class="nav-item <?php echo e(request()->routeIs('notifikasi.keamanan') ? 'active' : ''); ?>"
            data-title="Notifikasi Keamanan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                    d="M18 8C18 6.4087 17.3671 4.88258 16.2419 3.75736C15.1167 2.63214 13.5906 2 12 2C10.4094 2 8.88329 2.63214 7.75807 3.75736C6.63285 4.88258 6.00071 6.4087 6.00071 8C6.00071 12.499 4.58971 13.956 3.26271 15.326C3.13207 15.4692 3.04586 15.6472 3.01456 15.8385C2.98327 16.0298 3.00823 16.226 3.08642 16.4034C3.1646 16.5807 3.29264 16.7316 3.45496 16.8375C3.61729 16.9434 3.80689 16.9999 4.00071 17H20.0007C20.1945 17.0001 20.3841 16.9438 20.5465 16.8381C20.709 16.7324 20.8372 16.5817 20.9156 16.4045C20.994 16.2273 21.0192 16.0311 20.9881 15.8398C20.9571 15.6485 20.8711 15.4703 20.7407 15.327C19.4107 13.956 18.0007 12.499 18.0007 8Z"
                    stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M10.2707 21C10.4463 21.304 10.6987 21.5565 11.0028 21.732C11.3068 21.9075 11.6517 21.9999 12.0027 21.9999C12.3538 21.9999 12.6987 21.9075 13.0027 21.732C13.3067 21.5565 13.5592 21.304 13.7347 21"
                    stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="nav-label">Notifikasi Sistem</span>
        </a>

        
        <a href="<?php echo e(route('lokasi.brankas')); ?>"
            class="nav-item <?php echo e(request()->routeIs('lokasi.brankas') ? 'active' : ''); ?>" data-title="Lokasi Brankas">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                    d="M20 10C20 14.993 14.461 20.193 12.601 21.799C12.4277 21.9293 12.2168 21.9998 12 21.9998C11.7832 21.9998 11.5723 21.9293 11.399 21.799C9.539 20.193 4 14.993 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z" />
                <path
                    d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z" />
            </svg>
            <span class="nav-label">Lokasi Brankas</span>
        </a>

    </nav>

    
    <div class="sidebar-footer">
        <button type="button" class="nav-logout" onclick="openLogoutModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M16 17L21 12L16 7" />
                <path d="M21 12H9" />
                <path
                    d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" />
            </svg>
            <span class="nav-label">Logout</span>
        </button>
    </div>

</aside><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-admin/navigation/sidebar.blade.php ENDPATH**/ ?>