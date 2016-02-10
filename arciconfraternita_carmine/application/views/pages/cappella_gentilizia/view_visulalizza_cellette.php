<?php
$row_counter = 0;
?>
<div class="row">
	<div class="col-lg-12" name="selected" style="display:none; min-height: 350px;"></div>
</div>
<div class="row">
	<?php foreach ($tree as $key1 => $value1) { ?>
	
	<?php
	//aggiungo se serve una nuova riga
	if($row_counter >= 12){
		?>
	</div>
	<div class="row">
		<?php
		$row_counter = 0;
	}else{
		$row_counter += 6;
	}
	?>

	<div class="col-lg-6" name="cappella" cappella="<?php echo $value1['id_cappella']; ?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo $value1['nome']; ?></h3>
			</div>
			<!--  NOTE -->
			<div name="note" class="panel-body">Note: <?php if($value1['note'] != '') echo $value1['note']; else echo '-'?></div>
			<!--  PIANI -->
			<div name="piani" cappella="<?php echo $value1['id_cappella']; ?>" class="panel-body" style="display:none">
				<h3>Piani</h3>
				<div class="list-group">
					<?php 
					if($tree[$key1]['piani'] != array()){
						foreach ($tree[$key1]['piani'] as $key2 => $value2) { ?>
						<a onclick="a_piani(<?php echo $value1['id_cappella']; ?>, <?php echo $value2['piano']; ?>);" class="list-group-item" name="piano" cappella="<?php echo $value1['id_cappella']; ?>" piano="<?php echo $value2['piano']; ?>">
							<h4 class="list-group-item-heading"><?php echo $value2['piano']; ?></h4>
						</a>
						<?php }
					}else{?>
					<a href="#" class="list-group-item">
						<h4 class="list-group-item-heading">Nessun piano presente.</h4>
					</a>
					<?php } ?>
				</div>
			</div>
			<!--  SEZIONI -->
			<?php 
			if($tree[$key1]['piani'] != array()){
				foreach ($tree[$key1]['piani'] as $key2 => $value2) {
					?>
					<div name="sezioni" cappella="<?php echo $value1['id_cappella']; ?>" piano="<?php echo $value2['piano']; ?>" class="panel-body" style="display:none">
						<h3>Sezioni</h3>
						<div class="list-group">
							<?php foreach ($tree[$key1]['piani'][$key2]['sezioni'] as $key3 => $value3) { ?>
							<a href="<?php echo site_url('cappella_gentilizia/visualizza_cellette_result').'/'.$value1['id_cappella'].'/'.$value2['piano'].'/'.$value3['sezione']; ?>" class="list-group-item">
								<h4 class="list-group-item-heading"><?php echo $value3['sezione']; ?></h4>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php
				}
			} ?>
		</div>
	</div>
	<?php } ?>
</div>
<!-- NAV PILLS-->
<div class="row" name="nav_pills" style="display:none">
	<div class="col-lg-12">
		<ul class="nav nav-pills">
			<?php foreach ($tree as $key1 => $value1) { ?>
			<li name="cappella" cappella="<?php echo $value1['id_cappella']; ?>"><a href="#"><?php echo $value1['nome']; ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<script type="text/javascript">
$('div[name=cappella]').click(function(){
	var id = $(this).attr('cappella');
	//nav pills
	$('div[name=nav_pills]').slideDown(0);
	$('li[name=cappella]').removeClass("active");
	$('li[name=cappella][cappella='+id+']').addClass("active");
	//panels
	$('div[name=selected]').html($('div[name=cappella][cappella='+id+']').html());
	$('div[name=note]').slideUp(0, function(){
		$('div[name=piani][cappella='+id+']').slideDown();
	});
	$('div[name=cappella]').slideUp(0, function(){
		$('div[name=selected]').slideDown(0);
		$('div[name=note]').slideUp();
	});
});

$('li[name=cappella]').click(function(){
	var id = $(this).attr('cappella');
	$('div[name=piani]').hide();
	$('div[name=sezioni]').hide();
	$('div[name=selected]').hide(0, function(){
		$('div[name=selected]').html($('div[name=cappella][cappella='+id+']').html());
		$('div[name=selected]').show(0, function(){
			$('div[name=piani][cappella='+id+']').show();
		});
		//$('div[name=piani][cappella='+id+']').show(1000);
	});
	$('li[name=cappella]').removeClass("active");
	$('li[name=cappella][cappella='+id+']').addClass("active");
});

function a_piani(cappella, piano) {
	//alert(1);
	//var cappella = $(this).attr('cappella');
	//var piano = $(this).attr('piano');
	//nascondi i piani
	$('div[name=piani]').slideUp(0);
	$('div[name=sezioni][cappella='+cappella+'][piano='+piano+']').slideDown(0);
}
</script>