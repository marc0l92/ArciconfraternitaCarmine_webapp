function isDate(txtDate){
	var currVal = txtDate;
	if(currVal == '')
		return false;

	//Declare Regex 
	var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
  	var dtArray = currVal.match(rxDatePattern); // is format OK?

  	if (dtArray == null)
  		return false;

	//Checks for mm/dd/yyyy format.
	// ################  formato data americano
	//dtMonth = dtArray[1];
	//dtDay = dtArray[3];
	//dtYear = dtArray[5];
	dtDay = dtArray[1];
	dtMonth = dtArray[3];
	dtYear = dtArray[5];

	if (dtMonth < 1 || dtMonth > 12)
		return false;
	else if (dtDay < 1 || dtDay> 31)
		return false;
	else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
		return false;
	else if (dtMonth == 2)
	{
		var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
		if (dtDay> 29 || (dtDay ==29 && !isleap))
			return false;
	}
	return true;
}

function my_alert(messaggio, tipo){
	if(tipo == 1){
		//errore
		$('#alert').html('<div id="alert-content" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>ERRORE:</strong> '+messaggio+'</div>');
	}else{
		if(tipo == 2){
			//info
			$('#alert').html('<div id="alert-content" class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>'+messaggio+'</div>');
		}else{
			//success
			$('#alert').html('<div id="alert-content" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+messaggio+'</div>');
		}
	}
	$('#alert-content').delay('3000').hide('400');
}

function ajax_error_show(msg){
	if(msg['responseText'].trim() != ''){
		$('#ajax_error_content').html(msg['responseText']);
		$('#modal-ajax_error').modal('show');
	} else {
		$('#ajax_error_content').html(msg);
		//$('#modal-ajax_error').modal('show');
	}
}

function get_ajax_data(content, return_value){
	//input_check<?php echo $randomId; ?>()
	if(return_value != true){
		return false;
	}
	var btn = $(this);
	var ajax_data = '';
	$(content).find('input, textarea, select, radio, check').each(function(){
		var name = $(this).attr('name');
		var type = $(this).attr('type');
		var val = '';
		
		// non considero i radio non checked
		if(type == 'radio' && $(this).prop('checked') == false){
			name = '';
		}
		
		if(type == 'checkbox'){
			val = $(this).prop('checked');
		}else{
			if(type == 'radio'){
				val = $(this).attr('value1');
			}else{
				val = $(this).val();
			}
		}
		
		
		if(typeof name != 'undefined' && name != ''){
			ajax_data += '&'+name+'='+val;
		}
	});
	ajax_data = ajax_data.substr(1);
	return ajax_data;
}

function loadContent(url, content) {
	$(content).html("<p>Caricamento...</p>");
	//my_alert("Caricamento...", 2);
	$.ajax({url: url}).done(function(msg){
		$(content).html(msg);
	});
}

function my_datapicker(content){
	$(content).datepicker({
		format: 'dd/mm/yyyy',
		todayBtn: 'linked',
		weekStart: 1,
		startView: 2,
		language: 'it',
		autoclose: true,
		todayHighlight: true
	});
}