<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: quetepj2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:46:02 $

*/

$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteQuete(){
		chainetemp = chainetemp + document.forms[1].Quete.options[document.forms[1].Quete.selectedIndex].value+';';
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].Quete.options[document.forms[1].Quete.selectedIndex].text ;
		document.forms[0].chaine.value = chainetemp;
	}

	function RAZ(){
		document.forms[1].ListAdd.options.length = 0;
		document.forms[0].chaine.value = '';
		chainetemp= '';
	}
				
</script>";

$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
if (NOM_SCRIPT<> ("gerer_quete_proposee.".$phpExtJeu) )
        $SQL = "Select T1.id_quete as idselect, nom_quete as labselect from ".NOM_TABLE_QUETE." T1 ORDER BY T1.nom_quete ASC";
else         $SQL = "Select T1.id_quete as idselect, nom_quete as labselect from ".NOM_TABLE_QUETE." T1 where proposepar = ".$PERSO->ID." and proposepartype=2 ORDER BY T1.nom_quete ASC";


$var=faitSelect("Quete",$SQL,"",-1);
if ($var[0]>0) {
	$template_main .= "Liste des quetes disponibles";
	$template_main .= $var[1];
	$template_main .= "<br />\n";
	$template_main .= "<input value=\"Ajouter la quete\" type='button' onclick=\"ajouteQuete()\" /><br />&nbsp;<br />";
	$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />";
	$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />";
}
$template_main .= "</form>";
?>