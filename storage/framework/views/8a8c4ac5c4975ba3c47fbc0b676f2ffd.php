<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('body'); ?>
<div class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-primary-500 to-primary-700 px-4 py-8 safe-area-top safe-area-bottom">
    <div class="w-full max-w-md mx-auto">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4 p-2">
                <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-full w-auto object-contain">
            </div>
            <h1 class="text-2xl font-bold text-white">Task Go</h1>
            <p class="text-primary-100 text-sm mt-1">Complete Tasks. Earn Rewards.</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Welcome Back</h2>

            <?php if($errors->any()): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm text-red-600"><?php echo e($errors->first()); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Enter your email">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Enter your password">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-500 focus:ring-primary-500">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/30 active:scale-[0.98]">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="<?php echo e(route('register')); ?>" class="text-primary-500 font-medium hover:underline">Sign Up</a>
                </p>
            </div>
        </div>

        <!-- Other Login Options -->
        <div class="mt-6 text-center space-y-3">
            <a href="<?php echo e(route('agent.login')); ?>" class="block text-white/80 text-sm hover:text-white">
                Agent Login →
            </a>
            <a href="<?php echo e(route('admin.login')); ?>" class="block text-white/60 text-xs hover:text-white">
                Admin Portal
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/auth/user/login.blade.php ENDPATH**/ ?>