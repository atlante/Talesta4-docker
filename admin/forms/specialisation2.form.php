<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: specialisation2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/01/31 12:26:21 $

*/

$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteSpec(){
		chainetemp = chainetemp + document.forms[1].Spec.options[document.forms[1].Spec.selectedIndex].value+';';
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].Spec.options[document.forms[1].Spec.selectedIndex].text ;
		//alert(chainetemp);
		document.forms[0].chaine.value = chainetemp;
	}

	function RAZ(){
		document.forms[1].ListAdd.options.length = 0;
		document.forms[0].chaine.value = '';
		chainetemp= '';
	}
				
</script>";

$SQL = "Select T1.id_spec as idselect, T1.nom as labselect from ".NOM_TABLE_SPECNOM." T1 WHERE T1.id_spec > 1 ORDER BY T1.nom ASC";
$var=faitSelect("Spec",$SQL,"",-1);
if ($var[0]) {
	$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= $var[1];
	$template_main .= "<br />\n";
	$template_main .= "<input value=\"Ajouter la sp&eacute;cialisation\" type='button' onclick=\"ajouteSpec()\" /><br />&nbsp;<br />\n";
	$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />\n";
	$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />\n";
	$template_main .= "</form>\n";
}
else $template_main .= "Aucune spécialisation <br />";
?>