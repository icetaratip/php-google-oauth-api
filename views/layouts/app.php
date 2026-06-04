<?php
declare(strict_types=1);
/** @var string $baseUrl */
/** @var string $title */
/** @var string $content */
/** @var array|null $user */
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Google Login App') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="h-full font-sans antialiased text-slate-200">

    <?php if ($user): ?>
        <div class="flex h-full min-h-screen overflow-hidden bg-slate-950">
            <aside class="hidden md:flex md:w-64 md:flex-col bg-slate-900 border-r border-slate-800">
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-6 gap-2">
                        <i class="fa-solid fa-shield-halved text-2xl text-indigo-500"></i>
                        <span class="text-xl font-bold tracking-tight text-white">GoogleAuth</span>
                    </div>
                    <nav class="mt-8 flex-1 px-4 space-y-1">
                        <a href="<?= $baseUrl ?>/" class="group flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'dashboard' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                            <i class="fa-solid fa-chart-pie mr-3 text-lg transition-transform group-hover:scale-110"></i>
                            Dashboard
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-slate-800 p-4 bg-slate-900/50">
                    <div class="flex items-center w-full justify-between">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($user['picture'])): ?>
                                <img class="inline-block h-9 w-9 rounded-full ring-2 ring-indigo-500/50" src="<?= htmlspecialchars($user['picture']) ?>" alt="Profile">
                            <?php else: ?>
                                <div class="inline-block h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white uppercase text-sm">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="text-left">
                                <p class="text-xs font-semibold text-white leading-tight truncate max-w-[120px]"><?= htmlspecialchars($user['name']) ?></p>
                                <p class="text-[10px] text-slate-500 truncate max-w-[120px]"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                        </div>
                        <a href="<?= $baseUrl ?>/logout" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-all" title="Sign Out">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </a>
                    </div>
                </div>
            </aside>

            <div class="flex flex-col flex-1 overflow-hidden">
                <header class="flex md:hidden items-center justify-between px-6 py-4 bg-slate-900 border-b border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-2xl text-indigo-500"></i>
                        <span class="text-lg font-bold text-white">GoogleAuth</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <?php if (!empty($user['picture'])): ?>
                            <img class="h-8 w-8 rounded-full" src="<?= htmlspecialchars($user['picture']) ?>" alt="Profile">
                        <?php endif; ?>
                        <a href="<?= $baseUrl ?>/logout" class="text-slate-400 hover:text-rose-400">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </a>
                    </div>
                </header>

                <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 md:p-10">
                    <?= $content ?>
                </main>
            </div>
        </div>
    <?php else: ?>
        <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-950 relative overflow-hidden">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl translate-y-1/2"></div>
            
            <div class="relative z-10">
                <?= $content ?>
            </div>
        </div>
    <?php endif; ?>

</body>
</html>
