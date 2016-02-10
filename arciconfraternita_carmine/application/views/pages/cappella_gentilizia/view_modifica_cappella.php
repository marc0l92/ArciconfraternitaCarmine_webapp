<?php foreach ($cappelle as $key => $value) { ?>
<form>
	<div class="form-group panel" id='panel-<?php echo $value['id_cappella']; ?>'>
		<div class="row">
			<div class="col-lg-6">
				<label>Nome*</label>
				<input name="nome-<?php echo $value['id_cappella']; ?>" type="text" class="form-control" placeholder="Nome della cappella" value="<?php echo $value['nome']; ?>" disabled>
			</div>
		</div>
		<div class="row no-margin">
			<div class="col-lg-12">
				<label>Note</label>
				<textarea name="note-<?php echo $value['id_cappella']; ?>" class="form-control" disabled><?php echo $value['note']; ?></textarea>
			</div>
		</div>
		<div class="row space"></div>
		<div class="row">
			<div class="col-lg-12">
				<button name="salva" type="button" class="btn btn-default pull-right" value="<?php echo $value['id_cappella']; ?>" data-loading-text="Caricamento..." disabled>Salva</button>
				<button name="elimina" type="button" class="btn btn-default pull-right" value="<?php echo $value['id_cappella']; ?>" data-loading-text="Caricamento..." disabled>Elimina</button>
				<button name="modifica" type="button" class="btn btn-default pull-right" value="<?php echo $value['id_cappella']; ?>">Modifica</button>
			</div>
		</div>
	</div>
</form>
<?php } ?>
<small>* Riempire i campi obbligatori</small>

<script type="text/javascript">
$('button[name=salva]').click(function(){
	var btn = $(this);
	var id = $(this).val();
	var nome = $('input[name=nome-'+id+']').val();
	if(nome == ""){
		$('input[name=nome]').focus();
		my_alert('Riempire i campi obbligatori', 1);
		return false;
	}
	var note = $('textarea[name=note-'+id+']').val();
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('cappella_gentilizia/ajax_modifica_cappella'); ?>",
		type: "POST",
		dataType: "json",
		data: "nome="+nome+"&note="+note+'&id_cappella='+id,
		success: function(msg) {
			my_alert('Cappella modificata', 0);
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Cappella non modificata', 1);
			ajax_error_show(msg);
			btn.button('reset');
		}
	});
});

$('button[name=elimina]').click(function(){
	if (confirm("Sei sicuro di volerla eliminare?")){
		var btn = $(this);
		var id = $(this).val();
		btn.button('loading');
		$.ajax({
			url: "<?php echo site_url('cappella_gentilizia/ajax_elimina_cappella'); ?>",
			type: "POST",
			dataType: "json",
			data: 'id_cappella='+id,
			success: function(msg) {
				if(msg['eliminato'] == true){
					my_alert('Cappella eliminata', 0);
					$('#panel-'+id).hide(800);
				}else{
					my_alert('La cappella non può essere eliminata', 1);
				}
				btn.button('reset');
			},
			error : function (msg) {
				my_alert('Cappella non eliminata', 1);
				ajax_error_show(msg);
				btn.button('reset');
			}
		});
	}
});

$('button[name=modifica]').click(function(){
	var id = $(this).val();
	$('input[name=nome-'+id+']').removeAttr('disabled');
	$('textarea[name=note-'+id+']').removeAttr('disabled');
	$('button[value='+id+']').removeAttr('disabled');
	$(this).attr('disabled','disabled');
});
</script>