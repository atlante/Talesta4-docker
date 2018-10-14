<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: objet.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.21 $
$Date: 2010/01/24 16:36:51 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);

if(isset($for_mj)){
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($num_obj)){$num_obj = 1;}
$peutvoir=false;
if(isset($MJ)|| isset($for_mj)){
	$peutvoir=true;
	$Objet_vu = new Objet($num_obj);
}
if(isset($PERSO)){
	$trouve=false;
	$i=0;
	while($i<count($PERSO->Objets) && $peutvoir==false){
		if ($num_obj == $PERSO->Objets[$i]->ID) {
			$peutvoir=true;
			$Objet_vu = $PERSO->Objets[$i];
		}	
		else $i++;
	}
}
if($peutvoir){
	if ($Objet_vu == false)
		$template_main .= "Cet objet n'existe pas";
	else {			
		$template_main .= "<div class ='centerSimple'>";
		$template_main .= "Details de l'objet ".span($Objet_vu->nom,"objet").", Num&eacute;ro <b>".$Objet_vu->ID."</b>";
		
		$template_main .= "<table class='detailscenter'>";
		$template_main .= "<tr>";
			if( ($Objet_vu->image != "") && (file_exists("../templates/$template_name/images/".$Objet_vu->image) ) ){
				$template_main .= "<td><img src='../templates/$template_name/images/".$Objet_vu->image."' border='0' alt='image de l''objet' /></td>";
			}else{
				$template_main .= "<td>".GetImage($Objet_vu->Soustype)."</td>";
			}
			$template_main .= "<td>".span($Objet_vu->nom,"objet")."</td>";
			if($Objet_vu->description != ""){
			$template_main .= "<td rowspan='13'>".$Objet_vu->description."</td></tr>";
			} else {
				$template_main .= "<td rowspan='13'>Pas de description</td></tr>";
			}
			$template_main .= "<tr><td>".span($Objet_vu->type,"comp")."</td><td>".span($Objet_vu->Soustype,"comp")."</td></tr>";

			if($Objet_vu->type == "ArmeMelee" || $Objet_vu->type == "ArmeJet"){
				$template_main .= "<tr><td>D&eacute;gats</td><td>".span($Objet_vu->Degats[0],"degats")." &agrave; ".span($Objet_vu->Degats[1],"degats")."</td></tr>";
			}
			if($Objet_vu->type == "Armure"){
				$template_main .= "<tr><td>Absorbe</td><td>".span($Objet_vu->Degats[0],"degats")." degats de ".span($Objet_vu->competence,"comp")."</td></tr>";
			}

			if($Objet_vu->type == "Soins"){
				$template_main .= "<tr><td>Soigne de</td><td>".span($Objet_vu->Degats[0]." PVs","pv")." &agrave; ".span($Objet_vu->Degats[1]." PVs","pv")."</td></tr>";
			} 			
			
			if($Objet_vu->type == "SoinsPI"){
				$template_main .= "<tr><td>Soigne de</td><td>".span($Objet_vu->Degats[0]." PIs","pi")." &agrave; ".span($Objet_vu->Degats[1]." PIs","pi")."</td></tr>";
			} 			
			
			if($Objet_vu->Soustype == "Livre"){
				if($Objet_vu->caracteristique != ""){
					$template_main .= "<tr><td>Fait monter</td><td>".span($Objet_vu->caracteristique,"comp")."</td></tr>";
				} else {
					if($Objet_vu->competence != ""){
						$template_main .= "<tr><td>Fait monter</td><td>".span($Objet_vu->competence,"comp")."</td></tr>";
					} else {
						$template_main .= "<tr><td>Fait monter</td><td>".span("Sagesse","comp")."</td></tr>";
					}
				}
			}
			if($Objet_vu->type == "Nourriture"){
				$template_main .= "<tr><td>Fait gagner</td><td>".span($Objet_vu->Degats[0]." PVs","pv")." et ".span($Objet_vu->Degats[1]." PAs","pa")."</td></tr>";
			}
			if($Objet_vu->Soustype == "passe Partout"){
				$template_main .= "<tr><td>bonus au crochetage</td><td>entre ".span($Objet_vu->Degats[0],"bonus")." et ".span($Objet_vu->Degats[1],"bonus")."</td></tr>";
			}


			

			if( ($Objet_vu->type == "ArmeMelee" || $Objet_vu->type == "ArmeJet") || ($Objet_vu->type == "Armure") || ($Objet_vu->Soustype == "passe Partout")){
				if( ($Objet_vu->durabilite == -1) ){
					$template_main .= "<tr><td colspan='2'>".span("Indestructible","dur")."</td></tr>";
				}else{					
					if (!(isset($MJ)|| isset($for_mj))){
						$template_main .= "<tr><td>Durabilit</td><td>".span($Objet_vu->Dur_actu."/".$Objet_vu->durabilite,"mun")."</td></tr>";
					}				
					else 
					$template_main .= "<tr><td>durabilite Max</td><td>".span($Objet_vu->durabilite,"dur")."</td></tr>";
				}
			}
			if($Objet_vu->type == "ArmeMelee" || $Objet_vu->type == "ArmeJet"){
				if( ($Objet_vu->munitions == -1) ){
					$template_main .= "<tr><td colspan='2'>".span("munitions Infinies","mun")."</td></tr>";
				}else{
					$template_main .= "<tr><td>munitions Max</td><td>".span($Objet_vu->munitions,"mun")."</td></tr>";
					if (!(isset($MJ)|| isset($for_mj))){
						//ajout de KiwiToast
						$template_main .= "<tr><td>Munitions Restantes</td><td>".span($Objet_vu->Mun_actu,"mun")."</td></tr>";
					}
				}
			}

			if( ($Objet_vu->permanent == 0) ){
				$template_main .= "<tr><td>permanent</td><td>Non</td></tr>";
			}else{
				$template_main .= "<tr><td>permanent</td><td>OUI</td></tr>";
			}
			if( ($Objet_vu->anonyme == 0) ){
				$template_main .= "<tr><td>anonyme</td><td>Non</td></tr>";
			}else{
				$template_main .= "<tr><td>anonyme</td><td>OUI</td></tr>";
			}
			$template_main .= "<tr><td>poids</td><td>".span($Objet_vu->poids,"poids")." kg</td></tr>";
			if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
				$template_main .= "<tr><td>Prix</td><td>".span($Objet_vu->prix_base,"po")." PO</td></tr>\n";
			if($Objet_vu->type == "ArmeMelee" || $Objet_vu->type == "ArmeJet"){
				$template_main .= "<tr><td>caracteristique</td><td>".span($Objet_vu->caracteristique,"comp")."</td></tr>";
				$template_main .= "<tr><td>competence</td><td>".span($Objet_vu->competence,"comp")."</td></tr>";
				$template_main .= "<tr><td>Capacite Speciale</td><td>";
				if ($Objet_vu->competencespe=="")
					$template_main .= "&nbsp;";
				else	$template_main .= span($Objet_vu->competencespe,"comp");
				$template_main .= "</td></tr>\n";
			}
		
		
			
			$template_main .= "<tr><td>";
			$template_main .= "Utilisable uniquement par</td><td>";			
			if ($Objet_vu->EtatTempSpecifique!=null)
				$template_main .= span($Objet_vu->EtatTempSpecifique->nom,"etattemp");
			else $template_main .= "&nbsp;";	
			$template_main .= "</td></tr>";
		
			
			$etat = $Objet_vu->provoqueetat;
			if($etat != ""){
				$template_main .= "<tr><td colspan='3'><hr /></td></tr>";
				$temp = explode("|",$etat);
				for($i=0;$i<count($temp);$i++){
					$bidon = explode(";",$temp[$i]);
					$num_etat = abs($bidon[0]);
					$SQL = "SELECT * FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$num_etat;
					$result2 = $db->sql_query($SQL);
					if($db->sql_numrows($result2) > 0){
						$row2 = $db->sql_fetchrow($result2);
						if($bidon[0] < 0){
							$chaine = "Annule ".span($row2["nom"],"etattemp")." avec un pourcentage de chance de ".$bidon[1]." %";
						} else{
							$chaine = "Provoque ".span($row2["nom"],"etattemp")." pour";
							if($bidon[2] == -1){
								$chaine.= " une dur&eacute;e de ".span("illimit&eacute;e","date")." avec un pourcentage de chance de ".$bidon[1]." %";
							}else{ 
								$chaine .= " une dur&eacute;e de ".span($bidon[2],"date")." heures, avec un pourcentage de chance de ".$bidon[1]." %";
							}
						}
						
						$template_main .= "<tr><td colspan='3'>".$chaine."</td></tr>";
					}
				}
			}
		if(isset($MJ)){
			if($Objet_vu->Soustype == "Livre"){
				$template_main .= "<tr><td colspan='3'>Fait gagner entre ".span($Objet_vu->Degats[0],"xp")." et ".span($Objet_vu->Degats[1],"xp")." XP</td></tr>";
				$template_main .= "<tr><td colspan='3'>Difficult&eacute; : ".span( ($Objet_vu->Degats[0]+2*($Objet_vu->Degats[1])),"xp")."</td></tr>\n";
			}
			if($Objet_vu->Soustype == "Clef"){
				$SQL = "SELECT concat(concat(T2.trigramme,'-'),T2.nom) as Depart, concat(concat(T3.trigramme,'-'),T3.nom) as Arrivee FROM ".NOM_TABLE_OBJET." T1, ".NOM_TABLE_LIEU." T2, ".NOM_TABLE_LIEU." T3 WHERE T1.id_objet = ".$Objet_vu->id_objet." AND T2.id_lieu = T1.degats_min AND T3.id_lieu = T1.degats_max";
				$result2 = $db->sql_query($SQL);
				$row = $db->sql_fetchrow($result2);
				$template_main .= "<tr><td colspan='3'>Relies ".span($row["Depart"],"lieu")." et ".span($row["arrivee"],"lieu")."</td></tr>\n";
			}
			
		}

		$template_main .= "</table>\n";
		if( (!isset($MJ)) && (($Objet_vu->Soustype == "Clef") || ($Objet_vu->Soustype == "Livre")) ){$template_main .= "<a href='objet.$phpExtJeu?for_mj=1&amp;num_obj=".$num_obj."'>Plus de details (".span("MJ","mj")." uniquement)</a>";}
		$template_main .= "</div>";
	}
} else {
	$template_main .= "Vous ne pouvez pas voir cet objet. Soit vous ne le possedez pas, soit vous n'etes pas MJ";
}
if(!defined("__BARREGENERALE.PHP")){$template_main .= "</div>";include("../include/barregenerale.".$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>