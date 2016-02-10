<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cappella_gentilizia extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_cappella_gentilizia');
		$this->load->model('model_persone');
		$this->load->model('model_defunti');
		$this->load->library('fkms');
		$this->data['breadcrumb']['Home'] = site_url('home');
		$this->data['breadcrumb']['Cappella gentilizia'] = site_url('cappella_gentilizia');
	}

	public function index(){
		$this->data['title'] = 'Cappella gentilizia';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/view_cappella_gentilizia');
		$this->load->view('heading/view_footer');
	}

	public function nuova_cappella(){
		$this->data['title'] = 'Nuova cappella';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/cappella_gentilizia/view_nuova_cappella');
		$this->load->view('heading/view_footer');
	}

	public function ajax_insert_cappella(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$nuova_cappella = array('nome' => strtoupper($this->input->post('nome')), 'note' => $this->input->post('note'));
		if(!$this->fkms->insert_cappella($nuova_cappella)) return; // FKMS
		$last_insert_id = $this->model_cappella_gentilizia->insertCappella($nuova_cappella);
		echo json_encode(array('last_insert_id' => $last_insert_id));
		return;
	}

	public function nuova_celletta($idCelletta = 0){
		if($idCelletta != 0){
			$query = array('id_celletta' => $idCelletta);
			$result = $this->model_cappella_gentilizia->cercaCelletteResult($query);
			if(!isset($result[0])){
				die('ERRORE: Celletta inesistente.');
			}
			$result = $result[0]; //prendo la prima ed unica riga
			$result['data_concessione'] = $this->sqlToDate($result['data_concessione']);
			$result['data_responsabile_celletta'] = $this->sqlToDate($result['data_inizio_responsabilita']);
			$result['data_responsabile_defunto'] = $this->sqlToDate($result['data_inizio_responsabilita_defunto']);
			$this->data['data'] = $result;

			//lista defunti
			$query = array('id_celletta' => $idCelletta);
			$result_defunti = $this->model_defunti->cercaDefuntiResult($query);
			$count_defunti = 0;
			foreach ($result_defunti as $key => $value) {
				$result_defunti[$key]['data_inizio_sepoltura'] = $this->sqlToDate($value['data_inizio_sepoltura']);
				$count_defunti ++;
			}
			$this->data['data_defunti'] = $result_defunti;
			$this->data['data']['count_defunti'] = $count_defunti;

			$this->data['title'] = 'Dettagli celletta';
		}else{
			$this->data['title'] = 'Nuova celletta';
		}
		$this->data['cappelle'] = $this->model_cappella_gentilizia->getCappelle();
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/cappella_gentilizia/view_nuova_celletta');
		$this->load->view('heading/view_footer');
	}

	public function ajax_insert_celletta(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		//dati celletta
		$nuova_celletta = array(
			'id_cappella' => $this->input->post('cappella_celletta'),
			'piano' => $this->input->post('piano'),
			'sezione' => $this->input->post('sezione'),
			'fila' => $this->input->post('fila'),
			'numero' => $this->input->post('numero'),
			'tipo' => $this->input->post('tipo'),
			'id_acquirente' => $this->input->post('id-persona_acquirente'),
			'id_acquirente_defunto' => $this->input->post('id-defunto_acquirente'),
			'data_concessione' => $this->dateToSql($this->input->post('data_concessione')),
			'codice_bolletta' => $this->input->post('codice_bolletta'),
			'somma_pagata' => $this->input->post('somma_pagata'),
			'descrizione_lapide' => $this->input->post('descrizione_lapide'),
			'note' => $this->input->post('note')
			);
		$nuovo_responsabile = NULL;
		if($this->input->post('id-persona_responsabile') != NULL && $this->input->post('id-persona_responsabile') != ''){
			$nuovo_responsabile = array(
				'id_responsabile' => $this->input->post('id-persona_responsabile'),
				'data_inizio' => $this->dateToSql($this->input->post('data_responsabile_celletta'), 1),
				'note' => $this->input->post('note_responsabilita')
				);
		}
		if($nuova_celletta['tipo'] == 'pilone'){
			$nuovo_pilone = array(
				'id_cappella' => $this->input->post('id_cappella_pilone'),
				'piano' => $this->input->post('piano_pilone'),
				'sezione' => $this->input->post('sezione_pilone'),
				'numero' => $this->input->post('numero_pilone')
				);
		}else{
			$nuovo_pilone = NULL;
		}
		//dati defunti
		$counter_defunti = $this->input->post('counter_defunti');
		$defunti = array();
		for($i=0; $i<$counter_defunti; $i++){
			$defunti[$i]['id'] = $this->input->post('id-defunto'.$i);
			$defunti[$i]['data'] = $this->dateToSql($this->input->post('data_sepoltura'.$i), 1);
		}
		// dati responsabile defunti
		$responsabile_defunto = array('id_responsabile' => $this->input->post('id-persona_responsabile_defunto'),
			'data_inizio' => $this->dateToSql($this->input->post('data_responsabile_defunto'), 1),
			'note' => $this->input->post('note_responsabile_defunti'));
		//inserimento nel database
		$modifica = $this->input->post('modifica');
		//tipo di acquirente
		if($this->input->post('acquirente_defunto') != 'true'){
			// acquirente
			$nuova_celletta['id_acquirente_defunto'] = '0';
		}else{
			//acquirente defunto
			$nuova_celletta['id_acquirente'] = '0';
		}
		if($modifica == 1){
			$id_celletta = $this->input->post('id_celletta');
			$last_insert_id = $id_celletta;
			if(!$this->fkms->modifica_celletta($last_insert_id, $nuova_celletta, $nuovo_pilone, $defunti)) return; // FKMS
			$this->model_cappella_gentilizia->modificaCelletta($id_celletta, $nuova_celletta, $nuovo_responsabile, $nuovo_pilone);
			$this->model_cappella_gentilizia->modificaSepolture($last_insert_id, $defunti);
			$this->model_cappella_gentilizia->modificaResponsabileDefunti($defunti, $responsabile_defunto);

		}else{
			if(!$this->fkms->insert_celletta($nuova_celletta, $nuovo_pilone, $defunti)) return; // FKMS
			$last_insert_id = $this->model_cappella_gentilizia->insertCelletta($nuova_celletta, $nuovo_responsabile, $nuovo_pilone);
			$this->model_cappella_gentilizia->insertSepolture($last_insert_id, $defunti);
			$this->model_cappella_gentilizia->insertResponsabileDefunti($defunti, $responsabile_defunto);
		}
		echo json_encode(array('last_insert_id' => $last_insert_id));
	}

	public function ajax_cerca_celletta(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$query = array(
			'id_cappella' => $this->input->post('id_cappella'),
			'piano' => $this->input->post('piano'),
			'sezione' => $this->input->post('sezione'),
			'fila' => $this->input->post('fila'),
			'numero' => $this->input->post('numero')
			);
		
		$idPersona = $this->input->post('id_acquirente');
		
		$tipo_ricerca = $this->input->post('tipo_ricerca');
		$result = $this->model_cappella_gentilizia->cercaCelletta($query, $idPersona, $tipo_ricerca);
		$array = array('result' => $this->queryCelletteTable($result, $tipo_ricerca));
		echo json_encode($array);
	}

	public function modifica_cappella(){
		$this->data['cappelle'] = $this->model_cappella_gentilizia->getCappelle();
		$this->data['title'] = 'Modifica cappella';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/cappella_gentilizia/view_modifica_cappella');
		$this->load->view('heading/view_footer');
	}

	public function ajax_modifica_cappella(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$id_cappella = $this->input->post('id_cappella');
		$data = array(
			'nome' => strtoupper($this->input->post('nome')),
			'note' => $this->input->post('note')
			);
		$this->model_cappella_gentilizia->modifica_cappella($id_cappella, $data);
		echo '{}';
	}

	public function ajax_elimina_cappella(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$id_cappella = $this->input->post('id_cappella');
		if(!$this->fkms->elimina_cappella($id_cappella)) return; // FKMS
		$result = NULL;
		$result['eliminato'] = $this->model_cappella_gentilizia->elimina_cappella($id_cappella);
		echo json_encode($result);
	}

	public function cerca_celletta(){
		$this->data['autocomplete'] = $this->model_cappella_gentilizia->getAutocompleteData();
		$this->data['cappelle'] = $this->model_cappella_gentilizia->getCappelle();
		$this->data['title'] = 'Cerca celletta';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/cappella_gentilizia/view_cerca_celletta');
		$this->load->view('heading/view_footer');
	}

	public function cerca_celletta_result(){
		$query = array(
			'id_cappella' => $this->input->post('id_cappella'),
			'piano' => $this->input->post('piano'),
			'sezione' => $this->input->post('sezione'),
			'fila' => $this->input->post('fila'),
			'numero' => $this->input->post('numero'),
			'codice_bolletta' => $this->input->post('codice_bolletta'),
			'id_acquirente' => $this->input->post('id-persona_acquirente'),
			'id_responsabile' => $this->input->post('id-persona_responsabile'),
			'tipo' => $this->input->post('tipo')
			);
		if($query['tipo'] == 'pilone'){
			$query['id_cappella_pilone'] = $this->input->post('cappella_pilone');
			$query['piano_pilone'] = $this->input->post('piano_pilone');
			$query['sezione_pilone'] = $this->input->post('sezione_pilone');
			$query['numero_pilone'] = $this->input->post('numero_pilone');
		}
		
		//data_concessione
		$query = $this->dataIntervalloToSql('data_concessione', $query);

		$this->data['nome_colonne'] = array(
			'cappella_nome' => 'Cappella',
			'piano' => 'Piano',
			'sezione' => 'Sezione',
			'fila' => 'Fila',
			'numero' => 'Numero',
			'tipo' => 'Tipo',
			'acquistata' => 'Acquistata',
			'responsabile' => 'Con responsabile',
			'occupata' => 'Occupata',
			'count_defunti' => 'N. defunti'
			);
		$result = $this->model_cappella_gentilizia->cercaCelletteResult($query);
		foreach ($result as $key => $value) {
			//acquirente
			if(isset($value['id_acquirente']) && $value['id_acquirente'] != '' && $value['id_acquirente'] != '0'){
				$result[$key]['acquistata'] = 'Sì';
			}else{
				$result[$key]['acquistata'] = 'No';
			}
			//acquirente defunto
			if(isset($value['id_acquirente_defunto']) && $value['id_acquirente_defunto'] != '' && $value['id_acquirente_defunto'] != '0'){
				$result[$key]['acquistata'] = 'Sì*';
			}
			//responsabile
			if(isset($value['id_responsabile']) && $value['id_responsabile'] != '' && $value['id_responsabile'] != '0'){
				$result[$key]['responsabile'] = 'Sì';
			}else{
				$result[$key]['responsabile'] = 'No';
			}
			//occupata
			//if(isset($value['id_defunto']) && $value['id_defunto'] != '' && $value['id_defunto'] != '0'){
			if($value['count_defunti'] > 0){
				$result[$key]['occupata'] = 'Sì';
			}else{
				$result[$key]['occupata'] = 'No';
			}
		}
		$this->data['tabella'] = $result;
		$this->data['query'] = $query;
		$this->data['title'] = 'Risultato ricerca';
		$this->data['breadcrumb']['Cerca celletta'] = site_url('cappella_gentilizia/cerca_celletta');
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/cappella_gentilizia/view_cerca_celletta_result');
		$this->load->view('heading/view_footer');
	}

	public function visualizza_cellette(){
		$this->data['tree'] = $this->model_cappella_gentilizia->getCelletteTree();
		$this->data['title'] = 'Visulalizza cellette';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/cappella_gentilizia/view_visulalizza_cellette');
		$this->load->view('heading/view_footer');
	}

	public function visualizza_cellette_result($cappella=0, $piano=0, $sezione=0){
		$query = array(
			'id_cappella' => $cappella,
			'piano' => $piano,
			'sezione' => $sezione
			);
		$result = $this->model_cappella_gentilizia->cercaCelletteResult($query);
		foreach ($result as $key => $value) {
			//acquirente
			if(isset($value['id_acquirente']) && $value['id_acquirente'] != '' && $value['id_acquirente'] != '0'){
				$result[$key]['acquistata'] = 'Sì';
			}else{
				$result[$key]['acquistata'] = 'No';
			}
			//responsabile
			if(isset($value['id_responsabile']) && $value['id_responsabile'] != '' && $value['id_responsabile'] != '0'){
				$result[$key]['responsabile'] = 'Sì';
			}else{
				$result[$key]['responsabile'] = 'No';
			}
			//occupata
			if(isset($value['id_defunto']) && $value['id_defunto'] != '' && $value['id_defunto'] != '0'){
				$result[$key]['occupata'] = 'Sì';
			}else{
				$result[$key]['occupata'] = 'No';
			}
		}
		$this->data['tabella'] = $result;
		$this->data['size'] = $this->model_cappella_gentilizia->getTableSize($cappella, $piano, $sezione);
		$this->data['cappelle'] = $this->model_cappella_gentilizia->getCappelle();
		$this->data['query'] = array('cappella' => $cappella, 'piano' => $piano, 'sezione' =>  $sezione);
		$this->data['title'] = 'Visulalizza cellette griglia';
		$this->data['breadcrumb']['Visulalizza cellette'] = site_url('cappella_gentilizia/visulalizza_cellette');
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/cappella_gentilizia/view_visulalizza_cellette_result');
		$this->load->view('heading/view_footer');
	}
}

/* End of file cappella_gentilizia.php */
/* Location: ./application/controllers/cappella_gentilizia.php */