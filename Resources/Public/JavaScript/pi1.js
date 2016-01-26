$(document).ready(function(){	
	
	mooxCommentInitClearButtons();	
	mooxCommentInitClientValidation();
	mooxCommentPi1InitList();
	
	$('.tx-moox-comment-pi1 form.ajax').on('submit', function(event) {
	
		event.preventDefault();
		
		var url = $(this).attr("action") + "&type=985491";
		var wrapper = mooxCommentPi1GetWrapper($(this));
		var settings = $("#"+wrapper).data('settings');
		var form = "#" + $(this).attr("id");
		
		if(mooxCommentValidate($(this))){
		
			var data = { };
			
			$(form + ' input').each(function() {
				data[$(this).attr('name')] = $(this).val();		
			});
			
			$(form + ' textarea').each(function() {
				data[$(this).attr('name')] = $(this).val();		
			});
						
			data['tx_mooxcomment_pi1[settings]'] = settings;
			
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
						
						//message = mooxCommentLang['de'].messages.comment_saved;
						//mooxCommentAddMessage('',message,'ok','glyphicon-ok',wrapper)
						$(form).trigger("reset");
						mooxCommentPi1Refresh(wrapper,1);
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

	$('.tx-moox-comment-pi1 ul.fe-user-selector li a').on('click', function(event) {
		
		event.preventDefault();
		
		wrapper = mooxCommentPi1GetWrapper($(this));
		body = $("#"+wrapper+" .moox-ajax-body").first();
		
		body.removeAttr("data-fe-user");
		body.attr("data-fe-user",$(this).data("fe-user"));
		body.data("fe-user",$(this).data("fe-user"));	
		
		$(this).parents(".btn-group").find("button span.current-fe-user").text($(this).text());
		
		mooxCommentPi1Refresh(wrapper);
		
	});

	$('.tx-moox-comment-pi1 ul.order-selector li a').on('click', function(event) {
		
		event.preventDefault();
		
		wrapper = mooxCommentPi1GetWrapper($(this));
		body = $("#"+wrapper+" .moox-ajax-body").first();
		
		body.removeAttr("data-order-by");
		body.attr("data-order-by",$(this).data("order-by"));
		body.data("order-by",$(this).data("order-by"));
		body.removeAttr("data-order-direction");	
		body.attr("data-order-direction",$(this).data("order-direction"));
		body.data("order-direction",$(this).data("order-direction"));
		
		$(this).parents(".btn-group").find("button span.current-order").text($(this).text());
		
		count = body.find(".moox-ajax-item").length;
		
		mooxCommentPi1Refresh(wrapper);
		
	});

	$('.tx-moox-comment-pi1 .btn-append').on('click', function(event) {
		
		event.preventDefault();
		
		wrapper = mooxCommentPi1GetWrapper($(this));
		append = $(this).data("append");
		
		mooxCommentPi1Append(wrapper,append);
	});

	$('.tx-moox-comment-pi1 .btn-refresh').on('click', function(event) {
		
		event.preventDefault();
		
		wrapper = mooxCommentPi1GetWrapper($(this));
		body = $("#"+wrapper+" .moox-ajax-body").first();
		limit = $(this).data("limit");
		
		if(limit=='all'){
			$(this).parents(".btn-group").find("button span.moox-ajax-limit").text(body.data('count'));		
		} else {
			$(this).parents(".btn-group").find("button span.moox-ajax-limit").text(limit);
		}
		
		mooxCommentPi1Refresh(wrapper,false,limit);
	});
	
});

function mooxCommentPi1Refresh (wrapper,added,limit) {		
	
	var settings = $("#"+wrapper).data('settings');
	var body = $("#"+wrapper+" .moox-ajax-body").first();	
	if(limit=='all'){
		var count = body.data('count');
	} else if(limit>0){
		var count = limit;
	} else {
		var count = body.find(".moox-ajax-item").length;
	}
	var max = body.data("count");
	
	url = body.data("url");
	fe_user = body.data("fe-user");
	var order_by = body.data("order-by");
	var order_direction = body.data("order-direction");
	
	if(added && (order_by=='crdate' && order_direction=='DESC') ){
		count = count+1;
	}
	
	var data = { };	
	if(fe_user!='all' && fe_user>0){
		data['tx_mooxcomment_pi1[filter][feUser]'] = fe_user;
	}
	data['tx_mooxcomment_pi1[limit]'] = count;
	data['tx_mooxcomment_pi1[orderings]['+order_by+']'] = order_direction;
	data['tx_mooxcomment_pi1[settings]'] = settings;	
	
	$.ajax(url, {
		method: "POST",
		data: data,
		success: function(data) {
			
			max = $(data).filter("#moox-ajax-body-count").first();
			
			count_before = body.find(".moox-ajax-item").length;
			
			body.html($(data));
			
			count_after = body.find(".moox-ajax-item").length;
			
			if(max){
				max = max.val();
				body.removeAttr("data-count");
				body.attr("data-count",max);
				body.data("count",max);
				body.find("#moox-ajax-body-count").first().remove();
				if(!added){
					$("button span.moox-ajax-count").text(max);
					limit = parseInt($("button span.moox-ajax-limit").first().text());
					if(limit>max){
						$("button span.moox-ajax-limit").text(max);
					}
				}
			}
			
			if(added){
				if(order_by=='crdate' && order_direction=='DESC'){
					body.removeAttr("data-count");
					body.attr("data-count",body.data("count")+1);
					body.data("count",body.data("count")+1);
				}
				if(count_after>count_before){
					$("button span.moox-ajax-count").text(parseInt($("button span.moox-ajax-count").first().text())+1);
					$("button span.moox-ajax-limit").text(parseInt($("button span.moox-ajax-limit").first().text())+1);
				}				
			}
			
			if((count)<max){
				$("#"+wrapper+" .btn-append").show();
			} else {
				if(max==0){
					$("#"+wrapper+" .btn-append").hide();
				}
			}
			
			//mooxCommentPi1InitList();
		}
	});
}

function mooxCommentPi1Append (wrapper,append) {	
	
	var settings = $("#"+wrapper).data('settings');
	var body = $("#"+wrapper+" .moox-ajax-body").first();
	var max = body.data("count");
		
	url = body.data("url");	
	fe_user = body.data("fe-user");
	order_by = body.data("order-by");
	order_direction = body.data("order-direction");
	offset = body.find(".moox-ajax-item").length;

	var data = { };	
	if(fe_user!='all' && fe_user>0){
		data['tx_mooxcomment_pi1[filter][feUser]'] = fe_user;
	}
	data['tx_mooxcomment_pi1[offset]'] = offset;	
	data['tx_mooxcomment_pi1[orderings]['+order_by+']'] = order_direction;
	data['tx_mooxcomment_pi1[settings]'] = settings;
	
	$.ajax(url, {
		method: "POST",
		data: data,
		success: function(data) {
											
			body.append($(data));
			count = body.find(".moox-ajax-item").length;
			
			if($(data).filter(".moox-ajax-item").lenght<append || count>=max){
				$("#"+wrapper+" .btn-append").hide();
			}
			
			//mooxCommentPi1InitList();
			
		}
	});
	
}

function mooxCommentPi1GetWrapper (object) {
	return object.parents('.tx-moox-comment-pi1').attr("id");
}

function mooxCommentPi1InitList (){

	$(document).on('click', '.tx-moox-comment-pi1 .btn-modal', function(event) {
	
		event.preventDefault();
		
		wrapper = mooxCommentPi1GetWrapper($(this));
		
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
	
	$(document).on('click', '.tx-moox-comment-pi1 .btn-delete.ajax', function(event) {
	
		event.preventDefault();
		
		var wrapper = mooxCommentPi1GetWrapper($(this));
		var settings = $("#"+wrapper).data('settings');
		
		url = $(this).attr("href");
		dialog = $(this).data("dialog");
		
		var data = { };			
		data['tx_mooxcomment_pi1[settings]'] = settings;
		data['tx_mooxcomment_pi1[ajax]'] = 1;		
		
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
						
					mooxCommentPi1Refresh(wrapper);
				}

				$("html,body").animate({ scrollTop: ($('#'+wrapper+' .typo3-messages').first().offset().top)-60 }, "fast");
			}
		});
		
		if(dialog!=''){
			$('#'+dialog).modal('hide')
		}
	});
	
	$(document).on('click', '.tx-moox-comment-pi1 .btn-confirm.ajax', function(event) {
		
		event.preventDefault();
		
		var wrapper = mooxCommentPi1GetWrapper($(this));
		var settings = $("#"+wrapper).data('settings');
		
		url = $(this).attr("href");
		dialog = $(this).data("dialog");
		
		var data = { };			
		data['tx_mooxcomment_pi1[settings]'] = settings;
		data['tx_mooxcomment_pi1[ajax]'] = 1;		
		
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
						
					mooxCommentPi1Refresh(wrapper);
				}

				$("html,body").animate({ scrollTop: ($('#'+wrapper+' .typo3-messages').first().offset().top)-60 }, "fast");
			}
		});
		
		if(dialog!=''){
			$('#'+dialog).modal('hide')
		}
	});
}
