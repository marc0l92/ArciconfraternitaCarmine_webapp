<form>
	<div class="form-group">
		<div class="row">
			<div class="col-lg-12">
				<label>Nome*</label>
				<input name="nome" type="text" class="form-control" placeholder="Nome della cappella" control="not_null">
			</div>
		</div>
		<div class="row no-margin">
			<div class="col-lg-12">
				<label>Note</label>
				<textarea name="note" class="form-control"></textarea>
			</div>
		</div>
		<div class="row space"></div>
		<div class="row">
			<div class="col-lg-6">
				<small class="pull-left">* Riempire i campi obbligatori</small>
			</div>
			<div class="col-lg-6">
				<button id="salva" type="button" class="btn btn-default pull-right" data-loading-text="Caricamento...">Salva</button>
			</div>
		</div>
	</div>
</form>
<?php 
//import della libreria input check
$this->load->view('parts/view_input_check_script.php'); 
?>
<script type="text/javascript">
$('#salva').click(function(){
	var btn = $(this);
	var ajax_data = get_ajax_data('body', input_check());
	if(ajax_data == false){
		return false;
	}
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('cappella_gentilizia/ajax_insert_cappella'); ?>",
		type: "POST",
		dataType: "json",
		data: ajax_data,
		success: function(msg) {
			my_alert('Cappella aggiunta [id: '+msg['last_insert_id']+']', 0);
            //reset della form
            $('input').val('');
            $('textarea').val('');
            btn.button('reset');
        },
        error : function (msg) {
        	my_alert('Cappella non aggiunta', 1);
        	ajax_error_show(msg);
        	btn.button('reset');
        }
    });
});

</script>