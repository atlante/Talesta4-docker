<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: productionNaturelle2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.3 $
$Date: 2006/01/31 12:26:21 $

*/

$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteObjet(Obj,type){
		Obj.value = Obj.value + document.forms[1].Objet.options[document.forms[1].Objet.selectedIndex].value+
		'|'+ document.forms[1].stockmax.value + '|' + document.forms[1].remisestock.value+';';
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = document.forms[1].Objet.options[document.forms[1].Objet.selectedIndex].text +'|'
		+ document.forms[1].stockmax.value + '|' + document.forms[1].remisestock.value+ ' h';
	}

	function RAZ(){
		document.forms[1].ListAdd.options.length = 0;
		document.forms[0].chaine_vente.value = '';
		chainetemp= '';
	}
				
</script>";

$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
$SQL = "Select T1.id_objet as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),'  --> '),T1.nom),'   - '),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', poids '),T1.poids),', Prix '),T1.prix_base),')') as labselect 
from ".NOM_TABLE_OBJET." T1 WHERE T1.type = 'ProduitNaturel' ORDER BY T1.type, T1.sous_type, T1.nom ASC";
$var=faitSelect("Objet",$SQL,"",-1);
$template_main .= $var[1];
$template_main .= "<br />Stock Maxi (-1 pour illimité) <input value='-1' name='stockmax' type='texte' /><br />";
$template_main .= "Réapprovisionnement toutes les  (-1 pour jamais) <input value='-1' name='remisestock'  type='texte' /> h";

$template_main .= "<br /><input value=\"Ajouter l'objet produit\" type='button' onclick=\"ajouteObjet(document.forms[0].chaine_vente,'VENTE')\" />";
$template_main .= "<br />&nbsp;<br />";
$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />";
$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />";
$template_main .= "</form>";
?>