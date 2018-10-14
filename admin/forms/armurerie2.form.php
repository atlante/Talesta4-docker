<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: armurerie2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2010/01/24 16:37:08 $

*/


$template_main .="<script type='text/javascript'>
	var chainetemp = '';

	function ajouteObjet(Obj,type){
		Obj.value = Obj.value + document.forms[1].Objet.options[document.forms[1].Objet.selectedIndex].value;
		if (type=='VENTE') {
			Obj.value = Obj.value + '|'+ document.forms[1].stockmax.value + '|' 
			+ document.forms[1].remisestock.value+';';
		}	
		else {
			Obj.value = Obj.value +';';
		}
		document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
		document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text = type +' '+ document.forms[1].Objet.options[document.forms[1].Objet.selectedIndex].text ;
	}

	function RAZ(){
		document.forms[1].ListAdd.options.length = 0;
		document.forms[0].chaine_vente.value = '';
		document.forms[0].chaine_recharge.value = '';
		document.forms[0].chaine_repare.value = '';
		chainetemp= '';
	}
				
</script>";

$template_main .= "\t<form action='".NOM_SCRIPT."' method='post'>";
$SQL = "Select T1.id_objet as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),'  --> '),T1.nom),'   - '),case when T1.type='Armure' then concat(concat(' (Protege de ',T1.competence),')') else '' end),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', poids '),T1.poids),', Prix '),T1.prix_base),')') as labselect 
from ".NOM_TABLE_OBJET." T1 WHERE T1.type = 'ArmeJet' OR T1.type = 'ArmeMelee' OR T1.type = 'Armure' OR T1.type = 'Munition' ORDER BY T1.type, T1.sous_type, T1.nom ASC";
$var=faitSelect("Objet",$SQL,"",-1);
$template_main .= $var[1];
$template_main .= "<br />Stock (-1 pour illimité) (Pour les ventes uniquement) <input value='-1' name='stockmax' type='texte' /><br />";
$template_main .= "Réapprovisionnement toutes les  (-1 pour jamais) (Pour les ventes uniquement)<input value='-1' name='remisestock'  type='texte' /> h";
$template_main .= "<br /><input value=\"Ajouter l'objet a vendre\" type='button' onclick=\"ajouteObjet(document.forms[0].chaine_vente,'VENTE')\" />";
$template_main .= "<input value=\"Ajouter l'objet a recharger\" type='button' onclick=\"ajouteObjet(document.forms[0].chaine_recharge,'RECHARGEABLE')\" />";
$template_main .= "<input value=\"Ajouter l'objet a reparer\" type='button' onclick=\"ajouteObjet(document.forms[0].chaine_repare,'REPARABLE')\" />";
$template_main .= "<br />&nbsp;<br />";
$template_main .= "<select name=\"ListAdd\" size='5'></select><br />&nbsp;<br />";
$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ()\" />";
$template_main .= "</form>";
?>