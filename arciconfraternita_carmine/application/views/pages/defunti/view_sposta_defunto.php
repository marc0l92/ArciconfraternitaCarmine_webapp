<div class="row">
	<div class="col-lg-12">
		<?php 
		$this->load->view('parts/view_modal_defunto.php');
		$this->load->view('parts/view_input_defunto.php', array('requested' => true, 'default_id_value' => '', 'crea_nuovo_btn' => false, 'name' => 'defunto'));
		?>
	</div>
</div>
<div class="row">
	<div class="col-lg-5">
		<label>Data trasferimento*</label>
		<input name="data_trasferimento" type="text" class="form-control" placeholder="Data del cambio di sepoltura" control="not_null date">
	</div>
	<div class="col-lg-7">
		<?php $this->load->view('parts/view_modal_celletta.php', array('tipo_ricerca' => 'acquistate', 'label_celletta' => 'Celletta destinazione', 'id_acquirente_position' => 'input[name=id-defunto0]')); ?>
	</div>
</div>
<div class="row space"></div>
<div class="row">
	<div class="col-lg-6">
		<small class="pull-left">* Riempire i campi obbligatori</small>
	</div>
	<div class="col-lg-6">
		<button id="salva_spostamento" type="button" class="btn btn-default pull-right" data-loading-text="Caricamento...">Salva</button>
	</div>
</div>
<script type="text/javascript">
$('#salva_spostamento').click(function(){
	var btn = $(this);
	var defunto = $('input[name=id-defunto0]').val();
	if(defunto == ""){
		$('input[name=id-defunto0]').focus();
		my_alert('Riempire i campi obbligatori', 1);
		return false;
	}
	var celletta_destinazione = $('input[name=id_celletta0]').val();
	var data_trasferimento = $('input[name=data_trasferimento]').val();
	if(data_trasferimento == "" || isDate(data_trasferimento) == false){
		$('input[name=data_trasferimento]').focus();
		my_alert('Data non valida', 1);
		return false;
	}
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('defunti/ajax_sposta_defunto'); ?>",
		type: "POST",
		dataType: "json",
		data: "defunto="+defunto+'&celletta_destinazione='+celletta_destinazione+'&data_trasferimento='+data_trasferimento,
		success: function(msg) {
			my_alert('Defunto spostato', 0);
            //reset della form
            $('input').val('');
            btn.button('reset');
        },
        error : function (msg) {
        	my_alert('Defunto non spostato', 1);
        	ajax_error_show(msg);
        	btn.button('reset');
        }
    });
});

my_datapicker('input[name=data_trasferimento]');
</script>