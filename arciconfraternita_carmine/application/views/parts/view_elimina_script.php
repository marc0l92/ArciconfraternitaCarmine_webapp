<?php
if(!isset($button_id)) $button_id = '#elimina';
if(!isset($redirect)) $redirect = 'home';
if(!isset($script_tag)) $script_tag = true;
if(!is_array($tabella)) $tabella = array($tabella);
if(!is_array($where)) $where = array($where);
$data = 'num='.count($tabella);
$i = 0;
foreach ($tabella as $key => $value){
	$data .= '&tabella'.$i.'='.$tabella[$key];
	$data .= '&where'.$i.'=\''.$where[$key].'\'';
	$i++;
}
?>
<?php if($script_tag == true){ ?>
<script type="text/javascript">
	<?php } ?>
	$('<?php echo $button_id; ?>').click(function(){
		var btn = $(this);
		if (confirm("Sei sicuro di volerlo eliminare?")){
			btn.button('loading');
			$.ajax({
				url: "<?php echo site_url('home/ajax_elimina_riga'); ?>",
				type: "POST",
				dataType: "json",
				data: "<?php echo $data; ?>",
				success: function(msg) {
					my_alert('Eliminato', 0);
					window.location = "<?php echo site_url($redirect); ?>";
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
	<?php if($script_tag == true){ ?>
	</script>
	<?php 
} ?>