<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: inventaire.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.11 $
$Date: 2010/01/24 16:37:09 $

*/


$template_main .= "<table class='details'>";
for($i=0;$i<$compteur;$i++){
	$template_main .= "<tr>";
	$template_main .= "<td>Supprimer<input type='checkbox' name='del[".$ListeObj[$i]->id_clef."]' /></td>";
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
	if($ListeObj[$i]->temporaire == 0){ $span = "objet";}else{$span = "temporaire";}
	$temp = $ListeObj[$i]->nom." (".$ListeObj[$i]->Soustype.")";
	if($ListeObj[$i]->competencespe != ""){$temp .= " - ".$ListeObj[$i]->competencespe;}
	if($ListeObj[$i]->anonyme == 1){$temp .= " - anonyme";}
	if($ListeObj[$i]->equipe == 1){$temp = "* ".$temp." *";}

	$template_main .= span($temp,"objet");
	$template_main .= "</a></td>";
	$template_main .= "<td>".span("D&eacute;gats : ".$ListeObj[$i]->Degats[0]." &agrave; ".$ListeObj[$i]->Degats[1],"degats")."</td>";
	if($ListeObj[$i]->durabilite <= 0){
		$template_main .= "<td>Indestructible</td>";
	} else {
		$template_main .= "<td>Dur:<input type='text' size='2' name='dur[".$ListeObj[$i]->id_clef."]' value='".$ListeObj[$i]->Dur_actu."' />/".$ListeObj[$i]->durabilite;
               	$template_main .= "<input type='hidden' value='".$ListeObj[$i]->Dur_actu."' name='old_dur[".$ListeObj[$i]->id_clef."]' /></td>";
		
	}
	if($ListeObj[$i]->munitions <= 0){
		$template_main .= "<td>munitions Inifinies</td>";
	} else {
		$template_main .= "<td>Mun:<input type='text' size='2' name='mun[".$ListeObj[$i]->id_clef."]' value='".$ListeObj[$i]->Mun_actu."' />/".$ListeObj[$i]->munitions;
		$template_main .= "<input type='hidden' value='".$ListeObj[$i]->Mun_actu."' name='old_mun[".$ListeObj[$i]->id_clef."]' /></td>";
	}

        $template_main .= "<td>Equipé: ".faitOuiNon("eqp[".$ListeObj[$i]->id_clef."]","" ,$ListeObj[$i]->equipe);
        $template_main .= "<input type='hidden' value='";
        if ($ListeObj[$i]->equipe) $template_main .="1";
                else $template_main .="0";
        $template_main.="' name='old_eqp[".$ListeObj[$i]->id_clef."]' /></td>";
	
	$template_main .= "<td>".span($ListeObj[$i]->caracteristique."/".$ListeObj[$i]->competence." - Diff : ".$ListeObj[$i]->GetdifficulteUtilisation(),"comp")."</td>";
	$template_main .= "<td>".span($ListeObj[$i]->poids." kg","poids")."</td>";
	$template_main .= "<td>".span($ListeObj[$i]->prix_base." po","po")."</td>";
	$template_main .= "</tr>\n";
}
$template_main .= "</table>";
?>