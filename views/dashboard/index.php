<?php
declare(strict_types=1);
/** @var string $baseUrl */
/** @var string $title */
/** @var array $user */
?>
<div class="space-y-8">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">
                Dashboard Overview
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Welcome back, <span class="text-indigo-400 font-semibold"><?= htmlspecialchars($user['name']) ?></span>! Here is your Google authentication status.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-lg">
            <div id="stats-skeleton-1" class="animate-pulse space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="rounded-lg bg-slate-800 h-10 w-10"></div>
                    <div class="h-4 bg-slate-800 rounded w-24"></div>
                </div>
                <div class="h-8 bg-slate-800 rounded w-16"></div>
            </div>
            
            <div id="stats-content-1" class="hidden">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-xl">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-400 truncate">Total Members</p>
                        <p class="mt-1 text-3xl font-semibold text-white" id="stat-total-users">0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-lg">
            <div id="stats-skeleton-2" class="animate-pulse space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="rounded-lg bg-slate-800 h-10 w-10"></div>
                    <div class="h-4 bg-slate-800 rounded w-24"></div>
                </div>
                <div class="h-8 bg-slate-800 rounded w-32"></div>
            </div>
            
            <div id="stats-content-2" class="hidden">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-400 truncate">Last Active Time</p>
                        <p class="mt-1 text-sm font-semibold text-white truncate" id="stat-last-time">N/A</p>
                        <p class="text-[10px] text-slate-500 truncate" id="stat-last-name">N/A</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-lg col-span-1 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-xl">
                    <i class="fa-solid fa-user-shield text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-400 truncate">Session Protection</p>
                    <p class="mt-1 text-lg font-semibold text-emerald-400 flex items-center gap-1.5">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Active JWT</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-white mb-6 border-b border-slate-800 pb-4">
                Google Profile Data
            </h3>

            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <div class="flex-shrink-0 relative">
                    <?php if (!empty($user['picture'])): ?>
                        <img class="h-32 w-32 rounded-full border-4 border-indigo-500/30 object-cover shadow-2xl" src="<?= htmlspecialchars($user['picture']) ?>" alt="Google Photo">
                    <?php else: ?>
                        <div class="h-32 w-32 rounded-full border-4 border-indigo-500/30 bg-indigo-600 flex items-center justify-center font-bold text-white text-5xl uppercase shadow-2xl">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <span class="absolute bottom-1.5 right-1.5 block h-5 w-5 rounded-full ring-2 ring-slate-900 bg-emerald-400 shadow-md" title="Authenticated"></span>
                </div>

                <div class="flex-1 w-full space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Full Name</span>
                            <p class="text-lg font-semibold text-white"><?= htmlspecialchars($user['name'] ?? 'N/A') ?></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Email Address</span>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-semibold text-white truncate max-w-[180px] sm:max-w-none"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></span>
                                <?php if (isset($user['email_verified']) && (int)$user['email_verified'] === 1): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 gap-1 flex-shrink-0">
                                        <i class="fa-solid fa-circle-check"></i> Verified
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20 gap-1 flex-shrink-0">
                                        <i class="fa-solid fa-circle-xmark"></i> Unverified
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">First Name</span>
                            <p class="text-lg font-semibold text-white"><?= htmlspecialchars($user['first_name'] ?? 'N/A') ?></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Last Name</span>
                            <p class="text-lg font-semibold text-white"><?= htmlspecialchars($user['last_name'] ?? 'N/A') ?></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Locale / Language</span>
                            <p class="text-lg font-semibold text-indigo-400 uppercase"><?= htmlspecialchars($user['locale'] ?? 'N/A') ?></p>
                        </div>
                        <div class="space-y-1 sm:col-span-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Google Unique ID (sub)</span>
                            <p class="text-sm font-mono bg-slate-950/60 border border-slate-800 px-3 py-2.5 rounded-xl text-slate-300 select-all overflow-x-auto truncate max-w-full">
                                <?= htmlspecialchars($user['google_id']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('<?= $baseUrl ?>/api/dashboard/stats')
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const data = res.data;
                document.getElementById('stat-total-users').innerText = data.total_users;
                document.getElementById('stat-last-time').innerText = data.last_login_time;
                document.getElementById('stat-last-name').innerText = `by ${data.last_login_name}`;

                document.getElementById('stats-skeleton-1').classList.add('hidden');
                document.getElementById('stats-content-1').classList.remove('hidden');

                document.getElementById('stats-skeleton-2').classList.add('hidden');
                document.getElementById('stats-content-2').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Failed to load stats:', err);
        });
});
</script>
