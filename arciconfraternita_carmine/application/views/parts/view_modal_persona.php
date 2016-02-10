<?php
if(!isset($nuova_persona_btn)) $nuova_persona_btn = true;
?>
<script type="text/javascript">

function modal_persona(nome, id, nuova){
	$('span[name=nome-persona]').each(function(){
		$(this).html(nome);
	});
	$('input[name=id-feedback-input-persona]').val(id);
	if(nuova == true){
		<?php if($nuova_persona_btn){ ?>
			$('#modal-nuova-persona').modal('show');
			modal_nuova_persona_create();
			<?php } ?>
		}else{
			$('#modal-cerca-persona').modal('show');
		}
	}
	</script>
	<!-- MEMORIZZO L'ID DI CHI MI HA CHIAMATO = (INPUT_PERSONA) -->
	<input name="id-feedback-input-persona" value="" class="hidden">

	<!-- MODAL CERCA -->
	<div class="modal fade" id="modal-cerca-persona">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Scegli <span name="nome-persona"></span></h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="row">
							<div class="col-lg-3">
								<label>Codice</label>
								<input name="codice-persona" type="text" class="form-control" placeholder="Codice confratello">
							</div>
							<div class="col-lg-4">
								<label>Cognome</label>
								<input name="cognome-persona" type="text" class="form-control" placeholder="Cognome">
							</div>
							<div class="col-lg-3">
								<label>Nome</label>
								<input name="nome-persona" type="text" class="form-control" placeholder="Nome">
							</div>
							<div class="col-lg-2">
								<button id="cerca-persona" type="submit" class="btn btn-default second-row" data-loading-text="Caricamento...">Cerca</button>
							</div>
						</div>
					</form>
					<div class="row space"></div>
					<div class="row">
						<div class="col-lg-12" id="query_result-persona">
							Cerca un <span name="nome-persona"></span>.
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					<button id="scegli-persona" type="button" class="btn btn-default">Scegli</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<?php if($nuova_persona_btn){ ?>
	<!-- MODAL NUOVO -->
	<div class="modal fade" id="modal-nuova-persona">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Crea nuovo <span name="nome-persona"></span></h4>
				</div>
				<div class="modal-body"></div>
			</div>
		</div>
	</div><!-- modal -->
	<script type="text/javascript">
	// caricamento e cancellazione del contenuto del modal
	function modal_nuova_persona_create(){
		loadContent("<?php echo site_url('ajax_page/nuova_persona'); ?>", "#modal-nuova-persona > div > div > .modal-body");
	}
	function modal_nuova_persona_destroy(){
		$("#modal-nuova-persona").find(".modal-body").html('');
	}
	</script>
	<?php } ?>

	<script type="text/javascript">
	$('#cerca-persona').click(function(){
		var btn = $(this);
		btn.button('loading');
		var codice = $('input[name=codice-persona]').val();
		var cognome = $('input[name=cognome-persona]').val();
		var nome = $('input[name=nome-persona]').val();
		$.ajax({
			url: "<?php echo site_url('persone/ajax_cerca_persone/persona'); ?>",
			type: "POST",
			dataType: "json",
			data: 'codice='+codice+'&cognome='+cognome+'&nome='+nome,
			success: function(msg) {
				my_alert('Ricerca effettuata', 0);
				$('#query_result-persona').html(msg['result']);
				$('#query_result-persona').find('tr').click(function() {
					$('#modal-cerca-persona').animate({scrollTop: $("#scegli-persona").offset().top}, 2000);
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

	$('#scegli-persona').click(function(){
		var scelta = $('input[name=persona_scelto]:checked').val();
	//controllo se è stato scelto qualcuno
	if(typeof scelta === "undefinied"){
		return;
	}
	//valori che tornano all'input_persona
	$('input[name=description-persona][id-input='+$('input[name=id-feedback-input-persona]').val()+']').val('['+$('#query-persona-codice-'+scelta).html()+'] '+
		$('#query-persona-cognome-'+scelta).html()+' '+$('#query-persona-nome-'+scelta).html());
	$('input[name=id-persona'+$('input[name=id-feedback-input-persona]').val()+'][id-input='+$('input[name=id-feedback-input-persona]').val()+']').val(scelta);
	
	$('#modal-cerca-persona').modal('hide');
	$('#modal-cerca-persona').find('input').each(function(){
		$(this).val('');
	});
	$('#query_result-persona').html('Cerca un '+$('span[name=nome-persona]').html()+'.');
});

	<?php if($nuova_persona_btn){ ?>
		function on_save_persona(id, description){
	//valori che tornano all'input_persona
	$('input[name=description-persona][id-input='+$('input[name=id-feedback-input-persona]').val()+']').val(description);
	$('input[name=id-persona'+$('input[name=id-feedback-input-persona]').val()+'][id-input='+$('input[name=id-feedback-input-persona]').val()+']').val(id);

	$('#modal-nuova-persona').modal('hide');
}
<?php } ?>
</script>