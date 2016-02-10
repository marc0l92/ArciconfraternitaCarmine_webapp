<script type="text/javascript">
$('input[name=codice_capofamiglia]').change(function(){
	if($(this).val() == 0 || !isNumber($(this).val())){
		$('#capofamiglia_name').html('');
		return;
	}
	$.ajax({
		url: "<?php echo site_url('persone/ajax_cerca_capofamiglia'); ?>",
		type: "POST",
		dataType: "json",
		data: "codice_capofamiglia="+$(this).val(),
		success: function(msg) {
			if((msg['nome_cognome']+'') != 'undefined'){
				$('#capofamiglia_name').html('('+msg['nome_cognome']+')');
				$('#capofamiglia_name').addClass('has-success');
				$('#capofamiglia_name').removeClass('has-error');
			}else{
				$('#capofamiglia_name').html('(Non trovato)');
				$('#capofamiglia_name').addClass('has-error');
				$('#capofamiglia_name').removeClass('has-success');
			}
		},
		error : function (msg) {
			$('#capofamiglia_name').html('Non trovato');
		}
	});
});
function isNumber(n) { return /^-?[\d.]+(?:e-?\d+)?$/.test(n); } 
</script>