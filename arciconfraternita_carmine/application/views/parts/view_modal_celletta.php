<?php
if(!isset($id)) $id = 0;
if(!isset($tipo_ricerca)) $tipo_ricerca = 'acquistate'; //libere, occupate, acquistate
if(!isset($label_celletta)) $label_celletta = "Celletta";
if(!isset($id_acquirente_position)) $id_acquirente_position = "input[name=id_persona]";
?>

<!-- INPUT-->
<label><?php echo $label_celletta; ?></label>
<div class="input-group">
	<input name="celletta_description<?php echo $id?>" type="text" class="form-control" placeholder="Celletta, sepoltura o pilone" disabled="">
	<button type="button" class="close svuota" name="svuota<?php echo $id?>">&times;</button>
	<span class="input-group-btn">
		<button href="#modal-celletta-<?php echo $id?>" data-toggle="modal" class="btn btn-default" type="button" name="cerca">Cerca</button>
	</span>					
</div>
<input name="id_celletta<?php echo $id?>" type="text" class="form-control hidden" value="0">

<!-- MODAL CERCA -->
<div class="modal fade" id="modal-celletta-<?php echo $id?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Scegli celletta</h4>
			</div>
			<div class="modal-body">
				<form>
					<div class="row" id="riga_di_ricerca">
						<div class="col-lg-2">
							<?php $this->load->view('parts/view_select_cappella.php', array('name' => 'id_cappella', 'popover' => 'top')); ?>
						</div>
						<div class="col-lg-2">
							<label>Piano</label>
							<input name="piano" type="text" class="form-control" placeholder="N. Piano">
						</div>
						<div class="col-lg-2">
							<label>Sezione</label>
							<input name="sezione" type="text" class="form-control" placeholder="N. Sezione">
						</div>
						<div class="col-lg-2">
							<label>Fila</label>
							<input name="fila" type="text" class="form-control" placeholder="N. Fila">
						</div>
						<div class="col-lg-2">
							<label>Numero</label>
							<input name="numero" type="text" class="form-control" placeholder="Numero">
						</div>
						<div class="col-lg-2">
							<button id="cerca_celletta-<?php echo $id?>" type="submit" class="btn btn-default second-row" data-loading-text="Caricamento...">Cerca</button>
						</div>
					</div>
				</form>
				<div class="row space"></div>
				<div class="row">
					<div class="col-lg-12" id="query_cellette-<?php echo $id?>">
						Nessuna celletta già acquistata.
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="pull-left" style="text-align: left;">
					<?php if($tipo_ricerca == 'libere'){ ?>
					<span class="has-error">
						<label class="control-label">*Cellette occupate</label>
					</span>
					<?php }else{ ?>
					<?php if($tipo_ricerca == 'occupate'){ ?>
					<span class="has-error">
						<label class="control-label">*Cellette libere</label>
					</span>
					<?php }else{ ?>
					<?php if($tipo_ricerca == 'acquistate'){ ?>
					<span class="has-success">
						<label class="control-label">*Cellette acquistate</label>
					</span>
					<span class="has-error">
						<label class="control-label">*Cellette occupate o acquistate da altri</label>
					</span>
					<?php } ?>
					<?php } ?>
					<?php } ?>
				</div>
				<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
				<button id="scegli-<?php echo $id?>" type="button" class="btn btn-default">Scegli</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
$('#cerca_celletta-<?php echo $id?>').click(function(){
	var btn = $(this);
	btn.button('loading');
	var id_cappella = $('select[name=id_cappella]').val();
	var piano = $('input[name=piano]').val();
	var sezione = $('input[name=sezione]').val();
	var fila = $('input[name=fila]').val();
	var numero = $('input[name=numero]').val();
	var id_acquirente = $('<?php echo $id_acquirente_position; ?>').val();
	var ajax_data = 'id_cappella='+id_cappella+'&piano='+piano+'&sezione='+sezione+'&fila='+fila+'&numero='+numero+'&id_acquirente='+id_acquirente+'&tipo_ricerca=<?php echo $tipo_ricerca; ?>&modal_id=<?php echo $id?>';
	$.ajax({
		url: "<?php echo site_url('cappella_gentilizia/ajax_cerca_celletta'); ?>",
		type: "POST",
		dataType: "json",
		data: ajax_data,
		success: function(msg) {
			my_alert('Ricerca effettuata', 0);
			$('#query_cellette-<?php echo $id?>').html(msg['result']);
			$('#query_cellette-<?php echo $id?>').find('tr').click(function() {
				$('#modal-celletta-<?php echo $id; ?>').animate({
					scrollTop: $("#scegli-<?php echo $id; ?>").offset().top
				}, 2000);
			});
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Ricerca fallita', 1);
			btn.button('reset');
			ajax_error_show(msg);
		}
	});
	return false; // in modo da bloccare il submit della form
});

$('#scegli-<?php echo $id?>').click(function(){
	var scelta = $('input[name=celletta_scelta]:checked').val();
	//controllo se è stato scelto qualcuno
	if(typeof scelta === "undefinied"){
		return;
	}
	$('input[name=celletta_description<?php echo $id?>]').val($('#query-cellette-tipo-'+scelta).html()+' ['+$('#query-cellette-cappella-'+scelta).html()+', '+$('#query-cellette-piano-'+scelta).html()+', '+$('#query-cellette-sezione-'+scelta).html()+', '+$('#query-cellette-fila-'+scelta).html()+', '+$('#query-cellette-numero-'+scelta).html()+']');
	$('input[name=id_celletta<?php echo $id?>]').val(scelta);
	$('#modal-celletta-<?php echo $id?>').modal('hide');
	modal_celletta_reset<?php echo $id?>();
});

$('button[name=svuota<?php echo $id?>]').click(function(){
	$('input[name=celletta_description<?php echo $id?>]').val('');
	$('input[name=id_celletta<?php echo $id?>]').val('0');
});

function modal_celletta_reset<?php echo $id?>(){
	$('#query_cellette-<?php echo $id?>').html('Nessuna celletta già acquistata.');
	$('#modal-celletta-<?php echo $id?>').find('#riga_di_ricerca').find('input').each(function(){
		$(this).val('');
	});
}
</script>