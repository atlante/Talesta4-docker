<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: grimoire2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/01/31 12:26:21 $

*/

$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteSort(){
		chainetemp = chainetemp + document.forms[1].Sort.options[document.forms[1].Sort.selectedIndex].value+';';
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].Sort.options[document.forms[1].Sort.selectedIndex].text ;
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
$SQL = "Select T1.id_magie as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type), case when T1.anonyme = 1 then ' -(anonyme)' else '' end),'  --> '),T1.nom),'   - (Cha:'),T1.charges),', Degs '),T1.degats_min),'-'),T1.degats_max),', place '),T1.place),', Prix '),T1.prix_base),')') as labselect from ".NOM_TABLE_MAGIE." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
$var=faitSelect("Sort",$SQL,"",-1);
$template_main .= $var[1];
$template_main .= "<br />\n";
$template_main .= "<input value=\"Ajouter le sort\" type='button' onclick=\"ajouteSort()\" /><br />&nbsp;<br />";
$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />";
$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />";
$template_main .= "</form>";
?>