<?php $__env->startSection('title', 'Convert Currency'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- Current Balance Card -->
    <div class="bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 rounded-2xl p-5 text-white shadow-xl">
        <div class="text-center">
            <p class="text-primary-100 text-xs uppercase tracking-wide">Available Balance</p>
            <h2 class="text-3xl font-bold mt-1">৳<?php echo e(number_format($wallet->main_balance ?? 0, 2)); ?></h2>
            <p class="text-primary-200 text-sm mt-2">≈ $<?php echo e(number_format(($wallet->main_balance ?? 0) / ($usdtRate ?? 120), 2)); ?> USDT</p>
        </div>
    </div>

    <!-- Conversion Calculator -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <span>💱</span> Currency Converter
        </h3>

        <div class="space-y-4">
            <!-- From Currency -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                <div class="relative">
                    <input type="number" id="fromAmount" value="<?php echo e($wallet->main_balance ?? 0); ?>" 
                           class="w-full pl-4 pr-20 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           oninput="calculateConversion()">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
                        <span class="text-lg">🇧🇩</span>
                        <span class="font-semibold text-gray-600">BDT</span>
                    </div>
                </div>
            </div>

            <!-- Swap Icon -->
            <div class="flex justify-center">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                </div>
            </div>

            <!-- To Currency -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                <div class="relative">
                    <input type="number" id="toAmount" value="<?php echo e(number_format(($wallet->main_balance ?? 0) / ($usdtRate ?? 120), 2, '.', '')); ?>" 
                           class="w-full pl-4 pr-20 py-3 border border-gray-200 rounded-xl bg-gray-50" readonly>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
                        <span class="text-lg">💵</span>
                        <select id="toCurrency" class="font-semibold text-gray-600 bg-transparent border-0 focus:ring-0" onchange="calculateConversion()">
                            <option value="USDT">USDT</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exchange Rate Info -->
        <div class="mt-4 p-3 bg-blue-50 rounded-xl">
            <div class="flex items-center justify-between text-sm">
                <span class="text-blue-600">Current Rate</span>
                <span class="font-semibold text-blue-700">1 USDT = ৳<span id="currentRate"><?php echo e(number_format($usdtRate ?? 120, 2)); ?></span></span>
            </div>
        </div>
    </div>

    <!-- Quick Convert Amounts -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4">Quick Convert</h3>
        <div class="grid grid-cols-3 gap-2">
            <?php $__currentLoopData = [100, 500, 1000, 2000, 5000, 10000]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button onclick="setAmount(<?php echo e($amount); ?>)" class="py-2 px-3 bg-gray-100 hover:bg-primary-100 hover:text-primary-600 rounded-lg text-sm font-medium transition-colors">
                    ৳<?php echo e(number_format($amount)); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Conversion Rates Table -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <span>📊</span> Exchange Rates
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-xl">₿</span>
                    <div>
                        <p class="font-semibold text-gray-900">USDT (Tether)</p>
                        <p class="text-xs text-gray-500">Crypto Stablecoin</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-gray-900">৳<?php echo e(number_format($usdtRate ?? 120, 2)); ?></p>
                    <p class="text-xs text-green-600">+0.5%</p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-xl">💵</span>
                    <div>
                        <p class="font-semibold text-gray-900">USD (Dollar)</p>
                        <p class="text-xs text-gray-500">US Dollar</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-gray-900">৳<?php echo e(number_format($usdtRate ?? 120, 2)); ?></p>
                    <p class="text-xs text-green-600">+0.3%</p>
                </div>
            </div>
        </div>
        <p class="text-xs text-gray-400 text-center mt-3">Rates updated every 5 minutes</p>
    </div>

    <!-- Important Notes -->
    <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200">
        <div class="flex gap-3">
            <span class="text-xl">⚠️</span>
            <div>
                <h4 class="font-semibold text-amber-800">Important Note</h4>
                <p class="text-sm text-amber-700 mt-1">Currency conversion is for display purposes only. For actual withdrawal in USDT, please select Binance USDT option during withdrawal.</p>
            </div>
        </div>
    </div>
</div>

<script>
const usdtRate = <?php echo e($usdtRate ?? 120); ?>;

function calculateConversion() {
    const fromAmount = parseFloat(document.getElementById('fromAmount').value) || 0;
    const toCurrency = document.getElementById('toCurrency').value;
    
    let converted = 0;
    if (toCurrency === 'USDT' || toCurrency === 'USD') {
        converted = fromAmount / usdtRate;
    }
    
    document.getElementById('toAmount').value = converted.toFixed(2);
}

function setAmount(amount) {
    document.getElementById('fromAmount').value = amount;
    calculateConversion();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/wallet/convert.blade.php ENDPATH**/ ?>