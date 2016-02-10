<table class="table">
	<thead>
		<tr>
			<?php 
			$i=0;
			foreach ($nome_colonne as $key_colonne => $value_colonne) { ?>
			<th><?php echo $value_colonne; $i++; ?></th>
			<?php }?>
			<th>Azioni</th>
		</tr>
	</thead>
	<tbody>
		<?php if($tabella != array()){ ?>
		<?php foreach ($tabella as $key_riga => $riga) { ?>
		<tr>
			<?php foreach ($nome_colonne as $key_colonne => $value_colonne) { ?>
			<td>
				<?php
				if(isset($riga[$key_colonne]) && $riga[$key_colonne]!=''){
					echo $riga[$key_colonne];
				}else{
					echo '-';
				}
				?>
			</td>
			<?php }?>
			<td>
				<a href="<?php echo site_url('cappella_gentilizia/nuova_celletta').'/'.$riga['id_celletta']; ?>">
					<button type="button" class="btn btn-default btn-mini">Modifica</button>
				</a>
				<a href="<?php echo site_url('stampe/stampa_celletta').'/'.$riga['id_celletta']; ?>">
					<button type="button" class="btn btn-default btn-mini">Stampa</button>
				</a>
			</td>
		</tr>
		<?php }?>
		<?php }else{ ?>
		<td colspan="<?php echo $i+1; ?>">Nessun risultato trovato.</td>
		<?php }?>
	</tbody>
</table>
<div class="row space"></div>
<div class="row space"></div>
<p>* la celletta è acquistata da un defunto</p>
<div class="row space"></div>
<div class="panel">
	<div class="panel-heading">Riepilogo ricerca</div>
	<div class="row">
		<div class="col-lg-12">
			<strong>Numero di risultati trovati:</strong> <?php echo count($tabella); ?>
		</div>
	</div>
	<div class="row">
		<?php foreach ($query as $key => $value) {
			if($value != ''){ ?>
			<div class="col-lg-4"><strong><?php echo $key;?>: </strong><?php echo $value;?></div>
			<?php }
		}?>
	</div>
</div>

<script type="text/javascript">

$("td:contains('Sì')").addClass('si-background');
$("td:contains('Sì')").addClass('text-center');
$("td:contains('No')").addClass('no-background');
$("td:contains('No')").addClass('text-center');

</script>
<?php $this->load->view('parts/intro/view_intro_search_result'); ?>