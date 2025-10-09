var el = 'form-group';

//process btn with post,animation,nofify once
function _run(e) {
	var obj = $(e);
	//obj.prop('disabled', true);
	var ajax_config = {
		'form': obj.data('form'),
		'type': 'POST',
		'btn': obj.data('btnid'),
		'status': obj.data('status'),
		'loading': obj.data('loading'),
		'validator': obj.data('validator'),
		'callback': obj.data('callback'),
		'notify': obj.data('notify'),
		'animation': obj.data('animation'),
		'redirect_url': obj.data('redirect'),
		'param': obj.data('param'),
		'file': obj.data('file'),
		'el': obj.data('el'),
		func: function (result) {

			functionName = obj.data('callback');

			if (functionName) {
				window[functionName](result);
			}
		}
	};
	ajax_form(ajax_config);
}

function ajax(ajax_config) {

	var form_id = ajax_config['form'];
	var msg_id = ajax_config['msg'];
	var redirect_url = ajax_config['url'];
	var loading_msg = ajax_config['loading'];
	var validator = ajax_config['validator'];
	var responseBack = ajax_config['responseBack'];

	$("#" + form_id).submit(function (e) {

		$("#" + msg_id).html(loading_msg);
		var postData = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax(
			{
				url: formURL,
				type: "POST",
				data: postData,
				success: function (data, textStatus, jqXHR) {
					var responsData = data.split("|");

					$("#" + msg_id).fadeIn();

					if (validator == true) {

						if (ajax_config['function'] == 1) {
							if (responseBack) {
								ajax_config.func(responsData[0], responsData[1], responsData[2]);
							} else {
								$("#" + msg_id).html(responsData[1]);
								ajax_config.func(responsData[0], responsData[2]);
							}
						}
					} else {
						if (responseBack) {
							ajax_config.func(responsData[0], responsData[1], responsData[2]);
						} else {
							$("#" + msg_id).html(responsData[1]);
							if (ajax_config['function'] == 1) {
								ajax_config.func(responsData[0]);
							}
						}
					}

					if (redirect_url != "") {
						window.location.href = redirect_url;
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$("#" + msg_id).html('<pre><code class="prettyprint">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</code></pre>');
				}
			});
		e.preventDefault();	//STOP default action
		$(this).unbind();
	});

	$("#" + form_id).submit(); //SUBMIT FORM
}


function ajax_url(ajax_config) {
	var request_url = ajax_config['request_url'];
	var request_type = (ajax_config['type']) ? ajax_config['type'] : 'GET';
	var btn = ajax_config['btn'];
	var msg_id = ajax_config['status'];
	var redirect_url = ajax_config['url'];
	var loading_msg = (ajax_config['loading']) ? ajax_config['loading'] : 'Loading.....';
	var callBack = ajax_config['callback'];
	var responseBack = ajax_config['responseBack'];
	var notify = ajax_config['notify'];
	var animation = ajax_config['animation'];

	if (btn) {
		var temp_html = $("#" + btn).html();
		$("#" + btn).html(loading_msg);
	}
	if (msg_id) {
		$("#" + msg_id).html(loading_msg);
	}

	$.ajax({
		url: request_url,
		type: request_type,
		dataType: 'json',
		success: function (data) {
			var responsData = data;
			if (btn) {
				$("#" + btn).html(temp_html);
			}

			if (msg_id) {
				$("#" + msg_id).html(responsData.message);
			}

			if (callBack) {
				ajax_config.func(responsData.success, responsData.message, responsData.validation);
			}

			if (notify) {

				//notify error
				if (notify == 2 && responsData.success == 0) {
					$.makeNotify('danger', responsData.message);
				}
				//notify Success
				if (responsData.success == 1) {
					$.makeNotify('success', responsData.message);
				}
			}

			if (animation && responsData.success == 0) {
				if ($("#" + animation).exists()) {
					$('#' + animation).makeAnimated();
				}
			}

			if (redirect_url) {
				$.redirectPost(redirect_url, { post_data: responsData.message });
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
}


function ajax_form(ajax_config) {

	var form_id = ajax_config['form'];
	var request_url = ajax_config['request_url'];
	var request_type = (ajax_config['type']) ? ajax_config['type'] : 'POST';
	var btn = ajax_config['btn'];
	var msg_id = ajax_config['status'];
	var redirect_url = ajax_config['redirect_url'];
	var loading_msg = (ajax_config['loading']) ? ajax_config['loading'] : 'Loading.....';
	var validator = ajax_config['validator'];
	var callBack = ajax_config['callback'];
	var responseBack = ajax_config['responseBack'];
	var notify = ajax_config['notify'];
	var animation = ajax_config['animation'];
	var param = ajax_config['param'];
	var file = (ajax_config['file']) ? ajax_config['file'] : false;
	el = (ajax_config['el']) ? ajax_config['el'] : 'form-group';

	$("#" + form_id).submit(function (e) {
		if (btn) {
			var temp_html = $("#" + btn).html();
			$("#" + btn).html(loading_msg);
		}
		if (msg_id) {
			$("#" + msg_id).html(loading_msg);
		}

		if (file) {
			var postData = new FormData(this);
		} else {
			var postData = $(this).serializeArray();
		}

		if (param) {
			postData.push({ name: 'extra', value: param });
		}

		if (request_url) {
			var formURL = request_url;
		} else {
			var formURL = $(this).attr("action");
		}

		var ac = {
			url: formURL,
			type: request_type,
			data: postData,
			dataType: 'json',
			success: function (data, textStatus, jqXHR) {

				var responsData = data;

				//Update CSRF
				// if(responsData.csrf){

				// 	$("form input[name=csrf_name]").val(responsData.csrf.TokenName);
				// 	$("form input[name=csrf_value]").val(responsData.csrf.TokenValue);
				// }

				if (btn) {
					$("#" + btn).html(temp_html);
				}

				if (msg_id) {
					$("#" + msg_id).html(responsData.message);
				}

				if (callBack) {
					ajax_config.func(responsData);
				}

				if (notify) {

					//notify error
					if (notify == 2 && responsData.success == false) {
						$.makeNotify(responsData);
					}
					//notify Success
					if (responsData.success) {
						$.makeNotify(responsData);
					}
				}

				if (validator) {
					r = responsData.success;
					validData = responsData.errors;
					$.validator(form_id, validData);
				}

				if (animation && responsData.success == 0) {
					if ($("#" + animation).exists()) {
						$('#' + animation).makeAnimated();
					}
				}

				if (redirect_url && responsData.success == 1) {
					$.redirectPost(redirect_url, responsData.data);
				}


			},
			error: function (jqXHR, textStatus, errorThrown) {
				if (btn) {
					$("#" + btn).html(temp_html);
				}

				if (callBack) {
					ajax_config.func(jqXHR);
				}
			}
		}
		if (file) {
			ac.cache = false;
			ac.contentType = false;
			ac.processData = false;
		}
		$.ajax(ac);
		e.preventDefault();	//STOP default action
		$(this).unbind();
	});

	$("#" + form_id).submit(); //SUBMIT FORM
}


// jquery extend function
$.extend(
	{
		redirectPost: function (location, args) {
			var form = '';
			$.each(args, function (key, value) {
				//value = value.split('"').join('\"')
				form += '<input type="hidden" name="' + key + '" value="' + value + '">';
			});
			$('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
		},
		validator: function (form, errors) {
			$('form#' + form + ' .' + el).removeClass('has-error');
			$('form#' + form + ' .error-msg').remove('span');

			$.each(errors, function (k, v) {

				if (k.includes('.')) {

					ka = k.split('.');
					var k = ka[0];
					ka.shift();
					for (var i = 0; i < ka.length; i++) {
						k += '[' + ka[i] + ']';
					}

				}


				if ($('form#' + form + ' input[name="' + k + '"], textarea[name="' + k + '"], select[name="' + k + '"]').closest('.input-group').exists()) {
					$('form#' + form + ' input[name="' + k + '"], textarea[name="' + k + '"], select[name="' + k + '"]').parent().after('<span class="' + k + ' error-msg">' + v + '</span>');
				} else {
					$('form#' + form + ' input[name="' + k + '"], textarea[name="' + k + '"], select[name="' + k + '"]').after('<span class="' + k + ' error-msg">' + v + '</span>');
				}

				//$( 'form#addToDoForm input[name="'+k+'"], textarea[name="'+k+'"], select[name="'+k+'"]').parent().addClass('has-error');
				//$( 'form#addToDoForm input[name="'+k+'"], textarea[name="'+k+'"], select[name="'+k+'"]').parent().after( '<span class="'+k+'">Test</span>' );
				$('form#' + form + ' input[name="' + k + '"], textarea[name="' + k + '"], select[name="' + k + '"]').closest('.' + el).addClass('has-error');
			});
		},
		makeNotify: function (data) {
			$.toast({
				heading: data.notify.heading,
				text: data.notify.text,
				position: data.notify.position,
				loaderBg: data.notify.loaderBg,
				icon: data.notify.icon,
				hideAfter: 3500,
				stack: 6
			});

		}

	});




$.fn.exists = function () {
	return this.length !== 0;
}

$.fn.clean = function (form) {
	$(this)[0].reset();
	$(this).find('.error-msg').remove()
	$(this).find('.form-group').removeClass('has-error');
}

