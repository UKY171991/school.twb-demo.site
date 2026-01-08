import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global event listeners
document.addEventListener('DOMContentLoaded', () => {
    // 1. Logout handler (Removing inline JS)
    document.addEventListener('click', (e) => {
        const logoutTrigger = e.target.closest('.logout-trigger');
        if (logoutTrigger) {
            e.preventDefault();
            const message = logoutTrigger.dataset.confirm || 'Are you sure you want to log out?';
            if (confirm(message)) {
                const logoutForm = document.getElementById('logout-form');
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    const closestForm = logoutTrigger.closest('form');
                    if (closestForm) closestForm.submit();
                }
            }
        }
    });

    // 2. Universal Confirmation Handler (Removing inline JS)
    document.addEventListener('submit', (e) => {
        const confirmForm = e.target.closest('.confirm-action');
        if (confirmForm) {
            const message = confirmForm.dataset.confirm || 'Are you sure you want to proceed?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        }
    });

    // 3. Mobile Sidebar Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');

    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    // 4. Close sidebar on click outside on mobile
    document.addEventListener('mousedown', (e) => {
        if (sidebar && !sidebar.contains(e.target) && mobileMenuBtn && !mobileMenuBtn.contains(e.target)) {
            if (!sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        }
    });
});

Alpine.start();
