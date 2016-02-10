<?php
//nel caso è disabilitata la procedura di aggiornamento
//if(!isset($aggiornamento)) $aggiornamento = 'success';
?>
<?php //if($aggiornamento == 'success'){ ?>
<!--div id="alert-update" class="alert alert-success">
	<span class="ui-icon ui-icon-check pull-left"></span>Non ci sono nuovi aggiornamenti, si dispone dell'ultima versione.
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#alert-update').delay('5000').hide('400');
});
</script-->
<?php //}else{ ?>
<?php //if($aggiornamento == 'error'){ ?>
<!--div id="alert-update" class="alert alert-danger">
	<span class="ui-icon ui-icon-close pull-left"></span>Impossibile verificare la presenza di nuovi aggiornamenti.
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#alert-update').delay('5000').hide('400');
});
</script-->
<?php //}else{ ?>
<!--div id="alert-update" class="alert alert-warning">
	<span class="ui-icon ui-icon-check pull-left"></span>Aggiornamento trovato [ v <?php //echo $aggiornamento; ?> ].
	<button href="#modal-update" data-toggle="modal" type="button" class="btn btn-default btn-mini pull-right">Aggiorna</button>
</div-->
<?php //$this->load->view('parts/view_modal_update.php', $aggiornamento_data); ?>
<?php //} ?>
<?php //} ?>
<div class="list-group">
	<a href="<?php echo site_url('persone');?>" class="list-group-item">
		<h4 class="list-group-item-heading"><span class="ui-icon ui-icon-person pull-left"></span>Persone</h4>
		<p class="list-group-item-text">Gestisci l'inserimento modifica e visualizzazione dei confratelli e persone esterne.</p>
	</a>
</div>
<div class="list-group">
	<a href="<?php echo site_url('defunti');?>" class="list-group-item">
		<h4 class="list-group-item-heading"><span class="ui-icon ui-icon-script pull-left"></span>Defunti</h4>
		<p class="list-group-item-text">Gestisci l'inserimento modifica e visualizzazione defunti.</p>
	</a>
</div>
<div class="list-group">
	<a href="<?php echo site_url('cappella_gentilizia');?>" class="list-group-item">
		<h4 class="list-group-item-heading"><span class="ui-icon ui-icon-home pull-left"></span>Cappella gentilizia</h4>
		<p class="list-group-item-text">Gestisci l'inserimento modifica e visualizzazione delle cappelle, sepolturee piloni.</p>
	</a>
</div>
<div class="list-group">
	<a href="<?php echo site_url('stampe');?>" class="list-group-item">
		<h4 class="list-group-item-heading"><span class="ui-icon ui-icon-print pull-left"></span>Stampe</h4>
		<p class="list-group-item-text">Stampa i dati su carta.</p>
	</a>
</div>
<div class="list-group" >
	<a href="<?php echo site_url('altro');?>" class="list-group-item">
		<h4 class="list-group-item-heading"><span class="ui-icon ui-icon-wrench pull-left"></span>Altro</h4>
		<p class="list-group-item-text">Gestisci back-up, utenti e ripristino.</p>
	</a>
</div>
<?php $this->load->view('parts/intro/view_intro_home'); ?>