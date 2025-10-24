<?php
use App\Models\FooterLink;
$footerLinks = FooterLink::select()->first();
?>
<footer class="footer mb-2 w-100">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-4 mb-lg-0 mt-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    <?php echo e(get_copyright()); ?> &copy; <?php echo e(date('Y')); ?>

                </div>
            </div>
            <div class="col-lg-8 mt-4 footer-links-details">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="<?php echo e($footerLinks->aml_policy ?? '#'); ?>" class="nav-link text-muted" target="_blank">AML
                            Policy</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e($footerLinks->contact_us ?? '#'); ?>" class="nav-link text-muted" target="_blank"><?php echo e(__('page.contact_us')); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e($footerLinks->privacy_policy ?? '#'); ?>" class="nav-link text-muted" target="_blank"><?php echo e(__('page.privacy_policy')); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e($footerLinks->refund_policy ?? '#'); ?>" class="nav-link text-muted" target="_blank"><?php echo e(__('page.refund_policy')); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e($footerLinks->terms_condition ?? '#'); ?>" class="nav-link text-muted" target="_blank"><?php echo e(__('page.Terms&Conditions')); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/layouts/footer.blade.php ENDPATH**/ ?>