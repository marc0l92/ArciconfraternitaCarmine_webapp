<?php
// nel caso questa pagina viene richiamata dal view_modal_persona devono essere prelevate le informazioni
// da mandare alla pagina del modal per poterle successivamente salvare nelle loro caselle di testo
// in questo caso viene abilitata la variabile ON_SAVE
if(!isset($on_save)) $on_save = false;
if(!isset($data) || !isset($data['id_persona'])) $data = false;
if(!isset($show_confratello)) $show_confratello = true;
if(!isset($show_confratello_checkbox)) $show_confratello_checkbox = true;
?>
<form id="nuova_persona">
	<div class="form-group">
		<?php if(isset($data) && $data != false){ ?>
		<div class="row">
			<div class="col-lg-4 pull-right"><a class="pull-right" href="<?php echo site_url('stampe/stampa_persona').'/'.$data['id_persona']; ?>">Vai alla stampa -></a></div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-lg-6">
				<label>Cognome*</label>
				<input name="cognome" type="text" class="form-control" placeholder="Cognome" control="not_null">
			</div>
			<div class="col-lg-6">
				<label>Nome</label>
				<input name="nome" type="text" class="form-control" placeholder="Nome">
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label>Luogo di nascita</label>
				<input name="luogo_nascita" type="text" class="form-control" placeholder="Luogo di nascita">
			</div>
			<div class="col-lg-4">
				<label>Data di nascita</label>
				<input name="data_nascita" type="text" class="form-control" placeholder="Data di nascita" control="date">
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label>Indirizzo</label>
				<input name="indirizzo" type="text" class="form-control" placeholder="Indirizzo di residenza">
			</div>
			<div class="col-lg-4">
				<label>Città</label>
				<input name="citta" type="text" class="form-control" placeholder="Città di residenza">
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<label>Telefono</label>
				<input name="telefono" type="text" class="form-control" placeholder="Telefono">
			</div>
			<div class="col-lg-6">
				<label>Cellulare</label>
				<input name="cellulare" type="text" class="form-control" placeholder="Cellulare">
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<label>Sesso*</label>
				<select name="sesso" class="form-control">
					<option value="M">M</option>
					<option value="F">F</option>
				</select>
			</div>
			<div class="col-lg-3">
				<label>Infermo*</label>
				<select name="infermo" class="form-control">
					<option value="0">No</option>
					<option value="1">Si</option>
				</select>
			</div>
			<div class="col-lg-6">
				<label>Stato civile*</label>
				<select name="stato_civile" class="form-control">
					<option value="celibe/nubile">Celibe/Nubile</option>
					<option value="coniugato/a">Coniugato/a</option>
					<option value="vedovo/a">Vedovo/a</option>
					<option value="separato/a">Separato/a</option>
					<option value="divorziato/a">Divorziato/a</option>
				</select>
			</div>
		</div>
		<div class="row no-margin">
			<div class="col-lg-12">
				<label>Note</label>
				<textarea name="note" class="form-control"></textarea>
			</div>
		</div>
		<?php if(!($show_confratello_checkbox == false && $show_confratello == false)){ ?>
		<div class="row space"></div>
		<legend>Dati confratelli</legend>
		<?php } ?>
		<?php if($show_confratello_checkbox == true){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="checkbox">
					<label>
						<input name="tipo" type="checkbox" value="1" checked>
						Confratello o consorella
					</label>
				</div>
			</div>
		</div>
		<?php } ?>
		<div id="dati-confratelli">
			<div class="row">
				<div class="col-lg-4">
					<label>Codice*</label>
					<input name="codice" type="text" class="form-control" placeholder="Codice di identificazione del confratello" control="not_null number">
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<label>Data professione</label>
					<input name="data_professione" type="text" class="form-control" placeholder="Data inizio professione" control="date">
				</div>
				<div class="col-lg-6">
					<label>Codice capofamiglia</label><span class="space-left" id="capofamiglia_name"></span>
					<input name="codice_capofamiglia" type="text" class="form-control" placeholder="Codice del capo famiglia" control="number">
					<?php $this->load->view('parts/view_capofamiglia_script.php'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<label>Paternità</label>
					<input name="paternita" type="text" class="form-control" placeholder="Paternità">
				</div>
				<div class="col-lg-6">
					<label>Maternità</label>
					<input name="maternita" type="text" class="form-control" placeholder="Maternità">
				</div>
			</div>
		</div>
		<!-- div class="row space"></div>
		<legend>Dati cellette</legend>
		<div>
		</div -->
		<div class="row space"></div>
		<div class="row">
			<div class="col-lg-6">
				<small class="pull-left">* Riempire i campi obbligatori</small>
			</div>
			<div class="col-lg-6">
				<button id="salva_persona" type="button" class="btn btn-default pull-right" data-loading-text="Caricamento...">Salva</button>
				<button id="elimina" type="button" class="btn btn-default pull-right hidden" data-loading-text="Caricamento...">Elimina</button>
				<button id="modifica" type="button" class="btn btn-default pull-right hidden">Modifica</button>
			</div>
		</div>
	</div>
	<input name="FKMS_ignore" value="0" class="hidden">
</form>
<?php 
//import della libreria input check
$this->load->view('parts/view_input_check_script.php', array('content' => '#nuova_persona', 'id_input_check' => '_nuova_persona')); 
?>
<script type="text/javascript">
$('#salva_persona').click(function(){salva_persona_click();});
function salva_persona_click(){
	var ajax_data = get_ajax_data('#nuova_persona', input_check_nuova_persona());
	if(ajax_data == false){
		return false;
	}
	var btn = $('#salva_persona');
	ajax_data += '&tipo_cheched='+$('#nuova_persona').find('input:checked').prop("checked");
	ajax_data += "&function=salva_persona_click";
	ajax_data += "&modifica=<?php if($data != false) echo '1&id_persona='.$data['id_persona']; else echo 0; ?>";
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('persone/ajax_insert_persona'); ?>",
		type: "POST",
		dataType: "json",
		data: ajax_data,
		success: function(msg) {
			my_alert('Persona salvata [id: '+msg['last_insert_id']+']', 0);
			<?php  if($data != false){ ?>
			//non esistono sessioni multiple della stessa pagina
			$('input').attr('disabled','disabled');
			$('textarea').attr('disabled','disabled');
			$('select').attr('disabled','disabled');
			$('#modifica').removeClass('hidden');
			$('#elimina').removeClass('hidden');
			$('#salva_persona').addClass('hidden');
			<?php }else{ ?>
				<?php if($on_save != false) { ?>
					var codice = $('#nuova_persona').find('input[name=codice]').val();
					var cognome = $('#nuova_persona').find('input[name=cognome]').val().toUpperCase();
					var nome = $('#nuova_persona').find('input[name=nome]').val().toUpperCase();
					on_save_persona(msg['last_insert_id'], '['+codice+'] '+cognome+' '+nome);
					<?php } ?>
			//reset della form
			$('#nuova_persona').find('input').val('');
			$('#nuova_persona').find('textarea').val('');
			$('#nuova_persona').find(':input,select option').removeAttr('checked').removeAttr('selected');
			$('#nuova_persona').find('select option:first').attr('selected',true);
			$('#nuova_persona').find('input[type=checkbox]').prop('checked', true);
			$('#capofamiglia_name').html('');
			tipo_change(1500);
			<?php } ?>
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Persona non salvata', 1);
			ajax_error_show(msg);
			btn.button('reset');
		}
	});
}


$('#nuova_persona').find('input[name=tipo]').change(function(){tipo_change('1500');});
function tipo_change(time){
	var tipo = $('#nuova_persona').find('input[name=tipo]').prop("checked");
	if(tipo == false){
		//$('#dati-confratelli').find('input').attr('disabled','disabled');
		$('#nuova_persona').find('#dati-confratelli').hide(time);
		//control
		$('#nuova_persona').find('input[name=codice]').removeAttr('control');
		$('#nuova_persona').find('input[name=data_professione]').removeAttr('control');

	}else{
		//$('#dati-confratelli').find('input').removeAttr('disabled');
		$('#nuova_persona').find('#dati-confratelli').show(time);
		//control
		$('#nuova_persona').find('input[name=codice]').attr('control','not_null');
		$('#nuova_persona').find('input[name=data_professione]').attr('control','date');
	}
}

<?php if($data != false){ ?>
	$(document).ready(function(){	
		//se ci sono i dati
		<?php foreach ($data as $key => $value) { ?>
			$('[name=<?php echo $key; ?>]').val(<?php echo json_encode($value); ?>);
			<?php 
		} ?>
		<?php if(isset($data['codice']) && $data['codice'] != ''){ ?>
			$('input[name=tipo]').attr('checked', 'checked');
			<?php
		}else{ ?>
			$('input[name=tipo]').removeAttr('checked');
			<?php 
		} ?>
		$('input').attr('disabled','disabled');
		$('textarea').attr('disabled','disabled');
		$('select').attr('disabled','disabled');
		$('#modifica').removeClass('hidden');
		$('#elimina').removeClass('hidden');
		$('#salva_persona').addClass('hidden');
		tipo_change(0);
	});

	$('#modifica').click(function(){
		$('input').removeAttr('disabled');
		$('textarea').removeAttr('disabled');
		$('select').removeAttr('disabled');
		$('#salva_persona').removeClass('hidden');
		$('#salva_persona').html('Salva modifiche');
		$('#modifica').addClass('hidden');
		$('#elimina').addClass('hidden');
	});

	<?php
	// elimina la persona
	$array = array(
		'tabella' => array('confratelli', 'persone'),
		'where' => array('id_persona = '.$data['id_persona'], 'id_persona = '.$data['id_persona']),
		'script_tag' => 0,
		'redirect' => 'persone'
		);
	$this->load->view('parts/view_elimina_script.php', $array); 
	?>
	<?php 
} ?>

my_datapicker('input[name=data_nascita]');
my_datapicker('input[name=data_professione]');

<?php
//tramite l'opzione $show_confratello è possibile scegliere se la pagina deve esserre caricata con la sezione confratelli attiva
if($show_confratello == false){
	echo "$('input[name=tipo]').removeAttr('checked');";
	echo "tipo_change(0);";
}
?>
</script>
