<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-user-gear text-indigo-600"></i>
                <span>Pengaturan Akun & Manajemen User</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Kelola password akun Anda atau atur pemetaan akun Dokter Sp.PK dengan database SIMRS Khanza (`dokter`).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs rounded-xl font-semibold border border-slate-200 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-user text-indigo-500"></i>
                <span>Role Login: <b class="uppercase text-indigo-700"><?php echo htmlspecialchars($_SESSION['role']); ?></b></span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form Change Own Password Card -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 h-fit">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">Ubah Password Saya</h3>
            </div>

            <form method="POST" action="index.php?page=pengaturan_login" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1.5">Password Saat Ini</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-3 text-slate-400"></i>
                        <input type="password" name="old_password" required placeholder="Masukkan password lama" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1.5">Password Baru</label>
                    <div class="relative">
                        <i class="fa-solid fa-key absolute left-3 top-3 text-slate-400"></i>
                        <input type="password" name="new_password" required placeholder="Masukkan password baru" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition-all">
                    </div>
                </div>

                <button type="submit" name="update_own_password" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Perbarui Password Saya</span>
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Pengguna System &amp; Pemetaan Dokter Sp.PK</h3>
                </div>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <button type="button" onclick="openUserModal()" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-sm transition-all active:scale-95">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Tambah User Baru</span>
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($_SESSION['role'] !== 'admin'): ?>
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-amber-600"></i>
                    <span>Akses manajemen user penuh hanya dimiliki oleh akun bertipe <b>Administrator</b>.</span>
                </div>
            <?php endif; ?>

            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <th class="p-3">User &amp; Username</th>
                            <th class="p-3">Hak Akses (Role)</th>
                            <th class="p-3">Kode Dokter Khanza</th>
                            <th class="p-3">Terdaftar</th>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <th class="p-3 text-center">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400">Belum ada pengguna terdaftar.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr class="hover:bg-slate-50 transition-colors text-[11px]">
                                    <td class="p-3">
                                        <div class="font-bold text-slate-900"><?php echo htmlspecialchars($u['nama_lengkap']); ?></div>
                                        <div class="text-indigo-600 font-mono text-[10px]">@<?php echo htmlspecialchars($u['username']); ?></div>
                                    </td>
                                    <td class="p-3">
                                        <?php 
                                        $roleBg = "bg-slate-100 text-slate-700 border-slate-200";
                                        if ($u['role'] === 'admin') $roleBg = "bg-purple-100 text-purple-800 border-purple-200 font-bold";
                                        elseif ($u['role'] === 'dokter') $roleBg = "bg-indigo-100 text-indigo-800 border-indigo-200 font-bold";
                                        ?>
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] uppercase font-mono border <?php echo $roleBg; ?>">
                                            <?php echo htmlspecialchars($u['role']); ?>
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <?php if (!empty($u['kd_dokter'])): ?>
                                            <span class="px-2 py-0.5 rounded-lg font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] inline-flex items-center gap-1">
                                                <i class="fa-solid fa-user-doctor text-emerald-600"></i>
                                                <span><?php echo htmlspecialchars($u['kd_dokter']); ?></span>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-[10px] italic">- Tidak Terhubung -</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-slate-500 font-mono">
                                        <?php echo date('d/m/Y', strtotime($u['created_at'])); ?>
                                    </td>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <td class="p-3 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button" onclick='editUser(<?php echo json_encode($u); ?>)' class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Pengguna">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                                    <form method="POST" action="index.php?page=pengaturan_login" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                        <button type="submit" name="delete_user" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Pengguna">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <div id="userModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 id="userModalTitle" class="text-base font-bold text-slate-800">Tambah Pengguna Baru</h3>
                <button type="button" onclick="closeUserModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form method="POST" action="index.php?page=pengaturan_login" class="space-y-4 text-xs">
                <input type="hidden" name="user_id" id="modal_user_id">
                
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="modal_user_nama" required placeholder="misal: dr. Andri, Sp.PK" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Username Login</label>
                    <input type="text" name="username" id="modal_user_username" required placeholder="misal: dr_andri" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Role / Hak Akses</label>
                    <select name="role" id="modal_user_role" class="w-full p-2.5 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="dokter">Dokter Sp.PK</option>
                        <option value="petugas">Petugas Lab</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <!-- Pemetaan Kode Dokter Khanza -->
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">
                        Hubungkan Kode Dokter Khanza (Sp.PK)
                    </label>
                    <?php if (!empty($doctors_khanza)): ?>
                        <select name="kd_dokter" id="modal_user_kd_dokter" class="w-full p-2.5 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none font-mono">
                            <option value="">-- Tidak Dihubungkan / Non-Dokter --</option>
                            <?php foreach ($doctors_khanza as $dok): ?>
                                <option value="<?php echo htmlspecialchars($dok['kd_dokter']); ?>">
                                    [<?php echo htmlspecialchars($dok['kd_dokter']); ?>] <?php echo htmlspecialchars($dok['nm_dokter']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="text" name="kd_dokter" id="modal_user_kd_dokter" placeholder="Masukkan Kode Dokter Khanza (misal: D0000123)" class="w-full p-2.5 border border-slate-300 rounded-xl font-mono">
                        <p class="text-[10px] text-amber-600 mt-1">*Database SIMRS offline, masukkan Kode Dokter secara manual jika ada.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" id="modal_user_password" placeholder="••••••••" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-colors">Batal</button>
                    <button type="submit" name="save_user" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-sm transition-all active:scale-95">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<script>
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
        if (userRole) userRole.value = "dokter";
        var userKdDokter = document.getElementById('modal_user_kd_dokter');
        if (userKdDokter) userKdDokter.value = "";
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
        var userKdDokter = document.getElementById('modal_user_kd_dokter');
        if (userKdDokter) userKdDokter.value = data.kd_dokter ? data.kd_dokter : "";
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