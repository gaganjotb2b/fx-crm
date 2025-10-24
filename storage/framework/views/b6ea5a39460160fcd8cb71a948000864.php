
<?php $__env->startSection('title', 'Admin Lock Screen'); ?>
<?php $__env->startSection('style'); ?>
<!-- extra css here -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- new code -->
<div class="card mb-0">
    <div class="card-body ">
        <a href="index.html" class="brand-logo">
            <img src="<?php echo e(get_admin_logo()); ?>" height="28" alt="<?php echo e(config('app.name')); ?>">
            <!-- <h2 class="brand-text text-primary ms-1">Vuexy</h2> -->
        </a>
        <div class="text-center">
            <span class="avatar">
                <img class="round" src="<?php echo e($user_profile_photo); ?>" alt="avatar" width="40" height="40">
                <span class="avatar-status-online"></span>
            </span>
            <h4 class="card-title mb-1"><?php echo e(ucwords($user_name)); ?></h4>
            <span class="auth-user-activity">Last Activity: <span class="badge badge-light-info"><span id="hours"></span>:<span id="minutes"></span>:<span id="seconds"></span></span></span>
            <p class="card-text mb-2">Enter password to unlock your account.</p>
        </div>
        <form action="<?php echo e(route('admin.lock.screen')); ?>" method="POST" enctype="multipart/form-data" role="form" id="lock_sreen_form">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="user_id" value="<?php echo e($user_id); ?>">
            <input type="hidden" name="current_page" value="<?php echo e($current_page); ?>">
            <div class="mb-1">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="reset-password-new">Password</label>
                </div>
                <div class="input-group input-group-merge form-password-toggle">
                    <input type="password" class="form-control form-control-merge" id="signInPassword" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-password-new" tabindex="1" autofocus />
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </div>
            </div>
            <button type="button" class="btn btn-primary w-100 mb-4" id="lockscreenBtn" onclick="_run(this)" data-el="fg" data-form="lock_sreen_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="lockScreenLoginCallBack" data-btnid="lockscreenBtn">Unlock</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-js'); ?>
<script>
    var sec = -1;

    function pad(val) {
        return val > 9 ? val : "0" + val;
    }
    setInterval(function() {
        $("#seconds").html(pad(++sec % 60));
        $("#minutes").html(pad(parseInt(sec / 60, 10) % 60));
        $("#hours").html(pad(parseInt(sec / 3600, 10)));
    }, 1000);

    function lockScreenLoginCallBack(data) {
        if (data.status == true) {
            notify('success', data.message, 'Lock Screen');
            setTimeout(function() {
                window.location.href = data.current_page;
            }, 2000);
        } else {
            notify('error', data.message, "Lock Screen");
            $.validator("lock_sreen_form", data.errors);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.lock-layout-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/auth/admins/admin-lockscreen.blade.php ENDPATH**/ ?>