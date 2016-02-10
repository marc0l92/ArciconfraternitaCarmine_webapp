<div class="form-group">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Nome incarico</th>
				<th>Data inizio</th>
				<th>Codice confratello</th>
				<th>Cognome confratello</th>
				<th>Nome confratello</th>
				<th>Note</th>
				<th>Azioni</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($incarichi as $key => $value){ ?>
			<tr>
				<td>
					<span name="incarico-<?php echo $value['incarico_id_incarico']; ?>" title="<?php echo $value['incarico_note']; ?>">
						<?php echo $value['incarico_nome']; ?>
					</span>
				</td>
				<td>
					<span name="data_inizio-<?php echo $value['incarico_id_incarico']; ?>">
						<?php if(isset($confratelli[$key])) echo $confratelli[$key]['incarico_data_inizio']; ?>
					</span>
					<input name="data_inizio-<?php echo $value['incarico_id_incarico']; ?>" type="text" class="form-control hidden input-small" value="<?php if(isset($confratelli[$key])) echo $confratelli[$key]['incarico_data_inizio']; ?>">
					<script type="text/javascript">my_datapicker('input[name=data_inizio-<?php echo $value["incarico_id_incarico"]; ?>]');</script>					
				</td>
				<td>
					<span name="codice-<?php echo $value['incarico_id_incarico']; ?>">
						<?php if(isset($confratelli[$key])) echo $confratelli[$key]['confratello_codice']; ?>
					</span>
					<div name="codice-<?php echo $value['incarico_id_incarico']; ?>" class="input-group hidden">
						<input type="text" class="form-control input-small" value="<?php if(isset($confratelli[$key])) echo $confratelli[$key]['confratello_codice']; ?>" name="codice-<?php echo $value['incarico_id_incarico']; ?>">
						<span class="input-group-btn">
							<button name="cerca" value="<?php echo $value['incarico_id_incarico']; ?>" class="btn btn-default btn-small" type="button" data-loading-text="Caricamento...">Cerca</button>
						</span>
					</div>
				</td>
				<td>
					<span name="cognome-<?php echo $value['incarico_id_incarico']; ?>">
						<?php if(isset($confratelli[$key])) echo $confratelli[$key]['persona_cognome']; ?>
					</span>
				</td>
				<td>
					<span name="nome-<?php echo $value['incarico_id_incarico']; ?>">
						<?php if(isset($confratelli[$key])) echo $confratelli[$key]['persona_nome']; ?>
					</span>
				</td>
				<td>
					<textarea name="note-<?php echo $value['incarico_id_incarico']; ?>" disabled><?php if(isset($confratelli[$key])) echo $confratelli[$key]['incarico_confratello_note']; ?></textarea>
				</td>
				<td>
					<input name="id_confratello-<?php echo $value['incarico_id_incarico']; ?>" class="form-control hidden input-small" value="<?php if(isset($confratelli[$key])) echo $confratelli[$key]['confratello_id_confratello']; ?>" disabled>
					<input name="incarico_confratello_id-<?php echo $value['incarico_id_incarico']; ?>" class="form-control hidden input-small" value="<?php if(isset($confratelli[$key])) echo $confratelli[$key]['incarico_confratello_id']; ?>" disabled>
					<p name="modifica_riassegna-<?php echo $value['incarico_id_incarico']; ?>" style="text-transform:capitalize;" class="hidden" value="modifica">Modifica</p>
					<button name="riassegna" type="button" class="btn btn-default btn-mini" value="<?php echo $value['incarico_id_incarico']; ?>">Riassegna</button>
					<button name="modifica" type="button" class="btn btn-default btn-mini" value="<?php echo $value['incarico_id_incarico']; ?>" <?php if(!isset($confratelli[$key])) echo 'disabled=""';?>>Modifica</button>
					<button name="salva" type="button" class="btn btn-default btn-mini hidden" value="<?php echo $value['incarico_id_incarico']; ?>" data-loading-text="Caricamento...">Salva</button>
					<button name="annulla" type="button" class="btn btn-default btn-mini hidden"  value="<?php echo $value['incarico_id_incarico']; ?>">Annulla</button>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$('button[name=modifica]').click(function(){
	var id = $(this).val();
	fun_modifica_riassegna(id, 'modifica');
});
$('button[name=riassegna]').click(function(){
	var id = $(this).val();
	fun_modifica_riassegna(id, 'riassegna');
});

function fun_modifica_riassegna(id, valore){
	$('span[name=data_inizio-'+id+']').addClass('hidden');
	$('input[name=data_inizio-'+id+']').removeClass('hidden');
	$('span[name=codice-'+id+']').addClass('hidden');
	$('div[name=codice-'+id+']').removeClass('hidden');
	$('button[value='+id+'][name=modifica]').addClass('hidden');
	$('button[value='+id+'][name=riassegna]').addClass('hidden');
	$('button[value='+id+'][name=salva]').removeClass('hidden');
	$('button[value='+id+'][name=annulla]').removeClass('hidden');
	$('p[name=modifica_riassegna-'+id+']').removeClass('hidden');
	$('p[name=modifica_riassegna-'+id+']').val(valore);
	$('p[name=modifica_riassegna-'+id+']').html(valore);
	$('textarea[name=note-'+id+']').removeAttr('disabled');
}

$('button[name=annulla]').click(function(){
	var id = $(this).val();
	$('input[name=data_inizio-'+id+']').addClass('hidden');
	$('span[name=data_inizio-'+id+']').removeClass('hidden');
	$('span[name=codice-'+id+']').removeClass('hidden');	
	$('div[name=codice-'+id+']').addClass('hidden');
	$('p[name=modifica_riassegna-'+id+']').addClass('hidden');
	$('button[value='+id+'][name=modifica]').removeClass('hidden');
	$('button[value='+id+'][name=riassegna]').removeClass('hidden');
	$('button[value='+id+'][name=salva]').addClass('hidden');
	$('button[value='+id+'][name=annulla]').addClass('hidden');
	$('textarea[name=note-'+id+']').attr('disabled', 'disabled');
});

$('button[name=cerca]').click(function(){
	var btn = $(this);
	var id = $(this).val();
	var codice = $('input[name=codice-'+id+']').val();
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('defunti/ajax_cerca_confratello'); ?>",
		type: "POST",
		dataType: "json",
		data: "codice="+codice,
		success: function(msg) {
			my_alert('Confratello trovato', 0);
			$('input[name=id_confratello-'+id+']').val(msg['id_confratello']);
			$('span[name=codice-'+id+']').html(codice);
			$('span[name=cognome-'+id+']').html(msg['cognome']);
			$('span[name=nome-'+id+']').html(msg['nome']);
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Confratello non trovato', 1);
			ajax_error_show(msg);
			btn.button('reset');
		}
	});
});

$('button[name=salva]').click(function(){
	var btn = $(this);
	var id = $(this).val(); //id_incarico
	var id_confratello = $('input[name=id_confratello-'+id+']').val();
	if(id_confratello == ""){
		$('input[name=id_confratello-'+id+']').focus();
		my_alert('Riempire i campi obbligatori', 1);
		return false;
	}
	var data_inizio = $('input[name=data_inizio-'+id+']').val();
	if(isDate(data_inizio) == false){
		$('input[name=data_inizio-'+id+']').focus();
		my_alert('Data non valida', 1);
		return false;
	}
	var note = $('textarea[name=note-'+id+']').val();
	var modifica_riassegna = $('p[name=modifica_riassegna-'+id+']').val();
	var incarico_confratello_id = $('input[name=incarico_confratello_id-'+id+']').val();
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('incarichi/ajax_assegna_incarico'); ?>",
		type: "POST",
		dataType: "json",
		data: "id_incarico="+id+"&id_confratello="+id_confratello+"&data_inizio="+data_inizio+"&note="+note+
		'&modifica_riassegna='+modifica_riassegna+'&incarico_confratello_id='+incarico_confratello_id,
		success: function(msg) {
			my_alert('Incarico assegnato', 0);
			$('input[name=data_inizio-'+id+']').addClass('hidden');
			$('span[name=data_inizio-'+id+']').html(data_inizio);
			$('span[name=data_inizio-'+id+']').removeClass('hidden');
			$('span[name=codice-'+id+']').removeClass('hidden');
			$('div[name=codice-'+id+']').addClass('hidden');
			$('button[value='+id+'][name=modifica]').removeClass('hidden');
			$('button[value='+id+'][name=salva]').addClass('hidden');
			$('button[value='+id+'][name=annulla]').addClass('hidden');
			$('button[value='+id+'][name=riassegna]').removeClass('hidden');
			$('textarea[name=note-'+id+']').attr('disabled', 'disabled');
			$('button[value='+id+'][name=modifica]').removeAttr('disabled');
			$('p[name=modifica_riassegna-'+id+']').addClass('hidden');
			$('input[name=incarico_confratello_id-'+id+']').val(msg['incarico_confratello_id']);
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Incarico non assegnato', 1);
			ajax_error_show(msg);
			btn.button('reset');
		}
	});
});
</script>