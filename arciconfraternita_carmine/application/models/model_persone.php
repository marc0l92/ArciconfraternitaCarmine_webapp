<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_persone extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function insertPersonaConfratello($nuova_persona, $nuovo_confratello = NULL){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        $this->db->insert('persone', $this->db->escape($nuova_persona));
        $last_insert_id = $this->db->insert_id();
        $this->logRipristinoAdd('persone', 'DELETE', 'id_persona = '.$this->db->insert_id());
        if($nuovo_confratello != NULL){
            $nuovo_confratello['id_persona'] = $this->db->insert_id();
            if($nuovo_confratello['codice_capofamiglia'] == ''){
                $nuovo_confratello['codice_capofamiglia'] = 0;
            }
            $this->db->insert('confratelli', $this->db->escape($nuovo_confratello));
            $this->logRipristinoAdd('confratelli', 'DELETE', 'id_confratello = '.$this->db->insert_id());
        }
        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
        return $last_insert_id;
    }

    function modificaPersonaConfratello($idPersona, $persona, $confratello = NULL){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        // modifico la persona
        $where = "id_persona = ".$idPersona;
        $str = $this->db->update_string('persone', $this->db->escape($persona), $where);
        $this->logRipristinoAdd('persone', 'UPDATE', $where);
        $this->db->query($str);
        // modifico il confratello
        if($confratello != NULL){
            //cerco se esiste gia una riga in confratelli da modificare
            $result = $this->db->query('SELECT * FROM confratelli WHERE id_persona = '.$idPersona);
            $result = $result->result_array();
            if(isset($result[0])){
                // modifico la riga esistente
                $str = $this->db->update_string('confratelli', $confratello, $where);
                $this->logRipristinoAdd('confratelli', 'UPDATE', $where);
                $this->db->query($str);
            }else{
                // creo una riga da confratello
                $confratello['id_persona'] = $idPersona;
                $this->db->insert('confratelli', $this->db->escape($confratello));
                $this->logRipristinoAdd('confratelli', 'DELETE', 'id_confratello = '.$this->db->insert_id());
            }
        }else{
            //tolgo come confratello
            $this->logRipristinoAdd('confratelli', 'INSERT', 'id_persona = '.$idPersona);
            $this->db->query('DELETE FROM confratelli WHERE id_persona = '.$idPersona);
        }
        $this->db->trans_complete(); // conclude la transaction
        $this->logRipristinoExecute();
    }

    function getConfratelloData($codice){
        $query_str = "SELECT *
        FROM confratelli_persone
        WHERE codice = ".$this->db->escape($codice);
        $result = $this->db->query($query_str);
        $result = $result->result_array();
        if(isset($result[0])){
            return $result[0];
        }else{
            return NULL;
        }
    }

    //cellette di cui una persona è responsabile
    function getCelletteResponsabile($idPersona){
        $result = $this->db->query('SELECT * FROM cellette WHERE codice_responsabile = '.$this->db->escape($idPersona));
        return $result->result_array();
    }

    function codiceToIdPersona($codice){
        if($codice == ''){
            $codice = -1;
        }
        $result = $this->db->query('SELECT id_persona FROM confratelli WHERE codice = '.$this->db->escape($codice));
        $result = $result->result_array();
        if(isset($result[0])){
            return $result[0]['id_persona'];
        }else{
            return -1;
        }
    }

    function cercaPersoneResult($dati){
        $query_str = 'SELECT persone.id_persona AS id_persona, cognome, nome, codice, data_nascita, sesso, luogo_nascita, indirizzo,
        citta, telefono, cellulare, infermo, stato_civile, note, paternita, maternita, data_professione, codice_capofamiglia
        FROM persone LEFT JOIN confratelli ON persone.id_persona = confratelli.id_persona
        WHERE persone.id_persona != 0 AND 1 ';
        foreach ($dati as $key => $value) {
            if($value != ''){
                if($key[0] == '#'){
                    $query_str .= ' AND '.$value;
                }else{
                    if(is_numeric($value[0])){  //in modo da rilevare numeri e date
                        $query_str .= ' AND '.$key.' = '.$this->db->escape($value);
                    }else{
                        $query_str .= ' AND '.$key.' LIKE '.$this->db->escape('%'.$value.'%');
                    }
                }
            }
        }
        $query_str .= " ORDER BY persone.cognome, persone.nome, persone.id_persona";
        $result = $this->db->query($query_str);
        $result = $result->result_array();
        return $result;
    }

    function getAutocompleteData(){
        $autocomplete = array();

        $result = $this->db->query('SELECT DISTINCT cognome FROM persone');
        $autocomplete['cognome'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT nome FROM persone');
        $autocomplete['nome'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT luogo_nascita FROM persone');
        $autocomplete['luogo_nascita'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT citta FROM persone');
        $autocomplete['citta'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT codice FROM confratelli');
        $autocomplete['codice'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT codice_capofamiglia FROM confratelli');
        $autocomplete['codice_capofamiglia'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT paternita FROM confratelli');
        $autocomplete['paternita'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT maternita FROM confratelli');
        $autocomplete['maternita'] = $result->result_array();

        return $autocomplete;
    }
}