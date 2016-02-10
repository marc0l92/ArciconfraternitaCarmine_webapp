<?php
if(!isset($tabella) || count($tabella) == 0){
	echo 'Nessuna celletta trovata in questa sezione';
	return;
}
?>
<div class="row">
	<div class="col-lg-12">
		<h4>
			<span class="pull-left"><a href="<?php echo site_url('cappella_gentilizia/visualizza_cellette'); ?>"><?php echo $tabella[0]['cappella_nome']; ?></a></span>
			<span class="ui-icon ui-icon-carat-1-e pull-left"></span>
			<span class="pull-left"><?php echo $tabella[0]['piano']; ?></span>
			<span class="ui-icon ui-icon-carat-1-e pull-left"></span>
			<span class="pull-left"><?php echo $tabella[0]['sezione']; ?></span>
		</h4>
	</div>
</div>
<div class="row space"></div>
<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered text-center">
			<tbody>
				<?php for($i=1; $i <= $size['max_row']; $i++){?>
				<tr>
					<?php for($j=1; $j <= $size['max_column']; $j++){ ?>
					<td row="<?php echo $i; ?>" column="<?php echo $j;?>" class="disabled">
						<a row="<?php echo $i;?>" column="<?php echo $j;?>" class="disabled">
							<div>
								<h4 row="<?php echo $i;?>" column="<?php echo $j;?>">Non disponibile</h4>
								<p>Posizione: [<?php echo $i.'; '.$j;?>]<span name="tipo" row="<?php echo $i;?>" column="<?php echo $j;?>"></span></p>
								<p name="p1" row="<?php echo $i;?>" column="<?php echo $j;?>">-</p>
								<p name="p2" row="<?php echo $i;?>" column="<?php echo $j;?>">-</p>
							</div>
						</a>
					</td>
					<?php } ?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<p>* l'acquirente è un defunto.</p>
</div>
<script type="text/javascript">
$(document).ready(function(){
	<?php foreach ($tabella as $key => $value) { 
		$background = 1;
		?>
		$('a[row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').removeClass('disabled');
		$('td[row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').removeClass('disabled');
		$('a[row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').attr('href', "<?php echo site_url('stampe/stampa_celletta').'/'.$value['id_celletta']; ?>");
		$('span[name=tipo][row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').html('[<?php echo $value["tipo"]; ?>]');
		<?php if($value["defunto_description"] != ''){ 
			$background *= 2;
			?>
			$('h4[row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').html('Defunto: <?php echo $value["defunto_description"]; ?>');			
			<?php
		}else{ ?>
			$('h4[row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').html('Libero');
			<?php
		} ?>
		<?php if($value["acquirente_description"] != ''){ 
			$background *= 3;?>
			$('p[name=p1][row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').html('Acquirente: <?php echo $value["acquirente_description"]; ?>');
			<?php
		} ?>
		<?php if($value["defunto_acquirente_description"] != ''){ 
			$background *= 3;?>
			$('p[name=p1][row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').html('Acquirente*: <?php echo $value["defunto_acquirente_description"]; ?>');
			<?php
		} ?>
		<?php if($value["responsabile_description"] != ''){ 
			$background *= 3;?>
			$('p[name=p2][row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').html('Responsabile: <?php echo $value["responsabile_description"]; ?>');
			<?php 
		} 
		if($background == 1){
			//nessuna informazione
			$str_background = 'si';
		}else{
			if($background % 2 == 0){
				//c'è un defunto all'interno
				$str_background = 'no';
			}else{
				//nessun defunto ma c'è qualche info
				$str_background = 'forse';
			}
		}
		?>
		$('td[row=<?php echo $value["fila"]; ?>][column=<?php echo $value["numero"]; ?>]').addClass('<?php echo $str_background; ?>-background');
		<?php
	} ?>
	//fuori dal foreach
	//$('td.disabled').html($('a.disabled').html());
});
</script>