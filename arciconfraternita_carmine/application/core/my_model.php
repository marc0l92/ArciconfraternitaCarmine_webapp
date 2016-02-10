<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		//carica il database per poterlo usare in seguito
		$this->load->database();
		$this->log = array();
	}

	function log_string($str){
		$this->load->helper('file');
		write_file("./application/logs/my_log.php", '-'.$str.'-\n', 'a');
	}

	//funzioni per il log e ripristino
	function logRipristinoClear(){
		$this->log = array('id_utente' => $this->session->userdata('user_id'), 'query' => '', 'description' => '');
	}
	function logRipristinoAdd($table, $type, $where){
		switch ($type) {
			//delete
			case 'DELETE':
			$this->log['query'] .= 'DELETE FROM '.$table.' WHERE '.$where.'; ';
			$this->log['description'] .= 'Aggiunta una riga nella tabella: '.$table.'; ';
			break;
			//update
			case 'UPDATE':
			$result = $this->db->query('SELECT * FROM '.$table.' WHERE '.$where);
			//if(isset($result->result_array()[0])){
			$result = $result->result_array()[0];
			$this->log['query'] .= $this->db->update_string($table, $result, $where).'; ';
			$this->log['description'] .= 'Modificata una riga della tabella: '.$table.'; ';
			//}
			break;
			//insert
			case 'INSERT':
			$result = $this->db->query('SELECT * FROM '.$table.' WHERE '.$where);
			$result = $result->result_array();
			if(isset($result[0])){
				$result = $result[0];
				$this->log['query'] .= $this->db->insert_string($table, $result).'; ';
				$this->log['description'] .= 'Eliminata una riga dalla tabella: '.$table.'; ';
			}
			break;
		}
	}
	function logRipristinoExecute(){
		$this->db->insert('log_ripristino', $this->log);
		$this->log = array('id_utente' => $this->session->userdata('user_id'), 'query' => '', 'description' => '');
	}
	function logRipristinoExecuteOne($table, $type, $where){
		$this->logRipristinoClear();
		$this->logRipristinoAdd($table, $type, $where);
		$this->logRipristinoExecute();
	}

	public function sqlToDate($str){
		if($str != '' && $str != NULL && $str != "0000-00-00"){
			$date = new DateTime($str);
			return $date->format('d/m/Y');
		}else{
			return '';
		}
	}

	//$this->array_debug();
	function array_debug($var){
		echo '<p>ARRAY DEBUG</p>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
}