
$(function () {
    // START: IB Tree
    // ------------------------------------------------------------------------------
    $(document).on("click", "#view-tree", function () {
        let loader = $(this).data('loading');
        let btn_text = $(this).text();
        $(this).html(loader);
        var obj = $(this);
        var ib_email = $("#ib-email").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/ib-management/ib-tree-create',
            type: 'POST',
            data: { ib_id: ib_email },
            dataType: 'json',
            success: function (data) {
                // Configaration Of Js Tree
                if (data.success == true) {
                    $("#parent").html(data.parent);
                    $("#filer_0").html(data.ib_tree);
                    $("#ib-tree").jstree({
                        "plugins": ["themes", "html_data", "ui", "crrm", "hotkeys", "types"],
                        "core": { "initially_open": ["phtml_1"] },
                        "types": {

                            "default": {
                                "icon": "fas fa-plus"
                            },
                            "root": {
                                "icon": "fas fa-plus"
                            },
                            'f-open': {
                                'icon': "fas fa-minus"
                            },
                            'f-closed': {
                                'icon': "fas fa-plus"
                            }
                        },
                    }).bind("select_node.jstree", function (event, data) {
                        return data.instance.toggle_node(data.node);
                    });
                    /* Toggle between folder open and folder closed */
                    $("#ib-tree").on('open_node.jstree', function (event, data) {
                        data.instance.set_type(data.node, 'f-open');
                    });
                    $("#ib-tree").on('close_node.jstree', function (event, data) {
                        data.instance.set_type(data.node, 'f-closed');
                    });
                    $("#ib-tree").find(".jstree-unchecked").each(function () {
                        alert($(this).attr("id"));
                    });
                    /*************remove plus icon for leaf****** */
                    $('.jstree-leaf').each(function (index, item) {
                        $(this).find(".jstree-themeicon").remove();
                    });

                    // display success message
                    // ----------------------------------------------
                    toastr['success']('Your IB Tree was  generated', 'Tree Generated', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    $(".jstree-anchor").each(function () {
                        $(this).attr('href', 'javaScript:void(0)')
                    })
                } else {
                    let $errors = '';
                    if (data.hasOwnProperty('error_msg')) {
                        $errors += "  " + data.error_msg + '<br>';
                    }
                    if (data.hasOwnProperty('notfound_msg')) {
                        $errors += "  " + data.notfound_msg + '<br>';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Not found!',
                        html: $errors,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
                $(obj).html(btn_text);
            },
            error: function (e) {

            }
        });
        $(".jstree-anchor").each(function () {
            $(this).attr('href', 'javaScript:void(0)')
        })
    })//END: click function


    /*************************
     * s-jstree-root-icon
     *************************/
    $(document).on('click', ".s-jstree-root-icon , .s-ib-email-inner", function () {
        $("#ib-tree").slideToggle();
        $(this).closest("p").toggleClass("tree-is-open");
        if ($(this).closest("p").hasClass("tree-is-open")) {
            $(".s-jstree-root-icon").html('<i class="fas fa-plus"></i>');
        } else {
            $(".s-jstree-root-icon").html('<i class="fas fa-minus"></i>');
        }
    })
    /*****************root caret down click and expand the more info******************** */
    /************************************************************************************ */
    $("#parent").on("click", ".s-jstree-root-caret", function (event) {
        event.stopPropagation();
        if ($(this).closest("p").next(".more-info-con").length) {
            $(this).closest("p").next(".more-info-con").slideToggle();
        } else {
            $(this).closest('p').after("<div class='more-info-con root-mor-info-con' style='display:none'><div class='tree-info-row'><div>Name</div><div>:</div><div class='tdr-name'></div></div><div class='tree-info-row'><div class='phone-label'>Phone</div> <div>:</div> <div class='phone-number'>01747894142</div></div><div class='s-jstree-more-btn d-none'><span class='s-tdr-more-ac-icon'></span>More</div></div>");
            $(this).closest("p").next(".more-info-con").slideToggle();
        }
        $(this).toggleClass("rotate-caret-tree");
        let item_type = $(this).closest("li").data("type");
        if (item_type == 'trader') {
            $(this).closest('p').next(".more-info-con").addClass("tree-color-trader");
        } else {
            $(this).closest('p').next(".more-info-con").addClass("tree-color-ib");
        }
        // display name
        let trader_name = $(this).prev(".s-ib-email-inner").data("apnmae");
        $(this).closest('p').next(".more-info-con").find(".tdr-name").html(trader_name);
    });

    $(".jstree-anchor").hover(function () {
        $(this).css({
            "background-color": "rgba(0,0,0,0.2) !important"
        });
    });
});