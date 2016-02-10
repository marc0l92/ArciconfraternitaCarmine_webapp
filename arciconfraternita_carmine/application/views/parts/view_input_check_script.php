<?php
if(!isset($content)) $content = 'body';
if(!isset($id_input_check)) $id_input_check = '';
?>
<script type="text/javascript">
	function input_check<?php echo $id_input_check; ?>(){
		var return_value = '';
		$('<?php echo $content; ?>').find('input').removeClass('required');
		$('<?php echo $content; ?>').find('input').each(function(){
			var control = $(this).attr('control');
			var text = $(this).val();
			var name = $(this).attr('name');
		//non controllare gli imput nascosti
		if($(this).is(':hidden')){
			return;
		}
		//controllo not_null
		if(typeof control != 'undefined' && control.indexOf('not_null') >= 0 && (text == '')){
			$(this).addClass('required');
			return_value += '<br>- Riempire i campi obbligatori ['+name+']';
		}
		//controllo date
		if(typeof control != 'undefined' && control.indexOf('date') >= 0 && text != "" && isDate(text) == false){
			$(this).addClass('required');
			return_value += '<br>- Formato data non valido ['+name+']';
		}
		//controllo number
		if(typeof control != 'undefined' && control.indexOf('number') >= 0){
			text = text.replace(",",".");
			$(this).val(text);
			if(text != "" && !isNumber(text)){
				$(this).addClass('required');
				return_value += '<br>- Questo capo deve contenere un numero ['+name+']';
			}
		}
	});
		if(return_value != ''){
			my_alert(return_value, 1);
		}else{
			return_value = true;
		}
		return return_value;
	}

	function isNumber(n) {
		return /^-?[\d.]+(?:e-?\d+)?$/.test(n);
	} 
</script>