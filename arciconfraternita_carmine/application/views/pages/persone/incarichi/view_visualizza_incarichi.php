<div class="accordion" id="incarichi">
  <?php foreach ($incarichi as $key1 => $incarico){ ?>
  <div class="accordion-group" name="incarico" value="<?php echo $incarico['incarico_id_incarico'];?>">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#incarichi" href="#incarico-<?php echo $incarico['incarico_id_incarico'];?>">
        <?php echo $incarico['incarico_nome'];?>
        <button name="elimina1" value="<?php echo $incarico['incarico_id_incarico'];?>" type="button" class="btn btn-default btn-mini pull-right" data-loading-text="Caricamento...">Elimina</button>
      </a>
    </div>
    <div id="incarico-<?php echo $incarico['incarico_id_incarico'];?>" class="accordion-body collapse">
      <div class="accordion-inner">
        <?php if($incarico['incarico_note'] != ''){ ?>
        <p><?php echo $incarico['incarico_note'];?></p>
        <?php } ?>
        <?php if(isset($confratelli[$key1])){ ?>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Codice</th>
              <th>Cognome</th>
              <th>Nome</th>
              <th>Data inizio</th>
              <th>Note</th>
              <th>Azioni</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($confratelli[$key1] as $key => $value) { ?>
            <tr value="<?php echo $value['incarico_confratello_id'];?>">
              <td>
                <a href="<?php echo site_url('stampe/stampa_persona').'/'.$value['id_persona']; ?>"><?php echo $value['confratello_codice'];?></a>
              </td>
              <td>
                <?php echo $value['persona_cognome'];?>
              </td>
              <td>
                <?php echo $value['persona_nome'];?>
              </td>
              <td>
                <?php echo $value['incarico_data_inizio'];?>
              </td>
              <td>
                <?php echo $value['incarico_confratello_note'];?>
              </td>
              <td>
                <button name="elimina2" value="<?php echo $value['incarico_confratello_id'];?>" type="button" class="btn btn-default btn-mini" data-loading-text="Caricamento...">Elimina</button>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
</div>

<script type="text/javascript">
//elimina incarico confratello
$('button[name=elimina2]').click(function(){
  var id = $(this).attr('value');
  var btn = $(this);
  if (confirm("Sei sicuro di volerlo eliminare?")){
    btn.button('loading');
    $.ajax({
      url: "<?php echo site_url('home/ajax_elimina_riga'); ?>",
      type: "POST",
      dataType: "json",
      data: "num=1&tabella0=incarico_confratello&where0='incarico_confratello.id = "+id+"'",
      success: function(msg) {
        my_alert('Eliminato', 0);
        $('tr[value='+id+']').hide();
        btn.button('reset');
      },
      error : function (msg) {
        my_alert('Non eliminato', 1);
        ajax_error_show(msg);
        btn.button('reset');
      }
    });
  }
});

//elimina incarico
$('button[name=elimina1]').click(function(){
  var id = $(this).attr('value');
  var btn = $(this);
  if (confirm("Sei sicuro di volerlo eliminare?")){
    btn.button('loading');
    $.ajax({
      url: "<?php echo site_url('home/ajax_elimina_riga'); ?>",
      type: "POST",
      dataType: "json",
      data: "num=1&tabella0=incarichi&where0='id_incarico = "+id+"'",
      success: function(msg) {
        my_alert('Eliminato', 0);
        $('div[name=incarico][value='+id+']').hide();
        btn.button('reset');
      },
      error : function (msg) {
        my_alert('Non eliminato', 1);
        ajax_error_show(msg);
        btn.button('reset');
      }
    });
  }
});
</script>
