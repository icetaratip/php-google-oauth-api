<?php
declare(strict_types=1);
/** @var string $baseUrl */
?>
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
    <div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800 py-12 px-6 shadow-2xl rounded-2xl sm:px-10 text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-24 h-24 bg-rose-500/20 rounded-full blur-2xl"></div>
        
        <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-rose-500/10 text-rose-400 border border-rose-500/20 shadow-inner">
            <i class="fa-solid fa-circle-exclamation text-3xl"></i>
        </div>
        
        <h1 class="text-7xl font-extrabold text-white tracking-tight">404</h1>
        <h2 class="mt-4 text-xl font-bold text-slate-200">Page Not Found</h2>
        <p class="mt-2 text-sm text-slate-400 leading-relaxed">
            The page you are looking for does not exist or has been moved.
        </p>

        <div class="mt-8">
            <a href="<?= $baseUrl ?>/" class="inline-flex items-center justify-center w-full px-5 py-3 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-lg hover:shadow-indigo-500/20">
                <i class="fa-solid fa-house mr-2"></i>
                Go Back Home
            </a>
        </div>
    </div>
</div>
