<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_home extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function eliminaRiga($tabella, $where){
		$this->logRipristinoExecuteOne($tabella, 'INSERT', $where);
		$this->db->query('DELETE FROM '.$tabella.' WHERE '.$where);
	}

	function transactionStart(){
		$this->db->trans_start(); // da il via ad un transaction
	}
	function transactionComplete(){
		$this->db->trans_complete(); // conclude la transaction
	}

	function getAutoincrementValue($table){
		$result = $this->db->query('SHOW TABLE STATUS FROM `arciconfraternita_carmine` LIKE '.$this->db->escape($table));
		$result = $result->result_array();
		return $result[0]['Auto_increment'];
	}

	/*function query_query(){
		$this->load->helper('file');
		$dati = read_file('./dump1.sql');
		$dati = explode(';', $dati);
		foreach ($dati as $key => $value) {
			$confratello = array();
			$persona = array();
			$dati[$key] = explode(',', $value);
			$confratello['codice'] = $dati[$key][0];
			$persona['cognome'] = $dati[$key][1];
			if($persona['cognome'] == NULL || $persona['cognome'] == 'NULL'){
				continue;
			}
			$persona['nome'] = $dati[$key][2];
			$persona['sesso'] = $dati[$key][3];
			$persona['data_nascita'] = $dati[$key][4];
			$persona['luogo_nascita'] = $dati[$key][5];
			$confratello['data_professione'] = $dati[$key][6];
			$confratello['paternita'] = $dati[$key][7];
			$confratello['maternita'] = $dati[$key][8];
			$persona['stato_civile'] = $dati[$key][9];
			if($persona['stato_civile'] == NULL || $persona['stato_civile'] == 'NULL'){
				$persona['stato_civile'] = 'celibe/nubile';
			}
			$persona['indirizzo'] = $dati[$key][10];
			$persona['telefono'] = $dati[$key][11];
			if($dati[$key][12] != 'NULL'){
				$persona['note'] = $dati[$key][12];
			}else{
				$persona['note'] = NULL;
			}
			$persona['infermo'] = $dati[$key][13];
			if($persona['infermo'] == NULL || $persona['infermo'] == 'NULL'){
				$persona['infermo'] = 0;
			}
			$confratello['codice_capofamiglia'] = $dati[$key][14];
			echo '<pre>';
			print_r($persona);
			echo '</pre>';
			echo '<pre>';
			print_r($confratello);
			echo '</pre>';
			$flag = 1;
			if($confratello['codice'] == 1959){
				$flag = 1;
			}
			if($flag == 1){
				$this->db->insert('persone', $persona);
				$confratello['id_persona'] = $this->db->insert_id();
				$this->db->insert('confratelli', $confratello);
			}
		}
		echo '<pre>';
		print_r($dati);
		echo '</pre>';
	}*/
}