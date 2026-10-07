<aside id="mainSidebar" class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none">
    <div class="p-5 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-600 rounded-lg text-white">
                <i class="fa-solid fa-flask-vial text-xl"></i>
            </div>
            <div>
                <h2 class="font-bold text-white tracking-wide text-sm">Zulabs</h2>
                <p class="text-[11px] text-slate-400">Sistem Informasi Laboratorium</p>
            </div>
        </div>
        <!-- Tombol Tutup Sidebar (Mobile Only) -->
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white p-1">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-1.5 text-sm">
        <div class="px-3 py-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Menu Utama</div>
        
        <a href="index.php?page=dashboard" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all <?php echo $currentPage === 'dashboard' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-chart-pie w-5"></i>
            <span>Dashboard</span>
        </a>

        <a href="index.php?page=pemeriksaan_lab" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all <?php echo $currentPage === 'pemeriksaan_lab' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-notes-medical w-5"></i>
            <span>Otorisasi Lab Sp.PK</span>
        </a>

        <div class="px-3 py-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase mt-4">Sistem & Pengaturan</div>

        <a href="index.php?page=pengaturan_login" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all <?php echo $currentPage === 'pengaturan_login' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-user-gear w-5"></i>
            <span>Pengaturan Login</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-800">
        <a href="index.php?action=logout" class="flex items-center justify-between px-3 py-2 text-sm rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all">
            <span class="font-medium">Keluar</span>
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>
</aside>