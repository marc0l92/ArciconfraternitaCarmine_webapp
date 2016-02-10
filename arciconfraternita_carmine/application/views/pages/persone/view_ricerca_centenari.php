<script src="<?php echo base_url();?>resources/js/jquery-1.10.2.min.js"></script>
<style type="text/css">
.hidden
{
	display: none;
}
</style>
<form id="form" method="POST" action="<?php echo site_url('persone/cerca_persone_result'); ?>">
	<input name="intervallo_data_nascita" class="hidden" value="intervallo">
	<input name="data_nascita_inizio" type="text" class="hidden" placeholder="Inizio" value="01/01/<?php echo $inizio; ?>">
	<input name="data_nascita_fine" type="text" class="hidden" placeholder="Fine" value="31/12/<?php echo $fine; ?>">
	<input name="intervallo_data_professione" class="hidden" value="intervallo">
	<input name="data_professione_inizio" type="text" class="hidden" placeholder="Inizio" value="">
	<input name="data_professione_fine" type="text" class="hidden" placeholder="Fine" value="">
	<input name="confratelli" class="hidden" value="">
	<div class="hidden">
		<p>Se la pagina non viene caricata premere CONTINUA</p>
		<input type="submit" value="Continua">
	</div>
</form>
<script type="text/javascript">
$('#form').submit();
</script>