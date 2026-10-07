<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-user-doctor text-indigo-600"></i>
                <span>Otorisasi Hasil Lab (Sp.PK)</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Aplikasi Peninjauan Hasil Lab &amp; Otorisasi Checkbox. Otorisasi dicatat otomatis sesuai <b>Dokter PJ Pemeriksaan (`periksa_lab`)</b>.
            </p>
        </div>
        
        <!-- Status Indicator Reviewer Sp.PK & Mode PJ Lab -->
        <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 px-3.5 py-1.5 rounded-xl shadow-2xs">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-ping"></span>
            <div class="text-xs text-indigo-900">
                <span class="font-semibold">User Active: <b><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></b></span>
                <span class="ml-1 text-[10px] bg-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded font-bold">
                    <i class="fa-solid fa-user-gear mr-0.5"></i>Auto PJ Lab Mode
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="index.php" class="flex flex-col lg:flex-row items-end gap-3 text-xs">
            <input type="hidden" name="page" value="pemeriksaan_lab">
            
            <div class="w-full lg:w-44">
                <label class="block font-semibold text-slate-600 mb-1">Tgl Periksa Mulai</label>
                <div class="relative">
                    <i class="fa-regular fa-calendar absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                    <input type="date" name="tgl_awal" value="<?php echo htmlspecialchars($tgl_awal); ?>" required class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div class="w-full lg:w-44">
                <label class="block font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                <div class="relative">
                    <i class="fa-regular fa-calendar absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                    <input type="date" name="tgl_akhir" value="<?php echo htmlspecialchars($tgl_akhir); ?>" class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none" placeholder="1 hari saja">
                </div>
            </div>

            <div class="w-full lg:w-44">
                <label class="block font-semibold text-slate-600 mb-1">Filter Otorisasi</label>
                <select name="stts_filter" class="w-full py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none bg-white">
                    <option value="">-- Semua Status --</option>
                    <option value="Pending" <?php echo $stts_filter === 'Pending' ? 'selected' : ''; ?>>Pending (Menunggu)</option>
                    <option value="Valid" <?php echo $stts_filter === 'Valid' ? 'selected' : ''; ?>>Valid (Ter-Otorisasi)</option>
                    <option value="Hold" <?php echo $stts_filter === 'Hold' ? 'selected' : ''; ?>>Hold (Ditahan)</option>
                    <option value="Re-sample" <?php echo $stts_filter === 'Re-sample' ? 'selected' : ''; ?>>Re-sample (Ulang)</option>
                </select>
            </div>

            <div class="w-full lg:flex-1">
                <label class="block font-semibold text-slate-600 mb-1">Cari Pasien / Tes</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                    <input type="text" name="keyword" value="<?php echo htmlspecialchars(isset($keyword) ? $keyword : ''); ?>" placeholder="No. Rawat, Nama Pasien, No. RM..." class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center gap-2 w-full lg:w-auto">
                <button type="submit" class="flex-1 lg:flex-none px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tampilkan</span>
                </button>
                <button type="button" onclick="expandAllCards()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-[11px]" title="Buka Semua Kartu">
                    <i class="fa-solid fa-angles-down mr-1"></i> Buka All
                </button>
                <button type="button" onclick="collapseAllCards()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-[11px]" title="Tutup Semua Kartu">
                    <i class="fa-solid fa-angles-up mr-1"></i> Tutup
                </button>
            </div>
        </form>
    </div>

    <?php if ($sik_error): ?>
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg"></i>
            <div>
                <p class="font-bold">Peringatan Database SIMRS Khanza (SIK):</p>
                <p><?php echo htmlspecialchars($sik_error); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Summary Count Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-600 bg-slate-100/90 px-4 py-2.5 rounded-xl border border-slate-200 gap-2">
        <div>
            Ditemukan <span class="font-bold text-slate-900"><?php echo count($grouped_patients); ?> Pasien (No. Rawat)</span> 
            dengan total <span class="font-bold text-indigo-600"><?php echo count($exams_sik); ?> Parameter Tes</span>.
        </div>
        <div class="flex items-center gap-3 text-[11px]">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Valid</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pending / Hold</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span> Nilai Kritis</span>
        </div>
    </div>

    <?php if (empty($grouped_patients)): ?>
        <div class="bg-white p-12 rounded-2xl border border-slate-200 shadow-sm text-center space-y-3">
            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-clipboard-question"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Tidak Ada Data Pemeriksaan Lab</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Tidak ditemukan rekam pemeriksaan lab pada tanggal <b><?php echo date('d/m/Y', strtotime($tgl_awal)); ?></b> pada tabel <b>rspm_otorisasi</b>.
            </p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php 
            $cardIndex = 0;
            foreach ($grouped_patients as $no_rawat => $patientData): 
                $cardIndex++;
                $cleanNoRawat = preg_replace('/[^a-zA-Z0-9]/', '_', $no_rawat);

                // Patient Level Authorization Status Calculation
                $authBadgeClass = "bg-amber-50 text-amber-800 border-amber-200";
                $authText = "Menunggu Review Sp.PK";
                $authIcon = "fa-clock";

                if ($patientData['valid_count'] === $patientData['total_item']) {
                    $authBadgeClass = "bg-emerald-100 text-emerald-800 border-emerald-300";
                    $authText = "Ter-Otorisasi Penuh";
                    $authIcon = "fa-circle-check text-emerald-600";
                } elseif ($patientData['valid_count'] > 0) {
                    $authBadgeClass = "bg-blue-100 text-blue-800 border-blue-300";
                    $authText = "Otorisasi Sebagian (" . $patientData['valid_count'] . "/" . $patientData['total_item'] . ")";
                    $authIcon = "fa-chart-pie text-blue-600";
                }
            ?>
                <div id="patient_card_container_<?php echo $cleanNoRawat; ?>" class="patient-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-200 hover:border-indigo-300">
                    
                    <!-- Header Kartu Pasien (Clickable Accordion) -->
                    <div onclick="togglePatientCard('<?php echo $cleanNoRawat; ?>')" class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 cursor-pointer select-none hover:bg-indigo-50/40 transition-colors">
                        <div class="flex items-start sm:items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0 mt-0.5 sm:mt-0">
                                <i class="fa-solid fa-hospital-user text-base"></i>
                            </div>
                            <div>
                                <!-- Nama Pasien Utama & Badge No. RM -->
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h3 class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                                        <span><?php echo htmlspecialchars($patientData['nm_pasien']); ?></span>
                                    </h3>
                                    <span class="text-[10px] font-mono bg-slate-200 text-slate-700 px-2 py-0.5 rounded font-semibold">
                                        RM: <?php echo htmlspecialchars($patientData['no_rkm_medis']); ?>
                                    </span>
                                    <span class="text-[10px] bg-indigo-100 text-indigo-700 font-semibold px-2 py-0.5 rounded-full">
                                        <?php echo $patientData['total_item']; ?> Parameter
                                    </span>
                                </div>

                                <!-- Informatif: No Rawat, Poliklinik/Ruang, Dokter Pengirim, Dokter PJ Lab, & Tanggal Jam -->
                                <div class="text-[11px] text-slate-500 flex items-center gap-y-1 gap-x-3 flex-wrap">
                                    <span><i class="fa-solid fa-hashtag text-indigo-500 mr-1"></i>No. Rawat: <b><?php echo htmlspecialchars($no_rawat); ?></b></span>
                                    <span><i class="fa-solid fa-clinic-medical text-emerald-500 mr-1"></i>Poli/Ruang: <b><?php echo htmlspecialchars($patientData['nm_poli']); ?></b></span>
                                    <span><i class="fa-solid fa-user-doctor text-blue-500 mr-1"></i>Pengirim: <b><?php echo htmlspecialchars($patientData['nm_dokter']); ?></b></span>
                                    <span><i class="fa-solid fa-user-gear text-purple-600 mr-1"></i>PJ Lab: <b class="text-slate-800"><?php echo htmlspecialchars($patientData['nm_dokter_pj_lab']); ?></b></span>
                                    <span><i class="fa-regular fa-clock text-amber-500 mr-1"></i>Tgl: <b><?php echo date('d/m/Y', strtotime($patientData['tgl_periksa'])); ?></b> (<?php echo htmlspecialchars($patientData['jam']); ?>)</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-2 flex-wrap sm:flex-nowrap pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200">
                            <!-- Badge Peringatan Nilai Kritis (Jika Ada) -->
                            <?php if ($patientData['critical_count'] > 0): ?>
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold border bg-red-100 text-red-800 border-red-300 animate-pulse flex items-center gap-1.5">
                                    <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                                    <span>ADA <?php echo $patientData['critical_count']; ?> NILAI KRITIS!</span>
                                </span>
                            <?php endif; ?>

                            <!-- Badge Status Master Otorisasi Pasien (Selalu Tampil) -->
                            <span id="badge_patient_status_<?php echo $cleanNoRawat; ?>" class="px-2.5 py-1 rounded-lg text-[11px] font-bold border flex items-center gap-1.5 <?php echo $authBadgeClass; ?>">
                                <i class="fa-solid <?php echo $authIcon; ?>"></i>
                                <span><?php echo $authText; ?></span>
                            </span>

                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-sm shrink-0">
                                <i id="icon_chevron_<?php echo $cleanNoRawat; ?>" class="fa-solid fa-chevron-down transition-transform duration-300"></i>
                            </div>
                        </div>
                    </div>

                    <div id="body_card_<?php echo $cleanNoRawat; ?>" class="hidden p-4 sm:p-5 space-y-6 border-t border-slate-100 bg-white">
                        
                        <!-- Master Bulk Authorization Actions -->
                        <div class="bg-indigo-900 text-white p-3.5 rounded-xl flex flex-col lg:flex-row items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-2.5 text-xs">
                                <i class="fa-solid fa-user-shield text-indigo-300 text-base shrink-0"></i>
                                <div>
                                    <p class="font-bold">Otorisasi Master Seluruh Hasil Pasien Ini</p>
                                    <p class="text-[11px] text-indigo-200">
                                        Gunakan tombol di sebelah kanan untuk Menyetujui atau Membatalkan otorisasi seluruh <?php echo $patientData['total_item']; ?> parameter atas nama Dokter Sp.PK aktif.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 w-full lg:w-auto shrink-0">
                                <button type="button" 
                                        onclick="authorizeMaster('<?php echo htmlspecialchars($no_rawat); ?>', '<?php echo $patientData['tgl_periksa']; ?>', '<?php echo $patientData['jam']; ?>', 0, 'Pending')" 
                                        class="flex-1 lg:flex-none px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg shadow transition-all flex items-center justify-center gap-1.5 active:scale-95"
                                        title="Kembalikan seluruh parameter pasien ini ke status Pending (Belum di-otorisasi)">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    <span>Batalkan Otorisasi SEMUA</span>
                                </button>

                                <button type="button" 
                                        onclick="authorizeMaster('<?php echo htmlspecialchars($no_rawat); ?>', '<?php echo $patientData['tgl_periksa']; ?>', '<?php echo $patientData['jam']; ?>', <?php echo $patientData['critical_count']; ?>, 'Valid')" 
                                        class="flex-1 lg:flex-none px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg shadow transition-all flex items-center justify-center gap-1.5 active:scale-95"
                                        title="Menyetujui seluruh parameter pasien ini secara sekaligus">
                                    <i class="fa-solid fa-signature"></i>
                                    <span>Otorisasi SEMUA Parameter</span>
                                </button>
                            </div>
                        </div>

                        <?php 
                        foreach ($patientData['perawatan_list'] as $kd_jenis_prw => $package): 
                            $cleanKdJenis = preg_replace('/[^a-zA-Z0-9]/', '_', $kd_jenis_prw);
                            $pkgGroupId = "pkg_" . $cleanNoRawat . "_" . $cleanKdJenis;
                        ?>
                            <div class="bg-slate-50/80 rounded-xl border border-slate-200 p-3.5 space-y-3">
                                
                                <!-- GROUP HEADER WITH SELECT ALL CHECKBOX -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 pb-2.5 gap-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-vial-circle-check text-indigo-600"></i>
                                        <h4 class="font-bold text-slate-800 text-xs"><?php echo htmlspecialchars($package['nm_perawatan']); ?></h4>
                                        <span class="text-[10px] bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full font-mono"><?php echo $package['group_total']; ?> Parameter</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 font-semibold cursor-pointer select-none bg-white border border-slate-300 px-2.5 py-1 rounded-lg hover:bg-slate-100">
                                            <input type="checkbox" onchange="toggleSelectAllGroup('<?php echo $pkgGroupId; ?>', this.checked)" class="w-3.5 h-3.5 text-indigo-600 rounded focus:ring-indigo-500">
                                            <span>Pilih Semua / Batal Semua</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-2xs">
                                    <table id="table_<?php echo $pkgGroupId; ?>" class="w-full text-left text-xs border-collapse">
                                        <thead>
                                            <tr class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b">
                                                <th class="p-2.5 text-center w-12">Pilih</th>
                                                <th class="p-2.5">Nama Parameter / Template</th>
                                                <th class="p-2.5 text-center bg-indigo-50/80 text-indigo-900 w-32">Hasil Periksa</th>
                                                <th class="p-2.5">Nilai Rujukan Normal</th>
                                                <th class="p-2.5">Keterangan</th>
                                                <th class="p-2.5">Dokter Sp.PK Penanggung Jawab</th>
                                                <th class="p-2.5">Catatan Micro Sp.PK</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <?php foreach ($package['items'] as $item): 
                                                $id_template = $item['id_template'];
                                                $cleanIdTemplate = preg_replace('/[^a-zA-Z0-9]/', '_', $id_template);
                                                $itemRowId = "row_" . $cleanNoRawat . "_" . $cleanKdJenis . "_" . $cleanIdTemplate;
                                                $sttsMicro = $item['stts_otorisasi'] ? $item['stts_otorisasi'] : 'Pending';
                                                $isChecked = ($sttsMicro === 'Valid');
                                                
                                                // Deteksi frasa Kritis pada kolom keterangan
                                                $isKritis = (!empty($item['keterangan']) && (stripos($item['keterangan'], 'nilai kritis') !== false || stripos($item['keterangan'], 'kritis') !== false));

                                                $rowBg = "hover:bg-slate-50";
                                                if ($isKritis) {
                                                    $rowBg = "bg-red-100/90 hover:bg-red-200/90 border-l-4 border-l-red-600 font-semibold";
                                                } elseif ($isChecked) {
                                                    $rowBg = "bg-emerald-50/40 hover:bg-emerald-50/70";
                                                } elseif ($sttsMicro === 'Hold') {
                                                    $rowBg = "bg-amber-50/50 hover:bg-amber-50/80";
                                                } elseif ($sttsMicro === 'Re-sample') {
                                                    $rowBg = "bg-red-50/50 hover:bg-red-50/80";
                                                }
                                            ?>
                                                <tr id="<?php echo $itemRowId; ?>" data-id-template="<?php echo htmlspecialchars($id_template); ?>" data-is-kritis="<?php echo $isKritis ? '1' : '0'; ?>" class="<?php echo $rowBg; ?> transition-colors text-[11px]">
                                                    <!-- CHECKBOX SELECTION FOR AUTHORIZATION AND UN-AUTHORIZATION -->
                                                    <td class="p-2.5 text-center">
                                                        <input type="checkbox" 
                                                               id="chk_item_<?php echo $itemRowId; ?>" 
                                                               class="chk-item-<?php echo $pkgGroupId; ?> w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer" 
                                                               <?php echo $isChecked ? 'checked' : ''; ?>
                                                               onchange="updateRowHighlight('<?php echo $itemRowId; ?>', this.checked)">
                                                    </td>

                                                    <td class="p-2.5 <?php echo $isKritis ? 'text-red-950 font-extrabold' : 'font-bold text-slate-800'; ?>">
                                                        <?php echo htmlspecialchars(!empty($item['nama_template']) ? $item['nama_template'] : $item['id_template']); ?>
                                                    </td>
                                                    
                                                    <td class="p-2.5 text-center font-bold font-mono text-xs <?php echo $isKritis ? 'text-red-700 bg-red-200/80 border border-red-300 rounded font-extrabold animate-pulse' : 'text-indigo-900 bg-indigo-50/30'; ?>">
                                                        <?php echo htmlspecialchars($item['nilai']); ?>
                                                    </td>

                                                    <td class="p-2.5 text-slate-600 font-mono text-[10px]">
                                                        <?php echo htmlspecialchars($item['nilai_rujukan'] ? $item['nilai_rujukan'] : '-'); ?>
                                                    </td>

                                                    <!-- KETERANGAN RSPM_OTORISASI -->
                                                    <td class="p-2.5 font-mono text-[10px] <?php echo $isKritis ? 'text-red-700 font-bold' : 'text-slate-600'; ?>">
                                                        <?php if ($isKritis): ?>
                                                            <span class="inline-block px-2 py-0.5 rounded bg-red-600 text-white font-bold">
                                                                <i class="fa-solid fa-bell mr-1"></i><?php echo htmlspecialchars($item['keterangan']); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <?php echo htmlspecialchars($item['keterangan'] ? $item['keterangan'] : '-'); ?>
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- DOKTER SP.PK PENANGGUNG JAWAB OTORISASI -->
                                                    <td class="p-2.5 text-slate-600 text-[10px]">
                                                        <?php if (!empty($item['nm_dokter_sppk'])): ?>
                                                            <span class="font-bold text-slate-800 flex items-center gap-1">
                                                                <i class="fa-solid fa-user-check text-emerald-600"></i>
                                                                <?php echo htmlspecialchars($item['nm_dokter_sppk']); ?>
                                                            </span>
                                                            <span class="text-[9px] font-mono text-slate-400 block">
                                                                <?php echo !empty($item['tgl_otorisasi']) ? date('d/m/Y H:i', strtotime($item['tgl_otorisasi'])) : ''; ?>
                                                            </span>
                                                        <?php elseif (!empty($item['kd_dokter_sppk'])): ?>
                                                            <span class="font-mono text-slate-700 font-bold">[<?php echo htmlspecialchars($item['kd_dokter_sppk']); ?>]</span>
                                                        <?php else: ?>
                                                            <span class="text-slate-400 italic">- Belum -</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- CATATAN MICRO PARAMETER -->
                                                    <td class="p-2.5">
                                                        <input type="text" id="catatan_input_<?php echo $itemRowId; ?>" value="<?php echo htmlspecialchars($item['catatan_otorisasi'] ? $item['catatan_otorisasi'] : ''); ?>" placeholder="Catatan Sp.PK..." class="w-full p-1 border border-slate-300 rounded text-[11px] <?php echo $isKritis ? 'bg-white border-red-300 focus:ring-red-500' : ''; ?>">
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- BATCH SAVE BUTTON AT BOTTOM OF PACKAGE -->
                                <div class="flex justify-end pt-2">
                                    <button type="button" 
                                            onclick="savePackageBatch('<?php echo htmlspecialchars($no_rawat); ?>', '<?php echo $patientData['tgl_periksa']; ?>', '<?php echo $patientData['jam']; ?>', '<?php echo htmlspecialchars($kd_jenis_prw); ?>', '<?php echo $pkgGroupId; ?>')" 
                                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition-all shadow flex items-center gap-2 active:scale-95">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        <span>Simpan Otorisasi Hasil <?php echo htmlspecialchars($package['nm_perawatan']); ?></span>
                                    </button>
                                </div>

                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="toast_notification" class="fixed bottom-5 right-5 z-50 hidden transition-all duration-300">
    <div id="toast_body" class="px-4 py-3 rounded-xl shadow-2xl border text-xs font-semibold flex items-center gap-2 bg-slate-900 text-white border-slate-700">
        <i id="toast_icon" class="fa-solid fa-circle-info text-indigo-400 text-sm"></i>
        <span id="toast_message">Notifikasi</span>
    </div>
</div>

<script>
    function showNotification(message, type) {
        var toast = document.getElementById('toast_notification');
        var toastBody = document.getElementById('toast_body');
        var toastIcon = document.getElementById('toast_icon');
        var toastMsg = document.getElementById('toast_message');

        if (!toast) return;

        toastMsg.innerText = message;
        if (type === 'success') {
            toastBody.className = "px-4 py-3 rounded-xl shadow-2xl border text-xs font-semibold flex items-center gap-2 bg-emerald-900 text-emerald-100 border-emerald-700";
            toastIcon.className = "fa-solid fa-circle-check text-emerald-400 text-sm";
        } else if (type === 'error') {
            toastBody.className = "px-4 py-3 rounded-xl shadow-2xl border text-xs font-semibold flex items-center gap-2 bg-red-900 text-red-100 border-red-700";
            toastIcon.className = "fa-solid fa-circle-exclamation text-red-400 text-sm";
        } else {
            toastBody.className = "px-4 py-3 rounded-xl shadow-2xl border text-xs font-semibold flex items-center gap-2 bg-slate-900 text-white border-slate-700";
            toastIcon.className = "fa-solid fa-circle-info text-indigo-400 text-sm";
        }

        toast.classList.remove('hidden');
        setTimeout(function() {
            toast.classList.add('hidden');
        }, 3000);
    }

    // Toggle single patient card accordion
    function togglePatientCard(cleanNoRawat) {
        var body = document.getElementById('body_card_' + cleanNoRawat);
        var icon = document.getElementById('icon_chevron_' + cleanNoRawat);
        if (body && icon) {
            body.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    }

    function expandAllCards() {
        document.querySelectorAll('[id^="body_card_"]').forEach(function(b) { b.classList.remove('hidden'); });
        document.querySelectorAll('[id^="icon_chevron_"]').forEach(function(i) { i.classList.add('rotate-180'); });
    }

    function collapseAllCards() {
        document.querySelectorAll('[id^="body_card_"]').forEach(function(b) { b.classList.add('hidden'); });
        document.querySelectorAll('[id^="icon_chevron_"]').forEach(function(i) { i.classList.remove('rotate-180'); });
    }

    // Toggle Checkbox Select All / Deselect All in a package group
    function toggleSelectAllGroup(pkgGroupId, isChecked) {
        document.querySelectorAll('.chk-item-' + pkgGroupId).forEach(function(chk) {
            chk.checked = isChecked;
            var itemRowId = chk.id.replace('chk_item_', '');
            updateRowHighlight(itemRowId, isChecked);
        });
    }

    // Row Visual Highlight based on Checkbox state
    function updateRowHighlight(rowId, isChecked) {
        var row = document.getElementById(rowId);
        if (row) {
            var isKritis = row.getAttribute('data-is-kritis') === '1';
            if (isKritis) {
                row.className = "bg-red-100/90 hover:bg-red-200/90 border-l-4 border-l-red-600 font-semibold transition-colors text-[11px]";
            } else if (isChecked) {
                row.className = "bg-emerald-50/40 hover:bg-emerald-50/70 transition-colors text-[11px]";
            } else {
                row.className = "hover:bg-slate-50 transition-colors text-[11px]";
            }
        }
    }

    // BATCH SAVE AUTHORIZATION AND UN-AUTHORIZATION FOR PACKAGE
    function savePackageBatch(noRawat, tglPeriksa, jam, kdJenisPrw, pkgGroupId) {
        var rows = document.querySelectorAll('#table_' + pkgGroupId + ' tbody tr');
        var itemsData = [];

        rows.forEach(function(row) {
            var itemRowId = row.id;
            var idTemplate = row.getAttribute('data-id-template');
            var chk = document.getElementById('chk_item_' + itemRowId);
            var isChecked = chk ? (chk.checked ? 1 : 0) : 0;
            var catatan = document.getElementById('catatan_input_' + itemRowId) ? document.getElementById('catatan_input_' + itemRowId).value : '';

            itemsData.push({
                id_template: idTemplate,
                is_checked: isChecked,
                catatan: catatan
            });
        });

        var formData = new FormData();
        formData.append('ajax_update_otorisasi', '1');
        formData.append('mode', 'batch_package');
        formData.append('no_rawat', noRawat);
        formData.append('tgl_periksa', tglPeriksa);
        formData.append('jam', jam);
        formData.append('kd_jenis_prw', kdJenisPrw);
        formData.append('items', JSON.stringify(itemsData));

        fetch('index.php?page=pemeriksaan_lab', {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                showNotification('Perubahan otorisasi paket berhasil disimpan!', 'success');
            } else {
                showNotification('Gagal: ' + data.message, 'error');
            }
        })
        .catch(function(err) {
            showNotification('Terjadi kesalahan koneksi atau server.', 'error');
        });
    }

    // MASTER BULK AUTHORIZATION AND UN-AUTHORIZATION AJAX WITH SMART SAFEGUARD
    function authorizeMaster(noRawat, tglPeriksa, jam, criticalCount, targetStatus) {
        if (!targetStatus) targetStatus = 'Valid';

        if (targetStatus === 'Pending') {
            if (!confirm('KONFIRMASI BATAL OTORISASI:\n\nApakah Anda YAKIN ingin MEMBATALKAN OTORISASI untuk SELURUH parameter hasil pasien No. Rawat ' + noRawat + '?\n\nStatus seluruh hasil akan dikembalikan ke Pending.')) {
                return;
            }
        } else {
            if (criticalCount > 0) {
                var msgCrit = 'PERHATIAN PENTING!\n\nPasien ini memiliki ' + criticalCount + ' NILAI KRITIS.\n\nApakah Anda YAKIN ingin menyetujui SELURUH parameter hasil pasien ini secara langsung?';
                if (!confirm(msgCrit)) return;
            } else {
                if (!confirm('Apakah Anda yakin ingin melakukan OTORISASI MASTER untuk SELURUH parameter hasil pasien ini?')) return;
            }
        }

        var formData = new FormData();
        formData.append('ajax_update_otorisasi', '1');
        formData.append('mode', 'master');
        formData.append('no_rawat', noRawat);
        formData.append('tgl_periksa', tglPeriksa);
        formData.append('jam', jam);
        formData.append('stts_otorisasi', targetStatus);

        fetch('index.php?page=pemeriksaan_lab', {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                showNotification('Otorisasi Master berhasil diperbarui!', 'success');
                setTimeout(function() {
                    window.location.reload();
                }, 600);
            } else {
                showNotification('Gagal: ' + data.message, 'error');
            }
        })
        .catch(function(err) {
            showNotification('Terjadi kesalahan koneksi/server.', 'error');
        });
    }
</script>