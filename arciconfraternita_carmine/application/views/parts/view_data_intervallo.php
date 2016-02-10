<label><?php echo $label;?></label>
<div class="well well-small">
	<div class="row">
		<div class="col-lg-4">
			<select name="intervallo_<?php echo $name;?>" class="form-control" data-content="">
				<option value="intervallo" data-content="Scelgi la data di inizio e la data di fine dell'intervallo di ricerca" selected>Intervallo</option>
				<option value="prima" data-content="Fai una ricerca prendendo i dati precedenti al giorno scelto">Prima del giorno</option>
				<option value="dopo" data-content="Fai una ricerca prendendo i dati successivi al giorno scelto">Dopo il giorno</option>
				<option value="solo" data-content="Fai una ricerca prendendo i dati su un singolo giorno">Solo il giorno</option>
			</select>
			<script type="text/javascript">
			$('select[name=intervallo_<?php echo $name;?>] > option').mouseenter(function(){
				$('select[name=intervallo_<?php echo $name;?>]').removeAttr('data-content');
				$('select[name=intervallo_<?php echo $name;?>]').attr('data-content', $(this).attr('data-content'));
				$('select[name=intervallo_<?php echo $name;?>]').popover('show');
			});
			$('select[name=intervallo_<?php echo $name;?>]').blur(function(){
				$(this).popover('hide');
			});
			</script>
		</div>
		<div name="<?php echo $name;?>_intervallo" class="col-lg-4">
			<input name="<?php echo $name;?>_inizio" type="text" class="form-control" placeholder="Inizio">
		</div>
		<div name="<?php echo $name;?>_intervallo" class="col-lg-4">
			<input name="<?php echo $name;?>_fine" type="text" class="form-control" placeholder="Fine">
		</div>
		<div name="<?php echo $name;?>_prima" class="col-lg-8 hidden">
			<input name="<?php echo $name;?>_prima" type="text" class="form-control" placeholder="Prima del giorno">
		</div>
		<div name="<?php echo $name;?>_dopo" class="col-lg-8 hidden">
			<input name="<?php echo $name;?>_dopo" type="text" class="form-control" placeholder="Dopo il giorno">
		</div>
		<div name="<?php echo $name;?>_solo" class="col-lg-8 hidden">
			<input name="<?php echo $name;?>_solo" type="text" class="form-control" placeholder="Solo il giorno">
		</div>
	</div>
</div>


<script type="text/javascript">
$('select[name=intervallo_<?php echo $name;?>]').change(function(){
	var tipo = $('select[name=intervallo_<?php echo $name;?>]').val();
	$('div[name=<?php echo $name;?>_intervallo]').addClass('hidden');
	$('div[name=<?php echo $name;?>_prima]').addClass('hidden');
	$('div[name=<?php echo $name;?>_dopo]').addClass('hidden');
	$('div[name=<?php echo $name;?>_solo]').addClass('hidden');
	$('div[name=<?php echo $name;?>_'+tipo+']').removeClass('hidden');
	$('div[name=<?php echo $name;?>_'+tipo+'] > input').val('');
});
my_datapicker('input[name=<?php echo $name;?>_inizio]');
my_datapicker('input[name=<?php echo $name;?>_fine]');
my_datapicker('input[name=<?php echo $name;?>_prima]');
my_datapicker('input[name=<?php echo $name;?>_dopo]');
my_datapicker('input[name=<?php echo $name;?>_solo]');
</script>