function mooxCommentInitClearButtons(){
	$('.tx-moox-comment .has-clear input, .has-clear textarea').on('keyup', function() {
        if ($(this).val() == '') {
            $(this).parents('.form-group').addClass('has-empty-value');
        } else {
            $(this).parents('.form-group').removeClass('has-empty-value');
        }		
    }).trigger('change');

    $('.tx-moox-comment .has-clear .form-control-clear').on('click', function() {
        var input = $(this).parents('.form-group').find('input,textarea');
		input.val('').trigger('change');
		input.val('').trigger('keyup');

        // Trigger a "cleared" event on the input for extensibility purpose
        input.trigger('cleared');
    });	
	
	$('.tx-moox-comment .has-clear input, .has-clear textarea').each(function() {
        if ($(this).val() == '') {
            $(this).parents('.form-group').addClass('has-empty-value');
        } else {
            $(this).parents('.form-group').removeClass('has-empty-value');
        }		
    });
}

function mooxCommentInitClientValidation(){	
	$('.tx-moox-comment form.client-validation,.tx-moox-comment form.ajax').on('submit', function() {        
		return mooxCommentValidate($(this));				
    });
}

function mooxCommentClearErrors(plugin){
	$('#'+plugin+' form.client-validation,#'+plugin+' form.ajax').find("input.validate,textarea.validate,select.validate,div.radios.validate").each(function(){
		tagname = $(this).prop("tagName").toLowerCase();
		if(tagname=="div" && $(this).hasClass("radios")){
			$(this).removeClass('has-error');				
		} else {
			$(this).parents('.form-group').removeClass('has-error');
		}
		
	});
	if ( $("#"+plugin+" .typo3-messages").length ) {
		$("#"+plugin+" .typo3-messages").remove();
	}
}
function mooxCommentAddMessage(title,text,type,icon,plugin){
	if ( !$("#"+plugin+" .typo3-messages").length ) {
		if(title!=""){
			$('<div class="typo3-messages"><div class="alert alert-'+type+'"><div class="media"><div class="media-body"><h4 class="alert-title"><span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>'+title+': </h4><div class="alert-message">'+text+'</div></div></div>').prependTo("#"+plugin);
		} else {
			$('<div class="typo3-messages"><div class="alert alert-'+type+'"><div class="media"><div class="media-body"><div class="alert-message">'+text+'</div></div></div>').prependTo("#"+plugin);
		}
	} else {
		if(title!=""){
			$('<div class="alert alert-'+type+'"><div class="media"><div class="media-body"><h4 class="alert-title"><span class="glyphicon '+icon+' icon-alert" aria-hidden="true"></span>'+title+': </h4><div class="alert-message">'+text+'</div></div>').appendTo("#"+plugin+" .typo3-messages");
		} else {
			$('<div class="alert alert-'+type+'"><div class="media"><div class="media-body"><div class="alert-message">'+text+'</div></div>').appendTo("#"+plugin+" .typo3-messages");
		}
	}
}
function mooxCommentAddMessageNoBootstrap(title,text,type,icon,plugin){
	if ( !$("#"+plugin+" .typo3-messages").length ) {
		if(title!=""){
			$('<div class="typo3-messages"><div class="typo3-message message-'+type+'"><div class="message-header"><span class="glyphicon '+icon+' icon-alert" aria-hidden="true"></span>'+title+':</div><div class="message-body">'+text+'</div></div></div>').prependTo("#"+plugin);
		} else {
			$('<div class="typo3-messages"><div class="typo3-message message-'+type+'"><div class="message-header"><span class="glyphicon '+icon+' icon-alert" aria-hidden="true"></span></div><div class="message-body">'+text+'</div></div></div>').prependTo("#"+plugin);
		}
	} else {
		if(title!=""){
			$('<div class="typo3-message message-'+type+'"><div class="message-header"><span class="glyphicon '+icon+' icon-alert" aria-hidden="true"></span>'+title+':</div><div class="message-body">'+text+'</div></div>').appendTo("#"+plugin+" .typo3-messages");
		} else {
			$('<div class="typo3-message message-'+type+'"><div class="message-header"><span class="glyphicon '+icon+' icon-alert" aria-hidden="true"></span></div><div class="message-body">'+text+'</div></div>').appendTo("#"+plugin+" .typo3-messages");
		}
	}
}
function mooxCommentCheckEmail(email) {
	var filter = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;
	if (!filter.test(email)) {
		return false;
	} else {
		return true;
	}
}
function mooxCommentValidate(form){
	var plugin = form.parents('.tx-moox-comment').first().attr('id');
	var validated = true;
	
	if($("#"+plugin+" #client-validation").val()!="disabled"){
		
		$("#"+plugin+" #client-validation").remove();
		
		mooxCommentClearErrors(plugin);
		
		$('#'+plugin+' form.client-validation,#'+plugin+' form.ajax').find("input.validate,textarea.validate,select.validate,div.radios.validate").each(function(){
			
			error = false;
			
			var value = $(this).val();
			tagname = $(this).prop("tagName").toLowerCase();
			
			if(tagname=="div" && $(this).hasClass("radios")){
				name = $(this).data("name");
				type = "radios";				
			} else {
				type = $(this).attr("type");
				name = $(this).attr("name");
			}
			id = $(this).data("id");
			label = $(this).data("label");
			required = $(this).data("required");
			validator = $(this).data("validator");
			minlength = $(this).data("minlength");
			maxlength = $(this).data("maxlength");
			minitems = $(this).data("minitems");
			maxitems = $(this).data("maxitems");
			maxfilesize = $(this).data("maxfilesize");
			accept = $(this).attr("accept");
			limitlow = $(this).data("limit-low");
			limithigh = $(this).data("limit-high");
			
			if(type=="checkbox"){
				value = '';
				$('#'+plugin+' input[name="'+name+'"]:checked').each(function(){
					if(value!=''){
						value = value+','+$(this).val();
					} else {
						value = $(this).val();
					}
				});					
			}
			
			if(type=="radios"){
				if($('#'+plugin+' input[name="'+name+'"]:checked').length>0){
					value = $('#'+plugin+' input[name="'+name+'"]:checked').first().val();
				} else {
					value = '';
				}
			}
			
			if(type=="radio"){
				if($('#'+plugin+' input[name="'+name+'"]:checked').length>0){
					value = $('#'+plugin+' input[name="'+name+'"]:checked').first().val();
				} else {
					value = '';
				}
			}
			
			if((tagname!='select' && value!='' ) || (tagname=='select' && value!='' && value!='0')){
				if(!error && minlength && value.length<minlength){										
					message = mooxCommentLang['de'].errors.too_short.replace("%1",minlength);
					mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin)					
					error = true;
				}
				if(!error && maxlength && value.length>maxlength){										
					message = mooxCommentLang['de'].errors.too_long.replace("%1",maxlength);
					mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin)					
					error = true;
				}
				if(!error && limitlow && value<limitlow){										
					message = mooxCommentLang['de'].errors.too_small.replace("%1",limitlow);
					mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin)					
					error = true;
				}
				if(!error && limithigh && value>limithigh){										
					message = mooxCommentLang['de'].errors.too_large.replace("%1",limithigh);
					mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin)					
					error = true;
				}
				if(!error && validator=='email' && !mooxCommentCheckEmail(value)){
					message = mooxCommentLang['de'].errors.invalid_email;
					mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin)					
					error = true;
				}
				if(!error && validator=='password'){
					repetition = name.replace(id,id+"_repeat");
					if($('input[name="'+repetition+'"]').length){
						if(value!=$('input[name="'+repetition+'"]').val()){
							message = mooxCommentLang['de'].errors.password_not_equal;
							mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin)					
							error = true;
						}
					}
				}
				if(!error && validator=='file' && maxitems>0){
					cnt = $("."+id+"_files").length;
					if(cnt==maxitems){
						message = mooxCommentLang['de'].errors.too_many.replace("%1",maxitems);
						mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin);
						error = true;
					}
				}					
				if(!error && validator=='file' && minitems>0){						
					cnt = $("."+id+"_files").length;
					if((cnt+1)<minitems){
						message = mooxCommentLang['de'].errors.too_few.replace("%1",minitems);
						mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin);
						error = true;
					}						
				}					
				if(!error && validator=='tree' && maxitems>0){
					items = value.split(",");
					cnt = items.length;
					if(cnt>maxitems){
						message = mooxCommentLang['de'].errors.too_many.replace("%1",maxitems);
						mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin);
						error = true;
					}
				}					
				if(!error && validator=='tree' && minitems>0){						
					items = value.split(",");
					cnt = items.length;
					if(cnt<minitems){
						message = mooxCommentLang['de'].errors.too_few.replace("%1",minitems);
						mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin);
						error = true;
					}						
				}
			} else {					
				if(validator!='file' && validator!='tree' && required){
					message = mooxCommentLang['de'].errors.empty;
					mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin)					
					error = true;
				} else {
					
					if(validator=='file' && (required || (minitems && minitems>0))){							
						if(!minitems || minitems<1){
							minitems = 1;
						}
						cnt = $("."+id+"_files").length;
						if(cnt<minitems){
							if(minitems==1){
								message = mooxCommentLang['de'].errors.no_file_selected;
							} else {
								message = mooxCommentLang['de'].errors.too_few.replace("%1",minitems);
							}
							mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin);
							error = true;
						}	
					}						
					if(validator=='tree' && (required || (minitems && minitems>0))){							
						if(!minitems || minitems<1){
							minitems = 1;
						}
						if(value==""){
							cnt = 0;
						} else {
							items = value.split(",");
							cnt = items.length;
						}
						if(cnt<minitems){
							if(minitems==1){
								message = mooxCommentLang['de'].errors.no_item_selected;
							} else {
								message = mooxCommentLang['de'].errors.too_few.replace("%1",minitems);
							}
							mooxCommentAddMessage(label,message,'danger','glyphicon-warning-sign',plugin);
							error = true;
						}	
					}
				}
			}
			if(error){
				if(type=="radios"){					
					$(this).addClass('has-error');
				} else {
					$(this).parents('.form-group').addClass('has-error');
				}
				validated = false;
			}
			
		});
		
		if(!validated){
			$("html,body").animate({ scrollTop: ($('#'+plugin+' .typo3-messages').first().offset().top)-60 }, "fast");		
		}
	}
	
	return validated;
}
function mooxCommentShowModal(wrapper,id,href,css,title,body,button,close) {
	$(	'<div class="modal fade" id="'+id+'-modal" tabindex="-1" role="dialog" aria-labelledby="'+title+'">'+
			'<div class="modal-dialog" role="document">'+
				'<div class="modal-content">'+
					'<div class="modal-header">'+
						'<button type="button" class="close" data-dismiss="modal" aria-label="'+close+'"><span aria-hidden="true">&times;</span></button>'+
						'<h4 class="modal-title">'+title+'</h4>'+
					'</div>'+
					'<div class="modal-body">'+body+'</div>'+
					'<div class="modal-footer">'+
						'<button type="button" class="btn btn-default" data-dismiss="modal">'+close+'</button>'+
						'<a href="'+href+'" class="'+css+'" data-dialog="'+id+'-modal" title="'+button+'"><button type="button" class="btn btn-primary">'+button+'</button></a>'+
					'</div>'+
				'</div>'+
			'</div>'+
		'</div>').prependTo("#"+wrapper);	
	$('#'+id+'-modal').modal();	
}