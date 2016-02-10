<?php
// FKMS = foreign key managment system
if(!isset($annulla_btn)) 		$annulla_btn 		= true;
if(!isset($set_null_btn)) 		$set_null_btn 		= false;
if(!isset($delete_btn)) 		$delete_btn 		= false;
if(!isset($continue_btn)) 		$continue_btn 		= false;
if(!isset($nuova_persona_btn)) 	$nuova_persona_btn 	= false;
if(!isset($nuova_celletta_btn)) $nuova_celletta_btn = false;
if(!isset($error_message)) 		$error_message 		= 'Ci è stato un conflitto con una chiave esterna.';
?>

<div class="modal-body">
	<p><?php echo $error_message; ?></p>
</div>
<div class="modal-footer">
	<div class="row">
		<div class="col-lg-12">
			<?php if($delete_btn == true){ ?>
			<button id="fkms-delete" type="button" class="btn btn-default pull-right">Cancella righe</button>
			<?php } ?>
			<?php if($set_null_btn == true){ ?>
			<button id="fkms-set_null" type="button" class="btn btn-default pull-right">Imposta a NULL</button>
			<?php } ?>
			<?php if($continue_btn != false){ ?>
			<button id="fkms-continue" type="button" class="btn btn-default pull-right">Continua</button>
			<?php } ?>
			<?php if($annulla_btn == true){ ?>
			<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Annulla</button>
			<?php } ?>
		</div>
	</div>
</div>
<input name="FKMS_ignore" value="0" class="hidden">
<script type="text/javascript">
<?php if($set_null_btn == true){ ?>
	$('#fkms-set_null').click(function(){

	});
	<?php 
} ?>

<?php if($delete_btn == true){ ?>
	$('#fkms-delete').click(function(){

	});
	<?php 
} ?>

<?php if($continue_btn != false){ ?>
	$('#fkms-continue').click(function(){
		$('input[name=FKMS_ignore]').val('1');
		<?php echo $continue_btn.'();'; ?>
		$('#modal-ajax_error').modal('hide');
	});
	<?php 
} ?>
</script>