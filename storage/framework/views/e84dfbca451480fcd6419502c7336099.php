<?php
use App\Models\FooterLink;
$footerLinks = FooterLink::select()->first();
?>
<footer class="footer py-5">
    <div class="container">
        <div class="row footer-links-details">
            <div class="col-lg-8 mb-4 mx-auto text-center">
                <a href="<?php echo e($footerLinks->aml_policy ?? '#'); ?>" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    AML Policy
                </a>
                <a href="<?php echo e($footerLinks->contact_us ?? '#'); ?>" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    <?php echo e(__('page.contact_us')); ?>

                </a>
                <a href="<?php echo e($footerLinks->privacy_policy ?? '#'); ?>" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    <?php echo e(__('page.privacy_policy')); ?>

                </a>
                <a href="<?php echo e($footerLinks->refund_policy ?? '#'); ?>" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    <?php echo e(__('page.refund_policy')); ?>

                </a>
                <a href="<?php echo e($footerLinks->terms_condition ?? '#'); ?>" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    <?php echo e(__('page.Terms&Conditions')); ?>

                </a>
            </div>
            <div class="col-lg-8 mx-auto text-center mb-4 mt-2">

                <?php
                use App\Models\admin\SystemConfig;
                $company_social = SystemConfig::select('com_social_info')->first();
                if($company_social):
                $company_social = json_decode($company_social->com_social_info);
                ?>
                <?php $__currentLoopData = $company_social; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($value!="" && $key != "skype" && $key != 'whatsapp'): ?>
                <a href="<?php echo e($value); ?>" target="_blank" class="text-secondary me-xl-4 me-4">
                    <span class="text-lg fab fa-<?php echo e($key); ?>"></span>
                </a>
                <?php endif; ?>
                <?php if($value!="" && $key == "skype"): ?>
                <a href="skype:<?php echo e($value); ?>?call" target="_blank" class="text-secondary me-xl-4 me-4">
                    <span class="text-lg fab fa-<?php echo e($key); ?>"></span>
                </a>
                <?php endif; ?>
                <?php if($value!="" && $key == "whatsapp"): ?>
                <a href="https://wa.me/<?php echo e($value); ?>" target="_blank" class="text-secondary me-xl-4 me-4">
                    <span class="text-lg fab fa-<?php echo e($key); ?>"></span>
                </a>
                <?php endif; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-8 mx-auto text-center mt-1">
                <p class="mb-0 text-secondary">
                    <?php echo e(get_copyright()); ?> &copy; <?php echo e(date('Y')); ?>

                </p>
            </div>
        </div>
    </div>
</footer><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/layouts/login-footer.blade.php ENDPATH**/ ?>