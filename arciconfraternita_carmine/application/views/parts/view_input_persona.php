<?php 
if(!isset($name)) $name = 'persona';
if(!isset($id)) $id = 0;
if(!isset($crea_nuovo_btn)) $crea_nuovo_btn = true;
if(!isset($default_id_value)) $default_id_value = 0;
?>

<!-- INPUT-->
<label style="text-transform: capitalize;"><?php echo $name; ?></label>
<div class="input-group">
	<?php if($crea_nuovo_btn){ ?>
	<span class="input-group-btn">
		<button class="btn btn-default" type="button" name="nuova-persona" id-input="<?php echo $id; ?>" nome-input="<?php echo $name; ?>">Crea nuovo</button>
	</span>
	<?php } ?>
	<input name="description-persona" type="text" class="form-control" placeholder="Aggiungi <?php echo $name; ?>" id-input="<?php echo $id; ?>" control="disabled" disabled>
	<button type="button" class="close svuota" name="svuota" id-input="<?php echo $id; ?>">&times;</button>
	<span class="input-group-btn">
		<button class="btn btn-default" type="button" name="cerca-persona" id-input="<?php echo $id; ?>" nome-input="<?php echo $name; ?>">Cerca</button>
	</span>
</div>
<input name="id-persona<?php echo $id; ?>" id-input="<?php echo $id; ?>" type="text" class="form-control hidden" value="<?php echo $default_id_value; ?>">

<script type="text/javascript">

//funzioni delle input associate al modal

$('button[name=cerca-persona]').click(function(){
	modal_persona($(this).attr('nome-input'), $(this).attr('id-input'), false);
});

<?php if($crea_nuovo_btn){ ?>
$('button[name=nuova-persona]').click(function(){
	modal_persona($(this).attr('nome-input'), $(this).attr('id-input'), true);
});
<?php } ?>

$('button[name=svuota]').click(function(){
	$('input[name=description-persona][id-input='+$(this).attr('id-input')+']').val('');
	$('input[name=id-persona'+$(this).attr('id-input')+'][id-input='+$(this).attr('id-input')+']').val('<?php echo $default_id_value; ?>');
});
</script>