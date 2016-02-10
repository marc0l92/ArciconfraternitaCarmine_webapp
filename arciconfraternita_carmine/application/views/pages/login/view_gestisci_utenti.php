<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Nome utente*</th>
			<th>Password*</th>
			<th>Conferma password*</th>
			<th>Azioni</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($utenti as $key => $value) { ?>
		<tr id_utente="<?php echo $value['id_utente'];?>">
			<td><input type="text" name="username" value="<?php echo $value['nomeutente'];?>" id_utente="<?php echo $value['id_utente'];?>" disabled></td>
			<td><input type="password" name="password" value="password" id_utente="<?php echo $value['id_utente'];?>" disabled></td>
			<td><input type="password" name="password2" value="password" id_utente="<?php echo $value['id_utente'];?>" disabled></td>
			<td>
				<?php if($value['nomeutente'] == $this->session->userdata('username')){ ?>
				<button name="modifica" type="button" class="btn btn-default btn-mini" value="<?php echo $value['id_utente']; ?>">Modifica</button>
				<button name="salva" type="button" class="btn btn-default btn-mini hidden" value="<?php echo $value['id_utente']; ?>" data-loading-text="Caricamento...">Salva</button>
				<button name="annulla" type="button" class="btn btn-default btn-mini hidden" value="<?php echo $value['id_utente']; ?>">Annulla</button>
				<button name="elimina" type="button" class="btn btn-default btn-mini" value="<?php echo $value['id_utente']; ?>">Elimina</button>
				<?php }else{ 
					if($this->session->userdata('admin_enabled') == '1'){?>
					<button name="modifica" type="button" class="btn btn-default btn-mini" value="<?php echo $value['id_utente']; ?>">Modifica</button>
					<button name="salva" type="button" class="btn btn-default btn-mini hidden" value="<?php echo $value['id_utente']; ?>" data-loading-text="Caricamento...">Salva</button>
					<button name="annulla" type="button" class="btn btn-default btn-mini hidden" value="<?php echo $value['id_utente']; ?>">Annulla</button>
					<button name="elimina" type="button" class="btn btn-default btn-mini" value="<?php echo $value['id_utente']; ?>">Elimina</button>
					<?php }
				} ?>
			</td>
		</tr>
		<?php }?>
	</tbody>
	<tbody id="tbody2" class="hidden">
		<tr id_utente="0">
			<td><input type="text" name="username" value="" id_utente="0"></td>
			<td><input type="password" name="password" value="" id_utente="0"></td>
			<td><input type="password" name="password2" value="" id_utente="0"></td>
			<td>
				<button name="salva" type="button" class="btn btn-default btn-mini" value="0" data-loading-text="Caricamento...">Salva</button>
			</td>
		</tr>
	</tbody>
</table>
<div class="row space"></div>
<button type="button" name="nuovo_utente" class="btn btn-default">+ Crea un nuovo utente</button>
<script type="text/javascript">
$('button[name=elimina]').click(function(){
	if (confirm("Sei sicuro di volerlo eliminare?")){
		var btn = $(this);
		var id_utente = $(this).val();
		btn.button('loading');
		$.ajax({
			url: "<?php echo site_url('login/ajax_elimina_utente'); ?>",
			type: "POST",
			dataType: "json",
			data: "id_utente="+id_utente,
			success: function(msg) {
				my_alert('Utente eliminato', 0);
				$('tr[id_utente='+id_utente+']').hide(1500);
				btn.button('reset');
			},
			error : function (msg) {
				my_alert('Utente non eliminato', 1);
				ajax_error_show(msg);
				btn.button('reset');
			}
		});
	}
});

$('button[name=nuovo_utente]').click(function(){
	$('#tbody2').removeClass('hidden');
	$('button[name=nuovo_utente]').addClass('hidden');
});

$('button[name=modifica]').click(function(){
	var id_utente = $(this).val();
	$('input[id_utente='+id_utente+']').removeAttr('disabled');
	$('button[value='+id_utente+']').addClass('hidden');
	$('button[name=salva][value='+id_utente+']').removeClass('hidden');
	$('button[name=annulla][value='+id_utente+']').removeClass('hidden');
	$('button[name=nuovo_utente]').addClass('hidden');
	$('input[id_utente='+id_utente+'][name=password]').val('');
	$('input[id_utente='+id_utente+'][name=password2]').val('');
});

$('button[name=annulla]').click(function(){
	var id_utente = $(this).val();
	$('input[id_utente='+id_utente+']').attr('disabled', 'disabled');
	$('button[value='+id_utente+']').addClass('hidden');
	$('button[name=modifica][value='+id_utente+']').removeClass('hidden');
	$('button[name=elimina][value='+id_utente+']').removeClass('hidden');
	$('button[name=nuovo_utente]').removeClass('hidden');
	$('input[id_utente='+id_utente+'][name=password]').val('password');
	$('input[id_utente='+id_utente+'][name=password2]').val('password');
});

$('button[name=salva]').click(function(){
	var btn = $(this);
	var id_utente = $(this).val();
	var username = $('input[id_utente='+id_utente+'][name=username]').val();
	var password = $('input[id_utente='+id_utente+'][name=password]').val();
	var password2 = $('input[id_utente='+id_utente+'][name=password2]').val();
	if(password != password2){
		my_alert('Le password non coincidono', 1);
		return false;
	}
	if(password == ""){
		$('input[id_utente='+id_utente+'][name=password]').focus();
		my_alert('Riempire i campi obbligatori', 1);
		return false;
	}
	if(username == ""){
		$('input[id_utente='+id_utente+'][name=username]').focus();
		my_alert('Riempire i campi obbligatori', 1);
		return false;
	}
	btn.button('loading');
	$.ajax({
		url: "<?php echo site_url('login/ajax_modifica_utente'); ?>",
		type: "POST",
		dataType: "json",
		data: "id_utente="+id_utente+"&username="+username+"&password="+password,
		success: function(msg) {
			my_alert('Utente salvato', 0);
			$('input[id_utente='+id_utente+']').attr('disabled', 'disabled');
			$('button[value='+id_utente+']').addClass('hidden');
			$('button[name=modifica][value='+id_utente+']').removeClass('hidden');
			$('button[name=elimina][value='+id_utente+']').removeClass('hidden');
			$('input[id_utente='+id_utente+'][name=password]').val('password');
			$('input[id_utente='+id_utente+'][name=password2]').val('password');
			if(id_utente == 0){
				location.reload();
			}else{
				$('button[name=nuovo_utente]').removeClass('hidden');
			}
			btn.button('reset');
		},
		error : function (msg) {
			my_alert('Utente non salvato', 1);
			ajax_error_show(msg);
			btn.button('reset');
		}
	});
});
</script>