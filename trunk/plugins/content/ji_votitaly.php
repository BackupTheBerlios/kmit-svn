<?php
/*
# "VOTItaly" Plugin for Joomla! 1.5.x - Version 1.1
# License: http://www.gnu.org/copyleft/gpl.html
# Authors: Luca Scarpa & Silvio Zennaro
# Copyright (c) 2006 - 2009 Siloos snc - http://www.siloos.it
# Project page at http://www.joomitaly.com - Demos at http://demo.joomitaly.com
# ***Last update: Jan 06th, 2009***
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onBeforeDisplayContent', 'plgVotitaly' );

function plgVotitaly( &$row, &$params, $page=0 ) {

	global $my, $addScriptVotitalyPlugin;
	
	$database = & JFactory::getDBO();
	$uri = & JFactory::getURI();
	$plugin = & JPluginHelper::getPlugin('content', 'ji_votitaly');
	$plgParams = new JParameter( $plugin->params );
		$show_stars = $plgParams->get('show_stars', 1);
		$star_description = $plgParams->get('star_description', '');
		
	$id = $row->id;
	$html = '';
	
	if (isset($row->rating_count) && $params->get( 'show_vote' ) && !$params->get( 'popup' )) {

			if(JPlugin::loadLanguage( 'plg_content_ji_votitaly' ) === false)
				JPlugin::loadLanguage( 'plg_content_ji_votitaly', JPATH_ADMINISTRATOR );		
	
			$id 	= $row->id;
			$query = 'SELECT *' .
					' FROM #__content_rating' .
					' WHERE content_id = '.(int) $id;
			$database->setQuery($query);
			$rating = $database->loadObject();
			
			if (!$rating)	{
				$rating_count = 0;
				$rating_sum   = 0;
				$average      = 0;
				$width        = 0;
			} else {
				$rating_count = $rating->rating_count;
				$rating_sum = $rating->rating_sum;		
				$average = number_format(intval($rating_sum) / intval( $rating_count ),2);
				$width   = $average * 20;
			}
			$trans_star_description = _plgVotitaly_replaceDescString($star_description, $rating_count, $average);
			
			// +++++++++++++++++++++++++++++++++++++++
			// ++++++ Printing javascript code +++++++
			// +++++++++++++++++++++++++++++++++++++++
			$script='
<!-- VOTItaly Plugin v1.1 starts here -->
';

$script.='<link href="'.JURI::base().'plugins/content/ji_votitaly/css/votitaly.css" rel="stylesheet" type="text/css" />';	

$script.='
<script type="text/javascript">
'."
	window.addEvent('domready', function(){
	  var ji_vp = new VotitalyPlugin({
	  	submiturl: '".JURI::base()."plugins/content/ji_votitaly_ajax.php',
			loadingimg: '".JURI::base()."plugins/content/ji_votitaly/images/loading.gif',
			show_stars: ".($show_stars ? 'true' : 'false').",

			star_description: '".addslashes($star_description)."',		
			language: {
				updating: '".JText::_( 'VOTITALY_UPDATING')."',
				thanks: '".JText::_( 'VOTITALY_THANKS')."',
				already_vote: '".JText::_( 'VOTITALY_ALREADY_VOTE')."',
				votes: '".JText::_( 'VOTITALY_VOTES')."',
				vote: '".JText::_( 'VOTITALY_VOTE')."',
				average: '".JText::_( 'VOTITALY_AVERAGE')."',
				outof: '".JText::_( 'VOTITALY_OUTOF')."',
				error1: '".JText::_( 'VOTITALY_ERR1')."',
				error2: '".JText::_( 'VOTITALY_ERR2')."',
				error3: '".JText::_( 'VOTITALY_ERR3')."'
			}
	  });
	});
".'
</script>
<script type="text/javascript" src="'.JURI::base().'plugins/content/ji_votitaly/js/votitalyplugin.js"></script>
<!-- VOTItaly Plugin v1.1 ends here -->';		

			if(!$addScriptVotitalyPlugin){	
				$addScriptVotitalyPlugin = 1;
				JApplication::addCustomHeadTag($script);
			}
			// +++++++++++++++++++++++++++++++++++++++
			// +++++ /Printing javascript code +++++++
			// +++++++++++++++++++++++++++++++++++++++
						
// +++++++++++++++++++++++++++++++++++++++
// ++++++++ Printing html code +++++++++++
// +++++++++++++++++++++++++++++++++++++++
$html = '
<!-- Votitaly Plugin v1.1 starts here -->
<div class="votitaly-inline-rating" id="votitaly-inline-rating-'. $id .'">
	<div class="votitaly-get-id" style="display:none;">'. $id .'</div> 
';
if ($show_stars) {
	$html .= '
	<div class="votitaly-inline-rating-stars">
	  <ul class="votitaly-star-rating">
	    <li class="current-rating" style="width:'. $width .'%;">&nbsp;</li>
	    <li><a title="1 '. JText::_( 'VOTITALY_STAR' ) .'" class="votitaly-toggler one-star">1</a></li>
	    <li><a title="2 '. JText::_( 'VOTITALY_STARS' ) .'" class="votitaly-toggler two-stars">2</a></li>
	    <li><a title="3 '. JText::_( 'VOTITALY_STARS' ) .'" class="votitaly-toggler three-stars">3</a></li>
	    <li><a title="4 '. JText::_( 'VOTITALY_STARS' ) .'" class="votitaly-toggler four-stars">4</a></li>
	    <li><a title="5 '. JText::_( 'VOTITALY_STARS' ) .'" class="votitaly-toggler five-stars">5</a></li>
	  </ul>
	</div>
	';
}
$html .= '
	<div class="votitaly-box">
';
/*if ($show_votes || $show_average) {
	$html .= '('. 
		($show_votes ? $rating_count .' '. ($rating_count==1 ? JText::_( 'VOTITALY_VOTE' ) : JText::_( 'VOTITALY_VOTES' )) : '') .
		($show_votes && $show_average ? ', ' : '') .
		($show_average ? JText::_( 'VOTITALY_AVERAGE' ) .': '. $average .' '. JText::_( 'VOTITALY_OUTOF' ) : '') .
		')';
}
*/$html .= $trans_star_description;
$html .= '
	</div>
</div>
<!-- Votitaly Plugin v1.1 ends here -->
';
	}
// +++++++++++++++++++++++++++++++++++++++
// ++++++++ Printing html code +++++++++++
// +++++++++++++++++++++++++++++++++++++++	
	return $html;
}

function _plgVotitaly_replaceDescString( $string, $num_votes, $num_average ) 
{
	$patterns = array(
		'/{num_votes}/',
		'/{num_average}/',
		'/#LANG_VOTES/',
		'/#LANG_AVERAGE/',
		'/#LANG_OUTOF/',
	);
	$replacements = array( 
		$num_votes, 
		$num_average, 
		($num_votes==1 ? JText::_( 'VOTITALY_VOTE') : JText::_( 'VOTITALY_VOTES')),
		JText::_( 'VOTITALY_AVERAGE'),
		JText::_( 'VOTITALY_OUTOF')
	);
	
	return preg_replace($patterns, $replacements, $string);
}

/*

VOTItaly Plugin v1.1.02, DONES:
+ Plugin è ora XHTML valid - sz
+ Plugin ora risolve i problemi di Internal Server Error su alcuni server... - sz
+ Descrizione delle star completamente customizzabile - sz




* VALIDAZIONE XHTML PLUGIN (Just replace span for div¥s in ji_votitaly.php lines 101 and 110. Anp put a &nbsp; into <li>)


* that it will be better for my design to have only number of votes displayed without any text

* language file in administrator folder???

* permettere selezione di pubblicazione rating stars anche in home page e con articolo con voti = 0

* aggiungere lingua cinese http://www.joomitaly.com/component/option,com_fireboard/Itemid,11/catid,17/func,view/id,238/

* aggiungere altre immagini per le stelle (come fa Core Design Ajax Vote plugin.)

* $mainframe->registerEvent( 'onBeforeDisplayContent', 'plgVotitaly' );

* $mainframe->registerEvent( 'onAfterDisplayContent', 'plgVotitaly' );





[19.17.08] Luscarpa scrive:allora qualche effetto mootools sulle stelle

[19.17.08] Luscarpa scrive:possibilit‡ di decidere se visionare l'articolo

[19.17.19] Luscarpa scrive:in home page e quando ha 0 voti

[19.17.38] Luscarpa scrive:controllo tramite cookies

[19.17.55] Luscarpa scrive:se facciamo queste miglioriamo di molto

[19.18.00] Silvio Zennaro scrive:ok ma nn basta

[19.18.50] Silvio Zennaro scrive:PS: cosa intendi per effetti mootools?

[19.19.46] Luscarpa scrive:ad esempio attualmente

[19.19.55] Luscarpa scrive:fai solo il cambio di width

[19.19.59] Luscarpa scrive:sulle stelle

[19.20.11] Luscarpa scrive:resettandolo al nuovo valore calcolato

[19.20.17] Luscarpa scrive:si potrebbe volendo

[19.20.24] Luscarpa scrive:fare un fade anche sulle stelle

[19.20.33] Luscarpa scrive:e far comparire il nuovo valore

[19.20.43] Luscarpa scrive:e volendo anche altri effetti

[19.21.13] Luscarpa scrive:come

[19.21.17] Luscarpa scrive:l'effetto

[19.21.21] Luscarpa scrive:senza fade

[19.21.38] Luscarpa scrive:delle stelle che dall'attuale votazione in percentuale

[19.21.44] Luscarpa scrive:mettiamo siano al 80%

[19.22.09] Luscarpa scrive:va a 0% e poi al 82% che corrisponde al nuovo voto

[19.22.22] Silvio Zennaro scrive:ok chiaro

[19.22.35] Silvio Zennaro scrive:dici giochi di questo tipo insomma

[19.22.49] Luscarpa scrive:si oltre alle altre opzioni sopra

[19.22.54] Silvio Zennaro scrive:e ascolta

[19.22.55] Luscarpa scrive:ah si

[19.23.00] Luscarpa scrive:una che avevo messo

[19.23.05] Silvio Zennaro scrive:io pensavo dimensioni e colori diversi

[19.23.09] Silvio Zennaro scrive:delle stelle

[19.23.14] Luscarpa scrive:sul pro mai rilasciato

[19.23.23] Luscarpa scrive:di quello di joomlaworks

[19.23.33] Luscarpa scrive:avevo messo l'opzione di visualizzare le stelle a tutti

[19.23.42] Luscarpa scrive:o solo ai registrati

[19.23.55] Luscarpa scrive:una minchiata

[19.24.01] Luscarpa scrive:che aumenta le opzioni perÚ

[19.24.03] Silvio Zennaro scrive:e quindi anche la votazione ai soli loggati??

[19.24.10] Luscarpa scrive:si

[19.24.19] Luscarpa scrive:intendevo quello

[19.24.32] Luscarpa scrive:quindi tra cookie e registrati

[19.24.34] Silvio Zennaro scrive:ok

[19.24.37] Luscarpa scrive:uno praticamente

[19.24.45] Luscarpa scrive:non poteva fare voti malefici

[19.24.50] Luscarpa scrive:considerato che di default

[19.24.52] Luscarpa scrive:joomla

[19.24.54] Silvio Zennaro scrive:minchia a divente un plugin novo de balin

[19.25.06] Luscarpa scrive:tiene traccia del last ip

[19.25.25] Luscarpa scrive:cioË se uno vuole ghe la fa

[19.25.34] Luscarpa scrive:ma bi‡ che a se metta d'impegno

[19.25.58] Luscarpa scrive:si a devente novo, el mio versione pro

[19.26.06] Luscarpa scrive:aveva tutte ste funzionalit‡

[19.26.16] Luscarpa scrive:le pare tante

[19.26.26] Luscarpa scrive:ma per mi in mesa giornata

[19.26.31] Luscarpa scrive:ti lo finissi

[19.26.33] Luscarpa scrive:fai una intera

[19.26.39] Silvio Zennaro scrive:si su convinto

[19.26.51] Silvio Zennaro scrive:scaolta

[19.26.53] Silvio Zennaro scrive:ascolta

[19.26.58] Silvio Zennaro scrive:riussimo a trovare/fare

[19.27.13] Silvio Zennaro scrive:diverse combinazioni de colori e tre dimensioni per tipo?

[19.27.29] Silvio Zennaro scrive:che ne so, giallo sul blu,

[19.27.32] Silvio Zennaro scrive:giallo sul rosso

[19.27.40] Silvio Zennaro scrive:grigio sul nero

[19.27.50] Silvio Zennaro scrive:verde sul blu

[19.27.53] Silvio Zennaro scrive:ecc ecc

[19.27.55] Silvio Zennaro scrive:??

[19.28.13] Luscarpa scrive:si, riusciamo

[19.28.14] Silvio Zennaro scrive:secondo mi alla zente ghe piaze el discorso dei tempi

[19.28.18] Silvio Zennaro scrive:temi

[19.28.30] Silvio Zennaro scrive:ok

[19.28.34] Silvio Zennaro scrive:allora io predispongo

[19.28.38] Silvio Zennaro scrive:poi includeremo ok?

[19.28.42] Luscarpa scrive:yes

[19.29.04] Luscarpa scrive:le 3 dimensioni sono per il width 15/20/25

[19.29.09] Luscarpa scrive:piccole medie grandi

[19.29.45] Silvio Zennaro scrive:si, me ricordo de aver visto ste stelle pÏ grandi

[19.30.19] Silvio Zennaro scrive:si i ma parle solo de ipod e itunes

[19.30.26] Silvio Zennaro scrive:sbassË i schei del macx

[19.30.34] Luscarpa scrive:eheheh

[19.30.54] Silvio Zennaro scrive:cmq doman finisso editoriali

[19.31.14] Silvio Zennaro scrive:{manche solo la gestione sondaggi e voti sondaggi}

[19.31.22] Silvio Zennaro scrive:ma stasera cominsio co votitaly

[19.31.32] Luscarpa scrive:oro

[19.31.38] Luscarpa scrive:cmq fatte ste caratteristiche

[19.31.42] Luscarpa scrive:a se finio

[19.31.49] Luscarpa scrive:difficile trovarne altre

[19.31.54] Luscarpa scrive:se non si aggancia a una componente

[19.32.08] Luscarpa scrive:l'unica che mancherebbe

[19.32.15] Luscarpa scrive:Ë dare la possibilit‡

[19.32.25] Luscarpa scrive:di avere {votitaly}

[19.32.37] Luscarpa scrive:quindi la possibilit‡ come l'altro

[19.32.43] Luscarpa scrive:di mettere le votazioni interne

[19.32.53] Luscarpa scrive:ma al momento lascerei stare

[19.33.52] Silvio Zennaro scrive:eventualmente klo agganciamo in seguito

[19.34.08] Silvio Zennaro scrive:penso che co personalizzazioni del genere la gente torni da noi

[19.34.54] Silvio Zennaro scrive::)

*/ 