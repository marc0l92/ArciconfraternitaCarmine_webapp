<form method="POST" action="<?php echo site_url('persone/cerca_persone_result'); ?>">
	<div class="row">
		<div class="col-lg-12 form-group">
			<div class="row">
				<div class="col-lg-4">
					<label>Cognome</label>
					<input name="cognome" type="text" class="form-control" placeholder="Cognome">
				</div>
				<div class="col-lg-4">
					<label>Nome</label>
					<input name="nome" type="text" class="form-control" placeholder="Nome">
				</div>
				<div class="col-lg-2">
					<label>Sesso</label>
					<select name="sesso" class="form-control">
						<option value="">Tutti</option>
						<option value="M">M</option>
						<option value="F">F</option>
					</select>
				</div>
				<div class="col-lg-2">
					<label>Infermo</label>
					<select name="infermo" class="form-control">
						<option value="">Tutti</option>
						<option value="0">No</option>
						<option value="1">Si</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4">
					<label>Luogo di nascita</label>
					<input name="luogo_nascita" type="text" class="form-control" placeholder="Citta di residenza">
				</div>
				<div class="col-lg-4">
					<label>Città</label>
					<input name="citta" type="text" class="form-control" placeholder="Citta di residenza">
				</div>
				<div class="col-lg-4">
					<label>Stato civile</label>
					<select name="stato_civile" class="form-control">
						<option value="">Tutti</option>
						<option value="celibe/nubile">Celibe/Nubile</option>
						<option value="coniugato/a">Coniugato/a</option>
						<option value="vedovo/a">Vedovo/a</option>
						<option value="separato/a">Separato/a</option>
						<option value="divorziato/a">Divorziato/a</option>
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
			</div>
			<div class="row space"></div>
			<legend>Dati da confratello</legend>
			<div class="row">
				<div class="col-lg-2">
					<label>Confratello</label>
					<select name="confratello" class="form-control">
						<option value="">Tutti</option>
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="col-lg-3">
					<label>Codice</label>
					<input name="codice" type="text" class="form-control" placeholder="Codice del confratello">
				</div>
				<div class="col-lg-3">
					<label>Codice capofamiglia</label>
					<input name="codice_capofamiglia" type="text" class="form-control" placeholder="Codice del capo famiglia">
				</div>
				<div class="col-lg-2">
					<label>Paternità</label>
					<input name="paternita" type="text" class="form-control" placeholder="Paternità">
				</div>
				<div class="col-lg-2">
					<label>Maternità</label>
					<input name="maternita" type="text" class="form-control" placeholder="Maternità">
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<?php
					$array = array('label' => 'Data professione', 'name' => 'data_professione');
					$this->load->view('parts/view_data_intervallo.php', $array);
					?>
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