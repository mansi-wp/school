/**
 * AdminLTE & Dynamic Menu Management Helpers
 */
document.addEventListener('DOMContentLoaded', () => {
    // Dynamic sidebar active item highlighting
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            const treeview = link.closest('.has-treeview');
            if (treeview) {
                treeview.classList.add('menu-open');
                const parentLink = treeview.querySelector('.nav-link');
                if (parentLink) parentLink.classList.add('active');
            }
        }
    });

    // Auto-calculate remaining fee in fee collection
    const feeInput = document.getElementById('amount_paid');
    const balanceDisplay = document.getElementById('remaining_balance_display');
    if (feeInput && balanceDisplay) {
        const totalDue = parseFloat(feeInput.dataset.due || '0');
        feeInput.addEventListener('input', () => {
            const entered = parseFloat(feeInput.value || '0');
            const remaining = Math.max(0, totalDue - entered);
            balanceDisplay.textContent = '₹' + remaining.toLocaleString();
        });
    }
});
