<?php if($useFor == 'user_portal'): ?>
    <?php if($platform != 'both'): ?>
        <div class="form-group">
            <label for="client-group"><?php echo e(__('page.server')); ?></label>
            <input class="form-control" id="server" name="platform"
                value="<?php echo e(strtoupper($system_config->platform_type)); ?>" readonly>
        </div>
    <?php else: ?>
        <div class="form-group al-error-solve fg">
            <label for="server"><?php echo e(__('page.server')); ?></label>
            <select class="form-control multisteps-form__input  choice-colors" id="server" name="platform">
                <option value=""><?php echo e(__('page.choose-a-server')); ?></option>
                <?php echo $server; ?>

            </select>
        </div>
    <?php endif; ?>
<?php elseif($useFor == 'admin_portal_menual'): ?>
    <?php if($platform != 'both'): ?>
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-account">Platform</label>
                <input class="form-control" id="platform-account" name="platform"
                    value="<?php echo e(strtoupper($system_config->platform_type)); ?>" readonly>
            </div>
        </div>
    <?php else: ?>
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-account">Platform</label>
                <select name="platform" class="select2 form-select" id="platform-account">
                    <option value="">Select a platform</option>
                    <?php echo $server; ?>

                </select>
            </div>
        </div>
    <?php endif; ?>
<?php elseif($useFor == 'admin_portal_auto'): ?>
    <?php if($platform != 'both'): ?>
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-live">Platform</label>
                <input class="form-control" id="platform-live" name="platform"
                    value="<?php echo e(strtoupper($system_config->platform_type)); ?>" readonly>
            </div>
        </div>
    <?php else: ?>
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-live">Platform</label>
                <select name="platform" class="select2 form-select" id="platform-live">
                    <option value="">Select a platform</option>
                    <?php echo $server; ?>

                </select>
            </div>
        </div>
    <?php endif; ?>
<?php elseif($useFor == 'admin_portal_client_group'): ?>
    <?php if($platform != 'both'): ?>
        <div class="col-12 mb-1">
            <div class="form-element other-selector">
                <label class="form-label" for="platform-live">Platform</label>
                <input class="form-control" id="platform-live" name="platform"
                    value="<?php echo e(strtoupper($system_config->platform_type)); ?>" readonly>
            </div>
        </div>
    <?php else: ?>
        <div class="col-12 mb-1">
            <div class="form-element other-selector">
                <label class="form-label" for="server">Platform</label>
                <select class="select2 form-select" name="platform" id="server">
                    <optgroup>
                        <option value="">Select a platform</option>
                        <?php echo $server; ?>

                    </optgroup>
                </select>
            </div>
        </div>
    <?php endif; ?>
<?php elseif($useFor == 'admin_portal_report_filter'): ?>
    <?php if($platform != 'both'): ?>
        <div class="col-md-4">
            <label class="form-label" for="platform"><?php echo e(__('page.search_by')); ?>

                <?php echo e(__('page.platform')); ?></label>
                <input class="form-control" id="platform" name="platform"
                value="<?php echo e(strtoupper($system_config->platform_type)); ?>" readonly>
        </div>
    <?php else: ?>
        <div class="col-md-4">
            <label class="form-label" for="platform"><?php echo e(__('page.search_by')); ?>

                <?php echo e(__('page.platform')); ?></label>
            <select class="select2 form-select" id="platform">
                <option value=""><?php echo e(__('client-management.All')); ?></option>
                <?php echo $server; ?>

            </select>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/components/platform-option.blade.php ENDPATH**/ ?>