$(function () {
    // get data into edit offcanvas
    $(document).on("click", ".edit-group", function () {
        $("#group_id").val($(this).data('id'));
        $("#group-name-edit").val($(this).data('name'));
    });

    // manager group delete operation
    $(document).on("click",".delete-manager-group",function () {
        let id = $(this).data('id');
        let request_url = '/admin/manager-settings/manager-group-delete';
        let data = {id:id};
        confirm_alert('Are your confirm to delete account manager?','If you confirm to delete this? Please click ok button.',request_url,data,'Delete Manager Group');
    });
});