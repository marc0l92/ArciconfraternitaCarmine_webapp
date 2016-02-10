<?php 
if(!isset($name) || $name == '') $name = 'id_cappella';
if(!isset($star)) $star = 0;
?>

<?php if($star == 1){ ?>
<label>Cappella*</label>
<?php }else{ ?>
<label>Cappella</label>
<?php } ?>
<select name="<?php echo $name; ?>" class="form-control" data-content="" <?php if(isset($popover)) echo 'data-placement="'.$popover.'"'; ?>>
	<?php if($star != 1){ ?>
	<option value="">Tutte</option>
	<?php } ?>
	<?php if(count($cappelle) == 0){ ?>
	<option value="0">NON CI SONO CAPPELLE, CREANE QUALCUNA</option>
	<?php } ?>
	<?php foreach ($cappelle as $key => $value) { ?>
	<option value="<?php echo $value['id_cappella'];?>" data-content="<?php echo $value['note']; ?>"><?php echo $value['nome'];?></option>
	<script type="text/javascript">
	$('option[value=<?php echo $value['id_cappella'];?>]').mouseenter(function(){
		$('select[name=<?php echo $name; ?>]').removeAttr('data-content');
		$('select[name=<?php echo $name; ?>]').attr('data-content', $(this).attr('data-content'));
		$('select[name=<?php echo $name; ?>]').popover('show');
	});
	</script>
	<?php } ?>
</select>
<script type="text/javascript">
$('select[name=<?php echo $name; ?>]').blur(function(){
	$('select[name=<?php echo $name; ?>]').popover('hide');
});
$('option[value=]').mouseenter(function(){
	$('select[name=<?php echo $name; ?>]').popover('hide');
});
</script>