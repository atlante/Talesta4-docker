<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: sortFourniParEtat.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/05 05:50:13 $

*/

	$template_main .="<script type='text/javascript'>
		var chainetempSorts = '$sorts';
	
		function ajoutesorts(){
			chainetempSorts = chainetempSorts + document.forms[2].sorts.options[document.forms[2].sorts.selectedIndex].value+';';
	
			document.forms[2].ListAdd.options.length = document.forms[2].ListAdd.options.length +1;
			document.forms[2].ListAdd.options[(document.forms[2].ListAdd.options.length -1)].text = document.forms[2].sorts.options[document.forms[2].sorts.selectedIndex].text ;
			//alert(chainetemp);
			document.forms[0].chaineSorts.value = chainetempSorts;
		}
	
		function RAZSorts(){
			document.forms[2].ListAdd.options.length = 0;
			document.forms[0].chaineSorts.value = '';
			chainetempSorts= '';
		}
	</script>";

	$SQL = "Select T1.id_magie as idselect, concat(T1.type, concat( ' -- ', T1.nom)) as labselect from ".NOM_TABLE_MAGIE." T1 WHERE T1.id_magie > 1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("sorts",$SQL);
	if ($var[0]>0) {
		$template_main .= "<table class='detailscenter'  width='60%'>";
		$template_main .= "<tr><td>";
		$template_main .= "sorts fournis avec l'état (Au moment de la création du PJ/PNJ/monstre uniquement)</td><td>";
		$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
		if (NOM_SCRIPT<>"supprimer_etat.".$phpExtJeu ) {
	
			$template_main .= $var[1];
			$template_main .= "<br /><br />";
			$template_main .= "<input value=\"Ajouter le sort\" type='button' onclick=\"ajoutesorts()\" />";
			$template_main .= "<input value=\"Voir le sort\" type='button' onclick=\"a('../bdc/sort.$phpExtJeu?for_mj=1&amp;num_obj='+document.forms[2].sorts.options[document.forms[2].sorts.selectedIndex].value)\" />";	
			$template_main .= "<br />&nbsp;<br />";
		}
		$template_main .= "<select name=\"ListAdd\" size='5'>".$sortsValue."</select><br />&nbsp;<br />";
		if (NOM_SCRIPT<>"supprimer_etat.".$phpExtJeu ) 
			$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZSorts()\" />";
		$template_main .= "</form></td></tr>";
		$template_main .= "</table>";
}

?>