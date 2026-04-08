<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- Profile Header -->
    <div class="bg-white rounded-xl p-6 shadow-sm text-center">
        <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <?php if($user->avatar): ?>
                <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="w-20 h-20 rounded-full object-cover">
            <?php else: ?>
                <span class="text-2xl font-bold text-primary-600"><?php echo e(strtoupper(substr($user->name, 0, 2))); ?></span>
            <?php endif; ?>
        </div>
        <h2 class="text-xl font-bold text-gray-900"><?php echo e($user->name); ?></h2>
        <p class="text-gray-500"><?php echo e($user->email); ?></p>
        <div class="mt-3">
            <?php if($user->email_verified_at): ?>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Verified</span>
            <?php else: ?>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-full">Unverified</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Account Stats -->
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl p-3 shadow-sm text-center">
            <p class="text-2xl font-bold text-primary-500"><?php echo e($stats['tasks_completed']); ?></p>
            <p class="text-xs text-gray-500">Tasks Done</p>
        </div>
        <div class="bg-white rounded-xl p-3 shadow-sm text-center">
            <p class="text-2xl font-bold text-green-500">৳<?php echo e(number_format($stats['total_earned'], 0)); ?></p>
            <p class="text-xs text-gray-500">Total Earned</p>
        </div>
        <div class="bg-white rounded-xl p-3 shadow-sm text-center">
            <p class="text-2xl font-bold text-purple-500"><?php echo e($stats['total_referrals']); ?></p>
            <p class="text-xs text-gray-500">Referrals</p>
        </div>
    </div>

    <!-- Edit Profile -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Edit Profile</h3>
        
        <form action="<?php echo e(route('user.profile.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <?php $__errorArgs = ['name'];
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
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <?php $__errorArgs = ['phone'];
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
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <input type="file" name="avatar" accept="image/*"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <?php $__errorArgs = ['avatar'];
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
            
            <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all">
                Update Profile
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Change Password</h3>
        
        <form action="<?php echo e(route('user.profile.password')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" name="current_password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <?php $__errorArgs = ['current_password'];
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
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <?php $__errorArgs = ['password'];
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
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <button type="submit" class="w-full py-3 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-900 transition-all">
                Change Password
            </button>
        </form>
    </div>

    <!-- Account Info -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Account Information</h3>
        
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Email</p>
                    <p class="text-xs text-gray-500"><?php echo e($user->email); ?></p>
                </div>
                <span class="text-xs text-gray-400">Cannot change</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Member Since</p>
                    <p class="text-xs text-gray-500"><?php echo e($user->created_at->format('M d, Y')); ?></p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">User ID</p>
                    <p class="text-xs text-gray-500">#<?php echo e($user->id); ?></p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Referral Code</p>
                    <p class="text-xs text-gray-500"><?php echo e($user->referral_code); ?></p>
                </div>
                <button onclick="navigator.clipboard.writeText('<?php echo e($user->referral_code); ?>')" class="text-primary-500 text-sm font-medium">Copy</button>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <form action="<?php echo e(route('logout')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="w-full py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all">
            Logout
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/profile/index.blade.php ENDPATH**/ ?>