<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Defunti extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_defunti');
		$this->load->model('model_persone');
		$this->load->model('model_cappella_gentilizia');
		$this->load->library('fkms');
		$this->data['breadcrumb']['Home'] = site_url('home');
		$this->data['breadcrumb']['Defunti'] = site_url('defunti');
	}

	public function index(){
		$this->data['title'] = 'Defunti';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/view_defunti');
		$this->load->view('heading/view_footer');
	}

	public function nuovo_defunto($idDefunto = 0){
		if($idDefunto != 0){
			$query = array('id_defunto' => $idDefunto);
			$result = $this->model_defunti->cercaDefuntiResult($query);
			if(!isset($result[0])){
				die('ERRORE: Persona inesistente.');
			}
			$result = $result[0]; //prendo la prima ed unica riga
			$result['data_nascita'] = $this->sqlToDate($result['data_nascita']);
			$result['data_professione'] = $this->sqlToDate($result['data_professione']);
			$result['data_decesso'] = $this->sqlToDate($result['data_decesso']);
			$result['data_sepoltura'] = $this->sqlToDate($result['data_inizio_sepoltura']);
			//conversione in modo da tener conto dell'id del modal celletta
			$result['id_celletta0'] = $result['id_celletta'];
			if($result['id_celletta'] == 0){
				$result['celletta_description0'] = '';
			}else{
				$result['celletta_description0'] = $result['celletta_description']; 
			}
			// dati responsabile
			$result['id_persona0'] = $result['id_responsabile'];
			if($result['id_responsabile'] == 0){
				$result["responsabile_description"] = '';
			}else{
				$result["responsabile_description"] = $result["cognome_responsabile"] . " " . $result["nome_responsabile"];
			}
			$result['data_inizio_responsabilita'] = $this->sqlToDate($result['data_inizio_responsabilita']);

			$this->data['data'] = $result;
			$this->data['title'] = 'Dettagli defunto';
		}else{
			$this->data['title'] = 'Nuovo defunto';
		}
		$this->data['cappelle'] = $this->model_cappella_gentilizia->getCappelle();
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/defunti/view_nuovo_defunto');
		$this->load->view('heading/view_footer');
	}

	public function ajax_cerca_confratello(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$codice = $this->input->post('codice');
		$result = $this->model_persone->getConfratelloData($codice);
		if($result == NULL){
			return false;
		}
		$result['data_nascita'] = $this->sqlToDate($result['data_nascita']);
		$result['data_professione'] = $this->sqlToDate($result['data_professione']);
		$cellette = $this->model_defunti->getCelletteAcquistate($result['id_persona']);
		
		$result['query_cellette'] = $this->queryCelletteTable($cellette);
		echo json_encode($result);
		return;
	}

	public function ajax_insert_defunto(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		// dati defunto
		$nuovo_defunto = array(
			'cognome' => strtoupper($this->input->post('cognome')),
			'nome' => strtoupper($this->input->post('nome')),
			'luogo_nascita' => strtoupper($this->input->post('luogo_nascita')),
			'data_nascita' => $this->dateToSql($this->input->post('data_nascita')),
			'indirizzo' => strtoupper($this->input->post('indirizzo')),
			'citta' => strtoupper($this->input->post('citta')),
			'sesso' => $this->input->post('sesso'),
			'data_decesso' => $this->dateToSql($this->input->post('data_decesso')),
			'note' => $this->input->post('note'),
			'codice_confratello' => $this->input->post('codice_confratello'),
			'data_professione' => $this->dateToSql($this->input->post('data_professione')),
			'paternita' => strtoupper($this->input->post('paternita')),
			'maternita' => strtoupper($this->input->post('maternita'))
			);
		// dati sepoltura
		$nuova_sepoltura = array(
			'id_celletta' => $this->input->post('id_celletta0'),
			'data_inizio' => $this->dateToSql($this->input->post('data_sepoltura'), 1)
			);
		// dati responsabile
		$nuovo_responsabile = array(
			'id_responsabile' => $this->input->post('id-persona0'),
			'data_inizio' => $this->dateToSql($this->input->post('data_inizio_responsabilita'), 1),
			'note' => $this->input->post('note_responsabilita')
			);
		// modifica defunto esistente oppure crea uno nuovo ?
		$modifica = $this->input->post('modifica');
		$control_fields = array('ignore' => $this->input->post('FKMS_ignore'), 'function' => $this->input->post('function'));
		
		if($modifica == 1){
			$id_defunto = $this->input->post('id_defunto');
			$this->model_defunti->modificaDefunto($nuovo_defunto, $nuova_sepoltura, $nuovo_responsabile, $id_defunto);
			$last_insert_id = $id_defunto;
		}else{
			$tipo = $this->input->post('tipo-defunto'); //elimina confratello per renderlo defunto
			if(!$this->fkms->insert_defunto($nuovo_defunto, $nuova_sepoltura, $nuovo_responsabile, $tipo, $control_fields)) return; // FKMS
			$last_insert_id = $this->model_defunti->insertDefunto($nuovo_defunto, $nuova_sepoltura, $nuovo_responsabile, $tipo);
		}
		echo json_encode(array('last_insert_id' => $last_insert_id));
		return;
	}

	public function cerca_defunti(){
		$this->data['autocomplete'] = $this->model_defunti->getAutocompleteData();
		$this->data['cappelle'] = $this->model_cappella_gentilizia->getCappelle();
		$this->data['title'] = 'Cerca defunti';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/defunti/view_cerca_defunti');
		$this->load->view('heading/view_footer');
	}

	public function cerca_defunti_result(){
		$query = array(
			'cognome' => strtoupper($this->input->post('cognome')),
			'nome' => strtoupper($this->input->post('nome')),
			'luogo_nascita' => strtoupper($this->input->post('luogo_nascita')),
			'sesso' => $this->input->post('sesso'),
			'codice_confratello' => $this->input->post('codice_confratello'),
			'paternita' => strtoupper($this->input->post('paternita')),
			'maternita' => strtoupper($this->input->post('maternita')),
			'cappella' => $this->input->post('cappella'),
			'piano' => $this->input->post('piano'),
			'sezione' => $this->input->post('sezione'),
			'fila' => $this->input->post('fila'),
			'numero' => $this->input->post('numero'),
			'tipo' => $this->input->post('tipo')
			);
		//data di nascita
		$query = $this->dataIntervalloToSql('data_nascita', $query);
		//data del decesso
		$query = $this->dataIntervalloToSql('data_decesso', $query);
		//data di professione
		$query = $this->dataIntervalloToSql('data_professione', $query);
		//data di sepoltura
		$query = $this->dataIntervalloToSql('data_sepoltura', $query);

		$this->data['nome_colonne'] = array(
			'cognome' => 'Cognome',
			'nome' => 'Nome',
			'data_nascita' => 'Data di nascita',
			'data_decesso' => 'Data del decesso',
			'data_inizio_sepoltura' => 'Data sepoltura',
			'celletta_description' => 'Celletta'
			);
		$result = $this->model_defunti->cercaDefuntiResult($query);
		foreach ($result as $key => $value) {
			$result[$key]['data_nascita'] = $this->sqlToDate($result[$key]['data_nascita']);
			$result[$key]['data_decesso'] = $this->sqlToDate($result[$key]['data_decesso']);
			$result[$key]['data_professione'] = $this->sqlToDate($result[$key]['data_professione']);
			$result[$key]['data_inizio_sepoltura'] = $this->sqlToDate($result[$key]['data_inizio_sepoltura']);
			if($value['id_celletta'] == 0){
				$result[$key]['celletta_description'] = "";
			}
		}
		$this->data['tabella'] = $result;
		$this->data['query'] = $query;
		$this->data['title'] = 'Risultato ricerca';
		$this->data['breadcrumb']['Cerca defunti'] = site_url('defunti/cerca_defunti');
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/defunti/view_cerca_defunti_result');
		$this->load->view('heading/view_footer');	
	}

	public function sposta_defunto(){
		$this->data['cappelle'] = $this->model_cappella_gentilizia->getCappelle();
		$this->data['title'] = 'Sposta defunto';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/defunti/view_sposta_defunto');
		$this->load->view('heading/view_footer');
	}

	public function ajax_cerca_defunto(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo logged_in
		$query = array('id_celletta' => $this->input->post('id_celletta'));
		$result = $this->model_defunti->cercaDefuntiResult($query);
		$result = $result[0]; //prendo la prima ed unica riga
		$result['data_nascita'] = $this->sqlToDate($result['data_nascita']);
		$result['data_professione'] = $this->sqlToDate($result['data_professione']);
		$result['data_decesso'] = $this->sqlToDate($result['data_decesso']);
		$result['data_inizio_sepoltura'] = $this->sqlToDate($result['data_inizio_sepoltura']);
		echo json_encode($result);
	}

	public function ajax_sposta_defunto(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$defunto = $this->input->post('defunto');
		$celletta_destinazione = $this->input->post('celletta_destinazione');
		$data_trasferimento = $this->dateToSql($this->input->post('data_trasferimento'));
		$this->model_defunti->spostaDefunto($defunto, $celletta_destinazione, $data_trasferimento);
		echo '{}';
	}

	public function ajax_cerca_defunti($className = ""){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo logged_in
		$dati = array(
			'cognome' => strtoupper($this->input->post('cognome')),
			'nome' => strtoupper($this->input->post('nome'))
			);
		$dati = $this->dataIntervalloToSql('data_decesso-'.$className, $dati);
		$defunti = $this->model_defunti->cercaDefuntiResult($dati);
		$result = NULL;
		$result['result'] = $this->queryDefuntiTable($defunti, $className);
		echo json_encode($result);
	}
}

/* End of file defunti.php */
/* Location: ./application/controllers/defunti.php */