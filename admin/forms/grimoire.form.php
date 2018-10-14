<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: grimoire.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.10 $
$Date: 2006/01/31 12:26:20 $

*/


$template_main .= "<table class='details'>";
for($i=0;$i<$compteur;$i++){
	$template_main .= "<tr>";
	$template_main .= "<td>Supprimer<input type='checkbox' name='del[".$ListeSort[$i]->id_clef."]' /></td>";
	if($ListeSort[$i]->estpermanent()){
		$template_main .= "<td>#</td>";
	}else{
		$template_main .= "<td>-</td>";
	}
	if( ($ListeSort[$i]->image != "") &&(file_exists("../templates/$template_name/images/".$ListeSort[$i]->image)) ){
		$template_main .= "<td><img src='../templates/$template_name/images/".$ListeSort[$i]->image."' border='0' alt='image du sort' /></td>";
	}else{
		$template_main .= "<td>".GetImage($ListeSort[$i]->type)."</td>";
	}

	$template_main .= "<td><a href=\"javascript:a('../bdc/sort.$phpExtJeu?for_mj=1&amp;num_sort=".$ListeSort[$i]->ID."')\">";
	if($ListeSort[$i]->anonyme == 0){
		$template_main .= span($ListeSort[$i]->nom." (".$ListeSort[$i]->Soustype.")","sort");
	} else {
		$template_main .= span($ListeSort[$i]->nom." (".$ListeSort[$i]->Soustype.") - anonyme","sort");
	}
	$template_main .= "</a></td>";
	$template_main .= "<td>".span("D&eacute;gats : ".$ListeSort[$i]->Degats[0]." &agrave; ".$ListeSort[$i]->Degats[1],"degats")."</td>";
	if($ListeSort[$i]->charges <= 0){
		$template_main .= "<td>charges Inifinies</td>";
	} else {
		$template_main .= "<td>Cha:<input type='text' size='2' name='cha[".$ListeSort[$i]->id_clef."]' value='".$ListeSort[$i]->charges_actu."' />/".$ListeSort[$i]->charges."</td>";
	}
	$template_main .= "<td>".span($ListeSort[$i]->caracteristique."/".$ListeSort[$i]->competence." - Diff : ".$ListeSort[$i]->GetdifficulteUtilisation(),"comp")."</td>";
	$template_main .= "<td>".span($ListeSort[$i]->place." pl","poids")."</td>";
	$template_main .= "<td>".span($ListeSort[$i]->prix_base." po","po")."</td>";
	$template_main .= "</tr>\n";
}
$template_main .= "</table>";
?>