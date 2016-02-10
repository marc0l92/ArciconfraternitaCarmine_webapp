<div class="modal fade" id="modal-update">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img name="loading" class="hidden" src="<?php echo base_url().'/resources/images/loading.gif'; ?>">Aggiornamento in corso...</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-8">
						File: <span name="current_file">...</span>
					</div>
					<div class="col-lg-4">
						<p class="pull-right"><span name="current_progress">0</span>/<?php echo $number; ?></p>
					</div>
				</div>
				<div class="progress">
					<div id="progress_bar" class="progress-bar" style="width: 0%;">
						<span name="progress_bar-content" class="sr-only">0%</span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="inizia" type="button" class="btn btn-default">Inizia</button>
				<button id="annulla" type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
$('#inizia').click(function(){
	$('#inizia').attr('disabled', 'disabled');
	$('#annulla').attr('disabled', 'disabled');
	$('img[name=loading]').removeClass('hidden');
	$('span[name=current_file]').html("<?php echo $first_file; ?>");
	progress_next(0, <?php echo $number; ?>);
});

function progress_next(index, final_index){
	$('span[name=current_progress]').html(index);
	$('#progress_bar').css('width', (index/final_index*100)+'%');
	$('span[name=progress_bar-content]').html((index/final_index*100)+'%');
	if(index < final_index){
		$.ajax({
			url: "<?php echo site_url('home/ajax_save_update'); ?>",
			type: "POST",
			dataType: "json",
			data: 'index='+index,
			success: function(msg) {
				$('span[name=current_file]').html(msg['next_file']);
				progress_next(index+1, final_index);
			},
			error : function (msg) {
				my_alert('Errore durante l\'aggiornamento', 1);
				ajax_error_show(msg);
			}
		});
	}else{
		//aggiornamento finito
		$('#inizia').addClass('hidden');
		$('#annulla').removeAttr('disabled');
		$('#annulla').html('Fine');
		$('img[name=loading]').addClass('hidden');
		$('#alert-update').addClass('alert-success');
		$('#alert-update').html('<span class="ui-icon ui-icon-check pull-left"></span>Non ci sono nuovi aggiornamenti, si dispone dell\'ultima versione.');
		$.ajax({
			url: "<?php echo site_url('home/ajax_version_update'); ?>",
			error : function (msg) {
				my_alert('Errore: aggiornameno versione fallito', 1);
				ajax_error_show(msg);
			}
		});
	}
}
</script>