<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_cappella_gentilizia extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function insertCappella($nuova_cappella){
        $this->db->insert('cappelle', $this->db->escape($nuova_cappella));
        $last_insert_id = $this->db->insert_id();
        $this->logRipristinoExecuteOne('cappelle', 'DELETE', 'id_cappella = '.$last_insert_id);
        return $last_insert_id;
    }

    // preleva l'elenco dei nomi delle cappelle
    function getCappelle(){
        $result = $this->db->query('SELECT * FROM cappelle WHERE id_cappella != 0 ORDER BY nome');
        return $result->result_array();
    }

    function insertCelletta($nuova_celletta, $nuovo_responsabile = NULL, $nuovo_pilone = NULL){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        if($nuovo_pilone != NULL){
            $this->db->insert('piloni', $this->db->escape($nuovo_pilone));
            $nuova_celletta['id_pilone'] = $this->db->insert_id();
            $this->logRipristinoAdd('piloni', 'DELETE', 'id_pilone = '.$this->db->insert_id());
        }
        $this->db->insert('cellette', $this->db->escape($nuova_celletta));
        $last_insert_id = $this->db->insert_id();
        $this->logRipristinoAdd('cellette', 'DELETE', 'id_celletta = '.$this->db->insert_id());
        if($nuovo_responsabile != NULL){
            $nuovo_responsabile['id_celletta'] = $this->db->insert_id();
            $this->db->insert('responsabili_cellette', $this->db->escape($nuovo_responsabile));
            $this->logRipristinoAdd('responsabili_cellette', 'DELETE', 'responsabili_cellette.id = '.$this->db->insert_id());
        }
        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
        return $last_insert_id;
    }

    function insertSepolture($id_celletta, $defunti){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        foreach ($defunti as $key => $value) {
            $nuova_sepoltura = array('id_defunto' => $value['id'], 'id_celletta' => $id_celletta, 'data_inizio' => $value['data']);
            $this->db->insert('sepolture', $this->db->escape($nuova_sepoltura));
            $this->logRipristinoAdd('sepolture', 'DELETE', 'sepolture.id = '.$this->db->insert_id());
        }
        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
    }

    function insertResponsabileDefunti($defunti, $responsabile_defunto){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        foreach ($defunti as $key => $value) {
            // se il defunto non è NESSUNO
            if($value['id'] != 0){
                $responsabile_defunto['id_defunto'] = $value['id'];
                $this->db->insert('responsabili_defunti', $this->db->escape($responsabile_defunto));
                $this->logRipristinoAdd('responsabili_defunti', 'DELETE', 'responsabili_defunti.id = '.$this->db->insert_id());
            }
        }
        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
    }

    function modificaCelletta($id_celletta, $celletta, $responsabile_celletta = NULL, $pilone = NULL){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();

        // acquirente
        if($celletta['id_acquirente'] == ''){
            $celletta['id_acquirente'] = NULL;
        }

        // pilone
        $flag_ho_gia_modificato_la_celletta = false;
        if($pilone != NULL){
            $temp = $this->db->query('SELECT id_pilone FROM cellette WHERE id_celletta = '.$id_celletta);
            $temp = $temp->result_array();
            if(isset($temp[0]) && $temp[0]['id_pilone'] != "" && $temp[0]['id_pilone'] != NULL){
                //modifico il pilone esistente
                $id_pilone = $temp[0]['id_pilone'];
                $where = "id_pilone = ".$id_pilone;
                $str = $this->db->update_string('piloni', $this->db->escape($pilone), $where);
                $this->logRipristinoAdd('piloni', 'UPDATE', $where);
                $this->db->query($str);
            }else{
                //creo un pilone
                $this->db->insert('piloni', $this->db->escape($pilone));
                $this->logRipristinoAdd('piloni', 'DELETE', 'id_pilone = '.$this->db->insert_id());
                $celletta['id_pilone'] = $this->db->insert_id();
            }
        }else{
            //elimino il pilone se esiste
            $temp = $this->db->query('SELECT id_pilone FROM cellette WHERE id_celletta = '.$id_celletta);
            $temp = $temp->result_array();
            if(isset($temp[0])){
                $celletta['id_pilone'] = NULL;
                //devo fare prima questo backup in modo da creare il giuto ordine per il log
                $this->logRipristinoAdd('piloni', 'INSERT', 'id_pilone = '.$this->db->escape($temp[0]['id_pilone']));

                //se devo eliminare il pilone allora devo prima modificare la celletta
                $flag_ho_gia_modificato_la_celletta = true;
                $where = "id_celletta = ".$id_celletta;
                $str = $this->db->update_string('cellette', $this->db->escape($celletta), $where);
                $this->logRipristinoAdd('cellette', 'UPDATE', $where);
                $this->db->query($str);

                //$this->logRipristinoAdd('piloni', 'INSERT', 'id_pilone = '.$this->db->escape($temp[0]['id_pilone'])); //spostata 5 righe prima
                $this->db->query('DELETE FROM piloni WHERE id_pilone = '.$this->db->escape($temp[0]['id_pilone']));
            }
        }

        // celletta
        if(!$flag_ho_gia_modificato_la_celletta){        
            $where = "id_celletta = ".$id_celletta;
            $str = $this->db->update_string('cellette', $this->db->escape($celletta), $where);
            $this->logRipristinoAdd('cellette', 'UPDATE', $where);
            $this->db->query($str);
        }

        // responsabile celletta
        if($responsabile_celletta != NULL){
            $temp = $this->db->query('SELECT id FROM responsabili_cellette WHERE id_celletta = '.$id_celletta);
            $temp = $temp->result_array();
            if(isset($temp[0])){
                $id_responsabile_id = $temp[0]['id'];
                $where = "id = ".$this->db->escape($id_responsabile_id);
                $str = $this->db->update_string('responsabili_cellette', $this->db->escape($responsabile_celletta), $where);
                $this->logRipristinoAdd('responsabili_cellette', 'UPDATE', $where);
                $this->db->query($str);
            }else{
                $responsabile_celletta['id_celletta'] = $id_celletta;
                $this->db->insert('responsabili_cellette', $this->db->escape($responsabile_celletta));
                $this->logRipristinoAdd('responsabili_cellette', 'DELETE', 'responsabili_cellette.id = '.$this->db->insert_id());
            }
        }else{
            //elimino il responsabile celletta
            $temp = $this->db->query('SELECT id FROM responsabili_cellette WHERE id_celletta = '.$id_celletta);
            $temp = $temp->result_array();
            if(isset($temp[0])){
                $this->logRipristinoAdd('responsabili_cellette', 'INSERT', 'id = '.$this->db->escape($temp[0]['id']));
                $this->db->query('DELETE FROM responsabili_cellette WHERE id = '.$this->db->escape($temp[0]['id']));
            }
        }

        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
    }


    function modificaSepolture($id_celletta, $defunti){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        // cancello le sepolture in corso della celletta
        $where = ' id_celletta = '.$this->db->escape($id_celletta).' AND ISNULL(data_fine) ';
        $this->logRipristinoAdd('sepolture', 'INSERT', $where);
        $this->db->query('DELETE FROM sepolture WHERE'.$where);
        // cancello le sepolture in corso dei defunti
        foreach ($defunti as $key => $value) {
            $where = ' id_defunto = '.$this->db->escape($value['id']).' AND ISNULL(data_fine) ';
            $this->logRipristinoAdd('sepolture', 'INSERT', $where);
            $this->db->query('DELETE FROM sepolture WHERE'.$where);
        }
        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
        $this->insertSepolture($id_celletta, $defunti);
    }

    function modificaResponsabileDefunti($defunti, $responsabile_defunto){
        $this->db->trans_start(); // da il via ad un transaction
        $this->logRipristinoClear();
        foreach ($defunti as $key => $value) {
            $where = ' id_defunto = '.$this->db->escape($value['id']).' AND ISNULL(data_fine) ';
            $this->logRipristinoAdd('responsabili_defunti', 'INSERT', $where);
            $this->db->query('DELETE FROM responsabili_defunti WHERE'.$where);
        }
        $this->logRipristinoExecute();
        $this->db->trans_complete(); // conclude la transaction
        $this->insertResponsabileDefunti($defunti, $responsabile_defunto);
    }

    function cercaCelletta($query, $id_acquirente = -1, $tipo_ricerca = 'acquistate'){
        $result = $this->cercaCelletteResult($query);
        foreach ($result as $key => $value) {
            switch($tipo_ricerca){
                case 'acquistate':
                //colora in base all'occupazione
                if($value['id_defunto'] != 0 && $value['id_defunto'] != NULL){
                    $result[$key]['color'] = 'no';
                }
                //colora in base all'acquirente
                if(($value['id_acquirente'] == '' || $value['id_acquirente'] == NULL || $value['id_acquirente'] == 0) &&
                    ($value['id_acquirente_defunto'] == '' || $value['id_acquirente_defunto'] == NULL || $value['id_acquirente_defunto'] == 0)){
                    //senza acquirente
                    $result[$key]['color'] = '';
                } else {
                    if($value['id_acquirente'] == $id_acquirente || $value['id_acquirente_defunto'] == $id_acquirente){
                        $result[$key]['color'] = 'si';
                    }else{
                        $result[$key]['color'] = 'no';
                    }
                }
                break;
                case 'libere':
                if($value['id_defunto'] != 0 && $value['id_defunto'] != NULL){
                    $result[$key]['color'] = 'no';
                }
                break;
                case 'occupate':
                //cerco le libere per bloccarle
                if($value['id_defunto'] == 0 || $value['id_defunto'] == NULL){
                    $result[$key]['color'] = 'no';
                }
                break;
            }
        }
        return $result;
    }

    function modifica_cappella($id_cappella, $data){
        $where = "id_cappella = ".$this->db->escape($id_cappella);
        $str = $this->db->update_string('cappelle', $this->db->escape($data), $where);
        $this->logRipristinoExecuteOne('cappelle', 'UPDATE', $where);
        $this->db->query($str);
    }

    function elimina_cappella($id_cappella){
        $this->logRipristinoExecuteOne('cappelle', 'INSERT', 'id_cappella = '.$this->db->escape($id_cappella));
        $this->db->query('DELETE FROM cappelle WHERE id_cappella = '.$this->db->escape($id_cappella));
        return true;
    }

    function getAutocompleteData(){
        $autocomplete = array();

        $result = $this->db->query('SELECT DISTINCT piano FROM cellette ORDER BY piano');
        $autocomplete['piano'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT sezione FROM cellette ORDER BY sezione');
        $autocomplete['sezione'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT fila FROM cellette ORDER BY fila');
        $autocomplete['fila'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT numero FROM cellette ORDER BY numero');
        $autocomplete['numero'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT codice_bolletta FROM cellette ORDER BY codice_bolletta');
        $autocomplete['codice_bolletta'] = $result->result_array();
        //acquirenti
        $result = $this->db->query('SELECT DISTINCT codice AS codice_acquirente
            FROM cellette LEFT JOIN confratelli_persone ON confratelli_persone.id_persona = cellette.id_acquirente ORDER BY codice_acquirente');
        $autocomplete['codice_acquirente'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT cognome AS cognome_acquirente
            FROM cellette LEFT JOIN persone ON persone.id_persona = cellette.id_acquirente ORDER BY cognome_acquirente');
        $autocomplete['cognome_acquirente'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT nome AS nome_acquirente
            FROM cellette LEFT JOIN persone ON persone.id_persona = cellette.id_acquirente ORDER BY nome_acquirente');
        $autocomplete['nome_acquirente'] = $result->result_array();
        //responsabili cellette
        $result = $this->db->query('SELECT DISTINCT codice AS codice_responsabile
            FROM (cellette LEFT JOIN responsabili_cellette ON responsabili_cellette.id_celletta = cellette.id_celletta) LEFT JOIN confratelli_persone ON responsabili_cellette.id_responsabile = confratelli_persone.id_persona
            ORDER BY codice_responsabile');
        $autocomplete['codice_responsabile'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT cognome AS cognome_responsabile
            FROM (cellette LEFT JOIN responsabili_cellette ON responsabili_cellette.id_celletta = cellette.id_celletta) LEFT JOIN persone ON responsabili_cellette.id_responsabile = persone.id_persona
            ORDER BY cognome_responsabile');
        $autocomplete['cognome_responsabile'] = $result->result_array();
        $result = $this->db->query('SELECT DISTINCT nome AS nome_responsabile
            FROM (cellette LEFT JOIN responsabili_cellette ON responsabili_cellette.id_celletta = cellette.id_celletta) LEFT JOIN persone ON responsabili_cellette.id_responsabile = persone.id_persona
            ORDER BY nome_responsabile');
        $autocomplete['nome_responsabile'] = $result->result_array();
        return $autocomplete;
    }

    function cercaCelletteResult($dati){
        $query_str = 'SELECT * FROM dettagli_cellette WHERE id_celletta != 0 AND 1 ';
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
        $query_str.=' GROUP BY id_celletta';
        $result = $this->db->query($query_str);
        $result = $result->result_array();

        //elimino le informazioni su i "nessuno"
        foreach ($result as $key => $value) {
            if($value['id_acquirente'] == 0 || $value['id_acquirente'] == '0'){
                $result[$key]['id_acquirente'] = 0;
                $result[$key]['cognome_acquirente'] = NULL;
                $result[$key]['nome_acquirente'] = NULL;
                $result[$key]['acquirente_description'] = NULL;
            }
            if($value['id_responsabile'] == 0 || $value['id_responsabile'] == '0'){
                $result[$key]['id_responsabile'] = 0;
                $result[$key]['cognome_responsabile'] = NULL;
                $result[$key]['nome_responsabile'] = NULL;
                $result[$key]['responsabile_description'] = NULL;
                $result[$key]['data_inizio_responsabilita'] = NULL;
            }
            if($value['id_defunto'] == 0 || $value['id_defunto'] == '0'){
                $result[$key]['id_defunto'] = 0;
                $result[$key]['cognome_defunto'] = NULL;
                $result[$key]['nome_defunto'] = NULL;
                $result[$key]['defunto_description'] = NULL;
                $result[$key]['data_sepoltura'] = NULL;
            }
            if($value['id_responsabile_defunto'] == 0 || $value['id_responsabile_defunto'] == '0'){
                $result[$key]['id_responsabile_defunto'] = 0;
                $result[$key]['cognome_responsabile_defunto'] = NULL;
                $result[$key]['nome_responsabile_defunto'] = NULL;
                $result[$key]['responsabile_defunto_description'] = NULL;
                $result[$key]['data_inizio_responsabilita_defunto'] = NULL;
            }
        }

        // aggiungo il campo in cui conto i defunti per celletta
        foreach ($result as $key => $value) {
            $query_str = "SELECT COUNT(*) AS count FROM sepolture WHERE ISNULL(data_fine) AND id_defunto != 0 AND id_celletta = ".$value['id_celletta']." GROUP BY id_celletta";
            $result1 = $this->db->query($query_str);
            $result1 = $result1->result_array();
            if(isset($result1[0])){
                if($value['id_defunto'] == 0 || $value['id_defunto'] == NULL){
                    // se la GROUP BY carica come defunto NESSUNO allora lo cambio con un'altro
                    $query_str = "SELECT * FROM dettagli_cellette WHERE id_defunto != 0 AND id_celletta = ".$value['id_celletta'];
                    $result2 = $this->db->query($query_str);
                    $result2 = $result2->result_array();
                    $result[$key] = $result2[0];
                }
                $result[$key]['count_defunti'] = $result1[0]['count'];
            }else{
                $result[$key]['count_defunti'] = 0;
            }
        }

        return $result;
    }

    function getCelletteTree(){
        $tree = $this->getCappelle();
        foreach ($tree as $key1 => $value1) {
            $temp = $this->db->query('SELECT DISTINCT piano FROM cellette WHERE id_celletta !=0 AND id_cappella = '.$value1['id_cappella']);
            $temp = $temp->result_array();
            $tree[$key1]['piani'] = $temp;
            foreach ($tree[$key1]['piani'] as $key2 => $value2) {
               $temp = $this->db->query('SELECT DISTINCT sezione FROM cellette WHERE id_celletta !=0 AND id_cappella = '.$value1['id_cappella'].' AND piano = '.$value2['piano']);
               $temp = $temp->result_array();
               $tree[$key1]['piani'][$key2]['sezioni'] = $temp;
           }
       }
       return $tree;
   }

   function getTableSize($cappella='', $piano='', $sezione=''){
    $query_str = 'SELECT MAX(fila) AS max_row, MAX(numero) AS max_column FROM cellette WHERE 1';
    if($cappella != ''){
        $query_str .= ' AND id_cappella = '.$this->db->escape($cappella);
    }
    if($piano != ''){
        $query_str .= ' AND piano = '.$this->db->escape($piano);
    }
    if($sezione != ''){
        $query_str .= ' AND sezione = '.$this->db->escape($sezione);
    }
    $result = $this->db->query($query_str);
    $result = $result->result_array();
    return $result[0];
}
}
