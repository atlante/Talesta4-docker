<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: objet3.form.php,v $
*/

/**
Bri�ve Description � mettre ici
.\file
$Revision: 1.4 $
$Date: 2006/01/31 12:26:21 $

*/

	$template_main .="<script type='text/javascript'>
		var chainetemp2 = '$composantes';
	
		function ajoutecomposantes(){
			chainetemp2 = chainetemp2 + document.forms[2].composantes.options[document.forms[2].composantes.selectedIndex].value+';';
	
			document.forms[2].ListAdd.options.length = document.forms[2].ListAdd.options.length +1;
			document.forms[2].ListAdd.options[(document.forms[2].ListAdd.options.length -1)].text = document.forms[2].composantes.options[document.forms[2].composantes.selectedIndex].text ;
			//alert(chainetemp);
			document.forms[0].chaine2.value = chainetemp2;
		}
	
		function RAZ1(){
			document.forms[2].ListAdd.options.length = 0;
			document.forms[0].chaine2.value = '';
			chainetemp2= '';
		}
	</script>";

	$SQL = "Select T1.id_objet as idselect, concat(T1.type, concat( ' -- ', T1.nom)) as labselect from ".NOM_TABLE_OBJET." T1 WHERE T1.id_objet > 1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("composantes",$SQL);
	if ($var[0]>0) {
		$template_main .= "<table class='detailscenter'  width='60%'>";
		$template_main .= "<tr><td>";
		$template_main .= "Besoin composantes</td><td>";
		$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
		if (NOM_SCRIPT<>"supprimer_lieu.".$phpExtJeu && NOM_SCRIPT<>"supprimer_objet.".$phpExtJeu && NOM_SCRIPT<>"supprimer_magie.".$phpExtJeu) {
	
			$template_main .= $var[1];
			$template_main .= "<br /><br />";
			$template_main .= "<input value=\"Ajouter la composante\" type='button' onclick=\"ajoutecomposantes()\" />";
			$template_main .= "<input value=\"Voir la composante\" type='button' onclick=\"a('../bdc/objet.$phpExtJeu?for_mj=1&amp;num_obj='+document.forms[2].composantes.options[document.forms[2].composantes.selectedIndex].value)\" />";	
			$template_main .= "<br />&nbsp;<br />";
		}
		$template_main .= "<select name=\"ListAdd\" size='5'>".$composantesValue."</select><br />&nbsp;<br />";
		if (NOM_SCRIPT<>"supprimer_lieu.".$phpExtJeu && NOM_SCRIPT<>"supprimer_objet.".$phpExtJeu && NOM_SCRIPT<>"supprimer_magie.".$phpExtJeu) 
			$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ1()\" />";
		$template_main .= "</form></td></tr>";
		$template_main .= "</table>";
}

?>