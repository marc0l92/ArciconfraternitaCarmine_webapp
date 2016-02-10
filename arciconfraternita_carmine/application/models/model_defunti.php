<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_defunti extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    //cellette acquistate da una persona e senza defunto al suo interno
    function getCelletteAcquistate($idPersona){
        $idPersona = $this->db->escape($idPersona);
        $result = $this->db->query('SELECT * FROM dettagli_cellette WHERE id_acquirente = '.$idPersona.' AND (id_defunto = 0 OR id_defunto IS NULL)');
        $result = $result->result_array();
        foreach ($result as $key => $value) {
            $result[$key]['color'] = 'si';
        }
        return $result;
    }

    function insertDefunto($nuovo_defunto, $nuova_sepoltura, $nuovo_reposansabile, $eliminaConfratello = 0){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        // inserisco il nuovo defunto
        $this->db->insert('defunti', $this->db->escape($nuovo_defunto));
        $id_defunto = $this->db->insert_id();
        $this->logRipristinoAdd('defunti', 'DELETE', 'id_defunto = '.$this->db->insert_id());
        $nuova_sepoltura['id_defunto'] = $id_defunto;
        // inserisco la sepoltura
        $this->db->insert('sepolture', $this->db->escape($nuova_sepoltura));
        $this->logRipristinoAdd('sepolture', 'DELETE', 'sepolture.id = '.$this->db->insert_id());
        //inserisco una responsabilità defunto
        $nuovo_reposansabile['id_defunto'] = $nuova_sepoltura['id_defunto'];
        $this->db->insert('responsabili_defunti', $this->db->escape($nuovo_reposansabile));
        $this->logRipristinoAdd('responsabili_defunti', 'DELETE', 'responsabili_defunti.id = '.$this->db->insert_id());

        //elimino la persona e il confratello
        if($eliminaConfratello == 1 || $eliminaConfratello == true || $eliminaConfratello == 'true'){
            $result = $this->db->query('SELECT persone.id_persona FROM persone LEFT JOIN confratelli ON persone.id_persona = confratelli.id_persona WHERE codice = '.$nuovo_defunto['codice_confratello']);
            $result = $result->result_array();
            // sposto le cellette acquistate al defunto
            $result1 = $this->db->query('SELECT id_celletta FROM cellette WHERE id_acquirente = '.$result[0]['id_persona']);
            $result1 = $result1->result_array();
            foreach ($result1 as $key => $value) {
                $where = 'id_celletta = '.$value['id_celletta'];
                $str = $this->db->update_string('cellette', array('id_acquirente' => '0', 'id_acquirente_defunto' => $id_defunto), $where);
                $this->logRipristinoAdd('cellette', 'UPDATE', $where);
                $this->db->query($str);
            }
            //
            $this->logRipristinoAdd('confratelli', 'INSERT', 'codice = '.$this->db->escape($nuovo_defunto['codice_confratello']));
            $this->logRipristinoAdd('persone', 'INSERT', 'id_persona = '.$result[0]['id_persona']);
            $this->db->delete('confratelli', array('codice' => $nuovo_defunto['codice_confratello']));
            $this->db->delete('persone', array('id_persona' => $result[0]['id_persona']));
        }

        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
        return $nuova_sepoltura['id_defunto'];
    }

    function modificaDefunto($defunto, $sepoltura, $responsabile, $idDefunto){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        // modifico il defunto
        $where = "id_defunto = ".$this->db->escape($idDefunto);
        $str = $this->db->update_string('defunti', $defunto, $where);
        $this->logRipristinoAdd('defunti', 'UPDATE', $where);
        $this->db->query($str);
        // modifico la sepoltura
        //controllo la presenza della riga associata in sepolutre
        $where .= " AND ISNULL(data_fine) ";
        $result = $this->db->query('SELECT * FROM sepolture WHERE '.$where);
        if(!isset($result->result_array()[0])){
            $sepoltura1 = array('id_defunto' => $idDefunto, 'id_celletta' => 0, 'data_inizio' => '0000-00-00');
            $this->db->insert('sepolture', $this->db->escape($sepoltura1));
        }
        $str = $this->db->update_string('sepolture', $sepoltura, $where);
        $this->logRipristinoAdd('sepolture', 'UPDATE', $where);
        $this->db->query($str);
        // modifico il responsabile
        $result = $this->db->query('SELECT * FROM responsabili_defunti WHERE '.$where);
        if(!isset($result->result_array()[0])){
            $responsabile1 = array('id_defunto' => $idDefunto, 'id_responsabile' => 0, 'data_inizio' => '0000-00-00');
            $this->db->insert('responsabili_defunti', $this->db->escape($responsabile1));
        }
        $str = $this->db->update_string('responsabili_defunti', $responsabile, $where);
        $this->logRipristinoAdd('responsabili_defunti', 'UPDATE', $where);
        $this->db->query($str);
        // fine
        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
    }

    function cercaDefuntiResult($dati){
        $query_str = 'SELECT * FROM dettagli_defunti WHERE id_defunto != 0 ';
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
        $query_str .= ' GROUP BY id_defunto';
        $result = $this->db->query($query_str);
        $result = $result->result_array();
        return $result;
    }

    function getAutocompleteData(){
        $autocomplete = array();

        $result = $this->db->query('SELECT DISTINCT cognome FROM defunti');
        $autocomplete['cognome'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT nome FROM defunti');
        $autocomplete['nome'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT luogo_nascita FROM defunti');
        $autocomplete['luogo_nascita'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT citta FROM defunti');
        $autocomplete['citta'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT codice_confratello FROM defunti');
        $autocomplete['codice_confratello'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT paternita FROM defunti');
        $autocomplete['paternita'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT maternita FROM defunti');
        $autocomplete['maternita'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT piano FROM cellette');
        //cellette
        $autocomplete['piano'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT sezione FROM cellette');
        $autocomplete['sezione'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT fila FROM cellette');
        $autocomplete['fila'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT numero FROM cellette');
        $autocomplete['numero'] = $result->result_array();

        return $autocomplete;
    }

    function spostaDefunto($defunto, $celletta_destinazione, $data_trasferimento){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        
        $result = $this->db->query('SELECT * FROM sepolture WHERE ISNULL(data_fine) AND id_defunto = '.$this->db->escape($defunto));
        $result = $result->result_array();

        foreach ($result as $key => $value) {
            $where = "id = ".$value['id'];
            $str = $this->db->update_string('sepolture', array('data_fine' => $data_trasferimento), $where);
            $this->logRipristinoAdd('sepolture', 'UPDATE', $where);
            $this->db->query($str);

            $nuova_sepoltura = array('id_defunto' => '0', 'id_celletta' => $value['id_celletta'], 'data_inizio' => '0000-00-00');
            $this->db->insert('sepolture', $this->db->escape($nuova_sepoltura));
            $this->logRipristinoAdd('sepolture', 'DELETE', 'id = '.$this->db->insert_id());
        }
        
        $nuova_sepoltura = array('id_defunto' => $defunto, 'id_celletta' => $celletta_destinazione, 'data_inizio' => $data_trasferimento);
        $this->db->insert('sepolture', $this->db->escape($nuova_sepoltura));
        $this->logRipristinoAdd('sepolture', 'DELETE', 'id = '.$this->db->insert_id());

        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
    }
}