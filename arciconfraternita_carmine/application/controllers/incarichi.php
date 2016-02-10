<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Incarichi extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_incarichi');
		$this->load->library('fkms');
		$this->data['breadcrumb']['Home'] = site_url('home');
		$this->data['breadcrumb']['Persone'] = site_url('persone');
		$this->data['breadcrumb']['Gestisci incarichi'] = site_url('incarichi');
	}

	public function index(){
		$this->data['title'] = 'Gestisci incarichi';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/persone/view_gestisci_incarichi');
		$this->load->view('heading/view_footer');
	}

	public function nuovo_incarico(){
		$this->data['title'] = 'Nuovo incarico';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/persone/incarichi/view_nuovo_incarico');
		$this->load->view('heading/view_footer');
	}

	public function ajax_insert_incarico(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$nuovo_incarico = array('nome' => strtoupper($this->input->post('nome')),
			'note' => $this->input->post('note'));
		$last_insert_id = $this->model_incarichi->insertIncarico($nuovo_incarico);
		echo json_encode(array('last_insert_id' => $last_insert_id));
		return;
	}

	public function assegna_incarico(){
		$result = $this->model_incarichi->getIncarichi(1);
		$incarichi = $result['incarichi'];
		$confratelli = $result['confratelli'];
		foreach ($confratelli as $key => $value) {
			$confratelli[$key] = $confratelli[$key][0];
			$confratelli[$key]['incarico_data_inizio'] = $this->sqlToDate($confratelli[$key]['incarico_data_inizio']);

		}
		$this->data['incarichi'] = $incarichi;
		$this->data['confratelli'] = $confratelli;
		$this->data['title'] = 'Assegna incarico';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/persone/incarichi/view_assegna_incarico');
		$this->load->view('heading/view_footer');
	}

	public function ajax_assegna_incarico(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$query = array(
			'id_incarico' => $this->input->post('id_incarico'),
			'id_confratello' => $this->input->post('id_confratello'),
			'data_inizio' => $this->dateToSql($this->input->post('data_inizio')),
			'note' => $this->input->post('note')
			);
		$modifica_riassegna = $this->input->post('modifica_riassegna');
		$incarico_confratello_id = $this->input->post('incarico_confratello_id');
		if(!$this->fkms->assegna_incarico($query, $modifica_riassegna)) return; // FKMS
		$incarico_confratello_id = $this->model_incarichi->assegnaIncarico($query, $modifica_riassegna, $incarico_confratello_id);
		echo json_encode(array('incarico_confratello_id' => $incarico_confratello_id));
	}

	public function visualizza_incarichi(){
		$result = $this->model_incarichi->getIncarichi(0);
		$incarichi = $result['incarichi'];
		$confratelli = $result['confratelli'];
		foreach ($confratelli as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				$confratelli[$key1][$key2]['incarico_data_inizio'] = $this->sqlToDate($value2['incarico_data_inizio']);
			}
		}
		$this->data['incarichi'] = $incarichi;
		$this->data['confratelli'] = $confratelli;
		$this->data['title'] = 'Visualizza incarichi';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/persone/incarichi/view_visualizza_incarichi');
		$this->load->view('heading/view_footer');
	}
}

/* End of file incarichi.php */
/* Location: ./application/controllers/incarichi.php */