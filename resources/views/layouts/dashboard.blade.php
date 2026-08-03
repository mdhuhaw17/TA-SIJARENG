<!DOCTYPE html>
<html lang="id">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/JARENG.png') }}">
    <title>@yield('title', 'Dashboard')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            color: white;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .header-brand {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .header-page {
            opacity: 0.85;
            font-size: 13px;
        }

        /* ===== USER MENU ===== */
        .user-menu {
            position: relative;
        }

        .user-name {
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            transition: 0.2s;
            user-select: none;
        }

        .user-name:hover {
            background: rgba(255,255,255,0.25);
        }

        /* ===== DROPDOWN ===== */
        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            overflow: hidden;
            min-width: 140px;
            z-index: 200;
            border: 1px solid #e2e8f0;
        }

        .dropdown-header {
            padding: 10px 14px 6px;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown button {
            width: 100%;
            padding: 10px 14px;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
            font-size: 14px;
            color: #ef4444;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.15s;
        }

        .dropdown button:hover {
            background: #fef2f2;
        }

        /* ===== MAIN CONTENT WRAPPER ===== */
        .page-wrapper {
            padding: 20px;
            width: 100%;
        }

        /* ===== RESPONSIVE: DASHBOARD CARDS ===== */
        @media (max-width: 1100px) {
            .dashboard-cards {
                grid-template-columns: repeat(3, 1fr) !important;
            }
            .bottom-section {
                grid-template-columns: 1fr !important;
            }
            .scan-layout {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            .menu-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 480px) {
            .dashboard-cards {
                grid-template-columns: 1fr !important;
            }
            .btn-group {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div>
        <span class="header-brand">Absensi Les JARENG</span>
        <span class="header-page"> | @yield('header', 'Dashboard')</span>
    </div>

    <div class="user-menu">
        <span class="user-name" onclick="toggleDropdown()">
            {{ Auth::user()->name }} 👤
        </span>

        <div id="dropdown" class="dropdown">
            <div class="dropdown-header">Akun</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="page-wrapper">
    @yield('content')
</div>

<!-- GLOBAL MODAL NOTIFICATION (VANILLA CSS INLINE) -->
<div id="globalNotificationModal" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 16px;">
    <div id="globalNotificationCard" style="background: white; border-radius: 24px; width: 380px; max-width: 100%; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; transition: transform 0.25s ease, opacity 0.25s ease; transform: scale(0.95); opacity: 0; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
        
        <!-- Icon Container -->
        <div style="display: flex; justify-content: center; margin-bottom: 16px;">
            <!-- Success Icon -->
            <div id="iconSuccess" style="width: 64px; height: 64px; border-radius: 50%; background: #d1fae5; display: none; align-items: center; justify-content: center; color: #059669;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <!-- Error Icon -->
            <div id="iconError" style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; display: none; align-items: center; justify-content: center; color: #dc2626;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div style="text-align: center; margin-bottom: 24px; width: 100%;">
            <h3 id="notificationTitle" style="font-size: 18px; font-weight: bold; color: #1e293b; margin: 0 0 6px; font-family: inherit;">Berhasil!</h3>
            <p id="notificationMessage" style="font-size: 14px; color: #64748b; margin: 0; font-family: inherit; line-height: 1.5; font-weight: 500;">Tindakan Anda telah berhasil diselesaikan.</p>
        </div>

        <!-- Button -->
        <div style="width: 100%; display: flex; justify-content: center;">
            <button id="closeNotificationBtn" style="width: 100%; padding: 12px; border: none; border-radius: 14px; font-weight: bold; font-size: 14px; color: white; cursor: pointer; transition: 0.15s; text-align: center;" onclick="closeNotificationModal()">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- GLOBAL CONFIRMATION MODAL (VANILLA CSS INLINE) -->
<div id="globalConfirmModal" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 16px;">
    <div id="globalConfirmCard" style="background: white; border-radius: 24px; width: 380px; max-width: 100%; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; transition: transform 0.25s ease, opacity 0.25s ease; transform: scale(0.95); opacity: 0; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
        
        <!-- Icon Warning -->
        <div style="display: flex; justify-content: center; margin-bottom: 16px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div style="text-align: center; margin-bottom: 24px; width: 100%;">
            <h3 id="confirmTitle" style="font-size: 18px; font-weight: bold; color: #1e293b; margin: 0 0 6px; font-family: inherit;">Konfirmasi</h3>
            <p id="confirmMessage" style="font-size: 14px; color: #64748b; margin: 0; font-family: inherit; line-height: 1.5; font-weight: 500;">Apakah Anda yakin ingin melakukan tindakan ini?</p>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 12px; width: 100%;">
            <button id="confirmCancelBtn" style="flex: 1; padding: 12px; background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569; border-radius: 14px; font-weight: bold; font-size: 14px; cursor: pointer; transition: 0.15s; text-align: center;" onclick="closeConfirmModal()">
                Batal
            </button>
            <button id="confirmActionBtn" style="flex: 1; padding: 12px; border: none; background: #ef4444; color: white; border-radius: 14px; font-weight: bold; font-size: 14px; cursor: pointer; transition: 0.15s; text-align: center; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
function toggleDropdown() {
    let dropdown = document.getElementById("dropdown");
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
}

window.onclick = function(event) {
    if (!event.target.closest('.user-menu')) {
        let dropdown = document.getElementById("dropdown");
        if (dropdown) dropdown.style.display = "none";
    }
}

// Global Notification Modal Functions
function showNotificationModal(title, message, type = 'success') {
    const modal = document.getElementById('globalNotificationModal');
    const card = document.getElementById('globalNotificationCard');
    const titleEl = document.getElementById('notificationTitle');
    const msgEl = document.getElementById('notificationMessage');
    const iconSuccess = document.getElementById('iconSuccess');
    const iconError = document.getElementById('iconError');
    const btn = document.getElementById('closeNotificationBtn');

    titleEl.textContent = title;
    msgEl.textContent = message;

    iconSuccess.style.display = 'none';
    iconError.style.display = 'none';

    if (type === 'success') {
        iconSuccess.style.display = 'flex';
        btn.style.backgroundColor = '#10b981';
        btn.style.boxShadow = '0 4px 6px -1px rgba(16, 185, 129, 0.2)';
    } else {
        iconError.style.display = 'flex';
        btn.style.backgroundColor = '#ef4444';
        btn.style.boxShadow = '0 4px 6px -1px rgba(239, 68, 68, 0.2)';
    }

    modal.style.display = 'flex';
    setTimeout(() => {
        card.style.transform = 'scale(1)';
        card.style.opacity = '1';
    }, 10);
}

function closeNotificationModal() {
    const modal = document.getElementById('globalNotificationModal');
    const card = document.getElementById('globalNotificationCard');

    card.style.transform = 'scale(0.95)';
    card.style.opacity = '0';

    setTimeout(() => {
        modal.style.display = 'none';
    }, 250);
}

// Global Confirmation Modal Functions
let globalConfirmCallback = null;

function showConfirmModal(title, message, onConfirm) {
    const modal = document.getElementById('globalConfirmModal');
    const card = document.getElementById('globalConfirmCard');
    const titleEl = document.getElementById('confirmTitle');
    const msgEl = document.getElementById('confirmMessage');
    const actionBtn = document.getElementById('confirmActionBtn');

    titleEl.textContent = title;
    msgEl.textContent = message;
    globalConfirmCallback = onConfirm;

    actionBtn.onclick = () => {
        if (globalConfirmCallback) globalConfirmCallback();
        closeConfirmModal();
    };

    modal.style.display = 'flex';
    setTimeout(() => {
        card.style.transform = 'scale(1)';
        card.style.opacity = '1';
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('globalConfirmModal');
    const card = document.getElementById('globalConfirmCard');

    card.style.transform = 'scale(0.95)';
    card.style.opacity = '0';

    setTimeout(() => {
        modal.style.display = 'none';
        globalConfirmCallback = null;
    }, 250);
}

// Auto trigger from Laravel session flashes
window.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
        showNotificationModal('Berhasil', "{{ session('success') }}", 'success');
    @endif
    @if(session('error'))
        showNotificationModal('Terjadi Kesalahan', "{{ session('error') }}", 'error');
    @endif
});
</script>

</body>
</html>