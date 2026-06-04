<?php
declare(strict_types=1);
/** @var string $baseUrl */
/** @var string $title */
?>
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
    <div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800 py-10 px-6 shadow-2xl rounded-2xl sm:px-10 text-center relative overflow-hidden">
        <div class="absolute -top-10 -left-10 w-24 h-24 bg-indigo-500/20 rounded-full blur-2xl"></div>
        
        <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-inner">
            <i class="fa-solid fa-lock text-3xl"></i>
        </div>
        
        <h2 class="text-3xl font-extrabold text-white tracking-tight">Welcome Back</h2>
        <p class="mt-2 text-sm text-slate-400">
            Sign in securely using your Google Account
        </p>

        <div class="mt-8 flex justify-center">
            <div class="w-full">
                <?php
                $clientId = \App\Config\Env::get('GOOGLE_CLIENT_ID');
                if (empty($clientId) || $clientId === 'your_google_client_id_here'):
                ?>
                    <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl text-left">
                        <div class="flex gap-2 text-amber-400 font-semibold mb-1">
                            <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                            <span>Configuration Needed</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Please set your <code class="text-amber-300 font-mono">GOOGLE_CLIENT_ID</code> in the <code class="text-amber-300 font-mono">.env</code> file to enable Google Sign-In.
                        </p>
                    </div>
                <?php else: ?>
                    <div id="g_id_onload"
                         data-client_id="<?= htmlspecialchars($clientId) ?>"
                         data-context="signin"
                         data-ux_mode="redirect"
                         data-login_uri="<?= $baseUrl ?>/auth/google/callback"
                         data-auto_select="false"
                         data-itp_support="true">
                    </div>

                    <div class="g_id_signin flex justify-center"
                         data-type="standard"
                         data-shape="pill"
                         data-theme="filled_blue"
                         data-text="signin_with"
                         data-size="large"
                         data-logo_alignment="left"
                         data-width="100%">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <p class="mt-8 text-xs text-slate-500">
            By signing in, you agree to our Terms of Service and Privacy Policy.
        </p>
    </div>
</div>

<?php
$error = \App\Core\Session::getFlash('error');
if ($error):
?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        icon: 'error',
        title: 'Authentication Error',
        text: '<?= htmlspecialchars($error) ?>',
        confirmButtonColor: '#4f46e5'
    });
});
</script>
<?php endif; ?>
