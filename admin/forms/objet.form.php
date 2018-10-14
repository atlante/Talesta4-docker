<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: objet.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.18 $
$Date: 2010/01/24 16:37:09 $

*/

	if (NOM_SCRIPT==("supprimer_objet.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }

//$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "<tr><td align='center' colspan='2'><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_objet.htm')\">Aide</a></td></tr>";
	$template_main .= "<tr><td>nom de l'objet : </td><td><input type='text' $readonly maxlength='64' name='nom' value='".ConvertAsHTML($nom)."' size='45' /></td></tr>";
	$template_main .= "<tr><td>type d'objet : </td><td><select $disabled name='type'>";
		asort($liste_type_objs);
		reset($liste_type_objs);
		$tata = array_keys($liste_type_objs);
		$toto = array_values($liste_type_objs);
		$nb_liste_type_objs = count($liste_type_objs);
		for($i=0;$i<$nb_liste_type_objs;$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if($tata[$i] == $type){ $template_main .= " selected='selected'";}
			$template_main .= ">".$toto[$i][0]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>Degats Mini : </td><td><input type='text' $readonly name='degats_min' value='".$degats_min."' size='5' /></td></tr>";
	$template_main .= "<tr><td>Degats Max : </td><td><input type='text' $readonly name='degats_max' value='".$degats_max."' size='5' /></td></tr>";
	$template_main .= "<tr><td>durabilite (-1 = infini) : </td><td><input type='text' $readonly name='durabilite' value='".$durabilite."' size='4' /><input type='hidden' name='old_durabilite'  value='".$durabilite."' /></td></tr>";
	$template_main .= "<tr><td>Prix de l'objet: </td><td><input type='text' $readonly name='prix_base' value='".$prix_base."' size='4' /></td></tr>";
	$template_main .= "<tr><td>description: </td><td><textarea rows='5' cols='35' $readonly name='description'>".ConvertAsHTML($description)."</textarea></td></tr>";
	$template_main .= "<tr><td>poids: </td><td><input type='text' $readonly name='poids' value='".$poids."' size='4' /></td></tr>";
	$template_main .= "<tr><td>image (touchez pas): </td><td><input type='text' $readonly name='image' value='".$image."' size='15' /></td></tr>";
	$template_main .= "<tr><td>permanent: </td><td><select $disabled name='permanent'>";
		if($permanent == 0){
			$template_main .= "<option value='0' selected='selected'>Non</option><option value='1'>Oui</option>";
		} else {
			$template_main .= "<option value='0'>Non</option><option value='1' selected='selected'>Oui</option>";
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>anonyme: </td><td><select $disabled name='anonyme'>";
		if($anonyme == 0){
			$template_main .= "<option value='0' selected='selected'>Non</option><option value='1'>Oui</option>";
		} else {
			$template_main .= "<option value='0'>Non</option><option value='1' selected='selected'>Oui</option>";
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>munitions (-1 = infini) : </td><td><input type='hidden' name='old_munitions'  value='".$munitions."' /><input type='text' $readonly name='munitions' value='".$munitions."' size='4' /></td></tr>";
	$template_main .= "<tr><td>caracteristique : </td><td><select $disabled name='caracteristique'>";
		$temp = array_merge(array('&nbsp;'=>'Aucune'),$liste_caracs);
		ksort($temp);
		reset($temp);
		$tata = array_keys($temp);
		for($i=0;$i<count($temp);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if($tata[$i] == $caracteristique){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>competence : </td><td><select $disabled name='competence'>";
		$temp = array_merge(array('&nbsp;'=>'Aucune'),$liste_magie,$liste_competences);
		ksort($temp);
		reset($temp);
		$tata = array_keys($temp);
		for($i=0;$i<count($temp);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if($tata[$i] == $competence){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	
	//$template_main .= "<tr><td>Provoque Etat (<a href=\"javascript:a('listeetat.'.$phpExtJeu)\">Liste</a>) : </td><td><input type='text' name='provoqueetat' value='".$provoqueetat."' size='35' maxlength='100' /></td></tr>";
	$template_main .= "<tr><td>competence Sp&eacute;: </td><td><select $disabled name='competencespe'>";
	if($competencespe == "Vampire"){
		$template_main .= "<option value=''>Aucune</option><option value='Etourdissant'>Etourdissant</option><option value='Maudit (ne peut etre enleve une fois mis)'>Maudit (ne peut etre enleve une fois mis)</option><option value='Vampire' selected='selected'>Vampire</option>";
	} else {
		if($competencespe == "Maudit (ne peut etre enleve une fois mis)"){
			$template_main .= "<option value=''>Aucune</option><option value='Etourdissant' selected='selected'>Etourdissant</option><option value='Maudit (ne peut etre enleve une fois mis)' selected='selected'>Maudit (ne peut etre enleve une fois mis)</option><option value='Vampire'>Vampire</option>";
		} else 	if($competencespe == "Etourdissant"){
			$template_main .= "<option value=''>Aucune</option><option value='Etourdissant' selected='selected'>Etourdissant</option><option value='Maudit (ne peut etre enleve une fois mis)'>Maudit (ne peut etre enleve une fois mis)</option><option value='Vampire'>Vampire</option>";
		} else {
			$template_main .= "<option value='' selected='selected'>Aucune</option><option value='Etourdissant'>Etourdissant</option><option value='Maudit (ne peut etre enleve une fois mis)'>Maudit (ne peut etre enleve une fois mis)</option><option value='Vampire'>Vampire</option>";
		}
	}
	$template_main .= "</select>";
	
	$template_main .= "</td></tr>";

	$template_main .= "<tr><td>";
	
	$SQL = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1 WHERE T1.id_etattemp > 1 ORDER BY T1.nom ASC";
	
	$template_main .= "Utilisable uniquement par</td><td>";
	$var=faitSelect("id_etattempspecifique",$SQL,$disabled,$id_etattempspecifique, array(), array("&nbsp;"));	
	$template_main .= $var[1];
	$template_main .= "</td></tr>";
?>