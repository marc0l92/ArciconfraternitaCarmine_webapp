<script type="text/javascript">
$('#help_btn').removeClass('hidden');
$('#help_btn').click(function(){
	//
	var element = $('#page_content');
	element.attr('data-step', 1);
	element.attr('data-position', 'left');
	element.attr('data-intro', 'Dalla schermata home è possibile accedere a tutte le sezioni del sito.');
	//
	var element = $('#shortcuts');
	element.attr('data-step', 2);
	element.attr('data-position', 'right');
	element.attr('data-intro', 'Lateralmente è sempre possibile trovare i collegamenti rapidi alle sezioni più comuni.');
	//
	var element = $('ul.breadcrumb');
	element.attr('data-step', 3);
	element.attr('data-position', 'bottom');
	element.attr('data-intro', 'In ogni pagina è possibile vedere in che sezione ci si trova e tornare indietro usando questi link.');
	//
	var element = $('#logout');
	element.attr('data-step', 4);
	element.attr('data-position', 'left');
	element.attr('data-intro', 'E\' consigliato uscire dal proprio accout quando si finisce di lavorare in modo da garantire la sicurezza del sistema.');
	//
	var element = $('#help_btn');
	element.attr('data-step', 5);
	element.attr('data-position', 'left');
	element.attr('data-intro', 'Sulle pagine in cui è attivato questo pulsante è possibile visualizzare una guida come questa.');
	//
	var intro = introJs();
	intro.setOption('showBullets', false);
	intro.start();
});
</script>