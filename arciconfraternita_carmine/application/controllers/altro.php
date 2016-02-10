<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Altro extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->data['breadcrumb']['Home'] = site_url('home');
		$this->data['breadcrumb']['Altro'] = site_url('altro');
		$this->load->model('model_altro');
	}

	public function index(){
		$this->data['title'] = 'Altro';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/view_altro');
		$this->load->view('heading/view_footer');
	}

	public function statistiche(){
		$this->data['tabella'] = $this->model_altro->getStatistiche();
		$this->data['title'] = 'Statisctiche';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/altro/view_statistiche');
		$this->load->view('heading/view_footer');
	}

	public function back_up(){
		$this->data['breadcrumb']['Backup'] = '';
		// Load the DB utility class
		$this->load->dbutil();

		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup();

		// Load the file helper and write the file to your server
		$this->load->helper('file');
		write_file('arciconfraternita_carmine-backup_database.zip', $backup);

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download('arciconfraternita_carmine-backup_database.zip', $backup); 
	}

	public function log(){
		$this->data['tabella'] = $this->model_altro->getLog();
		$this->data['title'] = 'Log e ripristino';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/altro/view_log');
		$this->load->view('heading/view_footer');
	}

	public function ajax_ripristina_log(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$id_log = $this->input->post('id_log');
		$this->model_altro->ripristinaLog($id_log);
		echo '{}';
	}

	public function about(){
		$this->data['title'] = 'Informazioni applicazione';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/altro/view_about');
		$this->load->view('heading/view_footer');
	}

	public function database_fix(){
		$this->model_altro->databaseFix();
	}
}

/* End of file altro.php */
/* Location: ./application/controllers/altro.php */
