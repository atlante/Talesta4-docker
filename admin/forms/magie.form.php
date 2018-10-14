<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: magie.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2010/01/24 16:37:09 $

*/


	if (NOM_SCRIPT==("supprimer_magie.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }


$template_main .= "<table class='detailscenter'>";
	$template_main .= "<tr><td colspan='2'><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_sort.htm')\">Aide</a></td></tr>\n";
	$template_main .= "<tr><td>nom du sort : </td><td><input type='text' $readonly name='nom' maxlength='64' value='".ConvertAsHTML($nom)."' size='50' /></td></tr>\n";
	$template_main .= "<tr><td>type de sort : </td><td><select $disabled name='type'>";
		ksort($liste_magie);
		reset($liste_magie);
		$tata = array_keys($liste_magie);
		for($i=0;$i<count($liste_magie);$i++){
			$template_main .= "\t<option value='".$tata[$i]."'";
			if($tata[$i] == $type){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>Sous type de sort : </td><td><select $disabled name='sous_type'>";
		ksort($liste_stype_sorts);
		reset($liste_stype_sorts);
		$tata = array_keys($liste_stype_sorts);
		$toto = array_values($liste_stype_sorts);
		for($i=0;$i<count($liste_stype_sorts);$i++){
			$template_main .= "\t<option value='".$tata[$i]."'";
			if($tata[$i] == $sous_type){ $template_main .= " selected='selected'";}
			$template_main .= ">".$toto[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>\n";
	$template_main .= "<tr><td>Effets Mini sur la cible: </td><td><input type='text' $readonly name='degats_min' value='".$degats_min."' size='5' /></td></tr>\n";
	$template_main .= "<tr><td>Effets Max sur la cible: </td><td><input type='text' $readonly name='degats_max' value='".$degats_max."' size='5' /></td></tr>\n";
	$template_main .= "<tr><td>Prix de base du sort (A l'achat): </td><td><input type='text' $readonly name='prix_base' value='".$prix_base."' size='4' /></td></tr>\n";
	$template_main .= "<tr><td>Cout du sort en PA (Si vide, on prend le cout de Magie standard. <br />Ne pas oublier le - devant): </td><td><input type='text' $readonly name='coutpa' value='".$coutpa."' size='4' /></td></tr>\n";
	$template_main .= "<tr><td>Cout du sort en PI (Si vide, on prend le cout de Magie standard. <br />Ne pas oublier le - devant): </td><td><input type='text' $readonly name='coutpi' value='".$coutpi."' size='4' /></td></tr>\n";
	$template_main .= "<tr><td>Cout du sort en PO (Ne pas oublier le - devant): </td><td><input type='text' $readonly name='coutpo' value='".$coutpo."' size='4' /></td></tr>\n";
	$template_main .= "<tr><td>Cout du sort en PV (Ne pas oublier le - devant): </td><td><input type='text' $readonly name='coutpv' value='".$coutpv."' size='4' /></td></tr>\n";
	
	$template_main .= "<tr><td>description: </td><td><textarea rows='5' cols='35' $readonly name='description'>".ConvertAsHTML($description)."</textarea></td></tr>\n";
	$template_main .= "<tr><td>place: </td><td><input type='text' $readonly name='place' value='".$place."' size='4' /></td></tr>\n";
	$template_main .= "<tr><td>image (touchez pas): </td><td><input type='text' $readonly name='image' value='".$image."' size='15' /></td></tr>\n";
	$template_main .= "<tr><td>permanent: </td><td><select $disabled name='permanent'>";
		if($permanent == 0){
			$template_main .= "\t<option value='0' selected='selected'>Non</option><option value='1'>Oui</option>";
		} else {
			$template_main .= "\t<option value='0'>Non</option><option value='1' selected='selected'>Oui</option>";
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>anonyme: </td><td><select $disabled name='anonyme'>";
		if($anonyme == 0){
			$template_main .= "\t<option value='0' selected='selected'>Non</option><option value='1'>Oui</option>";
		} else {
			$template_main .= "\t<option value='0'>Non</option><option value='1' selected='selected'>Oui</option>";
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>charges (-1 = infini) : </td><td><input type='text' $readonly name='charges' value='".$charges."' size='4' /></td></tr>";
	$template_main .= "<tr><td>caracteristique : </td><td><select $disabled name='caracteristique'>";
		ksort($liste_caracs);
		reset($liste_caracs);
		$tata = array_keys($liste_caracs);
		for($i=0;$i<count($liste_caracs);$i++){
			$template_main .= "\t<option value='".$tata[$i]."'";
			if($tata[$i] == $caracteristique){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>competence : </td><td><select $disabled name='competence'>";
		$temp = array_merge($liste_magie,$liste_competences);
		ksort($temp);
		reset($temp);
		$tata = array_keys($temp);
		for($i=0;$i<count($temp);$i++){
			$template_main .= "\t<option value='".$tata[$i]."'";
			if($tata[$i] == $competence){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	//$template_main .= "<tr><td>Provoque Etat (<a href=\"javascript:a('listeetat.'.$phpExtJeu)\">Liste</a>) : </td><td><input type='text' name='provoqueetat' maxlength='100' value='".$provoqueetat."' size='35' /></td></tr>";

	$template_main .= "<tr><td>Cible du sort (N'est pas utilisé pour le moment): </td><td><select $disabled name='typecible'>";
		ksort($liste_type_cible);
		reset($liste_type_cible);
		$tata = array_keys($liste_type_cible);
		$toto = array_values($liste_type_cible);

		for($i=0;$i<count($liste_type_cible);$i++){
			$template_main .= "\t<option value='".$tata[$i]."'";
			if($tata[$i] == $typecible ){ $template_main .= " selected='selected'";}
			$template_main .= ">".$toto[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	$template_main .= "<tr><td>Peut être lancé à distance: (N'est pas utilisé pour le moment)</td><td><select $disabled name='sortdistant'>";
		if($sortdistant == 0){
			$template_main .= "\t<option value='0' selected='selected'>Non</option><option value='1'>Oui</option>";
		} else {
			$template_main .= "\t<option value='0'>Non</option><option value='1' selected='selected'>Oui</option>";
		}
	$template_main .= "</select></td></tr>";

	$template_main .= "<tr><td>";
	
	$SQL = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1 WHERE T1.id_etattemp > 1 ORDER BY T1.nom ASC";
	
	$template_main .= "Utilisable uniquement par</td><td>";
	$var=faitSelect("id_etattempspecifique",$SQL,$disabled,$id_etattempspecifique, array(), array("&nbsp;"));	
	$template_main .= $var[1];
	$template_main .= "</td></tr>";
	$template_main .= "</table>";
?>