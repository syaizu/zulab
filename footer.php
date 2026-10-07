<footer class="mt-auto py-4 px-6 border-t border-slate-200 bg-white text-center text-xs text-slate-500">
    <p>&copy; <?php echo date('Y'); ?> <b class="text-slate-700">Zulabs</b>. All rights reserved. Integrated with SIMRS Khanza.</p>
</footer>

<script>
    // Toggle Mobile Sidebar Drawer
    function toggleSidebar() {
        var sidebar = document.getElementById('mainSidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        if (sidebar && backdrop) {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    }

    // Modal Handlers untuk Modul Pengaturan User
    function openUserModal() {
        var modalTitle = document.getElementById('userModalTitle');
        if (modalTitle) modalTitle.innerText = "Tambah Pengguna Baru";
        var userId = document.getElementById('modal_user_id');
        if (userId) userId.value = "";
        var userNama = document.getElementById('modal_user_nama');
        if (userNama) userNama.value = "";
        var userUsername = document.getElementById('modal_user_username');
        if (userUsername) userUsername.value = "";
        var userRole = document.getElementById('modal_user_role');
        if (userRole) userRole.value = "petugas";
        var userPass = document.getElementById('modal_user_password');
        if (userPass) userPass.required = true;
        var userModal = document.getElementById('userModal');
        if (userModal) userModal.classList.remove('hidden');
    }

    function editUser(data) {
        var modalTitle = document.getElementById('userModalTitle');
        if (modalTitle) modalTitle.innerText = "Edit Pengguna";
        var userId = document.getElementById('modal_user_id');
        if (userId) userId.value = data.id;
        var userNama = document.getElementById('modal_user_nama');
        if (userNama) userNama.value = data.nama_lengkap;
        var userUsername = document.getElementById('modal_user_username');
        if (userUsername) userUsername.value = data.username;
        var userRole = document.getElementById('modal_user_role');
        if (userRole) userRole.value = data.role;
        var userPass = document.getElementById('modal_user_password');
        if (userPass) userPass.required = false;
        var userModal = document.getElementById('userModal');
        if (userModal) userModal.classList.remove('hidden');
    }

    function closeUserModal() {
        var userModal = document.getElementById('userModal');
        if (userModal) userModal.classList.add('hidden');
    }
</script>
</body>
</html>