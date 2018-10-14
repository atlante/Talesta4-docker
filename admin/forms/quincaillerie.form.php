<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: quincaillerie.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.11 $
$Date: 2010/01/24 16:37:10 $

*/


$template_main .= "<table class='details'>";
for($i=0;$i<$compteur;$i++){
	$template_main .= "<tr>";
	$template_main .= "<td>Supprimer<input type='checkbox' name='del[".$ListeObj[$i]->ID."]' /></td>";
	if($ListeObj[$i]->estpermanent()){
		$template_main .= "<td>#</td>";
	}else{
		$template_main .= "<td>-</td>";
	}
	if( ($ListeObj[$i]->image != "") &&(file_exists("../templates/$template_name/images/".$ListeObj[$i]->image)) ){
		$template_main .= "<td><img src='../templates/$template_name/images/".$ListeObj[$i]->image."' border='0' alt='image de l''objet' /></td>";
	}else{
		$template_main .= "<td>".GetImage($ListeObj[$i]->Soustype)."</td>";
	}

	$template_main .= "<td><a href=\"javascript:a('../bdc/objet.$phpExtJeu?for_mj=1&amp;num_obj=".$ListeObj[$i]->ID."')\">";
	$template_main .= span($ListeObj[$i]->nom." (".$ListeObj[$i]->Soustype.")","objet");
	$template_main .= "</a></td>";
	$template_main .= "<td>".span("D&eacute;gats : ".$ListeObj[$i]->Degats[0]." &agrave; ".$ListeObj[$i]->Degats[1],"degats")."</td>";
	if($ListeObj[$i]->durabilite <= 0){
		$template_main .= "<td>Indestructible</td>";
	} else {
		$template_main .= "<td>Dur:".$ListeObj[$i]->durabilite."</td>";
	}
	$template_main .= "<td>".span($ListeObj[$i]->caracteristique."/".$ListeObj[$i]->competence,"comp")."</td>";
	$template_main .= "<td>".span($ListeObj[$i]->poids." kg","poids")."</td>";
	$template_main .= "<td>".span($ListeObj[$i]->prix_base." po","po")."</td>";
	if($ListeObj[$i]->stockmax < 0 && $ListeObj[$i]->quantite<0)
		$template_main .= "<td colspan='2'>Stock illimité</td>";
	else {
	        if($ListeObj[$i]->stockmax < 0)
		        $template_main .= "<td>Quantité:".$ListeObj[$i]->quantite."</td>";
		else $template_main .= "<td>Quantité:".$ListeObj[$i]->quantite."/".$ListeObj[$i]->stockmax."</td>";        
		if($ListeObj[$i]->remisestock <= 0)
			$template_main .= "<td>Pas de réapprovisionnement automatique</td>";
		else 	$template_main .= "<td>Réapprovisionnement toutes les ".$ListeObj[$i]->remisestock ." h</td>";
	}
	$template_main .= "</tr>\n";
}
$template_main .= "</table>";
?>