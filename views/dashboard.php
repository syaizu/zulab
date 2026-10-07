<div class="space-y-6">
    <!-- Header Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-slate-900 to-blue-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 -mt-8 -mr-8 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-indigo-500/20 text-indigo-200 border border-indigo-400/30 px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Modul Otorisasi Lab Sp.PK Zulabs</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!
                </h1>
                <p class="text-indigo-200 text-xs sm:text-sm max-w-xl">
                    Sistem Peninjauan Otorisasi Laboratorium Terintegrasi SIMRS Khanza (`rspm_otorisasi`). Ringkasan aktivitas pemeriksaan tanggal <b><?php echo date('d/m/Y'); ?></b>.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="index.php?page=pemeriksaan_lab" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs sm:text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-notes-medical text-base"></i>
                    <span>Buka Otorisasi Lab</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Peringatan Nilai Kritis Hari Ini (Critical Value Alert Banner) -->
    <?php if ($totalCriticalToday > 0): ?>
        <div class="p-4 bg-red-600 text-white rounded-2xl shadow-lg shadow-red-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-pulse">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm uppercase tracking-wide">Peringatan Kritis Membutuhkan Penanganan!</h3>
                    <p class="text-xs text-red-100 mt-0.5">
                        Ditemukan <b class="underline font-bold"><?php echo $totalCriticalToday; ?> PASIEN</b> dengan Nilai Kritis pada pemeriksaan hari ini.
                    </p>
                </div>
            </div>
            <a href="index.php?page=pemeriksaan_lab&tgl_awal=<?php echo date('Y-m-d'); ?>&stts_filter=Pending" class="px-4 py-2 bg-white text-red-700 hover:bg-red-50 text-xs font-bold rounded-xl shrink-0 shadow transition-all text-center">
                Tinjau Sekarang &rarr;
            </a>
        </div>
    <?php endif; ?>

    <!-- 4 Main Key Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Total Pasien Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-indigo-300 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pasien Lab Hari Ini</p>
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fa-solid fa-hospital-user text-xl"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900"><?php echo $totalExamToday; ?></span>
                <span class="text-[11px] font-medium text-slate-400">No. Rawat</span>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-amber-300 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Menunggu Review</p>
                <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-600"><?php echo $totalPendingToday; ?></span>
                <span class="text-[11px] font-semibold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full border border-amber-200">Pending</span>
            </div>
        </div>

        <!-- Valid / Ter-Otorisasi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ter-Otorisasi</p>
                <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600"><?php echo $totalValidToday; ?></span>
                <span class="text-[11px] font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">Valid Hari Ini</span>
            </div>
        </div>

        <!-- Nilai Kritis Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-red-300 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Kritis</p>
                <div class="p-2.5 bg-red-50 text-red-600 rounded-xl">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-red-600"><?php echo $totalCriticalToday; ?></span>
                <span class="text-[11px] font-semibold bg-red-50 text-red-700 px-2 py-0.5 rounded-full border border-red-200">Kritis</span>
            </div>
        </div>
    </div>

    <!-- Grid Visual Progress & Action List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Section (2 Cols): Critical Action Queue & Progress -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Overall Progress Bar Card -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Pencapaian Otorisasi Hari Ini</h3>
                        <p class="text-xs text-slate-500">Persentase penyelesaian verifikasi hasil lab oleh Dokter Sp.PK hari ini.</p>
                    </div>
                    <?php 
                    $pctValid = $totalExamToday > 0 ? round(($totalValidToday / $totalExamToday) * 100) : 0;
                    ?>
                    <span class="text-lg font-extrabold font-mono text-indigo-600"><?php echo $pctValid; ?>%</span>
                </div>

                <!-- Progress Bar visual -->
                <div class="w-full h-3.5 bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
                    <?php 
                    $pctPending = $totalExamToday > 0 ? ($totalPendingToday / $totalExamToday) * 100 : 0;
                    $pctHold = $totalExamToday > 0 ? ($totalHoldToday / $totalExamToday) * 100 : 0;
                    $pctResample = $totalExamToday > 0 ? ($totalResampleToday / $totalExamToday) * 100 : 0;
                    ?>
                    <div style="width: <?php echo $pctValid; ?>%" class="bg-emerald-500 h-full transition-all" title="Valid (<?php echo round($pctValid); ?>%)"></div>
                    <div style="width: <?php echo $pctPending; ?>%" class="bg-amber-400 h-full transition-all" title="Pending (<?php echo round($pctPending); ?>%)"></div>
                    <div style="width: <?php echo $pctHold; ?>%" class="bg-blue-400 h-full transition-all" title="Hold (<?php echo round($pctHold); ?>%)"></div>
                    <div style="width: <?php echo $pctResample; ?>%" class="bg-red-500 h-full transition-all" title="Re-sample (<?php echo round($pctResample); ?>%)"></div>
                </div>

                <div class="flex items-center justify-between text-[11px] text-slate-600 pt-1 flex-wrap gap-2">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Valid: <b><?php echo $totalValidToday; ?></b></span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Pending: <b><?php echo $totalPendingToday; ?></b></span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span> Hold: <b><?php echo $totalHoldToday; ?></b></span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Re-sample: <b><?php echo $totalResampleToday; ?></b></span>
                </div>
            </div>

            <!-- List Pasien Kritis Hari Ini -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-red-50 text-red-600 rounded-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm">Pasien Nilai Kritis Terbaru Hari Ini</h3>
                    </div>
                    <a href="index.php?page=pemeriksaan_lab&tgl_awal=<?php echo date('Y-m-d'); ?>&keyword=kritis" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                </div>

                <?php if (empty($recentCriticals)): ?>
                    <div class="p-6 text-center text-slate-400 text-xs space-y-1">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-1 block"></i>
                        <p class="font-semibold text-slate-700">Tidak ada nilai kritis hari ini.</p>
                        <p class="text-[11px]">Seluruh parameter hasil periksa berada dalam rentang aman.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-slate-100">
                        <?php foreach ($recentCriticals as $c): ?>
                            <div class="py-3 flex items-center justify-between text-xs gap-3">
                                <div>
                                    <div class="font-bold text-slate-900 flex items-center gap-2">
                                        <span><?php echo htmlspecialchars($c['nm_pasien']); ?></span>
                                        <span class="text-[10px] bg-red-100 text-red-800 border border-red-200 px-1.5 py-0.2 rounded font-mono font-bold">KRITIS</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-3 mt-0.5">
                                        <span>RM: <b><?php echo htmlspecialchars($c['no_rkm_medis']); ?></b></span>
                                        <span>Poli: <b><?php echo htmlspecialchars($c['nm_poli']); ?></b></span>
                                        <span>Jam: <b><?php echo htmlspecialchars($c['jam']); ?></b></span>
                                    </div>
                                </div>
                                <a href="index.php?page=pemeriksaan_lab&tgl_awal=<?php echo date('Y-m-d'); ?>&keyword=<?php echo urlencode($c['no_rawat']); ?>" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg font-bold text-[11px] transition-colors shrink-0">
                                    Buka
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right Section (1 Col): Workload & System Status -->
        <div class="space-y-6">
            
            <!-- Card Kinerja Sp.PK Aktif -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Produktivitas Otorisasi Saya</h3>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100 flex items-center justify-between">
                        <div>
                            <p class="text-[11px] text-slate-500 font-medium">Sp.PK Aktif:</p>
                            <p class="font-bold text-indigo-950 text-sm"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></p>
                        </div>
                        <?php if (!empty($_SESSION['kd_dokter'])): ?>
                            <span class="px-2 py-1 bg-indigo-200 text-indigo-800 text-[10px] font-mono font-bold rounded">
                                <?php echo htmlspecialchars($_SESSION['kd_dokter']); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-slate-600 font-medium">Otorisasi Diselesaikan Hari Ini:</span>
                        <span class="text-base font-extrabold font-mono text-emerald-600"><?php echo $myAuthCountToday; ?> Pasien</span>
                    </div>
                </div>
            </div>

            <!-- Audit Feed Otorisasi Terakhir -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                        <span>Audit Log Terakhir</span>
                    </h3>
                </div>

                <?php if (empty($recentAuths)): ?>
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada data otorisasi yang divalidasi hari ini.</p>
                <?php else: ?>
                    <div class="space-y-2.5 text-xs">
                        <?php foreach ($recentAuths as $a): ?>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-bold text-slate-800 text-[11px]"><?php echo htmlspecialchars($a['nm_pasien']); ?></p>
                                    <p class="text-[10px] text-slate-500 font-mono">RM: <?php echo htmlspecialchars($a['no_rkm_medis']); ?></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-[9px] bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded">VALID</span>
                                    <p class="text-[9px] text-slate-400 font-mono mt-0.5"><?php echo date('H:i', strtotime($a['tgl_otorisasi'])); ?> WIB</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Indicator Koneksi Database SIMRS Khanza -->
            <div class="p-4 bg-slate-900 text-slate-300 rounded-2xl border border-slate-800 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-white flex items-center gap-1.5">
                        <i class="fa-solid fa-server text-indigo-400"></i> Server Khanza SIK
                    </span>
                    <?php if ($pdo_sik): ?>
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[10px] font-bold">ONLINE</span>
                    <?php else: ?>
                        <span class="px-2 py-0.5 rounded bg-red-500/20 text-red-400 border border-red-500/30 text-[10px] font-bold">OFFLINE</span>
                    <?php endif; ?>
                </div>
                <p class="text-[10px] text-slate-400 font-mono">
                    Host: 192.168.5.100:3306<br>
                    Database: test-simrs (`rspm_otorisasi`)
                </p>
            </div>

        </div>

    </div>
</div>
