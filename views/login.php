<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zulabs</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 antialiased min-h-screen">

<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-indigo-900 via-slate-900 to-blue-900">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
        <div class="bg-indigo-600 p-6 text-center text-white relative">
            <div class="inline-flex p-3 bg-white/10 rounded-full mb-3 backdrop-blur-sm">
                <i class="fa-solid font-bold fa-flask-vial text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-wide">Zulabs</h1>
            <p class="text-indigo-200 text-sm mt-1">Sistem Informasi Laboratorium Terintegrasi SIMRS Khanza</p>
        </div>
        
        <div class="p-8">
            <?php if (!empty($alertMessage)): ?>
                <div class="mb-4 p-3 rounded-lg text-sm bg-red-50 border border-red-200 text-red-700 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?php echo htmlspecialchars($alertMessage); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=login" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-3.5 text-slate-400"></i>
                        <input type="text" name="username" required placeholder="Masukkan username"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-3.5 text-slate-400"></i>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <button type="submit" name="login_submit"
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg shadow-indigo-200 transition-all active:scale-[0.98]">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-xs text-slate-500 text-center space-y-1">
                <p class="font-medium text-slate-700">Akun Demo Standar:</p>
                <p><b>Admin:</b> admin / admin123</p>
            </div>
        </div>

        <div class="bg-slate-50 py-3 px-6 text-center text-[11px] text-slate-400 border-t border-slate-100">
            &copy; <?php echo date('Y'); ?> <b>Zulabs</b>. All rights reserved.
        </div>
    </div>
</div>

</body>
</html>