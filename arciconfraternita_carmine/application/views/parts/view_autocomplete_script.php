<script type="text/javascript">
<?php if(isset($autocomplete)){
	foreach ($autocomplete as $key => $value) { ?>
		$('input[name=<?php echo $key; ?>]').typeahead([
		{
			name: '<?php echo $key; ?>',
			limit: 5,
			local: [
			<?php foreach ($value as $key1 => $value1) {
				echo '"'.$value1[$key].'",';
			} ?>
			]
		}
		]);
		<?php }
	} ?>
</script>