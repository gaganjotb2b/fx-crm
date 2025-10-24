
<?php $__env->startSection('title', 'IB Lock Screen'); ?>
<?php $__env->startSection('style'); ?>
<style>
    .app-auth-sign-in .app-auth-background {
        background: url("<?php echo e(asset('trader-assets/assets/img/backgrounds/lock-screen.svg')); ?>") no-repeat;
        background-size: 60%;
        background-position: center;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- new code -->
<div class="card py-lg-3">
    <div class="card-body text-center">
        <div class="info mb-4">
            <img class="avatar avatar-xxl bg-gradient-dark" alt="Profile photo" src="<?php echo e($user_profile_photo); ?>">
        </div>
        <h4 class="mb-0 font-weight-bolder"><?php echo e(ucwords($user_name)); ?></h4>
        <span class="auth-user-activity">
            Last Activity:
            <span class="badge badge-dark">
                <span id="hours"></span>
                :<span id="minutes"></span>
                :<span id="seconds"></span>
            </span>
        </span>
        <p class="mb-4">Enter password to unlock your account.</p>
        <form action="<?php echo e(route('ib.lock.screen')); ?>" method="POST" enctype="multipart/form-data" role="form" id="lock_sreen_form">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="user_id" value="<?php echo e($user_id); ?>">
            <input type="hidden" name="current_page" value="<?php echo e($current_page); ?>">
            <div class="mb-3">
                <input type="password" class="form-control bg-gradient-primary" name="password" placeholder="Password" id="signInPassword" aria-label="password">
            </div>
            <div class="text-center">
                <!-- <button type="button" class="btn btn-lg bg-gradient-dark mt-3 mb-0">Unlock</button> -->
                <button type="button" class="btn btn-lg bg-gradient-dark mt-3 mb-0" id="lockscreenBtn" onclick="_run(this)" data-el="fg" data-form="lock_sreen_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="lockScreenLoginCallBack" data-btnid="lockscreenBtn">Unlock</button>
            </div>
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
<?php echo $__env->make('layouts.lock-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/auth/ibs/ib-lockscreen.blade.php ENDPATH**/ ?>