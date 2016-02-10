<form method="POST" action="<?php echo site_url('defunti/cerca_defunti_result'); ?>">
	<div class="row">
		<div class="col-lg-12 form-group">
			<div class="row">
				<div class="col-lg-4">
					<label>Cognome</label>
					<input name="cognome" type="text" class="form-control" placeholder="Cognome">
				</div>
				<div class="col-lg-3">
					<label>Nome</label>
					<input name="nome" type="text" class="form-control" placeholder="Nome" >
				</div>
				<div class="col-lg-3">
					<label>Luogo di nascita</label>
					<input name="luogo_nascita" type="text" class="form-control" placeholder="Luogo di nascita" >
				</div>
				<div class="col-lg-2">
					<label>Sesso</label>
					<select name="sesso" class="form-control" >
						<option value="" selected>Tutti</option>
						<option value="M">M</option>
						<option value="F">F</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<?php
					$array = array('label' => 'Data di nascita', 'name' => 'data_nascita');
					$this->load->view('parts/view_data_intervallo.php', $array);
					?>
				</div>
				<div class="col-lg-6">
					<?php
					$array = array('label' => 'Data del decesso', 'name' => 'data_decesso');
					$this->load->view('parts/view_data_intervallo.php', $array);
					?>
				</div>
			</div>
			<div class="row space"></div>
			<legend>Dati da confratello</legend>
			<div class="row">
				<div class="col-lg-6">
					<label>Codice confratello</label>
					<input name="codice_confratello" type="text" class="form-control" placeholder="Codice del confratello">
				</div>
				<div class="col-lg-6">
					<?php
					$array = array('label' => 'Data professione', 'name' => 'data_professione');
					$this->load->view('parts/view_data_intervallo.php', $array);
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<label>Paternità</label>
					<input name="paternita" type="text" class="form-control" placeholder="Paternità" >
				</div>
				<div class="col-lg-6">
					<label>Maternità</label>
					<input name="maternita" type="text" class="form-control" placeholder="Maternità" >
				</div>
			</div>
			<div class="row space"></div>
			<legend>Dati della celletta</legend>
			<div class="row">
				<div class="col-lg-3">
					<?php $this->load->view('parts/view_select_cappella.php'); ?>
				</div>
				<div class="col-lg-2">
					<label>Piano</label>
					<input name="piano" type="text" class="form-control" placeholder="N. Piano" data-content="ok">
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
			<div class="row">
				<div class="col-lg-6">
					<?php
					$array = array('label' => 'Data di sepoltura', 'name' => 'data_sepoltura');
					$this->load->view('parts/view_data_intervallo.php', $array);
					?>
				</div>
				<div class="col-lg-3">
					<label>Tipo</label>
					<select name="tipo" class="form-control">
						<option value="">Tutti</option>
						<option value="celletta">Celletta</option>
						<option value="sepoltura">Sepoltura</option>
						<option value="pilone">Pilone</option>
					</select>
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