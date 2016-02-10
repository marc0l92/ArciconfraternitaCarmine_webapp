<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Gestione | <?php echo $title; ?></title>
  <link rel="shortcut icon" href="<?php echo base_url();?>resources/images/favicon.png" />

  <!--  StyleSheet  -->
  <link href="<?php echo base_url();?>resources/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url();?>resources/css/typeahead.js-bootstrap.css" rel="stylesheet">
  <link href="<?php echo base_url();?>resources/css/jQuery-theme/jquery-ui.min.css" rel="stylesheet">
  <link href="<?php echo base_url();?>resources/css/datepicker.css" rel="stylesheet">
  <link href="<?php echo base_url();?>resources/css/introjs.min.css" rel="stylesheet">
  <link href="<?php echo base_url();?>resources/css/customization.css?t=<?php time(); ?>" rel="stylesheet">
  <link href="<?php echo base_url();?>resources/css/colors.css" rel="stylesheet">
  
  <!--  JavaScript  -->
  <script src="<?php echo base_url();?>resources/js/jquery-1.10.2.min.js"></script>
  <script src="<?php echo base_url();?>resources/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>resources/js/typeahead.min.js"></script>
  <script src="<?php echo base_url();?>resources/js/datepicker/bootstrap-datepicker.js"></script>
  <script src="<?php echo base_url();?>resources/js/datepicker/locales/bootstrap-datepicker.it.js"></script>
  <script src="<?php echo base_url();?>resources/js/intro.min.js"></script>
  <script src="<?php echo base_url();?>resources/js/personal.js"></script>

</head>
<?php
if($this->session->userdata('logged_in') == false) {
  if ($title != 'Login') {
    header("Location: ".site_url('login'));
  } else {
    return;
  }
}
?>
<body>    
  <div id="container">
    <div class="navbar hidden-print">
      <a class="navbar-brand pull-left" href="<?php echo site_url('home'); ?>">Arciconfraternita del Carmine</a>
      <?php if(isset($breadcrumb)){ ?>
      <ul class="breadcrumb pull-left">
        <?php 
        foreach ($breadcrumb as $key => $value) { 
          if($value == ''){ ?>
          <li class="active"><?php echo $key; ?></li>
          <?php }else{ ?>
          <li><a href="<?php echo $value; ?>"><?php echo $key; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
        <a href="<?php echo site_url('login/logout'); ?>">
          <button id="logout" type="button" class="btn btn-default navbar-btn pull-right">Logout</button>
        </a>
        <p class="navbar-text pull-right">
          Utente: <?php echo $this->session->userdata('username'); ?>
        </p>
      </div>
      <div class="row no-margin">
        <div class="col-lg-2 hidden-print visible-lg" id="shortcuts">
          <ul class="nav nav-pills nav-stacked">
            <li><a href="<?php echo site_url('home'); ?>"><span class="ui-icon ui-icon-arrowthick-1-e pull-left"></span>Home</a></li>
            <li><a href="<?php echo site_url('persone'); ?>"><span class="ui-icon ui-icon-person pull-left"></span>Persone</a></li>
            <ul class="nav nav-pills-sub submenu">
              <li><a href="<?php echo site_url('persone/cerca_persone'); ?>"><span class="ui-icon ui-icon-search pull-left"></span>Cerca persone</a></li>
              <li class="linea"></li>
              <li><a href="<?php echo site_url('persone/nuova_persona'); ?>"><span class="ui-icon ui-icon-plus pull-left"></span>Nuova persona</a></li>
              <li class="linea"></li>
              <li><a href="<?php echo site_url('incarichi'); ?>"><span class="ui-icon ui-icon-suitcase pull-left"></span>Gestisci incarichi</a></li>
            </ul>
            <li><a href="<?php echo site_url('defunti'); ?>"><span class="ui-icon ui-icon-script pull-left"></span>Defunti</a></li>
            <ul class="nav nav-pills-sub submenu">
              <li><a href="<?php echo site_url('defunti/cerca_defunti'); ?>"><span class="ui-icon ui-icon-search pull-left"></span>Cerca defunti</a></li>
              <li class="linea"></li>
              <li><a href="<?php echo site_url('defunti/nuovo_defunto'); ?>"><span class="ui-icon ui-icon-plus pull-left"></span>Nuovo defunto</a></li>
              <li class="linea"></li>
              <li><a href="<?php echo site_url('defunti/sposta_defunto'); ?>"><span class="ui-icon ui-icon-transfer-e-w pull-left"></span>Sposta defunto</a></li>
            </ul>
            <li><a href="<?php echo site_url('cappella_gentilizia'); ?>"><span class="ui-icon ui-icon-home pull-left"></span>Cappella gentilizia</a></li>
            <ul class="nav nav-pills-sub submenu">
              <li><a href="<?php echo site_url('cappella_gentilizia/cerca_celletta'); ?>"><span class="ui-icon ui-icon-search pull-left"></span>Cerca celletta</a></li>
              <li class="linea"></li>
              <li><a href="<?php echo site_url('cappella_gentilizia/visualizza_cellette'); ?>"><span class="ui-icon ui-icon-note pull-left"></span>Visualizza cellette</a></li>
              <li class="linea"></li>
              <li><a href="<?php echo site_url('cappella_gentilizia/nuova_celletta'); ?>"><span class="ui-icon ui-icon-plus pull-left"></span>Nuova celletta</a></li>
            </ul>
            <li><a href="<?php echo site_url('Stampe'); ?>"><span class="ui-icon ui-icon-print pull-left"></span>Stampe</a></li>
            <li><a href="<?php echo site_url('altro'); ?>"><span class="ui-icon ui-icon-wrench pull-left"></span>Altro</a></li>
          </ul>
        </div>
        <div class="fixed-alert">
          <div class="row">
            <div id="alert" class="col-lg-4 col-lg-offset-4">
            </div>
          </div>
        </div>
        <div class="col-lg-10" id="page_content">
          <div class="row no-margin">
            <button id="help_btn" class="btn btn-warning btn-mini pull-right hidden hidden-print" type="button"><span class="ui-icon ui-icon-help pull-left"></span>Aiuto</button>
            <legend><?php echo $title; ?></legend>
          </div>