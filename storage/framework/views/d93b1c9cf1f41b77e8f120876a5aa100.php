<?php $__env->startSection('title', 'Withdraw'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- Available Balance -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Available for Withdrawal</p>
                <p class="text-2xl font-bold text-gray-900">৳<?php echo e(number_format($wallet->main_balance, 2)); ?></p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Minimum withdrawal: ৳<?php echo e($minWithdrawal); ?> | Maximum: ৳<?php echo e($maxWithdrawal); ?></p>
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Request Withdrawal</h3>
        
        <form action="<?php echo e(route('user.withdrawals.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            
            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount (BDT)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">৳</span>
                    <input type="number" name="amount" value="<?php echo e(old('amount')); ?>" min="<?php echo e($minWithdrawal); ?>" max="<?php echo e($maxWithdrawal); ?>" step="0.01"
                        class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Enter amount">
                </div>
                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <!-- Quick Amounts -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Select</label>
                <div class="grid grid-cols-4 gap-2">
                    <?php $__currentLoopData = [500, 1000, 2000, 5000]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quickAmount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($quickAmount <= $wallet->main_balance): ?>
                            <button type="button" onclick="setAmount(<?php echo e($quickAmount); ?>)" 
                                class="py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                                ৳<?php echo e(number_format($quickAmount)); ?>

                            </button>
                        <?php else: ?>
                            <button type="button" disabled
                                class="py-2 border border-gray-100 rounded-lg text-sm font-medium text-gray-400 cursor-not-allowed">
                                ৳<?php echo e(number_format($quickAmount)); ?>

                            </button>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            
            <!-- Withdraw All -->
            <button type="button" onclick="setAmount(<?php echo e($wallet->main_balance); ?>)" 
                class="w-full py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-all">
                Withdraw All (৳<?php echo e(number_format($wallet->main_balance, 2)); ?>)
            </button>
            
            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Method</label>
                <div class="space-y-2">
                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
                            <input type="radio" name="payment_method_id" value="<?php echo e($method->id); ?>" 
                                class="w-4 h-4 text-primary-500 focus:ring-primary-500"
                                <?php echo e(old('payment_method_id') == $method->id ? 'checked' : ''); ?>>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900"><?php echo e($method->name); ?></p>
                            </div>
                            <?php if($method->icon): ?>
                                <img src="<?php echo e(asset('storage/' . $method->icon)); ?>" alt="<?php echo e($method->name); ?>" class="w-10 h-10 object-contain">
                            <?php endif; ?>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['payment_method_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <!-- Account Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Your Account Number</label>
                <input type="text" name="account_number" value="<?php echo e(old('account_number')); ?>"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Enter your account number">
                <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <!-- Account Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                <input type="text" name="account_name" value="<?php echo e(old('account_name', auth()->user()->name)); ?>"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Name on account">
                <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <!-- Summary -->
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Withdrawal Amount</span>
                    <span class="text-sm font-medium text-gray-900" id="withdrawal-amount">৳0.00</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Processing Fee</span>
                    <span class="text-sm font-medium text-gray-900" id="processing-fee">৳0.00</span>
                </div>
                <div class="border-t border-gray-200 my-2"></div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900">You Will Receive</span>
                    <span class="text-lg font-bold text-green-600" id="receive-amount">৳0.00</span>
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all active:scale-[0.98]">
                Request Withdrawal
            </button>
        </form>
    </div>

    <!-- Important Notes -->
    <div class="bg-yellow-50 rounded-xl p-4">
        <h4 class="font-medium text-yellow-800 mb-2">Important Notes</h4>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Withdrawals are processed within 24-48 hours</li>
            <li>• Make sure your account details are correct</li>
            <li>• Minimum withdrawal amount is ৳<?php echo e($minWithdrawal); ?></li>
            <li>• Maximum withdrawal amount is ৳<?php echo e($maxWithdrawal); ?></li>
        </ul>
    </div>

    <!-- Recent Withdrawals -->
    <?php if($recentWithdrawals->count() > 0): ?>
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Withdrawals</h3>
            <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
                <?php $__currentLoopData = $recentWithdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            <?php if($withdrawal->status == 'approved'): ?> bg-green-100
                            <?php elseif($withdrawal->status == 'pending'): ?> bg-yellow-100
                            <?php else: ?> bg-red-100 <?php endif; ?>">
                            <?php if($withdrawal->status == 'approved'): ?>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            <?php elseif($withdrawal->status == 'pending'): ?>
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900"><?php echo e($withdrawal->paymentMethod->name ?? 'Unknown'); ?> - <?php echo e($withdrawal->account_number); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($withdrawal->created_at->format('M d, Y h:i A')); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">৳<?php echo e(number_format($withdrawal->amount, 2)); ?></p>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                <?php if($withdrawal->status == 'approved'): ?> bg-green-100 text-green-700
                                <?php elseif($withdrawal->status == 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                                <?php echo e(ucfirst($withdrawal->status)); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
const feePercent = <?php echo e($withdrawalFee ?? 0); ?>;

function setAmount(amount) {
    document.querySelector('input[name="amount"]').value = amount;
    updateSummary(amount);
}

document.querySelector('input[name="amount"]').addEventListener('input', function(e) {
    updateSummary(e.target.value);
});

function updateSummary(amount) {
    amount = parseFloat(amount) || 0;
    const fee = (amount * feePercent / 100);
    const receive = amount - fee;
    
    document.getElementById('withdrawal-amount').textContent = '৳' + amount.toFixed(2);
    document.getElementById('processing-fee').textContent = '৳' + fee.toFixed(2);
    document.getElementById('receive-amount').textContent = '৳' + receive.toFixed(2);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/withdrawals/create.blade.php ENDPATH**/ ?>