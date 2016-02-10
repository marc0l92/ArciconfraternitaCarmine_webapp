<?php 
if(!isset($name)) $name = 'defunto';
if(!isset($id)) $id = 0;
// valore iniziale da dare all'input che contiene l'id
if(!isset($default_id_value)) $default_id_value = 0;
if(!isset($crea_nuovo_btn)) $crea_nuovo_btn = true;
if(!isset($requested)) $requested = false;
?>

<!-- INPUT-->
<label style="text-transform: capitalize;"><?php echo $name; ?><?php echo ($requested)?'*':'';?></label>
<div class="input-group">
	<?php if($crea_nuovo_btn){ ?>
	<span class="input-group-btn">
		<button class="btn btn-default" type="button" name="nuovo-defunto" id-input="<?php echo $id; ?>" nome-input="<?php echo $name; ?>">Crea nuovo</button>
	</span>
	<?php } ?>
	<input name="description-defunto" type="text" class="form-control" placeholder="Aggiungi <?php echo $name; ?>" id-input="<?php echo $id; ?>" control="disabled" disabled>
	<button type="button" class="close svuota" name="svuota" id-input="<?php echo $id; ?>">&times;</button>
	<span class="input-group-btn">
		<button class="btn btn-default" type="button" name="cerca-defunto" id-input="<?php echo $id; ?>" nome-input="<?php echo $name; ?>">Cerca</button>
	</span>
</div>
<input name="id-defunto<?php echo $id; ?>" id-input="<?php echo $id; ?>" type="text" class="form-control hidden" value="<?php echo $default_id_value; ?>" <?php echo ($requested)?'control="not_null"':'';?>>

<script type="text/javascript">

//funzioni delle input associate al modal

$('button[name=cerca-defunto]').click(function(){
	modal_defunto($(this).attr('nome-input'), $(this).attr('id-input'), false);
});

<?php if($crea_nuovo_btn){ ?>
$('button[name=nuovo-defunto]').click(function(){
	modal_defunto($(this).attr('nome-input'), $(this).attr('id-input'), true);
});
<?php } ?>

$('button[name=svuota]').click(function(){
	$('input[name=description-defunto][id-input='+$(this).attr('id-input')+']').val('');
	$('input[name=id-defunto'+$(this).attr('id-input')+'][id-input='+$(this).attr('id-input')+']').val('<?php echo $default_id_value; ?>');
});
</script>