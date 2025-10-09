
function showpage(url,xyz){
	$("#"+xyz).html('<div style="text-align: center;"><i class="uk-icon-spinner uk-icon-medium uk-icon-spin"></i></div>');
	$.ajax({
	    url : url,
	    type: "get",
	    success: function (response) {
	       $("#"+xyz).html(response);
	    },
	    error: function(jqXHR, textStatus, errorThrown) {
	       console.log(textStatus, errorThrown);
	    }
	});
}

function run_save(form, url, id='update_results'){
	var ajax_config = {
        'form': "#"+form,
        'url' : url,
        'msg': "#"+id,
        'loading': 'Processing..........',
        func: function(data){
        	if(id != ""){
        		$("#"+id).html(data.message);
        	}

        }
    };
    ajax(ajax_config);
}

function _save(form, url, id, show){
	var old_text = $("#"+id).html();

	var ajax_config = {
        'form': "#"+form,
        'url' : url,
        'msg': "#"+id,
        'loading': '<div style="text-align: center;"><i class="uk-icon-spinner uk-icon-medium uk-icon-spin"></i></div>',
        func: function(data){
        	$("#"+id).html(old_text);
        	if(data.success != 1){
                UIkit.notify({
                    message : data.message,
                    status  : 'danger',
                    timeout : 10000,
                    pos     : show
                });
            }else{
                 UIkit.notify({
                    message : data.message,
                    status  : 'success',
                    timeout : 5000,
                    pos     : show
                });
            }
        }
    };
    ajax(ajax_config);
}

function goUrl(url){
	top.location.href = url;
}



//Common Ajax Function
function ajax_run(f,m){
    var ajax_config = {
        'form': "#"+f,
        'url' : "",
        'msg': "#"+m,
        'loading': 'Processing..........',
        func: function(data){
        	$("#"+m).html(data);
        }
    };
    ajax(ajax_config);
}

//Common Ajax Function 2
function run(f,u,m){
    var ajax_config = {
        'form': "#"+f,
        'url' : u,
        'msg': "#"+m,
        'loading': 'Processing..........',
        func: function(data){
        	$("#"+m).html(data);

        }
    };
    ajax(ajax_config);
}




//Common Ajax
function ajax(ajax_config){

	var form_id = ajax_config['form'];
	var form_url = ajax_config['url'];
	var msg_id = ajax_config['msg'];
	var loading_msg = ajax_config['loading'];

	$(form_id).submit(function(e)
	{
		$(msg_id).html(loading_msg);
		var postData = $(this).serializeArray();
		if(form_url != ""){
			var formURL = form_url;
		}else{
			var formURL = $(this).attr("action");
		}
		
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			dataType: 'json',
			success:function(data, textStatus, jqXHR) 
			{	
				ajax_config.func(data);	
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$(msg_id).html('<pre><code class="prettyprint">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</code></pre>');
			}
		});
	    e.preventDefault();	//STOP default action
	    $(this).unbind();
	});
		
	$(form_id).submit(); //SUBMIT FORM
}

