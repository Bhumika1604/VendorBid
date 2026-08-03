/* =========================================================
   VendorBid — Custom JavaScript
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    // -----------------------------------------------------
    // Sidebar toggle (mobile / tablet)
    // -----------------------------------------------------
    var sidebar   = document.getElementById('vbSidebar');
    var toggleBtn = document.getElementById('vbSidebarToggle');
    var backdrop  = document.getElementById('vbSidebarBackdrop');

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('show');
    }

    function openSidebar() {
        if (sidebar) sidebar.classList.add('show');
        if (backdrop) backdrop.classList.add('show');
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (sidebar && sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeSidebar);
    }

    // -----------------------------------------------------
    // Show / hide password fields
    // -----------------------------------------------------
    document.querySelectorAll('.vb-toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;

            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) { icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
            } else {
                input.type = 'password';
                if (icon) { icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
            }
        });
    });

    // -----------------------------------------------------
    // Auto-dismiss alerts after 5 seconds
    // -----------------------------------------------------
    document.querySelectorAll('.alert').forEach(function (alertEl) {
        setTimeout(function () {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // -----------------------------------------------------
    // Confirm before following destructive (delete) links
    // -----------------------------------------------------
    document.querySelectorAll('.vb-confirm-delete').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var message = link.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            if (!window.confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // -----------------------------------------------------
    // Confirm before submitting irreversible actions (e.g. Award Project)
    // -----------------------------------------------------
    document.querySelectorAll('.vb-confirm-award').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var message = btn.getAttribute('data-confirm') || 'Are you sure you want to proceed? This cannot be undone.';
            if (!window.confirm(message)) {
                e.preventDefault();
            }
        });
    });

});
