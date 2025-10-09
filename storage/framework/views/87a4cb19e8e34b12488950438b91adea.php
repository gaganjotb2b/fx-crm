<!-- end main content -->
<!-- <span class="d-none" id="envSessionTime"><?php echo e(env('SESSION_LIFETIME')); ?></span> -->
<!-- lock screen popup -->
<button type="button" id="sesstionLockButton" class="btn btn-primary d-none " data-bs-toggle="modal" data-bs-target="#sesstionLockPopup"></button>
<!-- Modal -->
<div class="modal fade" id="sesstionLockPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sesstionLockPopupLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sesstionLockPopupLabel">Session Expire Soon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="font-size: 16px">
                <p>Your session will expire in
                    <span class="badge badge-warning badge-light-info">
                        <span id="modal-minute"></span>:
                        <span id="modal-second"></span>
                    </span>
                    seconds. Do you want to extend the
                    session?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="session_button_extent" class="btn btn-primary" style="font-size: 14px">Extent</button>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\b2b\Downloads\crm\resources\views/layouts/lock-screen-modal.blade.php ENDPATH**/ ?>