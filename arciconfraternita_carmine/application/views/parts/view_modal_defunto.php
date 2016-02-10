<?php
if(!isset($nuovo_defunto_btn)) $nuovo_defunto_btn = true;
?>
<script type="text/javascript">

function modal_defunto(nome, id, nuova){
	$('span[name=nome-defunto]').each(function(){
		$(this).html(nome);
	});
	$('input[name=id-feedback-input-defunto]').val(id);
	if(nuova == true){
		<?php if($nuovo_defunto_btn){ ?>
			$('#modal-nuovo-defunto').modal('show');
			modal_nuovo_defunto_create();
			<?php } ?>
		}else{
			$('#modal-cerca-defunto').modal('show');
		}
	}
	</script>
	<!-- MEMORIZZO L'ID DI CHI MI HA CHIAMATO = (INPUT_DEFUNTO) -->
	<input name="id-feedback-input-defunto" value="" class="hidden">

	<!-- MODAL CERCA -->
	<div class="modal fade" id="modal-cerca-defunto">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Scegli <span name="nome-defunto"></span></h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="row">
							<div class="col-lg-6">
								<label>Cognome</label>
								<input name="cognome-defunto" type="text" class="form-control" placeholder="Cognome">
							</div>
							<div class="col-lg-6">
								<label>Nome</label>
								<input name="nome-defunto" type="text" class="form-control" placeholder="Nome">
							</div>
						</div>
						<div class="row">
							<div class="col-lg-10">
								<?php
								$array = array('label' => 'Data del decesso', 'name' => 'data_decesso');
								$this->load->view('parts/view_data_intervallo.php', $array);
								?>
							</div>
							<div class="col-lg-2">
								<button id="cerca-defunto" type="submit" class="btn btn-default second-row" data-loading-text="Caricamento...">Cerca</button>
							</div>
						</div>
					</form>
					<div class="row space"></div>
					<div class="row">
						<div class="col-lg-12" id="query_result-defunto">
							Cerca un <span name="nome-defunto"></span>.
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					<button id="scegli-defunto" type="button" class="btn btn-default">Scegli</button>
				</div>
			</div>
		</div>
	</div> <!-- modal -->
	<?php if($nuovo_defunto_btn){ ?>
	<!-- MODAL NUOVO -->
	<div class="modal fade" id="modal-nuovo-defunto">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Crea nuovo <span name="nome-defunto"></span></h4>
				</div>
				<div class="modal-body"></div>
			</div>
		</div>
	</div> <!-- modal -->
	<script type="text/javascript">
	// caricamento e cancellazione del contenuto del modal
	function modal_nuovo_defunto_create(){
		loadContent("<?php echo site_url('ajax_page/nuovo_defunto'); ?>", "#modal-nuovo-defunto > div > div > .modal-body");
	}
	function modal_nuovo_defunto_destroy(){
		$("#modal-nuovo-defunto").find(".modal-body").html('');
	}
	</script>
	<?php } ?>

	<script type="text/javascript">
	$('#cerca-defunto').click(function(){
		var btn = $(this);
		btn.button('loading');
		var cognome = $('input[name=cognome-defunto]').val();
		var nome = $('input[name=nome-defunto]').val();
		var intervallo_data_decesso = $('select[name=intervallo_data_decesso-defunto]').val();
		var data_decesso_inizio = $('select[name=data_decesso-defunto_inizio]').val();
		var data_decesso_fine = $('select[name=data_decesso-defunto_fine]').val();
		var data_decesso_prima = $('select[name=data_decesso-defunto_prima]').val();
		var data_decesso_dopo = $('select[name=data_decesso-defunto_dopo]').val();
		var data_decesso_solo = $('select[name=data_decesso-defunto_solo]').val();
		$.ajax({
			url: "<?php echo site_url('defunti/ajax_cerca_defunti/defunto'); ?>",
			type: "POST",
			dataType: "json",
			data: 'cognome='+cognome+'&nome='+nome+'&intervallo_data_decesso='+intervallo_data_decesso+
			'&data_decesso_inizio='+data_decesso_inizio+'&data_decesso_fine='+data_decesso_fine+
			'&data_decesso_prima='+data_decesso_prima+'&data_decesso_dopo='+data_decesso_dopo+
			'&data_decesso_solo='+data_decesso_solo,
			success: function(msg) {
				my_alert('Ricerca effettuata', 0);
				$('#query_result-defunto').html(msg['result']);
				$('#query_result-defunto').find('tr').click(function() {
					$('#modal-cerca-defunto').animate({
						scrollTop: $("#scegli-defunto").offset().top
					}, 2000);
				});
				btn.button('reset');
			},
			error : function (msg) {
				my_alert('Ricerca fallita', 1);
				ajax_error_show(msg);
				btn.button('reset');
			}
		});
	return false; // in modo da bloccare il submit della form
});

$('#scegli-defunto').click(function(){
	var scelta = $('input[name=defunto_scelto]:checked').val();
	//controllo se è stato scelto qualcuno
	if(typeof scelta === "undefinied"){
		return;
	}
	$('input[name=description-defunto][id-input='+$('input[name=id-feedback-input-defunto]').val()+']').val($('#query-defunto-cognome-'+scelta).html()+' '+$('#query-defunto-nome-'+scelta).html()+' ['+$('#query-defunto-data_decesso-'+scelta).html()+']');
	$('input[name=id-defunto'+$('input[name=id-feedback-input-defunto]').val()+'][id-input='+$('input[name=id-feedback-input-defunto]').val()+']').val(scelta);

	$('#modal-cerca-defunto').modal('hide');
	$('#modal-cerca-defunto').find('input').each(function(){
		$(this).val('');
	});
	$('#query_result-defunto').html('Cerca un '+$('span[name=nome-defunto]').html()+'.');
});

<?php if($nuovo_defunto_btn){ ?>
	function on_save_defunto(id, description){
		$('input[name=description-defunto][id-input='+$('input[name=id-feedback-input-defunto]').val()+']').val(description);
		$('input[name=id-defunto'+$('input[name=id-feedback-input-defunto]').val()+'][id-input='+$('input[name=id-feedback-input-defunto]').val()+']').val(id);
		$('#modal-nuovo-defunto').modal('hide');
	}
	<?php } ?>

	</script>