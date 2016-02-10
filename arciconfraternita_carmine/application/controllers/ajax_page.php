<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_page extends MY_Controller {

	public function __construct(){
		parent::__construct();
		//$this->load->model('model_altro');
	}

	public function index(){
	}

	public function nuova_persona(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$array = array();
		$array["on_save"] = true;
		$array['show_confratello'] = false;
		$this->load->view('pages/persone/view_nuova_persona.php', $array);
	}

	public function nuovo_defunto(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$array = array();
		$array['on_save'] = true;
		$array['celletta_hide'] = true;
		$array['responsabile_hide'] = true;
		$this->load->view('pages/defunti/view_nuovo_defunto.php', $array);
	}
}

/* End of file ajax_page.php */
/* Location: ./application/controllers/ajax_page.php */
