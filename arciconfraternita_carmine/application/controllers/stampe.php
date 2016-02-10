<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stampe extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_stampe');
		$this->load->model('model_persone');
		$this->data['breadcrumb']['Home'] = site_url('home');
		$this->data['breadcrumb']['Stampe'] = site_url('stampe');
	}

	public function index(){
		$this->data['title'] = 'Stampe';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/view_stampe');
		$this->load->view('heading/view_footer');
	}

	public function stampa_persona($idPersona=0){
		$array = array();
		//titolo
		$array[] = array(
			'name' => 'Dati anagrafici <small><a id="modifica" class="hidden-print" href="'.site_url('persone/nuova_persona').'/'.$idPersona.'">[Modifica]</a></small>',
			'value' => '#',
			'dimension' => '12',
			);
		// dati anagrafici
		$array = $this->model_stampe->datiPersona($idPersona, $array);
		$array[] = array(
			'name' => 'Dati da confratello',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiConfratello($idPersona, $array);
		//trovo chi è il capofamilgia
		if(isset($array['codice_capofamiglia']['value']) && $array['codice_capofamiglia']['value'] != ''){
			$id_capofamiglia = $this->model_persone->codiceToIdPersona($array['codice_capofamiglia']['value']);
			// dati del capofamiglia
			$array[] = array(
				'name' => 'Dati anagrafici del capofamiglia <small><a id="dettagli_capofamiglia" class="hidden-print" href="'.site_url('stampe/stampa_persona').'/'.$id_capofamiglia.'">[Dettagli]</a></small>',
				'value' => '#',
				'dimension' => '12',
				);
		}else{
			$id_capofamiglia = -1;
			// dati del capofamiglia
			$array[] = array(
				'name' => 'Dati anagrafici del capofamiglia',
				'value' => '#',
				'dimension' => '12',
				);
		}
		//mi salvo il suo codice
		if(isset($array['codice']['value'])){
			$codice_confratello = $array['codice']['value'];
		}else{
			$codice_confratello = -1;
		}
		$array = $this->model_stampe->datiPersona($id_capofamiglia, $array);
		// dati delle cellette acquistate
		$array[] = array(
			'name' => 'Cellette acquistate',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiCelletteAcquistate($idPersona, $array);
		// dati delle cellette di cui è responsabile
		$array[] = array(
			'name' => 'Cellette di cui è responsabile',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiCelletteResponsabile($idPersona, $array);
		// dati dei defunti di cui è responsabile
		$array[] = array(
			'name' => 'Defunti di cui è responsabile',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiDefuntiResponsabile($idPersona, $array);
		// dati delle persone di cui è capofamiglia
		$array[] = array(
			'name' => 'Confratelli di cui è capofamiglia',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiFigliCapofamiglia($codice_confratello, $array);
		$array[] = array(
			'name' => 'Parenti',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiParenti($idPersona, $array);
		$array[] = array(
			'name' => 'Storico incarichi',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiStoricoIncarichi($idPersona, $array);
		$this->data['data'] = $array;
		if($idPersona > 1){
			$this->data['previous_link'] = site_url('stampe/stampa_persona').'/'.($idPersona-1);
		}
		$this->data['next_link'] = site_url('stampe/stampa_persona').'/'.($idPersona+1);
		$this->data['title'] = 'Stampa persona';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/stampe/view_stampa_riepilogo');
		$this->load->view('heading/view_footer');
	}

	public function stampa_celletta($idCelletta=0){
		$array = array();
		$array[] = array(
			'name' => 'Dati celletta <small><a id="modifica" class="hidden-print" href="'.site_url('cappella_gentilizia/nuova_celletta').'/'.$idCelletta.'">[Modifica]</a></small>',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiCelletta($idCelletta, $array);
		$array[] = array(
			'name' => 'Storico responsabili celletta',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiStoricoResponsabiliCelletta($idCelletta, $array);
		$array[] = array(
			'name' => 'Storico delle sepolture nella celletta',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiStoricoSepoltureCelletta($idCelletta, $array);
		$this->data['data'] = $array;
		if($idCelletta > 1){
			$this->data['previous_link'] = site_url('stampe/stampa_celletta').'/'.($idCelletta-1);
		}
		$this->data['next_link'] = site_url('stampe/stampa_celletta').'/'.($idCelletta+1);
		$this->data['title'] = 'Stampa celletta';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/stampe/view_stampa_riepilogo');
		$this->load->view('heading/view_footer');
	}

	public function stampa_defunto($idDefunto=0){
		$array = array();
		$array[] = array(
			'name' => 'Dati defunto <small><a id="modifica" class="hidden-print" href="'.site_url('defunti/nuovo_defunto').'/'.$idDefunto.'">[Modifica]</a></small>',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiDefunto($idDefunto, $array);
		$array[] = array(
			'name' => 'Vecchi dati da confratello',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiConfratelloDefunto($idDefunto, $array);
		$array[] = array(
			'name' => 'Cellette acquistate',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiCelletteAcquistateDefunto($idDefunto, $array);
		$array[] = array(
			'name' => 'Storico sepolture',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiStoricoSepoltureDefunto($idDefunto, $array);
		$array[] = array(
			'name' => 'Storico responsabili',
			'value' => '#',
			'dimension' => '12',
			);
		$array = $this->model_stampe->datiStoricoResponsabiliDefunto($idDefunto, $array);
		$this->data['data'] = $array;
		if($idDefunto > 1){
			$this->data['previous_link'] = site_url('stampe/stampa_defunto').'/'.($idDefunto-1);
		}
		$this->data['next_link'] = site_url('stampe/stampa_defunto').'/'.($idDefunto+1);
		$this->data['title'] = 'Stampa defunto';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/stampe/view_stampa_riepilogo');
		$this->load->view('heading/view_footer');
	}

}

/* End of file stampe.php */
/* Location: ./application/controllers/stampe.php */