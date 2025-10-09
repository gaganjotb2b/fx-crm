//<----------Callbackk function------------>
function createCallBack(data) {
    const btn = document.getElementById("submitBtn");
    if (data.success) {
        toastr['success'](data.message, 'Generate', {
            showMethod: 'slideDown',
            hideMethod: 'slideUp',
            closeButton: true,
            tapToDismiss: false,
            progressBar: true,
            timeOut: 4000,
        });
        btn.disabled = true;
        setTimeout(()=>{
            btn.disabled = false;
            console.log('Button Activated')}, 8000);
        $("#voucher_form")[0].reset();

    } else {
        $.validator("voucher_form", data.errors);
        btn.disabled=false;
        $('#expire_date_error').html(data.date_error);
    }
}
(function (window, document, $) { 
     // on change document type for general user
    // ---------------------------------------------------------------------------------
    $('#user_classifie').val("");
    $(document).on("change","#user_classifie",function () {
        let id_group = $(this).val();
        if (id_group.toLowerCase()==='general') {
            $("#trader_field").slideDown();
        }
        else{
            $("#trader_field").slideUp();
        }
        $.ajax({
            type: "GET",
            url: '/admin/voucher/trader-email',
            dataType: 'json',
            success: function (data) {
                // console.log(data.options);
                $("#trader").html(data.options);
            }
        });
    });

    // //user type switch
    // $('#user_type').val("");
    // $(document).on("change","#user_type",function(){
    //     let user_type=$(this).val();
    // //    console.log(user_type);
    // if(user_type.toLowerCase()==='trader'){
    //     console.log('hi trader');
    //     // $.ajax({
    //     //     type: "GET",
    //     //     url: '/admin/voucher/trader-email',
    //     //     dataType: 'json',
    //     //     success: function (data) {
    //     //      console.log(data);
    //     //     }
    //     // });
    // }
    // if(user_type.toLowerCase()==='ib'){
    //     console.log('hi ib');
    // }
    // if(user_type.toLowerCase()==='manager'){
    //     console.log('hi manager');
    // }


        
    // })

 // on change document type for classic user to show switch button
    $('#user_classifie').val("");
    $(document).on("change","#user_classifie",function(){
        let switch_group=$(this).val();
        if(switch_group.toLowerCase() ==='classic'){
            $("#user_switch").slideDown();
        }
        else{
            $("#user_switch").slideUp();
        }
    });


//Reset input field-----------------------------------------
    const btn = document.getElementById('rstButton');
        btn.addEventListener('click', function handleClick(event) {
        // 👇️ if you are submitting a form (prevents page reload)
        // event.preventDefault();
        const sentMail = document.getElementById('email');
        // Send value to server
        // 👇️ clear input field
        sentMail.value = '';
    });

   //trader_value

    

})(window, document, jQuery);

//checkbox disable script
let checkbox = document.querySelectorAll('.checkbox')
let b = false;
function checkChange(){
    b = !b
    if(b){
        for(let i = 0 ; i< checkbox.length; i++){
            if(checkbox[i].checked === false){
                checkbox[i].disabled = 'true';
            }  
        }
    }else{
        for(let i = 0 ; i< checkbox.length; i++){
            checkbox[i].removeAttribute('disabled');
        }
        
    }
}
