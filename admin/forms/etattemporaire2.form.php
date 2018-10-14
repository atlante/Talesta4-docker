
<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: etattemporaire2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/01/31 12:26:20 $

*/
$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteEtat(){
		chainetemp = chainetemp + document.forms[1].Etat.options[document.forms[1].Etat.selectedIndex].value+'|'+document.forms[1].duree.value+';';
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].Etat.options[document.forms[1].Etat.selectedIndex].text +' ('+document.forms[1].duree.value+' heure)' ;
		//alert(chainetemp);
		document.forms[0].chaine.value = chainetemp;
	}

	function RAZ(){
		document.forms[1].ListAdd.options.length = 0;
		document.forms[0].chaine.value = '';
		chainetemp= '';
	}
				
</script>";



$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
$SQL = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1 WHERE T1.id_etattemp > 1 ORDER BY T1.nom ASC";
$var=faitSelect("Etat",$SQL,"",-1);
$template_main .= $var[1];
$template_main .= "&nbsp;<input type='text' name='duree' value='0' size='4' /> heures (-1 = permanent)";
$template_main .= "<br />\n";
$template_main .= "<input value=\"Ajouter l'Etat temporaire\" type='button' onclick=\"ajouteEtat()\" />\n";
$template_main .= "<input value=\"Voir l'etat\" type='button' onclick=\"a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat='+document.forms[1].Etat.options[document.forms[1].Etat.selectedIndex].value)\" /><br />&nbsp;<br />";	
$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />\n";
$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />\n";
$template_main .= "</form>\n";
?>