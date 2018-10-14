<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: quincaillerie2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/01/24 16:37:10 $

*/

$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteObjet(){
		chainetemp = chainetemp + document.forms[1].Objet.options[document.forms[1].Objet.selectedIndex].value;
		chainetemp = chainetemp + '|'+ document.forms[1].stockmax.value + '|' 
			+ document.forms[1].remisestock.value+';';
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

$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
$SQL = "Select T1.id_objet as idselect, concat(concat(concat(concat(T1.type,'- '),T1.sous_type),'-->'),T1.nom) as labselect from ".NOM_TABLE_OBJET." T1 WHERE (T1.type = 'Divers' OR T1.type ='ObjetSimple' OR T1.type ='Soins' OR T1.type ='SoinsPI' OR T1.type ='Outil' ) ORDER BY T1.type, T1.sous_type, T1.nom ASC";
$var= faitSelect("Objet",$SQL,"",-1);
$template_main .= $var[1];
$template_main .= "<br />Stock (-1 pour illimité)  <input value='-1' name='stockmax' type='texte' /><br />";
$template_main .= "Réapprovisionnement toutes les  (-1 pour jamais) <input value='-1' name='remisestock'  type='texte' /> h";
$template_main .= "<br />\n";
$template_main .= "<input value=\"Ajouter l'objet\" type='button' onclick=\"ajouteObjet()\" /><br />&nbsp;<br />\n";
$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />\n";
$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />\n";
$template_main .= "</form>\n";
?>