<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- User Welcome & Balance Card -->
    <div class="bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 rounded-2xl p-5 text-white shadow-xl relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <!-- User Info -->
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center ring-2 ring-white/30">
                    <span class="text-lg font-bold"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                </div>
                <div>
                    <p class="text-primary-100 text-xs">Hello,</p>
                    <h3 class="font-semibold text-lg"><?php echo e(auth()->user()->name); ?></h3>
                </div>
            </div>

            <!-- Main Balance -->
            <div class="mb-4">
                <p class="text-primary-200 text-xs uppercase tracking-wide">💰 Wallet Balance</p>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-bold">৳<?php echo e(number_format($wallet->main_balance ?? 0, 2)); ?></h2>
                    <span class="text-primary-200 text-sm">BDT</span>
                </div>
            </div>

            <!-- Currency Conversion -->
            <div class="bg-white/10 rounded-xl p-3 mb-4 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💱</span>
                        <span class="text-xs text-primary-100">Convert Currency</span>
                    </div>
                    <a href="<?php echo e(route('user.wallet.convert')); ?>" class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium hover:bg-white/30 transition-all">
                        Convert →
                    </a>
                </div>
                <div class="flex items-center gap-4 mt-2 text-sm">
                    <div>
                        <span class="text-primary-200 text-xs">USDT</span>
                        <p class="font-semibold">$<?php echo e(number_format(($wallet->main_balance ?? 0) / ($usdtRate ?? 120), 2)); ?></p>
                    </div>
                    <div class="w-px h-6 bg-white/20"></div>
                    <div>
                        <span class="text-primary-200 text-xs">Rate</span>
                        <p class="font-semibold">৳<?php echo e(number_format($usdtRate ?? 120, 2)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <a href="<?php echo e(route('user.deposits.create')); ?>" class="flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 rounded-xl py-3 transition-all">
                    <span class="text-lg">📥</span>
                    <span class="font-medium">Deposit</span>
                </a>
                <a href="<?php echo e(route('user.withdrawals.create')); ?>" class="flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 rounded-xl py-3 transition-all">
                    <span class="text-lg">📤</span>
                    <span class="font-medium">Withdraw</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Daily Earnings -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg">📊</span>
                </div>
                <span class="text-xs text-gray-500 font-medium">Daily Earnings</span>
            </div>
            <p class="text-xl font-bold text-gray-900">৳<?php echo e(number_format($todayEarnings ?? 0, 2)); ?></p>
            <p class="text-xs text-green-600 mt-1">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                    Today
                </span>
            </p>
        </div>

        <!-- Total Tasks Completed -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg">📑</span>
                </div>
                <span class="text-xs text-gray-500 font-medium">Tasks Done</span>
            </div>
            <p class="text-xl font-bold text-gray-900"><?php echo e($stats['tasks_completed'] ?? 0); ?></p>
            <p class="text-xs text-blue-600 mt-1">Completed</p>
        </div>
    </div>

    <!-- Stats Cards Row 2 -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Pending Earnings -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg">⏳</span>
                </div>
                <span class="text-xs text-gray-500 font-medium">Pending</span>
            </div>
            <p class="text-xl font-bold text-gray-900">৳<?php echo e(number_format($wallet->pending_balance ?? 0, 2)); ?></p>
            <p class="text-xs text-yellow-600 mt-1"><?php echo e($pendingSubmissions ?? 0); ?> tasks pending</p>
        </div>

        <!-- Total Referrals -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg">👥</span>
                </div>
                <span class="text-xs text-gray-500 font-medium">Referrals</span>
            </div>
            <p class="text-xl font-bold text-gray-900"><?php echo e($stats['total_referrals'] ?? 0); ?></p>
            <p class="text-xs text-purple-600 mt-1">Active users</p>
        </div>
    </div>

    <!-- Referral Code Card -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl p-4 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-lg">🔗</span>
                    <span class="text-purple-200 text-xs uppercase tracking-wide">Your Referral Code</span>
                </div>
                <p class="text-2xl font-bold tracking-wider"><?php echo e(auth()->user()->referral_code); ?></p>
            </div>
            <button onclick="copyReferralCode('<?php echo e(auth()->user()->referral_code); ?>')" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl font-medium text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Copy
            </button>
        </div>
        <div class="mt-3 pt-3 border-t border-white/20">
            <p class="text-xs text-purple-200">Share & earn ৳<?php echo e($referralBonus ?? 50); ?> for each friend who joins!</p>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-4 gap-3">
        <a href="<?php echo e(route('user.deposits.create')); ?>" class="flex flex-col items-center p-3 bg-white rounded-xl shadow-sm border border-gray-100 active:scale-95 transition-transform">
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                <span class="text-xl">📥</span>
            </div>
            <span class="text-xs font-medium text-gray-700">Deposit</span>
        </a>
        <a href="<?php echo e(route('user.withdrawals.create')); ?>" class="flex flex-col items-center p-3 bg-white rounded-xl shadow-sm border border-gray-100 active:scale-95 transition-transform">
            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                <span class="text-xl">📤</span>
            </div>
            <span class="text-xs font-medium text-gray-700">Withdraw</span>
        </a>
        <a href="<?php echo e(route('user.tasks.index')); ?>" class="flex flex-col items-center p-3 bg-white rounded-xl shadow-sm border border-gray-100 active:scale-95 transition-transform">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                <span class="text-xl">📋</span>
            </div>
            <span class="text-xs font-medium text-gray-700">Tasks</span>
        </a>
        <a href="<?php echo e(route('user.referrals.index')); ?>" class="flex flex-col items-center p-3 bg-white rounded-xl shadow-sm border border-gray-100 active:scale-95 transition-transform">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                <span class="text-xl">👥</span>
            </div>
            <span class="text-xs font-medium text-gray-700">Refer</span>
        </a>
    </div>

    <!-- Announcements -->
    <?php if(isset($announcements) && $announcements->count() > 0): ?>
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span>📢</span> Announcements
            </h3>
            <div class="space-y-2">
                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-blue-500">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span>📣</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900"><?php echo e($announcement->title); ?></h4>
                                <p class="text-sm text-gray-500 mt-1"><?php echo e(Str::limit($announcement->content, 100)); ?></p>
                                <p class="text-xs text-gray-400 mt-2"><?php echo e($announcement->created_at->diffForHumans()); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Recent Transactions -->
    <?php if(isset($recentTransactions) && $recentTransactions->count() > 0): ?>
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <span>📜</span> Recent Activity
                </h3>
                <a href="<?php echo e(route('user.wallet.transactions')); ?>" class="text-sm text-primary-500 font-medium">View All →</a>
            </div>
            <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
                <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo e($transaction->amount > 0 ? 'bg-green-100' : 'bg-red-100'); ?>">
                            <?php if($transaction->amount > 0): ?>
                                <span class="text-lg">📥</span>
                            <?php else: ?>
                                <span class="text-lg">📤</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($transaction->description); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($transaction->created_at->diffForHumans()); ?></p>
                        </div>
                        <p class="text-sm font-bold <?php echo e($transaction->amount > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e($transaction->amount > 0 ? '+' : ''); ?>৳<?php echo e(number_format(abs($transaction->amount), 2)); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Copy Referral Code Script -->
<script>
function copyReferralCode(code) {
    const url = '<?php echo e(url('/register')); ?>?ref=' + code;
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Referral link copied!', 'success');
        }).catch(() => {
            fallbackCopyTextToClipboard(url);
        });
    } else {
        fallbackCopyTextToClipboard(url);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showToast('Referral link copied!', 'success');
    } catch (err) {
        showToast('Failed to copy', 'error');
    }
    
    document.body.removeChild(textArea);
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-20 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-white text-sm font-medium z-50 transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/dashboard.blade.php ENDPATH**/ ?>