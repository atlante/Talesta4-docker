<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: chemin.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2006/01/31 12:26:20 $

*/


	if (NOM_SCRIPT==("supprimer_chemin.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }


$template_main .= "<table class='detailscenter' width='60%'>";
$template_main .= "<tr><td colspan='2'><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_chemin.htm')\">Aide</a></td></tr>";
	$template_main .= "<tr><td>Lieu de D&eacute;part : </td><td>";
		$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
		$var=faitSelect("id_lieu_1",$SQL,$disabled,$id_lieu_1);
		$template_main .= $var[1];
	$template_main .= "\n</td></tr>";
	if($etape==0|| $etape=="1"|| $etape==0.5) {
		$template_main .= "<tr><td>Lieu d'Arriv&eacute;e : </td><td>";
		$SQL = "Select concat(concat(T1.id_lieu,'$sep'),T1.nom) as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
		$var=faitSelect("id_lieu_2",$SQL,$disabled,$id_lieu_2);
		$template_main .= $var[1];
		$template_main .= "\n</td></tr>";
		$template_main .= "<tr><td>difficulte : </td><td><input type='text' $readonly name='difficulte' value='".$difficulte."' size='5' /></td></tr>";
		$template_main .= "<tr><td>type : </td><td><select $disabled name='type'>";
		$tata = array_keys($liste_types_chemins);
		$toto = array_values($liste_types_chemins);
		for($i=0;$i<count($liste_types_chemins);$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $type){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select></td></tr>\n";
	}
	else {
		$template_main .= "<tr><td colspan='2'><input type='hidden' name='type' value='2' /></td></tr>";
	}
	$template_main .= "<tr><td>pass : </td><td><input type='text' $readonly name='pass' value='".$pass."' size='20' /></td></tr>";
	if($etape==0|| $etape=="1"|| $etape==0.5) 
		$template_main .= "<tr><td>distance : </td><td><input type='text' $readonly name='distance' value='".$distance."' size='5' /></td></tr>";
	$template_main .= "<tr><td>Retour ?</td><td><input type='checkbox' $disabled name='retour'";
	if ((NOM_SCRIPT==("creer_chemin.".$phpExtJeu)  && $etape==0)
	||(NOM_SCRIPT==("creer_chemin.".$phpExtJeu)  && $etape==0.5 && isset($retour) && $retour==true)
	|| NOM_SCRIPT==("creerGuilde.".$phpExtJeu) ) 
		$template_main .= " checked='checked' ";	
	
	$template_main .= " /></td></tr>";
	$template_main .= "</table>";
?>