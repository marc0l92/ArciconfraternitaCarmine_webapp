<?php
if(!isset($celletta_hide)) $celletta_hide = false;
if(!isset($responsabile_hide)) $responsabile_hide = false;
?>
<form id="nuovo_defunto">
	<?php if(!isset($data)){ ?>
	<div name="radio_tipo">
		<div class="radio">
			<label>
				<input type="radio" name="tipo-defunto" value="1" value1="1" checked>
				Rendi un confratello defunto.
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="tipo-defunto" value="0" value1="0">
				Crea un nuovo defunto.
			</label>
		</div>
	</div>
	<?php } ?>
	<div class="form-group">
		<?php if(!isset($data)){ ?>
		<div class="row" id="ricerca_tramite_codice">
			<div class="col-lg-4">
				<form>
					<label>Codice confratello*</label>
					<div class="input-group">
						<input name="codice_confratello" type="text" class="form-control" placeholder="Codice del confratello" control="number">
						<span class="input-group-btn">
							<button id="cerca_confratello" class="btn btn-default" type="button" data-loading-text="Caricamento...">Cerca</button>
						</span>
					</div>
				</form>
			</div>
			<input name="id_persona" value="0" class="hidden">
		</div>
		<?php } ?>
		<?php if(isset($data)){ ?>
		<div class="row">
			<div class="col-lg-4 pull-right"><a class="pull-right" href="<?php echo site_url('stampe/stampa_defunto').'/'.$data['id_defunto']; ?>">Vai alla stampa -></a></div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-lg-6">
				<label>Cognome*</label>
				<input name="cognome" type="text" class="form-control" placeholder="Cognome" tag="nuovo_defunto" control="not_null" disabled>
			</div>
			<div class="col-lg-6">
				<label>Nome</label>
				<input name="nome" type="text" class="form-control" placeholder="Nome" tag="nuovo_defunto" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label>Luogo di nascita</label>
				<input name="luogo_nascita" type="text" class="form-control" placeholder="Luogo di nascita" tag="nuovo_defunto" disabled>
			</div>
			<div class="col-lg-4">
				<label>Data di nascita</label>
				<input name="data_nascita" type="text" class="form-control" placeholder="Data di nascita" tag="nuovo_defunto" control="date" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label>Indirizzo</label>
				<input name="indirizzo" type="text" class="form-control" placeholder="Indirizzo di residenza" tag="nuovo_defunto" disabled>
			</div>
			<div class="col-lg-4">
				<label>Città</label>
				<input name="citta" type="text" class="form-control" placeholder="Città di residenza" tag="nuovo_defunto" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<label>Sesso*</label>
				<select name="sesso" class="form-control" tag="nuovo_defunto" disabled>
					<option value="M">M</option>
					<option value="F">F</option>
				</select>
			</div>
			<div class="col-lg-5">
				<label>Data decesso</label>
				<input name="data_decesso" type="text" class="form-control" placeholder="Data del decesso" control="date">
			</div>
		</div>
		<div class="row no-margin">
			<div class="col-lg-12">
				<label>Note</label>
				<textarea name="note" class="form-control"></textarea>
			</div>
		</div>
		<!--  ####################### CONFRATELLO #########################-->
		<div id="dati-confratelli">
			<div class="row space"></div>
			<legend>Dati da confratello</legend>
			<div class="row">
				<?php if(isset($data)){ // solo quando si modifica un defunto ?>
				<div class="col-lg-6">
					<label>Codice confratello</label>
					<input name="codice_confratello" type="text" class="form-control" placeholder="Codice da confratello" disabled>
				</div>
				<?php } ?>
				<div class="col-lg-6">
					<label>Data professione</label>
					<input name="data_professione" type="text" class="form-control" placeholder="Data inizio professione" disabled>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<label>Paternità</label>
					<input name="paternita" type="text" class="form-control" placeholder="Paternità" disabled>
				</div>
				<div class="col-lg-6">
					<label>Maternità</label>
					<input name="maternita" type="text" class="form-control" placeholder="Maternità" disabled>
				</div>
			</div>
		</div>
		<?php if($celletta_hide == false){ ?>
		<!--  ####################### CELLETTA #########################-->
		<div class="row space"></div>
		<legend>Dati della sepoltura</legend>
		<div class="row">
			<div class="col-lg-6">
				<?php 
				$array = array('tipo_ricerca' => 'acquistate', 'id_acquirente_position' => 'input[name=id_persona]');
				if(isset($data)){
					$array['id_acquirente_position'] = '#id_defunto_acquirente';
					?>
					<input id="id_defunto_acquirente" type="text" class="hidden" value="<?php echo $data["id_defunto"]; ?>">
					<?php
				}
				$this->load->view('parts/view_modal_celletta.php', $array);
				?>
			</div>
			<div class="col-lg-6">
				<label>Data sepoltura</label>
				<input name="data_sepoltura" type="text" class="form-control" placeholder="Data della sepoltura" control="date">
			</div>
		</div>
		<?php } ?>

		<?php if($responsabile_hide == false){ ?>
		<!--  ####################### RESPONSABILE #########################-->
		<div class="row space"></div>
		<legend>Dati responsabile</legend>
		<div class="row">
			<div class="col-lg-6">
				<?php $this->load->view('parts/view_modal_persona.php'); ?>
				<?php $this->load->view('parts/view_input_persona.php', array('name' => 'responsabile')); ?>
			</div>
			<div class="col-lg-6">
				<label>Data inizio responsabilità</label>
				<input name="data_inizio_responsabilita" type="text" class="form-control" placeholder="Data inizio responsabilità" control="date">
			</div>
		</div>
		<div class="row no-margin">
			<div class="col-lg-12">
				<label>Note responsabilità</label>
				<textarea name="note_responsabilita" class="form-control"></textarea>
			</div>
		</div>
		<?php } ?>

		<div class="row space"></div>
		<div class="row">
			<div class="col-lg-6">
				<small class="pull-left">* Riempire i campi obbligatori</small>
			</div>
			<div class="col-lg-6">
				<button id="salva-defunto" type="button" class="btn btn-default pull-right" data-loading-text="Caricamento...">Salva</button>
				<button id="elimina" type="button" class="btn btn-default pull-right hidden" data-loading-text="Caricamento...">Elimina</button>
				<button id="modifica" type="button" class="btn btn-default pull-right hidden">Modifica</button>
			</div>
		</div>
		<input name="FKMS_ignore" value="0" class="hidden">
	</div>
</form>
<?php $this->load->view('parts/view_input_check_script.php', array('content' => '#nuovo_defunto', 'id_input_check' => '_nuovo_defunto')); ?>
<script type="text/javascript">
$('#cerca_confratello').click(function(){
	var btn = $(this);
	btn.button('loading');
	var codice = $('#nuovo_defunto').find('input[name=codice_confratello]').val();
	if(codice == ''){
		my_alert('Inserire un codice per la ricerca', 1);
		btn.button('reset');
		return;
	}
	$.ajax({
		url: "<?php echo site_url('defunti/ajax_cerca_confratello'); ?>",
		type: "POST",
		dataType: "json",
		data: "codice="+codice,
		success: function(msg) {
			my_alert('Confratello trovato', 0);
			$('#nuovo_defunto').find('input[name=cognome]').val(msg['cognome']);
			$('#nuovo_defunto').find('input[name=nome]').val(msg['nome']);
			$('#nuovo_defunto').find('input[name=luogo_nascita]').val(msg['luogo_nascita']);
			$('#nuovo_defunto').find('input[name=data_nascita]').val(msg['data_nascita']);
			$('#nuovo_defunto').find('input[name=indirizzo]').val(msg['indirizzo']);
			$('#nuovo_defunto').find('input[name=citta]').val(msg['citta']);
			$('#nuovo_defunto').find('select[name=sesso]').val(msg['sesso']);
			$('#nuovo_defunto').find('textarea[name=note]').val(msg['note1']);
			$('#nuovo_defunto').find('input[name=data_professione]').val(msg['data_professione']);
			$('#nuovo_defunto').find('input[name=paternita]').val(msg['paternita']);
			$('#nuovo_defunto').find('input[name=maternita]').val(msg['maternita']);
			$('#nuovo_defunto').find('input[name=id_persona]').val(msg['id_persona']);
			$('#nuovo_defunto').find('#query_cellette-0').html(msg['query_cellette']);
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Confratello non trovato', 1);
			ajax_error_show(msg);
			btn.button('reset');
		}
	});
});

$('#salva-defunto').click(function(){ salva_defunto_click(); });
function salva_defunto_click(){
	modal_nuova_persona_destroy();
	var ajax_data = get_ajax_data('#nuovo_defunto', input_check_nuovo_defunto());
	if(ajax_data == false){
		return false;
	}
	ajax_data += "&function=salva_defunto_click";
	ajax_data += "&modifica=<?php if(isset($data)) echo '1&id_defunto='.$data['id_defunto']; else echo 0; ?>";
	var tipo = $('#nuovo_defunto').find('input[name=tipo]:checked').val();
	var btn = $('#salva-defunto');
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('defunti/ajax_insert_defunto'); ?>",
		type: "POST",
		dataType: "json",
		data: ajax_data,
		success: function(msg) {
			my_alert('Defunto salvato [id: '+msg['last_insert_id']+']', 0);
			<?php if(isset($data)){ ?>
				$('input').attr('disabled', 'disabled');
				$('textarea').attr('disabled', 'disabled');
				$('select').attr('disabled', 'disabled');
				//celletta
				$('button[name=cerca]').attr('disabled', 'disabled');
				$('button[name=svuota0]').attr('disabled', 'disabled');
				//responsabile
				$('button[name=nuova-persona]').attr('disabled', 'disabled');
				$('button[name=svuota]').attr('disabled', 'disabled');
				$('button[name=cerca-persona]').attr('disabled', 'disabled');
				//azioni
				$('#salva-defunto').addClass('hidden');
				$('#modifica').removeClass('hidden');
				$('#elimina').removeClass('hidden');
				<?php }else{ ?>
					<?php
					// nel caso questa pagina viene richiamata dal view_modal_defunto devono essere prelevate le informazioni
					// da mandare alla pagina del modal per poterle successivamente salvare nelle loro caselle di testo
					if(isset($on_save)) { 
						?>
					// codice javascript che viene aggiunto alla pagina per richiamare la funzione del modal
					var cognome = $('#nuovo_defunto').find('input[name=cognome]').val().toUpperCase();
					var nome = $('#nuovo_defunto').find('input[name=nome]').val().toUpperCase();
					var data_decesso = $('#nuovo_defunto').find('input[name=data_decesso]').val();

					on_save_defunto(msg['last_insert_id'], cognome+' '+nome+' ['+data_decesso+']');
					<?php 
				} ?>
            //reset della form
            $('#nuovo_defunto').find('input').val('');
            $('#nuovo_defunto').find('textarea').val('');
            $('#nuovo_defunto').find(':input,select option').removeAttr('selected');
            $('#nuovo_defunto').find('select option:first').attr('selected',true);
            <?php if($celletta_hide == false){ ?>
            	modal_celletta_reset0();
            	<?php 
            } ?>
            <?php } ?>
            btn.button('reset');
        },
        error : function (msg) {
        	my_alert('Defunto non salvato', 1);
        	ajax_error_show(msg);
        	btn.button('reset');
        }
    });
}

$('#nuovo_defunto').find('input[type=radio][value=1]').click(function(){
	//rendi un confratello defunto
	$('#nuovo_defunto').find('input[name=codice_confratello]').removeAttr('disabled');
	$('#nuovo_defunto').find('#cerca_confratello').removeAttr('disabled');
	$('#nuovo_defunto').find('[tag=nuovo_defunto]').attr('disabled','disabled');
	$('#nuovo_defunto').find('#dati-confratelli').show(1500);
	$('#nuovo_defunto').find('#ricerca_tramite_codice').show(1500);
	//reset della form
	$('#nuovo_defunto').find('input').val('');
	$('#nuovo_defunto').find('textarea').val('');
	$('#nuovo_defunto').find(':input,select option').removeAttr('selected');
	$('#nuovo_defunto').find('select option:first').attr('selected',true);
	<?php if($celletta_hide == false){ ?>
		modal_celletta_reset0();
		<?php 
	} ?>
});
$('#nuovo_defunto').find('input[type=radio][value=0]').click(function(){
	//crea un nuovo defunto
	$('#nuovo_defunto').find('input[name=codice_confratello]').attr('disabled','disabled');
	$('#nuovo_defunto').find('#cerca_confratello').attr('disabled','disabled');
	$('#nuovo_defunto').find('[tag=nuovo_defunto]').removeAttr('disabled');
	$('#nuovo_defunto').find('#dati-confratelli').hide(1500);
	$('#nuovo_defunto').find('#ricerca_tramite_codice').hide(1500);
	//reset della form
	$('#nuovo_defunto').find('input').val('');
	$('#nuovo_defunto').find('textarea').val('');
	$('#nuovo_defunto').find(':input,select option').removeAttr('selected');
	$('#nuovo_defunto').find('select option:first').attr('selected',true);
	<?php if($celletta_hide == false){ ?>
		modal_celletta_reset0();
		<?php 
	} ?>
});

<?php if(isset($data)){ ?>
	$(document).ready(function(){
		$('input').attr('disabled', 'disabled');
		$('textarea').attr('disabled', 'disabled');
		$('select').attr('disabled', 'disabled');
		//celletta
		$('button[name=cerca]').attr('disabled', 'disabled');
		$('button[name=svuota0]').attr('disabled', 'disabled');
		//responsabile
		$('button[name=nuova-persona]').attr('disabled', 'disabled');
		$('button[name=svuota]').attr('disabled', 'disabled');
		$('button[name=cerca-persona]').attr('disabled', 'disabled');
		//azioni
		$('#salva-defunto').addClass('hidden');
		$('#modifica').removeClass('hidden');
		$('#elimina').removeClass('hidden');
		<?php foreach ($data as $key => $value) { ?>
			$('[name=<?php echo $key; ?>]').val(<?php echo json_encode($value); ?>);
			<?php 
		} ?>
		$('input[name=description-persona][id-input=0]').val(<?php echo json_encode($data["responsabile_description"]); ?>);
	});

	$('#modifica').click(function(){
		$('input').removeAttr('disabled');
		$('select').removeAttr('disabled');
		$('textarea').removeAttr('disabled');
		// celletta
		$('input[name=celletta_description0]').attr('disabled', 'disabled');
		$('button[name=svuota0]').removeAttr('disabled');
		$('button[name=cerca]').removeAttr('disabled');
		// responsabile
		$('button[name=nuova-persona]').removeAttr('disabled');
		$('input[name=description-persona]').attr('disabled', 'disabled');
		$('button[name=svuota]').removeAttr('disabled');
		$('button[name=cerca-persona]').removeAttr('disabled');
		// azioni
		$('#salva-defunto').html('Salva modifiche');
		$('#salva-defunto').removeClass('hidden');
		$('#modifica').addClass('hidden');
		$('#elimina').addClass('hidden');
	});

	<?php
	// elimina la defunto
	$array = array(
		'tabella' => array('sepolture', 'responsabili_defunti', 'defunti'),
		'where' => array('id_defunto = '.$data['id_defunto'], 'id_defunto = '.$data['id_defunto'], 'id_defunto = '.$data['id_defunto']),
		'script_tag' => 0,
		'redirect' => 'defunti'
		);
	$this->load->view('parts/view_elimina_script.php', $array); 
	?>

	<?php
} ?>

my_datapicker('input[name=data_nascita]');
my_datapicker('input[name=data_decesso]');
my_datapicker('input[name=data_professione]');
my_datapicker('input[name=data_sepoltura]');
my_datapicker('input[name=data_inizio_responsabilita]');
</script>