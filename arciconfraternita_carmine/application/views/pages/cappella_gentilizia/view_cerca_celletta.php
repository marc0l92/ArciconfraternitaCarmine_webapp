<?php $this->load->view('parts/view_modal_persona.php'); ?>
<form method="POST" action="<?php echo site_url('cappella_gentilizia/cerca_celletta_result'); ?>">
	<div class="row">
		<div class="col-lg-12 form-group">
			<h4>Posizione</h4>
			<div class="row">
				<div class="col-lg-3">
					<?php $this->load->view('parts/view_select_cappella.php', array('star' => 0)); ?>
				</div>
				<div class="col-lg-2">
					<label>Piano</label>
					<input name="piano" type="text" class="form-control" placeholder="N. Piano">
				</div>
				<div class="col-lg-2">
					<label>Sezione</label>
					<input name="sezione" type="text" class="form-control" placeholder="N. Sezione">
				</div>
				<div class="col-lg-2">
					<label>Fila</label>
					<input name="fila" type="text" class="form-control" placeholder="N. Fila">
				</div>
				<div class="col-lg-3">
					<label>Numero</label>
					<input name="numero" type="text" class="form-control" placeholder="Numero">
				</div>
			</div>
			<h4>Informazioni</h4>
			<div class="row">
				<div class="col-lg-3">
					<label>Tipo</label>
					<select name="tipo" class="form-control">
						<option value="">Tutti</option>
						<option value="celletta">Celletta</option>
						<option value="sepoltura">Sepoltura</option>
						<option value="pilone">Pilone</option>
					</select>
				</div>
				<div class="col-lg-3">
					<label>Codice bolletta</label>
					<input name="codice_bolletta" type="text" class="form-control" placeholder="Codice bolletta">
				</div>
				<div class="col-lg-6">
					<?php 
					$array = array('name' => 'responsabile', 'crea_nuovo_btn' => false, 'id' => '_responsabile', 'default_id_value' => '');
					$this->load->view('parts/view_input_persona.php', $array); 
					?>
				</div>

			</div>
			<div class="row">
				<div class="col-lg-6">
					<?php 
					$array = array('name' => 'acquirente', 'crea_nuovo_btn' => false, 'id' => '_acquirente', 'default_id_value' => '');
					$this->load->view('parts/view_input_persona.php', $array); ?>
				</div>

				<div class="col-lg-6">
					<?php
					$array = array('label' => 'Data concessione', 'name' => 'data_concessione');
					$this->load->view('parts/view_data_intervallo.php', $array);
					?>
				</div>
			</div>
			<div class="row space"></div>
			<div name="dati_pilone" style="display:none;">
				<legend>Dati pilone</legend>
				<div class="row">
					<div class="col-lg-3">
						<label>Cappella</label>
						<select name="cappella_pilone" class="form-control" disabled>
							<option value="">Tutte</option>
							<?php foreach ($cappelle as $key => $value) { ?>
							<option value="<?php echo $value['id_cappella'];?>" title="<?php echo $value['note']; ?>"><?php echo $value['nome'];?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-lg-3">
						<label>Piano</label>
						<input name="piano_pilone" type="text" class="form-control" placeholder="N. Piano" disabled>
					</div>
					<div class="col-lg-3">
						<label>Sezione</label>
						<input name="sezione_pilone" type="text" class="form-control" placeholder="N. Sezione" disabled>
					</div>
					<div class="col-lg-3">
						<label>Numero</label>
						<input name="numero_pilone" type="text" class="form-control" placeholder="Numero" disabled>
					</div>
				</div>
			</div>
			<div class="row space"></div>
			<div class="row">
				<div class="col-lg-6">
					<small class="pull-left">* Laciare vuoti i campi su cui non si vuole fare la ricerca</small>
				</div>
				<div class="col-lg-6">
					<button type="submit" class="btn btn-default pull-right">Cerca</button>
				</div>
			</div>
		</div>
	</div>
</form>
<?php $this->load->view('parts/view_autocomplete_script.php'); ?>
<script type="text/javascript">
$('select[name=tipo]').change(function(){
	var tipo = $('select[name=tipo]').val();
	if(tipo == 'pilone'){
		$('select[name=cappella_pilone]').removeAttr('disabled');
		$('input[name=piano_pilone]').removeAttr('disabled');
		$('input[name=sezione_pilone]').removeAttr('disabled');
		$('input[name=numero_pilone]').removeAttr('disabled');
		$('div[name=dati_pilone]').show(1500);
	}else{
		$('select[name=cappella_pilone]').attr('disabled','disabled');
		$('input[name=piano_pilone]').attr('disabled','disabled');
		$('input[name=sezione_pilone]').attr('disabled','disabled');
		$('input[name=numero_pilone]').attr('disabled','disabled');
		$('div[name=dati_pilone]').hide(1500);
	}
});
</script>