<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: objetFourniParEtat.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/05 05:50:12 $

*/

	$template_main .="<script type='text/javascript'>
		var chainetemp2 = '$objets';
	
		function ajouteobjets(){
			chainetemp2 = chainetemp2 + document.forms[1].objets.options[document.forms[1].objets.selectedIndex].value+';';
	
			document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
			document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].objets.options[document.forms[1].objets.selectedIndex].text ;
			//alert(chainetemp);
			document.forms[0].chaineObjets.value = chainetemp2;
		}
	
		function RAZ1(){
			document.forms[1].ListAdd.options.length = 0;
			document.forms[0].chaineObjets.value = '';
			chainetemp2= '';
		}
	</script>";

	$SQL = "Select T1.id_objet as idselect, concat(T1.type, concat( ' -- ', T1.nom)) as labselect from ".NOM_TABLE_OBJET." T1 WHERE T1.id_objet > 1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("objets",$SQL);
	if ($var[0]>0) {
		$template_main .= "<table class='detailscenter'  width='60%'>";
		$template_main .= "<tr><td>";
		$template_main .= "Objets fournis avec l'état (Au moment de la création du PJ/PNJ/monstre uniquement)</td><td>";
		$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
		if (NOM_SCRIPT<>"supprimer_etat.".$phpExtJeu ) {
	
			$template_main .= $var[1];
			$template_main .= "<br /><br />";
			$template_main .= "<input value=\"Ajouter l'objet\" type='button' onclick=\"ajouteobjets()\" />";
			$template_main .= "<input value=\"Voir l'objet\" type='button' onclick=\"a('../bdc/objet.$phpExtJeu?for_mj=1&amp;num_obj='+document.forms[1].objets.options[document.forms[1].objets.selectedIndex].value)\" />";	
			$template_main .= "<br />&nbsp;<br />";
		}
		$template_main .= "<select name=\"ListAdd\" size='5'>".$objetsValue."</select><br />&nbsp;<br />";
		if (NOM_SCRIPT<>"supprimer_etat.".$phpExtJeu ) 
			$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ1()\" />";
		$template_main .= "</form></td></tr>";
		$template_main .= "</table>";
}

?>