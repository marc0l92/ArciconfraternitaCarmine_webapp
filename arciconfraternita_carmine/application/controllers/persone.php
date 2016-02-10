<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persone extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_persone');
		$this->load->model('model_home');
		$this->load->library('fkms');
		$this->data['breadcrumb']['Home'] = site_url('home');
		$this->data['breadcrumb']['Persone'] = site_url('persone');
	}

	public function index(){
		$this->data['title'] = 'Persone';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/view_persone');
		$this->load->view('heading/view_footer');
	}

	public function nuova_persona($idPersona = 0){
		if($idPersona != 0){
			$query = array('persone.id_persona' => $idPersona);
			$result = $this->model_persone->cercaPersoneResult($query);
			if(!isset($result[0])){
				die('ERRORE: Persona inesistente.');
			}
			$result = $result[0]; //prendo la prima ed unica riga
			$result['data_nascita'] = $this->sqlToDate($result['data_nascita']); //converto la data di nascita
			$result['data_professione'] = $this->sqlToDate($result['data_professione']); //converto la data di data_professione
			$this->data['data'] = $result;
			$this->data['title'] = 'Dettagli persona';
		}else{
			$this->data['title'] = 'Nuova persona';
		}
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/persone/view_nuova_persona');
		$this->load->view('heading/view_footer');
	}

	public function ajax_insert_persona(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login

		$nuova_persona = array(
			'cognome' => strtoupper($this->input->post('cognome')),
			'nome' => strtoupper($this->input->post('nome')),
			'luogo_nascita' => strtoupper($this->input->post('luogo_nascita')),
			'data_nascita' => $this->dateToSql($this->input->post('data_nascita')),
			'indirizzo' => strtoupper($this->input->post('indirizzo')),
			'citta' => strtoupper($this->input->post('citta')),
			'telefono' => strtoupper($this->input->post('telefono')),
			'cellulare' => strtoupper($this->input->post('cellulare')),
			'sesso' => $this->input->post('sesso'),
			'infermo' => $this->input->post('infermo'),
			'stato_civile' => $this->input->post('stato_civile'),
			'note' => $this->input->post('note')
			);
		$tipo = $this->input->post('tipo');
		if($tipo == 'true'){
			$nuovo_confratello = array(
				'codice' => $this->input->post('codice'),
				'data_professione' => $this->dateToSql($this->input->post('data_professione')),
				'codice_capofamiglia' => $this->input->post('codice_capofamiglia'),
				'paternita' => strtoupper($this->input->post('paternita')),
				'maternita' => strtoupper($this->input->post('maternita'))
				);
		}else{
			$nuovo_confratello = NULL;
		}
		
		$modifica = $this->input->post('modifica');
		$control_fields = array('ignore' => $this->input->post('FKMS_ignore'), 'function' => $this->input->post('function'));
		if($modifica == 1){
			$id_persona = $this->input->post('id_persona');
			if(!$this->fkms->modifica_persona($id_persona, $nuova_persona, $nuovo_confratello, $control_fields)) return; // FKMS
			$this->model_persone->modificaPersonaConfratello($id_persona, $nuova_persona, $nuovo_confratello);
			$last_insert_id = $id_persona;
		}else{
			if(!$this->fkms->insert_persona($nuova_persona, $nuovo_confratello, $control_fields)) return; // FKMS
			$last_insert_id = $this->model_persone->insertPersonaConfratello($nuova_persona, $nuovo_confratello);
		}
		echo json_encode(array('last_insert_id' => $last_insert_id));
		return;
	}

	public function ajax_cerca_persone($className = ''){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo logged_in
		$dati = array(
			'codice' => $this->input->post('codice'),
			'cognome' => strtoupper($this->input->post('cognome')),
			'nome' => strtoupper($this->input->post('nome'))
			);
		$persone = $this->model_persone->cercaPersoneResult($dati);
		$result = NULL;
		$result['result'] = $this->queryPersoneTable($persone, $className);
		echo json_encode($result);
	}

	public function cerca_persone(){
		$this->data['title'] = 'Cerca persone';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->data['autocomplete'] = $this->model_persone->getAutocompleteData();
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/persone/view_cerca_persone');
		$this->load->view('heading/view_footer');
	}

	public function cerca_persone_result(){
		$query = array(
			'cognome' => strtoupper($this->input->post('cognome')),
			'nome' => strtoupper($this->input->post('nome')),
			'sesso' => $this->input->post('sesso'),
			'infermo' => $this->input->post('infermo'),
			'luogo_nascita' => strtoupper($this->input->post('luogo_nascita')),
			'citta' => strtoupper($this->input->post('citta')),
			'stato_civile' => $this->input->post('stato_civile'),
			'codice' => $this->input->post('codice'),
			'codice_capofamiglia' => $this->input->post('codice_capofamiglia'),
			'paternita' => strtoupper($this->input->post('paternita')),
			'maternita' => strtoupper($this->input->post('maternita'))
			);

		//data di nascita
		$query = $this->dataIntervalloToSql('data_nascita', $query);
		//data di professione
		$query = $this->dataIntervalloToSql('data_professione', $query);
		
		$confratello = $this->input->post('confratello');
		if($confratello == '1'){
			$query['#confratello'] = 'codice IS NOT NULL';
		}else{
			if($confratello == '0'){
				$query['#confratello'] = 'codice IS NULL';
			}else{
				//$query['#confratello'] = '1';
			}
		}
		$this->data['nome_colonne'] = array(
			'cognome' => 'Cognome',
			'nome' => 'Nome',
			'data_nascita' => 'Data di nascita',
			'sesso' => 'Sesso',
			'codice' => 'Codice'
			);
		$result = $this->model_persone->cercaPersoneResult($query);
		foreach ($result as $key => $value) {
			$result[$key]['data_nascita'] = $this->sqlToDate($result[$key]['data_nascita']);
		}
		$this->data['tabella'] = $result;
		$this->data['query'] = $query;
		$this->data['title'] = 'Risultato ricerca';
		$this->data['breadcrumb']['Cerca persone'] = site_url('persone/cerca_persone');
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/persone/view_cerca_persone_result');
		$this->load->view('heading/view_footer');
	}

	public function ricerca_centenari(){
		$this->data['inizio'] = date('Y') - 100;
		$this->data['fine'] = date('Y') - 100;
		$this->load->view('pages/persone/view_ricerca_centenari', $this->data);
	}

	public function ricerca_cinquanta_prof(){
		$this->data['inizio'] = date('Y') - 50;
		$this->data['fine'] = date('Y') - 50;
		$this->load->view('pages/persone/view_ricerca_cinquanta_prof', $this->data);	
	}

	/* RICHIAMATO: parts/view_capofamiglia_script.php
	   DESCRIZIONE: preleva il nome e cognome del capofamiglia
	*/
	   public function ajax_cerca_capofamiglia(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$codice_capofamiglia = $this->input->post('codice_capofamiglia');
		$result = $this->model_persone->getConfratelloData($codice_capofamiglia);
		if($result != NULL){
			echo json_encode(array('nome_cognome' => $result['nome'].' '.$result['cognome']));
		}else{
			echo '{}';
		}
		return;
	}
}

/* End of file persone.php */
/* Location: ./application/controllers/persone.php */