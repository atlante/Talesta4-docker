<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: inventaire2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.14 $
$Date: 2006/01/31 12:26:21 $

*/

$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteObjet(){
		chainetemp = chainetemp + document.forms[1].Objet.options[document.forms[1].Objet.selectedIndex].value+';';
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].Objet.options[document.forms[1].Objet.selectedIndex].text ;
		//alert(chainetemp);
		document.forms[0].chaine.value = chainetemp;
	}

	function RAZ(){
		document.forms[1].ListAdd.options.length = 0;
		document.forms[0].chaine.value = '';
		chainetemp= '';
	}
				
</script>";

$SQL = "Select T1.id_objet as idselect,  concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),
	case when anonyme<>0 then 'anonyme' else '' end)
	,'  --> '),T1.nom),'   - '),case when T1.type='Armure' then concat(concat(' (Protege de ',T1.competence),')') else '' end),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', poids '),T1.poids),', Prix '),T1.prix_base),')') as labselect from ".NOM_TABLE_OBJET." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
$var=faitSelect("Objet",$SQL,"",-1);
if ($var[0]>0) {
	$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= $var[1];
	$template_main .= "<br />\n";
	$template_main .= "<input value=\"Ajouter l'objet\" type='button' onclick=\"ajouteObjet()\" /><br />&nbsp;<br />\n";
	$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />\n";
	$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />\n";
	$template_main .= "</form>\n";
}	
?>