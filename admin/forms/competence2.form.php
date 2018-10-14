<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: competence2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:20 $

*/



$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteObjet(Obj){
		Obj.value = Obj.value + document.forms[1].competence.options[document.forms[1].competence.selectedIndex].value+';';
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].competence.options[document.forms[1].competence.selectedIndex].text ;
	}

	function RAZ(){
		document.forms[1].ListAdd.options.length = 0;
		document.forms[0].chaine_vente.value = '';
		chainetemp= '';
	}
				
</script>";

$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "competence : <select name='competence'>";
		$tata = array_keys($liste_comp_full);
		for($i=0;$i<count($tata);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select>";
	$template_main .= "<br />";
$template_main .= "<input value=\"Ajouter la comp&eacute;tence\" type='button' onclick=\"ajouteObjet(document.forms[0].chaine_vente)\" />";
$template_main .= "<br />&nbsp;<br />";
$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />";
$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />";
$template_main .= "</form>\n";
?>