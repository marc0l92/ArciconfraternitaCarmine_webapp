<div class="form-group" id="nuova_celletta">
	<?php 
	// contengono i modal per eseguire la ricerca e l'aggiunta di persone e defunti
	$this->load->view('parts/view_modal_persona.php');
	$this->load->view('parts/view_modal_defunto.php');
	?>
	<?php if(isset($data)){ ?>
	<div class="row">
		<div class="col-lg-4 pull-right"><a class="pull-right" href="<?php echo site_url('stampe/stampa_celletta').'/'.$data['id_celletta']; ?>">Vai alla stampa -></a></div>
	</div>
	<?php } ?>
	<div class="row">
		<div class="col-lg-3">
			<?php $this->load->view('parts/view_select_cappella.php', array('name' => 'cappella_celletta', 'star' => 1)); ?>
		</div>
		<div class="col-lg-2">
			<label>Piano*</label>
			<input name="piano" type="text" class="form-control" placeholder="N. Piano" control="not_null number">
		</div>
		<div class="col-lg-2">
			<label>Sezione*</label>
			<input name="sezione" type="text" class="form-control" placeholder="N. Sezione" control="not_null number">
		</div>
		<div class="col-lg-2">
			<label>Fila*</label>
			<input name="fila" type="text" class="form-control" placeholder="N. Fila" control="not_null number">
		</div>
		<div class="col-lg-3">
			<label>Numero*</label>
			<input name="numero" type="text" class="form-control" placeholder="Numero" control="not_null number">
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3">
			<label>Tipo*</label>
			<select name="tipo" class="form-control">
				<option value="celletta">Celletta</option>
				<option value="sepoltura">Sepoltura</option>
				<option value="pilone">Pilone</option>
			</select>
		</div>
	</div>
	<div class="row space"></div>
	<div class="row">
		<div class="col-lg-12">
			<div class="checkbox">
				<label>
					<input name="acquirente_defunto" type="checkbox" value="1">
					Acquirente defunto
				</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6" id="div_acquirente">
			<?php $this->load->view('parts/view_input_persona.php', array('name' => 'acquirente', 'id' => '_acquirente')); ?>
		</div>
		<div class="col-lg-6" style="display:none;" id="div_acquirente_defunto">
			<?php $this->load->view('parts/view_input_defunto.php', array('name' => 'acquirente defunto', 'id' => '_acquirente')); ?>
		</div>
		<div class="col-lg-6">
			<label>Data concessione</label>
			<input name="data_concessione" type="text" class="form-control" placeholder="Data concessione" value="" control="date">
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<label>Somma pagata</label>
			<div class="input-group">
				<input name="somma_pagata" type="text" class="form-control" placeholder="Somma pagata" control="number">
				<span class="input-group-addon">€</span>
			</div>
		</div>
		<div class="col-lg-6">
			<label>Codice bolletta</label>
			<input name="codice_bolletta" type="text" class="form-control" placeholder="Codice bolletta">
		</div>
	</div>
	<div class="row space"></div>
	<div class="row">
		<div class="col-lg-6">
			<?php $this->load->view('parts/view_input_persona.php', array('name' => 'responsabile celletta', 'id' => '_responsabile')); ?>
		</div>
		<div class="col-lg-6">
			<label>Data inizio responsabilità</label>
			<input name="data_responsabile_celletta" type="text" class="form-control" placeholder="Data inizio responsabilità" value="" control="date">
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<label>Note sul responsabile della celletta</label>
			<textarea name="note_responsabilita" class="form-control" style="height: 35px;"></textarea>
		</div>
	</div>
	<div class="row space"></div>
	<div class="row">
		<div class="col-lg-12">
			<label>Descrizione lapide</label>
			<textarea name="descrizione_lapide" class="form-control"></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<label>Note</label>
			<textarea name="note" class="form-control"></textarea>
		</div>
	</div>
	<div id="dati-pilone" style="display:none;">
		<div class="row space"></div>
		<legend>Dati pilone</legend>
		<div class="row">
			<div class="col-lg-3">
				<?php $this->load->view('parts/view_select_cappella.php', array('star' => 1, 'name' => 'id_cappella_pilone')); ?>
			</div>
			<div class="col-lg-3">
				<label>Piano*</label>
				<input name="piano_pilone" type="text" class="form-control" placeholder="N. Piano">
			</div>
			<div class="col-lg-3">
				<label>Sezione*</label>
				<input name="sezione_pilone" type="text" class="form-control" placeholder="N. Sezione">
			</div>
			<div class="col-lg-3">
				<label>Numero*</label>
				<input name="numero_pilone" type="text" class="form-control" placeholder="Numero">
			</div>
		</div>
	</div>
	<div class="row space"></div>
	<legend>Dati defunto</legend>
	<div class="row">
		<div class="col-lg-6">
			<?php $this->load->view('parts/view_input_persona.php', array('name' => 'responsabile defunto', 'id' => '_responsabile_defunto')); ?>
		</div>
		<div class="col-lg-6">
			<label>Data inizio responsabilità</label>
			<input id-input="0" name="data_responsabile_defunto" type="text" class="form-control" placeholder="Data inizio responsabilità" value="" control="date">
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<label>Note sul responsabile dei defunti</label>
			<textarea name="note_responsabile_defunti" class="form-control" style="height: 35px;"></textarea>
		</div>
	</div>
	<div id="modello_defunto">
		<div class="row space"></div>
		<div class="well well-small" name="defunto">
			<div class="row">
				<div class="col-lg-6">
					<?php $this->load->view('parts/view_input_defunto.php', array('id' => 0, 'name' => 'defunto')); ?>
				</div>
				<div class="col-lg-6">
					<label>Data sepoltura</label>
					<input id-input="0" name="data_sepoltura0" type="text" class="form-control" placeholder="Data sepoltura" value="" control="date">
				</div>
			</div>
		</div>
	</div>
	<div id="elenco_defunti">
	</div>
	<div class="row space"></div>
	<div class="row">
		<div class="col-lg-12">
			<button id="aggiungi_defunto" type="button" class="btn btn-default">Aggiungi un'altro defunto</button>
			<button id="rimuovi_defunto" type="button" class="btn btn-default" disabled="">Rimuovi l'ultimo defunto</button>
		</div>
	</div>
	<div class="row space"></div>
	<div class="row">
		<div class="col-lg-6">
			<small class="pull-left">* Riempire i campi obbligatori</small>
		</div>
		<div class="col-lg-6" name="actions">
			<button id="salva_celletta" type="button" class="btn btn-default pull-right" data-loading-text="Caricamento...">Salva</button>
			<button id="elimina_celletta" type="button" class="btn btn-default pull-right hidden" data-loading-text="Caricamento...">Elimina</button>
			<button id="modifica_celletta" type="button" class="btn btn-default pull-right hidden">Modifica</button>
		</div>
	</div>
</div> <!-- form-group -->
<?php $this->load->view('parts/view_input_check_script.php', array('content' =>'#nuova_celletta', 'id_input_check' => '_nuova_celletta')); ?>
<script type="text/javascript">
//variabili globali
defunti = 0;

$('#salva_celletta').click(function(){
	modal_nuova_persona_destroy();
	modal_nuovo_defunto_destroy();
	var ajax_data = get_ajax_data('#nuova_celletta', input_check_nuova_celletta());
	if(ajax_data == false){
		return false;
	}
	ajax_data += '&acquirente_defunto='+$('input[name=acquirente_defunto]').prop("checked");
	var btn = $(this);
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('cappella_gentilizia/ajax_insert_celletta'); ?>",
		type: "POST",
		dataType: "json",
		data: ajax_data+"&counter_defunti="+(defunti+1)+"&modifica=<?php if(isset($data)) echo '1&id_celletta='.$data['id_celletta']; else echo 0; ?>",
		success: function(msg) {
			<?php if(isset($data)){
				?>
				my_alert('Celletta modificata [id: '+msg['last_insert_id']+']', 0);
				$('#nuova_celletta').find('input').attr('disabled','disabled');
				$('#nuova_celletta').find('textarea').attr('disabled','disabled');
				$('#nuova_celletta').find('select').attr('disabled','disabled');
				$('#nuova_celletta').find("button").attr('disabled','disabled');
				// actions
				$('#nuova_celletta').find("div[name=actions]").find("button").removeAttr('disabled');
				$('#modifica_celletta').removeClass('hidden');
				$('#elimina_celletta').removeClass('hidden');
				$('#salva_celletta').addClass('hidden');
				<?php 
			}else{ ?>
				my_alert('Celletta aggiunta [id: '+msg['last_insert_id']+']', 0);
				$('input, textarea').val('');
				$(':input,select option').removeAttr('checked').removeAttr('selected');
				$('select option:first').attr('selected',true);
				piloni_togggle();
				<?php 
			} ?>
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Celletta non aggiunta o modificata', 1);
			ajax_error_show(msg);
			btn.button('reset');
		}
	});
});

$('select[name=tipo]').change(function(){piloni_togggle();});
function piloni_togggle(){
	var tipo = $('select[name=tipo]').val();
	if(tipo == 'pilone'){
		$('#dati-pilone').show('1500');
		$('#dati-pilone').find('input').removeAttr('disabled');
		//$('#dati-pilone').find('input').attr('control', 'not_null'); //non serve perche è stata implementata nella input_check
	}else{
		$('#dati-pilone').hide('1500');
		$('#dati-pilone').find('input').attr('disabled', 'disabled');
		$('#dati-pilone').find('input').val('');
		$('#dati-pilone').find(':input,select option').removeAttr('checked').removeAttr('selected');
		$('#dati-pilone').find('select option:first').attr('selected',true);
		//$('#dati-pilone').find('input').removeAttr('control');
	}
}

$('input[name=acquirente_defunto]').change(function(){acquirente_defunto_change('1500');});
function acquirente_defunto_change(time){
	$('input[name=acquirente_defunto]').attr("disabled", 'disabled');
	var acquirente_defunto = $('input[name=acquirente_defunto]').prop("checked");
	if(acquirente_defunto == false){
		$('#div_acquirente_defunto').fadeOut(time, function(){
			$('#div_acquirente').fadeIn(time, function(){
				$('input[name=acquirente_defunto]').removeAttr("disabled");
			});
		});
	}else{
		$('#div_acquirente').fadeOut(time, function(){
			$('#div_acquirente_defunto').fadeIn(time, function(){
				$('input[name=acquirente_defunto]').removeAttr("disabled");
			});
		});
	}
	//$('input[name=acquirente_defunto]').removeAttr("disabled");
}
//si esegue al caricamento della pagina in modo da essere conformi con la checkbox
acquirente_defunto_change('0');

$('#aggiungi_defunto').click(function() {aggiungi_defunto();});	
function aggiungi_defunto(){
	//aggiungo una copia del modello
	$('#elenco_defunti').append($('#modello_defunto').html());
	defunti++;
	$('#rimuovi_defunto').removeAttr('disabled');
	//aggiorno il modello
	$('#modello_defunto').find('input, button').each(function(){
		var id_input = $(this).attr('id-input');
		id_input++;
		$(this).attr('id-input', id_input);
		var name = $(this).attr('name');
		if(name.indexOf('id-defunto') >= 0){
			$(this).attr('name', 'id-defunto'+id_input);
		}
		if(name.indexOf('data_sepoltura') >= 0){
			$(this).attr('name', 'data_sepoltura'+id_input);
		}
	});
}

$('#rimuovi_defunto').click(function() {
	$('#elenco_defunti').find('div[name=defunto]:last').remove();
	defunti--;
	if(defunti <= 0){
		$('#rimuovi_defunto').attr('disabled', 'disabled');
	}
});

<?php if(isset($data)){ ?>
	$(document).ready(function(){
		<?php foreach ($data as $key => $value) { ?>
			$('[name=<?php echo $key; ?>]').val(<?php echo json_encode($value); ?>);
			<?php 
		} ?>
		//informazioni per i casi particolari
		$('[name=cappella_celletta]').val(<?php echo json_encode($data["id_cappella"]); ?>);
		$('[name=id_cappella_pilone]').val(<?php echo json_encode($data["id_cappella_pilone"]); ?>);
		
		
		<?php if($data["id_acquirente"] != 0 && $data["id_acquirente"] != NULL){ ?>
			$('[name=description-persona][id-input=_acquirente]').val(<?php echo json_encode($data["acquirente_description"]); ?>);
			$('[name=id-persona_acquirente][id-input=_acquirente]').val(<?php echo json_encode($data["id_acquirente"]); ?>);

			$('[name=description-defunto][id-input=_acquirente]').val('');
			$('[name=id-defunto_acquirente][id-input=_acquirente]').val('0');
			$('input[name=acquirente_defunto]').prop("checked", false);
			acquirente_defunto_change('0');
			<?php 
		}else{ ?>
			$('[name=description-persona][id-input=_acquirente]').val('');
			$('[name=id-persona_acquirente][id-input=_acquirente]').val(0);

			$('[name=description-defunto][id-input=_acquirente]').val(<?php echo json_encode($data["defunto_acquirente_description"]); ?>);
			$('[name=id-defunto_acquirente][id-input=_acquirente]').val(<?php echo json_encode($data["id_acquirente_defunto"]); ?>);

			$('input[name=acquirente_defunto]').prop("checked", true);
			acquirente_defunto_change('0');
			<?php
		} ?>

		$('[name=description-persona][id-input=_responsabile]').val(<?php echo json_encode($data["responsabile_description"]); ?>);
		$('[name=id-persona_responsabile][id-input=_responsabile]').val(<?php echo json_encode($data["id_responsabile"]); ?>);
		$('[name=description-persona][id-input=_responsabile_defunto]').val(<?php echo json_encode($data["responsabile_defunto_description"]); ?>);
		$('[name=id-persona_responsabile_defunto][id-input=_responsabile_defunto]').val(<?php echo json_encode($data["id_responsabile_defunto"]); ?>);
		$('[name=note_responsabilita]').val(<?php echo json_encode($data["note_responsabile_celletta"]); ?>);
		<?php if(isset($data['piano_pilone']) && $data['piano_pilone'] != NULL && $data['piano_pilone'] != '') {?>
			piloni_togggle();
			<?php 
		} ?>
		// data_defunti
		<?php 
		for($i=0; $i<$data['count_defunti'] - 1; $i++){
			?>
			aggiungi_defunto();
			<?php 
		}
		$i = 0;
		foreach ($data_defunti as $key => $value) { ?>
			$('input[name=description-defunto][id-input=<?php echo $i;?>]').val(<?php echo json_encode($value["cognome"]." ".$value["nome"]); ?>);
			$('input[name=id-defunto<?php echo $i;?>][id-input=<?php echo $i;?>]').val(<?php echo json_encode($value["id_defunto"]); ?>);
			$('input[name=data_sepoltura<?php echo $i;?>][id-input=<?php echo $i;?>]').val(<?php echo json_encode($value["data_inizio_sepoltura"]); ?>);
			<?php 
			$i++;
		} ?>

		//inizializzazione della pagina
		$('#nuova_celletta').find('input, textarea, select').attr('disabled','disabled');
		$('#nuova_celletta').find('button').attr('disabled','disabled');
		$('#modifica_celletta').removeClass('hidden').removeAttr('disabled');
		$('#elimina_celletta').removeClass('hidden').removeAttr('disabled');
		$('#salva_celletta').addClass('hidden').removeAttr('disabled');
	});

$('#modifica_celletta').click(function(){
	$('input, textarea, select').removeAttr('disabled');
	$('button').removeAttr('disabled');
	$('input[control=disabled]').attr('disabled','disabled');
	$('#salva_celletta').removeClass('hidden');
	$('#salva_celletta').html('Salva modifiche');
	$('#modifica_celletta').addClass('hidden');
	$('#elimina_celletta').addClass('hidden');
});

<?php
if($data['tipo'] == 'pilone'){
	$array = array(
		'tabella' => array('sepolture', 'responsabili_cellette', 'piloni', 'cellette'),
		'where' => array('id_defunto = 0 AND id_celletta = '.$data['id_celletta'], 'id_celletta = '.$data['id_celletta'], 'id_pilone = '.$data['id_pilone'], 'id_celletta = '.$data['id_celletta']),
		'script_tag' => 0,
		'redirect' => 'cappella_gentilizia'
		);
}else{
	$array = array(
		'tabella' => array('sepolture', 'responsabili_cellette', 'cellette'),
		'where' => array('id_defunto = 0 AND id_celletta = '.$data['id_celletta'], 'id_celletta = '.$data['id_celletta'], 'id_celletta = '.$data['id_celletta']),
		'script_tag' => 0,
		'redirect' => 'cappella_gentilizia'
		);
}
$array['button_id'] = '#elimina_celletta';
$this->load->view('parts/view_elimina_script.php', $array); 
?>

<?php 
} ?>

my_datapicker('input[name=data_concessione]');
my_datapicker('input[name=data_responsabile_defunto]');
my_datapicker('input[name=data_responsabile_celletta]');
</script>