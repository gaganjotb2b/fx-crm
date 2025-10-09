
$.fn.filterInput = function () {

//filter special

	$('.filter').on('keypress blur keyup', function(e){
		var string = $(this).val();
		$(this).val(string.replace(/[^a-z0-9\s-_.]/gi, ''));
	});


//filter address
	$('.filter-address').on('keypress blur keyup', function(e){
		var string = $(this).val();
		$(this).val(string.replace(/[^a-z0-9\s-_+.,@#/*()]/gi, ''));
	});

//filter mobile
	$('.filter-mobile').on('keypress blur keyup', function(e){
		var string = $(this).val();
		$(this).val(string.replace(/[^0-9\+]/gi, ''));
	});

//filter email
	$('.filter-email').on('keypress blur keyup', function(e){
		var string = $(this).val();
		$(this).val(string.replace(/[^a-z0-9\-_.@]/gi, ''));
	});

//only number
	$('.filter-num').on('keypress blur keyup', function(e){
		var string = $(this).val();
		$(this).val(string.replace(/[^0-9\.]/gi, ''));
	});

	$('.filter-error').on('keypress blur keyup', function(e){
		var _self = $(this);
		var _parent = $(this).closest('.form-group');

		var string = _self.val();
		if (string != "" && $(_parent.find('.error-msg')).exists()) {
	        _parent.find('.error-msg').remove();
	        _parent.removeClass('has-error');
	    }
	});

	$('.filter-eye').on('click', function(e){
		var _self = $(this);
		var _parent = $(this).closest('.form-group');

		var temp = _parent.find('input'); 
        if (temp.attr('type') === "password") { 
            temp.attr('type', 'text'); 
            _self.removeClass("mdi mdi-eye");
            _self.addClass("mdi mdi-eye-off");

        }else { 
           temp.attr('type', 'password'); 
           _self.removeClass("mdi mdi-eye-off");
           _self.addClass("mdi").addClass("mdi-eye");
        } 
	});


}


