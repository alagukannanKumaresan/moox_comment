$(document).ready(function(){	
	
	mooxCommentInitClearButtons();	
	mooxCommentInitClientValidation();
	mooxCommentPi2InitList();
	
	$('.tx-moox-comment-pi2 form.ajax').on('submit', function(event) {
	
		event.preventDefault();
		
		var url = $(this).attr("action") + "&type=985492";
		var wrapper = mooxCommentPi2GetWrapper($(this));
		var settings = $("#"+wrapper).data('settings');
		var form = "#" + $(this).attr("id");
		var name = $(this).attr("name");
		
		if(mooxCommentValidate($(this))){
		
			var data = { };
			
			$(form + ' input').each(function() {
				if($(this).attr("type")=="radio"){				
					data[$(this).attr('name')] = value = $('input[name="'+$(this).attr('name')+'"]:checked').first().val();	
				} else {
					data[$(this).attr('name')] = $(this).val();		
				}
			});
			
			$(form + ' textarea').each(function() {
				data[$(this).attr('name')] = $(this).val();		
			});
						
			data['tx_mooxcomment_pi2[settings]'] = settings;
			
			$.ajax(url, {
				method: "POST",
				data: data,
				success: function(data) {
					
					if($(data).find(".message-error").length>0){
					
						$(data).prependTo("#"+wrapper);
						
					} else {
						
						if($(data).find(".message-ok").length>0){
							$(data).prependTo("#"+wrapper);
						}
						
						$(form).trigger("reset");
						if(name=="rate"){
							$('form[name="rate"]').remove();
						}
						mooxCommentPi2Refresh(wrapper,1);
					}
					
				},
				error: function() {
					message = 'Error';
					mooxCommentAddMessage('',message,'error','glyphicon-warning-sign',wrapper)
					$(form).trigger("reset");
				}
			});
			
		}
		
		return false;
	});
});

function mooxCommentPi2Refresh (wrapper,added,limit) {		
	
	var settings = $("#"+wrapper).data('settings');
	var body = $("#"+wrapper+" .moox-ajax-body").first();	
	
	url = body.data("url");
		
	var data = { };	
	data['tx_mooxcomment_pi2[settings]'] = settings;	
	
	$.ajax(url, {
		method: "POST",
		data: data,
		success: function(data) {
			
			body.html($(data));
						
			//mooxCommentPi2InitList();
		}
	});
}

function mooxCommentPi2GetWrapper (object) {
	return object.parents('.tx-moox-comment-pi2').attr("id");
}

function mooxCommentPi2InitList (){

	$(document).on('click', '.tx-moox-comment-pi2 .btn-modal', function(event) {
	
		event.preventDefault();
		
		wrapper = mooxCommentPi2GetWrapper($(this));
		
		href = $(this).attr("href");
		if($(this).data("url")){
			href = $(this).data("url");
		}
		css = $(this).data("css");
		id = $(this).attr("id");	
		title = $(this).data("title");
		body = $(this).data("body");
		button = $(this).data("button");
		close = $(this).data("close");
		
		mooxCommentShowModal(wrapper,id,href,css,title,body,button,close);
		
	});	
}
