<?php
//id incrementale che permette di distinguere le varie sezioni in modo da poterle nascondere alla stampa
$id_sezione = 0;
//serve per evitare che il primo titolo abbia uno spazio sopra di lui
$primo = true;
?>
<div class="row" id_sezione="<?php echo $id_sezione; ?>">
	<?php 
	$col = 0;
	foreach ($data as $key => $value) {
		if(!isset($value['value'])) $value['value'] = '';
		if(!isset($value['name'])) $value['name'] = '';
		if(!isset($value['dimension'])) $value['dimension'] = 6;
		?>
		<?php
		if($col + $value['dimension'] > 12){
			$col = 0;
			?>
		</div>
		<div class="row" id_sezione="<?php echo $id_sezione; ?>">
			<?php
		} ?>
		<span class="col-lg-<?php echo $value['dimension']; ?>">
			<?php if($value['value'] == '#'){ ?>
			<?php $id_sezione++; ?>
			<?php if(!$primo){ ?>
			<div class="row space" id_sezione="<?php echo $id_sezione; ?>"></div>
			<?php } ?>
			<h4 class="pull-left"><span class="dot-list"></span><?php echo $value['name']; ?></h4>
			<p class="pull-right hidden-print" id="cb_stampa"><input id_sezione="<?php echo $id_sezione; ?>" type="checkbox" checked="checked" id=""><span class="space-left">Visualizza nella stampa</span></p>
			<?php }else{ ?>
			<?php if($value['value'] == 'linea'){ ?>
			<div class="linea"></div>
			<?php }else{ ?>
			<label><?php if($value['name'] != '') echo $value['name'].' :'; ?></label><span class="space-left"><?php if($value['value'] != '' || $value['name'] == '') echo $value['value']; else echo '-'; ?></span>
			<?php } ?>
			<?php } ?>
		</span>
		<?php
		$col += $value['dimension'];
	} 
	?>
</div>
<div class="row space"></div>
<ul class="pager">
	<?php if (isset($previous_link)){ ?>
	<li class="previous hidden-print"><a href="<?php echo $previous_link; ?>">&larr; Precedente</a></li>
	<?php }else{ ?>
	<li class="previous disabled hidden-print"><a href="#">&larr; Precedente</a></li>
	<?php } ?>
	<li class="next hidden-print"><a href="<?php echo $next_link; ?>">Successivo &rarr;</a></li>
</ul>

<script type="text/javascript">

	$('h4').each(function(){
		$(this).parent().parent().attr('id_sezione', parseInt($(this).parent().parent().attr('id_sezione'))+1);
	});

	$('input[type=checkbox]').change(function(){
		var cheched = $(this).prop("checked");
		if(cheched == false){
			$('div[id_sezione='+$(this).attr('id_sezione')+']').addClass('hidden-print');
		}else{
			$('div[id_sezione='+$(this).attr('id_sezione')+']').removeClass('hidden-print');
		}
	});
</script>
<?php $this->load->view('parts/intro/view_intro_stampa_riepilogo'); ?>