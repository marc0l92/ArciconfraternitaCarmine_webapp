<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_incarichi extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function insertIncarico($nuovo_incarico){
        $this->db->insert('incarichi', $this->db->escape($nuovo_incarico));
        $last_insert_id = $this->db->insert_id();
        $this->logRipristinoExecuteOne('incarichi', 'DELETE', 'id_incarico = '.$last_insert_id);
        return $last_insert_id;
    }

    function getIncarichi($limit = 1){
        $confratelli = array();
        $query_str = 'SELECT id_incarico AS incarico_id_incarico, nome AS incarico_nome, note AS incarico_note FROM incarichi WHERE id_incarico != 0';
        $incarichi = $this->db->query($query_str);
        $incarichi = $incarichi->result_array();
        foreach ($incarichi as $key => $value) {
            $query_str = 'SELECT incarico_confratello.id AS incarico_confratello_id, incarico_confratello.id_confratello AS confratello_id_confratello, confratelli.codice AS confratello_codice,
            incarico_confratello.data_inizio AS incarico_data_inizio, incarico_confratello.note AS incarico_confratello_note,
            persone.nome AS persona_nome, persone.cognome AS persona_cognome, confratelli.id_persona
            FROM ((incarichi LEFT JOIN incarico_confratello ON incarichi.id_incarico = incarico_confratello.id_incarico) LEFT JOIN confratelli ON incarico_confratello.id_confratello = confratelli.id_confratello) LEFT JOIN persone ON confratelli.id_persona = persone.id_persona
            WHERE incarico_confratello.id_incarico = '.$value['incarico_id_incarico'].' 
            ORDER BY DATEDIFF(NOW(), incarico_confratello.data_inizio) ASC';
            if($limit != 0){
                $query_str .= ' LIMIT 0,'.$this->db->escape($limit);
            }else{
                $query_str .= '';
            }
            $temp = $this->db->query($query_str);
            $temp = $temp->result_array();
            if(isset($temp[0])){
                $confratelli[$key] = $temp;
            }
        }
        $result = array('incarichi' => $incarichi, 'confratelli' => $confratelli);
        return $result;
    }

    function assegnaIncarico($data, $modifica_riassegna='riassegna', $incarico_confratello_id=0){
        if($modifica_riassegna == 'modifica'){
            //modifica
            $where = "id = ".$this->db->escape($incarico_confratello_id);
            //$this->array_debug($where);
            $this->logRipristinoExecuteOne('incarico_confratello', 'UPDATE', $where);
            $str = $this->db->update_string('incarico_confratello', $data, $where);
            $this->db->query($str);
            return $incarico_confratello_id;
        }else{
            //riassegna
            $this->db->insert('incarico_confratello', $this->db->escape($data));
            $last_id = $this->db->insert_id();
            $this->logRipristinoExecuteOne('incarico_confratello', 'DELETE', 'incarico_confratello.id = '.$last_id);
            return $last_id;
        }
    }
}