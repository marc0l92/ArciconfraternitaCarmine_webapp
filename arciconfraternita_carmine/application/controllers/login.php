<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_login');
		$this->data['breadcrumb']['Home'] = site_url('home');
		$this->data['breadcrumb']['Altro'] = site_url('altro');
	}

	public function index($retry = 0){
		$this->data['retry'] = $retry;
		$this->data['title'] = 'Login';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/view_login');
		$this->load->view('heading/view_footer');
	}

	public function login_result(){
		echo 'Analisi della richiesta di login...';
		$this->load->library('encrypt');
		$username = strtoupper($this->input->post('username'));
		$password = $this->input->post('password');
		$password = $this->encrypt->sha1($password);
		$login = $this->model_login->checkLogin($username, $password);
		if($login == false){
			header("Location: ".site_url('login/index').'/1');
		}else{
			$this->session->set_userdata('user_id', $login['id_utente']);
			$this->session->set_userdata('admin_enabled', $login['admin_enabled']);
			$this->session->set_userdata('logged_in', true);
			$this->session->set_userdata('username', $username);
			header("Location: ".site_url('altro/database_fix'));
			//header("Location: ".site_url('home'));
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		header("Location: ".site_url('login'));
	}

	public function gestisci_utenti(){
		$this->data['utenti'] = $this->model_login->getUtenti();

		$this->data['title'] = 'Gestisci utenti';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/login/view_gestisci_utenti');
		$this->load->view('heading/view_footer');
	}

	public function ajax_elimina_utente(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$utente = $this->input->post('id_utente');
		$this->model_login->deleteUser($utente);
		if($utente == $this->session->userdata('user_id')){
			$this->session->sess_destroy();
		}
		echo '{}';
	}

	public function ajax_modifica_utente(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$this->load->library('encrypt');
		$this->load->library('fkms');
		$id_utente = $this->input->post('id_utente');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$password = $this->encrypt->sha1($password);
		if(!$this->fkms->insert_utente($id_utente, $username)) return; // FKMS
		$this->model_login->modificaUtente($id_utente, $username, $password);
		if($id_utente == $this->session->userdata('user_id')){
			$this->session->set_userdata('username', $username);
		}
		echo '{}';
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
