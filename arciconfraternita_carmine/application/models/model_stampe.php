<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_stampe extends MY_Model {

    function __construct() {
        parent::__construct();
    }
//#######################################################################################################
//####################################### STAMPA PERSONA ################################################
//#######################################################################################################

    function datiPersona($idPersona, $array){
        $result = $this->db->query('SELECT * FROM persone WHERE id_persona = '.$this->db->escape($idPersona).' LIMIT 1');
        $result = $result->result_array();
        if(isset($result[0])){
            $value = $result[0];
            $array[] = array(
                'name' => 'Cognome',
                'value' => $value['cognome'],
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Nome',
                'value' => $value['nome'],
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Luogo di nascita',
                'value' => $value['luogo_nascita'],
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Data di nascita',
                'value' => $this->sqlToDate($value['data_nascita']),
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Indirizzo di residenza',
                'value' => $value['indirizzo'],
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Città di residenza',
                'value' => $value['citta'],
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Telefono',
                'value' => $value['telefono'],
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Cellulare',
                'value' => $value['cellulare'],
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Sesso',
                'value' => $value['sesso'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Infermo',
                'value' => $value['infermo'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Stato civile',
                'value' => $value['stato_civile'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Identificativo di sistema',
                'value' => $value['id_persona'],
                'dimension' => '12',
                );
            $array[] = array(
                'name' => 'Note',
                'value' => $value['note'],
                'dimension' => '12',
                );
        }else{
            $array[] = array(
                'name' => '',
                'value' => 'Persona non trovata.',
                'dimension' => '12',
                );
        }
        return $array;
    }

    function datiConfratello($idPersona, $array){
       $result = $this->db->query('SELECT * FROM confratelli WHERE id_persona = '.$this->db->escape($idPersona));
       $result = $result->result_array();
       if(isset($result[0])){
        $value = $result[0];
        $array['codice'] = array(
            'name' => 'Codice',
            'value' => $value['codice'],
            'dimension' => '12',
            );
        $array[] = array(
            'name' => 'Paternità',
            'value' => $value['paternita'],
            'dimension' => '6',
            );
        $array[] = array(
            'name' => 'Maternità',
            'value' => $value['maternita'],
            'dimension' => '6',
            );
        $array[] = array(
            'name' => 'Data inizio professione',
            'value' => $this->sqlToDate($value['data_professione']),
            'dimension' => '6',
            );
        $array['codice_capofamiglia'] = array(
            'name' => 'Codice capofamiglia',
            'value' => $value['codice_capofamiglia'],
            'dimension' => '6',
            );
            // per evitare di avere come capofamiglia NESSUNO
        if($value['codice_capofamiglia'] == 0){
            $array['codice_capofamiglia']['value'] = '';
        }
        $result = $this->db->query('SELECT nome
            FROM (incarico_confratello LEFT JOIN incarichi ON incarico_confratello.id_incarico = incarichi.id_incarico) LEFT JOIN confratelli ON incarico_confratello.id_confratello = confratelli.id_confratello
            WHERE id_persona = '.$this->db->escape($idPersona).' ORDER BY data_inizio DESC');
        $result = $result->result_array();
        if(isset($result[0])){
            $value = $result[0];
            $array[] = array(
                'name' => 'Incarico attuale',
                'value' => $value['nome'],
                'dimension' => '12',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Non è un confratello.',
            'dimension' => '12',
            );
            */
}
return $array;
}

function datiCelletteAcquistate($idPersona, $array){
   $result = $this->db->query('SELECT * FROM dettagli_cellette WHERE id_acquirente = '.$this->db->escape($idPersona));
   $result = $result->result_array();
   if(isset($result[0])){
    foreach ($result as $key => $value) {
        $array[] = array(
            'name' => '',
            'value' => '<a id="dettagli_celletta_acquistata" class="hidden-print" href="'.site_url('stampe/stampa_celletta').'/'.$value['id_celletta'].'">[Dettagli]</a>',
            'dimension' => '12',
            );
        $array[] = array(
            'name' => 'Cappella',
            'value' => $value['cappella_nome'],
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Piano',
            'value' => $value['piano'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Sezione',
            'value' => $value['sezione'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Fila',
            'value' => $value['fila'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Numero',
            'value' => $value['numero'],
            'dimension' => '2',
            );
                //riga successiva
        $array[] = array(
            'name' => 'Tipo',
            'value' => ucfirst($value['tipo']),
            'dimension' => '2',
            );
        if($value['tipo'] == 'pilone'){
            $array[] = array(
                'name' => 'Cappella pilone',
                'value' => $value['nome_cappella_pilone'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Piano pilone',
                'value' => $value['piano_pilone'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Sezione pilone',
                'value' => $value['sezione_pilone'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Numero pilone',
                'value' => $value['numero_pilone'],
                'dimension' => '2',
                );
        }else{
            $array[] = array(
                'name' => '',
                'value' => '',
                'dimension' => '9',
                );
        }
        //linea finale
        $array[] = array(
            'name' => '',
            'value' => 'linea',
            'dimension' => '12',
            );
    }
}else{
    $keys = array_keys($array); 
    unset($array[$keys[count($keys)-1]]);
    /*
    $array[] = array(
        'name' => '',
        'value' => 'Non ha acquistato nessuna celletta.',
        'dimension' => '12',
        );
    */
}
return $array;
}

function datiCelletteResponsabile($idPersona, $array){
    $result = $this->db->query('SELECT * FROM dettagli_cellette WHERE id_responsabile = '.$this->db->escape($idPersona));
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => '',
                'value' => '<a id="dettagli_celletta_responsabile" class="hidden-print" href="'.site_url('stampe/stampa_celletta').'/'.$value['id_celletta'].'">[Dettagli]</a>',
                'dimension' => '12',
                );
            $array[] = array(
                'name' => 'Cappella',
                'value' => $value['cappella_nome'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Piano',
                'value' => $value['piano'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Sezione',
                'value' => $value['sezione'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Fila',
                'value' => $value['fila'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Numero',
                'value' => $value['numero'],
                'dimension' => '2',
                );
                //riga successiva
            $array[] = array(
                'name' => 'Tipo',
                'value' => $value['tipo'],
                'dimension' => '3',
                );
            if($value['tipo'] == 'pilone'){
                $array[] = array(
                    'name' => 'Cappella pilone',
                    'value' => $value['cappella_pilone'],
                    'dimension' => '3',
                    );
                $array[] = array(
                    'name' => 'Piano pilone',
                    'value' => $value['piano_pilone'],
                    'dimension' => '2',
                    );
                $array[] = array(
                    'name' => 'Sezione pilone',
                    'value' => $value['sezione_pilone'],
                    'dimension' => '2',
                    );
                $array[] = array(
                    'name' => 'Numero pilone',
                    'value' => $value['numero_pilone'],
                    'dimension' => '2',
                    );
            }else{
                $array[] = array(
                    'name' => '',
                    'value' => '',
                    'dimension' => '9',
                    );
            }
        //linea finale
            $array[] = array(
                'name' => '',
                'value' => 'linea',
                'dimension' => '12',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
        /*
        $array[] = array(
            'name' => '',
            'value' => 'Non è responsabile di nessuna celletta.',
            'dimension' => '12',
            );
            */
}
return $array;
}

function datiDefuntiResponsabile($idPersona, $array){
    $result = $this->db->query('SELECT * FROM responsabili_defunti LEFT JOIN defunti ON responsabili_defunti.id_defunto = defunti.id_defunto WHERE id_responsabile = '.$this->db->escape($idPersona).' AND ISNULL(responsabili_defunti.data_fine)');
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => 'Nome cognome',
                'value' => '<a id="dettagli_defunto_responsabile" href="'.site_url('stampe/stampa_defunto').'/'.$value['id_defunto'].'">'.$value['nome'].' '.$value['cognome'].'</a>',
                'dimension' => '6',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
            $array[] = array(
            'name' => '',
            'value' => 'Non ci sono defunti di cui è responsabile.',
            'dimension' => '12',
            );
            */
}
return $array;
}

function datiFigliCapofamiglia($codice_capofamiglia, $array){
    $result = $this->db->query('SELECT * FROM confratelli_persone WHERE codice_capofamiglia = '.$this->db->escape($codice_capofamiglia));
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => '<a href="'.site_url('stampe/stampa_persona').'/'.$value['id_persona'].'">'.$value['codice'].'</a>',
                'value' => $value['nome'].' '.$value['cognome'],
                'dimension' => '4',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Non ci sono confratelli che hanno questa persona come capofamiglia.',
            'dimension' => '12',
            );
            */
}
return $array;
}

function datiParenti($idPersona, $array){
    $result = $this->db->query('SELECT * FROM dettagli_parenti WHERE id_persona1 = '.$this->db->escape($idPersona));
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => 'Nome cognome',
                'value' => '<a id="dettagli_parente" href="'.site_url('stampe/stampa_persona').'/'.$idPersona.'">'.$value['nome2'].' '.$value['cognome2'].'</a>',
                'dimension' => '4',
                );
            if($value['rapporto'] != 'altro'){
                $array[] = array(
                    'name' => 'Rapporto',
                    'value' => $value['rapporto'],
                    'dimension' => '3',
                    );
            }else{
                $array[] = array(
                    'name' => 'Rapporto',
                    'value' => $value['altro_rapporto'],
                    'dimension' => '3',
                    );
            }
            $array[] = array(
                'name' => 'Note',
                'value' => $value['note'],
                'dimension' => '5',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Non ci rapporti di parentela.',
            'dimension' => '12',
            );
            */
}
return $array;
}

function datiStoricoIncarichi($idPersona, $array){
    $result = $this->db->query('SELECT nome, data_inizio, incarico_confratello.note 
        FROM (incarico_confratello LEFT JOIN incarichi ON incarico_confratello.id_incarico = incarichi.id_incarico) LEFT JOIN confratelli ON incarico_confratello.id_confratello = confratelli.id_confratello
        WHERE id_persona = '.$this->db->escape($idPersona).' ORDER BY data_inizio');
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => 'Incarico',
                'value' => $value['nome'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Data inizio',
                'value' => $this->sqlToDate($value['data_inizio']),
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Note',
                'value' => $value['note'],
                'dimension' => '5',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Questo confratello non ha mai avuto un incarico.',
            'dimension' => '12',
            );
            */
}
return $array;
}

//#######################################################################################################
//###################################### STAMPA CELLETTA ################################################
//#######################################################################################################

function datiCelletta($idCelletta, $array){
    $result = $this->db->query('SELECT * FROM dettagli_cellette WHERE id_celletta = '.$this->db->escape($idCelletta));
    $result = $result->result_array();
    $result_defunti = $this->db->query('SELECT * FROM defunti_cellette WHERE id_celletta = '.$this->db->escape($idCelletta).' AND ISNULL(data_fine)');
    $result_defunti = $result_defunti->result_array();
    if(isset($result[0])){
        $value = $result[0];
        $array[] = array(
            'name' => 'Cappella',
            'value' => $value['cappella_nome'],
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Piano',
            'value' => $value['piano'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Sezione',
            'value' => $value['sezione'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Fila',
            'value' => $value['fila'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Numero',
            'value' => $value['numero'],
            'dimension' => '2',
            );
                //riga successiva
        $array[] = array(
            'name' => 'Tipo',
            'value' => ucfirst($value['tipo']),
            'dimension' => '2',
            );
        if($value['tipo'] == 'pilone'){
            $array[] = array(
                'name' => 'Cappella pilone',
                'value' => $value['nome_cappella_pilone'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Piano pilone',
                'value' => $value['piano_pilone'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Sezione pilone',
                'value' => $value['sezione_pilone'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Numero pilone',
                'value' => $value['numero_pilone'],
                'dimension' => '2',
                );
        }else{
            $array[] = array(
                'name' => '',
                'value' => '',
                'dimension' => '9',
                );
        }
        $array[] = array(
            'name' => 'Data concessione',
            'value' => $this->sqlToDate($value['data_concessione']),
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Codice bolletta',
            'value' => $value['codice_bolletta'],
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Somma pagata',
            'value' => $value['somma_pagata']." €",
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Descrizione lapide',
            'value' => $value['descrizione_lapide'],
            'dimension' => '12',
            );
        $array[] = array(
            'name' => 'Identificativo di sistema',
            'value' => $value['id_celletta'],
            'dimension' => '12',
            );
        $array[] = array(
            'name' => 'Note',
            'value' => $value['note'],
            'dimension' => '12',
            );
        
        if($value['id_acquirente'] != 0 && $value['id_acquirente'] != ''){
            $array[] = array(
                'name' => 'Acquirente',
                'value' => '<a href="'.site_url('stampe/stampa_persona').'/'.$value['id_acquirente'].'">'.$value['nome_acquirente'].' '.$value['cognome_acquirente'].'</a>',
                'dimension' => '6',
                );
        }else{
            if($value['id_acquirente_defunto'] != 0 && $value['id_acquirente_defunto'] != ''){
                $array[] = array(
                    'name' => 'Acquirente defunto',
                    'value' => '<a href="'.site_url('stampe/stampa_defunto').'/'.$value['id_acquirente_defunto'].'">'.$value['nome_acquirente_defunto'].' '.$value['cognome_acquirente_defunto'].'</a>',
                    'dimension' => '6',
                    );
            }
        }
        
        if($value['id_responsabile'] != 0 && $value['id_responsabile'] != ''){
            $array[] = array(
                'name' => 'Responsabile',
                'value' => '<a href="'.site_url('stampe/stampa_persona').'/'.$value['id_responsabile'].'">'.$value['nome_responsabile'].' '.$value['cognome_responsabile'].'</a>',
                'dimension' => '6',
                );
        }

        // creo la lista dei defunti
        $defunti_list = '';
        foreach ($result_defunti as $key1 => $value1) {
            if($value1['id_defunto'] != 0 && $value1['id_defunto'] != ''){
                $defunti_list .= '<a href="'.site_url('stampe/stampa_defunto').'/'.$value1['id_defunto'].'">'.$value1['nome'].' '.$value1['cognome'].'</a>, ';
            }
        }
        // aggiungo la lista dei defunti
        if($defunti_list != ''){
            $array[] = array(
                'name' => 'Defunti',
                'value' => $defunti_list,
                'dimension' => '12',
                );
        }else{
            $array[] = array(
                'name' => 'Defunti',
                'value' => 'Questa celletta è libera.',
                'dimension' => '12',
                );
        }
    }else{
        $array[] = array(
            'name' => '',
            'value' => 'Celletta non trovata.',
            'dimension' => '12',
            );

    }
    return $array;
}

function datiStoricoResponsabiliCelletta($idCelletta, $array){
    $result = $this->db->query('SELECT id_responsabile, nome, cognome, data_inizio, data_fine, responsabili_cellette.note FROM responsabili_cellette LEFT JOIN persone ON responsabili_cellette.id_responsabile = persone.id_persona WHERE id_responsabile != 0 AND id_celletta = '.$this->db->escape($idCelletta).' ORDER BY data_inizio');
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => 'Nome cognome',
                'value' => '<a href="'.site_url('stampe/stampa_persona').'/'.$value['id_responsabile'].'">'.$value['nome'].' '.$value['cognome'].'</a>',
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Data inizio',
                'value' => $this->sqlToDate($value['data_inizio']),
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Data fine',
                'value' => $this->sqlToDate($value['data_fine']),
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Note',
                'value' => $value['note'],
                'dimension' => '3',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Questa celletta non ha mai avuto un responsabile.',
            'dimension' => '12',
            );*/
}
return $array;
}

function datiStoricoSepoltureCelletta($idCelletta, $array){
    $result = $this->db->query('SELECT * FROM sepolture LEFT JOIN defunti ON sepolture.id_defunto = defunti.id_defunto WHERE sepolture.id_defunto != 0 AND id_celletta = '.$this->db->escape($idCelletta).' ORDER BY data_inizio, data_fine');
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => 'Nome cognome',
                'value' => '<a href="'.site_url('stampe/stampa_defunto').'/'.$value['id_defunto'].'">'.$value['nome'].' '.$value['cognome'].'</a>',
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Data inizio',
                'value' => $this->sqlToDate($value['data_inizio']),
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Data fine',
                'value' => $this->sqlToDate($value['data_fine']),
                'dimension' => '3',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Questa celletta non ha mai avuto una sepoltura.',
            'dimension' => '12',
            );*/
}
return $array;
}

//#######################################################################################################
//###################################### STAMPA DEFUNTO #################################################
//#######################################################################################################

function datiDefunto($idDefunto, $array){
    $result = $this->db->query('SELECT * FROM dettagli_defunti WHERE id_defunto = '.$this->db->escape($idDefunto));
    $result = $result->result_array();
    if(isset($result[0])){
        $value = $result[0];
        $array[] = array(
            'name' => 'Nome',
            'value' => $value['nome'],
            'dimension' => '6',
            );
        $array[] = array(
            'name' => 'Cognome',
            'value' => $value['cognome'],
            'dimension' => '6',
            );
        $array[] = array(
            'name' => 'Luogo di nascita',
            'value' => $value['luogo_nascita'],
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Data di nascita',
            'value' => $this->sqlToDate($value['data_nascita']),
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Data del decesso',
            'value' => $this->sqlToDate($value['data_decesso']),
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Indirizzo di residenza',
            'value' => $value['indirizzo'],
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Città di residenza',
            'value' => $value['citta'],
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Sesso',
            'value' => $value['sesso'],
            'dimension' => '4',
            );
        if(isset($value['id_celletta']) && $value['id_celletta'] != 0 && $value['id_celletta'] != NULL){
            $array[] = array(
                'name' => 'Celletta attuale',
                'value' => '<a class="hidden-print" href="'.site_url('stampe/stampa_celletta').'/'.$value['id_celletta'].'">'.$value['celletta_description'].'</a>',
                'dimension' => '6',
                );
        }else{
            $array[] = array(
                'name' => 'Celletta attuale',
                'value' => 'Questo defunto non si trova in nessuna celletta.',
                'dimension' => '6',
                );
        }
        if(isset($value['id_responsabile']) && $value['id_responsabile'] != 0 && $value['id_responsabile'] != NULL){
            $array[] = array(
                'name' => 'Responsabile attuale',
                'value' => '<a class="hidden-print" href="'.site_url('stampe/stampa_persona').'/'.$value['id_responsabile'].'">'.$value['nome_responsabile'].' '.$value['cognome_responsabile'].'</a>',
                'dimension' => '6',
                );
        }else{
            $array[] = array(
                'name' => 'Responsabile attuale',
                'value' => 'Questo defunto non ha nessun responsabile.',
                'dimension' => '6',
                );
        }
        $array[] = array(
            'name' => 'Identificativo di sistema',
            'value' => $value['id_defunto'],
            'dimension' => '12',
            );
        $array[] = array(
            'name' => 'Note',
            'value' => $value['note'],
            'dimension' => '12',
            );
    }else{
        $array[] = array(
            'name' => '',
            'value' => 'Defunto non trovato.',
            'dimension' => '12',
            );
    }
    return $array;
}

function datiConfratelloDefunto($idDefunto, $array){
    $result = $this->db->query('SELECT * FROM defunti WHERE id_defunto = '.$this->db->escape($idDefunto));
    $result = $result->result_array();
    if(isset($result[0]) && isset($result[0]['codice_confratello']) && $result[0]['codice_confratello'] != 0 && $result[0]['codice_confratello'] != NULL){
        $value = $result[0];
        $array[] = array(
            'name' => 'Codice',
            'value' => $value['codice_confratello'],
            'dimension' => '6',
            );
        $array[] = array(
            'name' => 'Data professione',
            'value' => $this->sqlToDate($value['data_professione']),
            'dimension' => '6',
            );
        $array[] = array(
            'name' => 'Paternità',
            'value' => $value['paternita'],
            'dimension' => '6',
            );
        $array[] = array(
            'name' => 'Maternità',
            'value' => $value['maternita'],
            'dimension' => '6',
            );
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Questo defunto non è stato confratello.',
            'dimension' => '12',
            );*/
}
return $array;
}

function datiCelletteAcquistateDefunto($idDefunto, $array){
   $result = $this->db->query('SELECT * FROM dettagli_cellette WHERE id_acquirente_defunto = '.$this->db->escape($idDefunto));
   $result = $result->result_array();
   if(isset($result[0])){
    foreach ($result as $key => $value) {
        $array[] = array(
            'name' => '',
            'value' => '<a id="dettagli_celletta_acquistata" class="hidden-print" href="'.site_url('stampe/stampa_celletta').'/'.$value['id_celletta'].'">[Dettagli]</a>',
            'dimension' => '12',
            );
        $array[] = array(
            'name' => 'Cappella',
            'value' => $value['cappella_nome'],
            'dimension' => '4',
            );
        $array[] = array(
            'name' => 'Piano',
            'value' => $value['piano'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Sezione',
            'value' => $value['sezione'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Fila',
            'value' => $value['fila'],
            'dimension' => '2',
            );
        $array[] = array(
            'name' => 'Numero',
            'value' => $value['numero'],
            'dimension' => '2',
            );
                //riga successiva
        $array[] = array(
            'name' => 'Tipo',
            'value' => ucfirst($value['tipo']),
            'dimension' => '2',
            );
        if($value['tipo'] == 'pilone'){
            $array[] = array(
                'name' => 'Cappella pilone',
                'value' => $value['nome_cappella_pilone'],
                'dimension' => '4',
                );
            $array[] = array(
                'name' => 'Piano pilone',
                'value' => $value['piano_pilone'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Sezione pilone',
                'value' => $value['sezione_pilone'],
                'dimension' => '2',
                );
            $array[] = array(
                'name' => 'Numero pilone',
                'value' => $value['numero_pilone'],
                'dimension' => '2',
                );
        }else{
            $array[] = array(
                'name' => '',
                'value' => '',
                'dimension' => '9',
                );
        }
        //linea finale
        $array[] = array(
            'name' => '',
            'value' => 'linea',
            'dimension' => '12',
            );
    }
}else{
    $keys = array_keys($array); 
    unset($array[$keys[count($keys)-1]]);
    /*
    $array[] = array(
        'name' => '',
        'value' => 'Non ha acquistato nessuna celletta.',
        'dimension' => '12',
        );
    */
}
return $array;
}

function datiStoricoSepoltureDefunto($idDefunto, $array){
    $result = $this->db->query('SELECT * FROM (sepolture LEFT JOIN cellette ON sepolture.id_celletta = cellette.id_celletta)
        LEFT JOIN cappelle ON cellette.id_cappella = cappelle.id_cappella
        WHERE sepolture.id_celletta != 0 AND id_defunto = '.$this->db->escape($idDefunto).' ORDER BY data_inizio');
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => 'Descrizione celletta',
                'value' => '<a href="'.site_url('stampe/stampa_celletta').'/'.$value['id_celletta'].'">'.$value['nome'].' - '.$value['piano'].' - '.$value['sezione'].' - '.$value['fila'].' - '.$value['numero'].'</a>',
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Data inizio',
                'value' => $this->sqlToDate($value['data_inizio']),
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Data fine',
                'value' => $this->sqlToDate($value['data_fine']),
                'dimension' => '3',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Questo defunto non ha mai avuto una sepoltura.',
            'dimension' => '12',
            );*/
}
return $array;
}

function datiStoricoResponsabiliDefunto($idDefunto, $array){
    $result = $this->db->query('SELECT persone.id_persona, persone.nome, persone.cognome, responsabili_defunti.data_fine, responsabili_defunti.data_inizio, responsabili_defunti.note 
        FROM responsabili_defunti LEFT JOIN persone ON responsabili_defunti.id_responsabile = persone.id_persona WHERE id_responsabile != 0 AND id_defunto = '.$this->db->escape($idDefunto).' ORDER BY data_inizio');
    $result = $result->result_array();
    if(isset($result[0])){
        foreach ($result as $key => $value) {
            $array[] = array(
                'name' => 'Nome e cognome',
                'value' => '<a href="'.site_url('stampe/stampa_persona').'/'.$value['id_persona'].'">'.$value['nome'].' '.$value['cognome'].'</a>',
                'dimension' => '6',
                );
            $array[] = array(
                'name' => 'Data inizio',
                'value' => $this->sqlToDate($value['data_inizio']),
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Data fine',
                'value' => $this->sqlToDate($value['data_fine']),
                'dimension' => '3',
                );
            $array[] = array(
                'name' => 'Note',
                'value' => $value['note'],
                'dimension' => '12',
                );
        }
    }else{
        $keys = array_keys($array); 
        unset($array[$keys[count($keys)-1]]);
            /*
        $array[] = array(
            'name' => '',
            'value' => 'Questo defunto non ha mai avuto un responsabile.',
            'dimension' => '12',
            );*/
}
return $array;
}

}