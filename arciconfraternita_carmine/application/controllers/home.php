
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_update');
		$this->load->model('model_home');
		$this->load->library('fkms');
		$this->data['breadcrumb']['Home'] = site_url('home');
	}

	public function index(){
		/*
		$current_version = $this->model_update->getSetting('version');
		$project_name = $this->model_update->getSetting('project_name');
		$update_url = $this->model_update->getSetting('update_url');
		$file_headers = @get_headers($update_url);
		if($file_headers != '' && $file_headers[0] != 'HTTP/1.1 404 Not Found') {
			$result = file_get_contents($update_url);
			$result = json_decode($result, true);
			if($result['version'][$project_name] != $current_version){
				$this->data['aggiornamento'] = $result['version'][$project_name];
				$this->data['aggiornamento_data']['number'] = count($result[$project_name]);
				$this->data['aggiornamento_data']['first_file'] = $result[$project_name][0];
			}else{
				$this->data['aggiornamento'] = "success";
			}
		}else{
			$this->data['aggiornamento'] = "error";
		}
		*/
		$this->data['title'] = 'Home';
		$this->data['breadcrumb'][$this->data['title']] = '';
		$this->load->view('heading/view_head', $this->data);
		$this->load->view('pages/view_home');
		$this->load->view('heading/view_footer');
	}

	public function ajax_save_update(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$base_path = './application';
		$this->load->helper('file');
		$index = $this->input->post('index');
		
		$project_name = $this->model_update->getSetting('project_name');
		$update_url = $this->model_update->getSetting('update_url');
		$result = file_get_contents($update_url);
		$result = json_decode($result, true);
		$file_name = $result[$project_name][$index];

		if($file_name != ''){
			if($file_name[0] == '#'){
				$file_name = substr($file_name, 1);
				if(!is_dir($base_path.'/'.$file_name)){ //se la cartella non esiste gia
					mkdir($base_path.'/'.$file_name, 0777, true);
				}
			}else{
				if($file_name[0] == '@'){
					$file_name = substr($file_name, 1);
					$this->model_update->updateQuery($file_name);
				}else{
					$update_base_url = $this->model_update->getSetting('update_base_url');
					$contents = file_get_contents($update_base_url.'/'.$project_name.'/'.$file_name.'.txt');
					write_file($base_path.'/'.$file_name, $contents, 'w');
				}
			}
		}
		if(isset($result[$project_name][$index+1])){
			$array = array('next_file' => $result[$project_name][$index+1]);
		}else{
			$array = array('next_file' => '');
		}
		echo json_encode($array);
	}

	public function ajax_version_update(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$project_name = $this->model_update->getSetting('project_name');
		$update_url = $this->model_update->getSetting('update_url');
		$result = file_get_contents($update_url);
		$result = json_decode($result, true);
		$this->model_update->setSetting('version', $result['version'][$project_name]);
		echo '{}';
	}

	public function ajax_elimina_riga(){
		if($this->session->userdata('logged_in') == false) die('Accesso negato'); //controllo login
		$num = $this->input->post('num');
		$this->model_home->transactionStart();
		for($i = 0; $i < $num; $i++){
			$tabella = $this->input->post('tabella'.$i);
			$where = $this->input->post('where'.$i);
			//elimino gli apostrofi
			$where = substr($where, 1);
			$where = substr($where, 0, -1);
			switch ($tabella) {
				case 'cellette':
					if(!$this->fkms->elimina_celletta($where)) return; // FKMS
					break;
				case 'incarichi':
					if(!$this->fkms->elimina_incarico($where)) return; // FKMS
					break;
				case 'persone':
					if(!$this->fkms->elimina_persona($where)) return; // FKMS
					break;
				case 'confratelli':
					if(!$this->fkms->elimina_confratello($where)) return; // FKMS
					break;
			}
			$this->model_home->eliminaRiga($tabella, $where);
		}
		$this->model_home->transactionComplete();
		echo '{}';
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */