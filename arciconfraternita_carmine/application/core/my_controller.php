<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function dateToSql($str, $accept_null = 0){
		if($str != '' && $str != NULL){
			// sostituisco i vari separatori consentiti
			$str = str_replace('/', '-', $str);
			$str = str_replace("\\", '-', $str);
			// modifico il formato
			$date = new DateTime($str);
			return $date->format('Y-m-d');
		}else{
			if($accept_null == 0){
				return NULL;
			}else{
				return '0000-00-00';
			}

		}
	}

	public function sqlToDate($str){
		if($str != '' && $str != NULL && $str != "0000-00-00"){
			$date = new DateTime($str);
			return $date->format('d/m/Y');
			//return date("d/m/Y", strtotime($str));
		}else{
			return '';
		}
	}

	public function queryToTable($restult, $colonne){
		$str = '<table class="table"><thead><tr>';
		foreach ($colonne as $key => $value) {
			$str .= '<th>'.$value.'</th>';
		}
		$str .= '</tr></thead><tbody>';
		foreach ($restult as $key1 => $value1) {
			$str .= '<tr>';
			foreach ($value1 as $key2 => $value2) {
				$str .= '<td>'.$value2.'</td>';
			}
			$str .= '</tr>';
		}
		$str .= '</tbody></table>';
		return $str;
	}

	public function queryCelletteTable($restult){
		//ordino il risultato in una tabella
		// table-stripped cancella i miei colori
		$str = '<table class="table table-hover"><thead><tr>';
		$str .= '<th></th>';
		$str .= '<th>Cappella</th>';
		$str .= '<th>Piano</th>';
		$str .= '<th>Sezione</th>';
		$str .= '<th>Fila</th>';
		$str .= '<th>Numero</th>';
		$str .= '<th>Tipo</th>';
		$str .= '</tr></thead><tbody>';
		foreach ($restult as $key => $value) {
			if(isset($value['color'])){
				if($value['color'] == 'si'){
					$str .= '<tr class="si-background">';
				}else{
					if($value['color'] == 'no'){
						$str .= '<tr class="no-background">';
					}
				}
			}else{
				$str .= '<tr>';
			}
			$str .= '<td><input type="radio" name="celletta_scelta" value="'.$value['id_celletta'].'" title="'.$value['id_celletta'].'"></td>';
			if(isset($value['cappella_nome'])){
				$str .= '<td id="query-cellette-cappella-'.$value['id_celletta'].'">'.$value['cappella_nome'].'</td>';
			}else{
				$str .= '<td id="query-cellette-cappella-'.$value['id_celletta'].'">'.$value['id_cappella'].'</td>';
			}
			$str .= '<td id="query-cellette-piano-'.$value['id_celletta'].'">'.$value['piano'].'</td>';
			$str .= '<td id="query-cellette-sezione-'.$value['id_celletta'].'">'.$value['sezione'].'</td>';
			$str .= '<td id="query-cellette-fila-'.$value['id_celletta'].'">'.$value['fila'].'</td>';
			$str .= '<td id="query-cellette-numero-'.$value['id_celletta'].'">'.$value['numero'].'</td>';
			$str .= '<td id="query-cellette-tipo-'.$value['id_celletta'].'">'.$value['tipo'].'</td>';
			$str .= '</tr>';
		}
		$str .= '</tbody></table>';
		$str .= '<script type="text/javascript">$("tr").click(function(){if($(this).find("input").attr("disabled") != "disabled")$(this).find("input").prop("checked", true);});</script>';
		return $str;
	}

	public function queryPersoneTable($restult, $className){
		//ordino il risultato in una tabella
		$str = '<table class="table table-striped table-hover"><thead><tr>';
		$str .= '<th></th>';
		$str .= '<th>Codice</th>';
		$str .= '<th>Cognome</th>';
		$str .= '<th>Nome</th>';
		$str .= '</tr></thead><tbody>';
		foreach ($restult as $key => $value) {
			if($value['codice'] == ''){
				$value['codice'] = '-';
			}
			if($value['nome'] == ''){
				$value['nome'] = '-';
			}

			$str .= '<tr>';
			$str .= '<td><input type="radio" name="'.$className.'_scelto" value="'.$value['id_persona'].'" title="'.$value['id_persona'].'"></td>';
			$str .= '<td id="query-'.$className.'-codice-'.$value['id_persona'].'">'.$value['codice'].'</td>';
			$str .= '<td id="query-'.$className.'-cognome-'.$value['id_persona'].'">'.$value['cognome'].'</td>';
			$str .= '<td id="query-'.$className.'-nome-'.$value['id_persona'].'">'.$value['nome'].'</td>';
			$str .= '</tr>';
		}
		$str .= '</tbody></table>';
		$str .= '<script type="text/javascript">$("tr").click(function(){if($(this).find("input").attr("disabled") != "disabled")$(this).find("input").prop("checked", true);});</script>';
		return $str;
	}

	public function queryDefuntiTable($restult, $className){
		//ordino il risultato in una tabella
		$str = '<table class="table table-striped table-hover"><thead><tr>';
		$str .= '<th></th>';
		$str .= '<th>Cognome</th>';
		$str .= '<th>Nome</th>';
		$str .= '<th>Data del decesso</th>';
		$str .= '</tr></thead><tbody>';
		foreach ($restult as $key => $value) {
			if($value['nome'] == ''){
				$value['nome'] = '-';
			}
			if($value['data_decesso'] == ''){
				$value['data_decesso'] = '-';
			}else{
				$value['data_decesso'] = $this->sqlToDate($value['data_decesso']);
			}

			$str .= '<tr>';
			$str .= '<td><input type="radio" name="'.$className.'_scelto" value="'.$value['id_defunto'].'" title="'.$value['id_defunto'].'"></td>';
			$str .= '<td id="query-'.$className.'-cognome-'.$value['id_defunto'].'">'.$value['cognome'].'</td>';
			$str .= '<td id="query-'.$className.'-nome-'.$value['id_defunto'].'">'.$value['nome'].'</td>';
			$str .= '<td id="query-'.$className.'-data_decesso-'.$value['id_defunto'].'">'.$value['data_decesso'].'</td>';
			$str .= '</tr>';
		}
		$str .= '</tbody></table>';
		$str .= '<script type="text/javascript">$("tr").click(function(){if($(this).find("input").attr("disabled") != "disabled")$(this).find("input").prop("checked", true);});</script>';
		return $str;
	}

	public function dataIntervalloToSql($name, $query){
		$tipo = $this->input->post('intervallo_'.$name);
		if($tipo == 'intervallo'){
			$inizio = $this->dateToSql($this->input->post($name.'_inizio'));
			$fine = $this->dateToSql($this->input->post($name.'_fine'));

			if($inizio != '')
				$query['#'.$name.'_inizio'] = 'DATEDIFF('.$name.', \''.$inizio.'\')>0';
			if($fine != '')
				$query['#'.$name.'_fine'] = 'DATEDIFF('.$name.', \''.$fine.'\')<0';
		}else{
			if($tipo == 'prima'){
				$prima = $this->dateToSql($this->input->post($name.'_prima'));
				if($prima != '')
					$query['#'.$name.'_prima'] = 'DATEDIFF('.$name.', \''.$this->dateToSql($this->input->post($name.'_prima')).'\')<=0';
			}else{
				if($tipo == 'dopo'){
					$dopo = $this->dateToSql($this->input->post($name.'_dopo'));
					if($dopo != '')
						$query['#'.$name.'_dopo'] = 'DATEDIFF('.$name.', \''.$this->dateToSql($this->input->post($name.'_dopo')).'\')>=0';
				}else{
					if($tipo == 'solo'){
						$solo = $this->dateToSql($this->input->post($name.'_solo'));
						if($solo != '')
							$query['#'.$name.'_solo'] = 'DATEDIFF('.$name.', \''.$this->dateToSql($this->input->post($name.'_solo')).'\')=0';
					}else{
						$query['#'.$name.''] = '1';
					}
				}
			}
		}
		return $query;
	}

	function curl_post($url, array $post = NULL, array $options = array()){
		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $url,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 4,
			CURLOPT_POSTFIELDS => http_build_query($post)
			);

		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		if( ! $result = curl_exec($ch))
		{
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}

	function curl_get($url, array $get = NULL, array $options = array()){
		$defaults = array(
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT => 4
			);

		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		if( ! $result = curl_exec($ch))
		{
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
		return $result;
	} 

	function log_string($str){
		$this->load->helper('file');
		write_file("./application/logs/my_log.php", '-'.$str.'-\n', 'a');
	}

	function array_debug($var){
		echo '<p>ARRAY DEBUG</p>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
}