<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: sort.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.17 $
$Date: 2010/01/24 16:36:51 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);

if(isset($for_mj)){
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($num_sort)){$num_sort = 1;}
$peutvoir=false;
if(isset($MJ)|| isset($for_mj)){
	$peutvoir=true;
	$Sort_vu = new Magie($num_sort);
}
if(isset($PERSO)){
	$trouve=false;
	$i=0;
	while($i<count($PERSO->Sorts) && $peutvoir==false){
		if ($num_sort == $PERSO->Sorts[$i]->ID) {
			$peutvoir=true;
			$Sort_vu = $PERSO->Sorts[$i];
		}	
		else $i++;
	}
}
if($peutvoir){
	if ($Sort_vu == false)
		$template_main .= "Ce sort n'existe pas";
	else {			
		$template_main .= "<div class ='centerSimple'>";
		$template_main .= "Details du sort ".span($Sort_vu->nom,"sort").", Num&eacute;ro <b>".$Sort_vu->ID."</b>";
		
		$template_main .= "<table class='detailscenter'>";
		$template_main .= "<tr>";
			if( ($Sort_vu->image != "") && (file_exists("../templates/$template_name/images/".$Sort_vu->image) ) ){
				$template_main .= "<td><img src='../templates/$template_name/images/".$Sort_vu->image."' border='0' alt='image du sort' /></td>";
			}else{
				$template_main .= "<td>".GetImage($Sort_vu->type)."</td>";
			}
			$template_main .= "<td>".span($Sort_vu->nom,"sort")."</td>";
			if($Sort_vu->description != ""){
				$template_main .= "<td rowspan='17'>".$Sort_vu->description."</td></tr>";
			} else {
				$template_main .= "<td rowspan='17'>Pas de description</td></tr>";
			}
			$template_main .= "<tr><td>".span($Sort_vu->type,"comp")."</td><td>".span($Sort_vu->Soustype,"comp")."</td></tr>";

			if($Sort_vu->Soustype == "Soin"){
				$template_main .= "<tr><td>Gain de PV</td><td>entre ".span($Sort_vu->Degats[0],"degats")." et ".span($Sort_vu->Degats[1],"degats")."</td></tr>";
			} else {
				$template_main .= "<tr><td>Degats</td><td>entre ".span($Sort_vu->Degats[0],"degats")." et ".span($Sort_vu->Degats[1],"degats")."</td></tr>";
			}
			if( ($Sort_vu->charges == -1) ){
					$template_main .= "<tr><td colspan='2'>".span("charges Infinies","mun")."</td></tr>";
			}else{
					$template_main .= "<tr><td>charges Max</td><td>".span($Sort_vu->charges,"cha")."</td></tr>";
			}
			
			if( ($Sort_vu->permanent == 0) ){
				$template_main .= "<tr><td>permanent</td><td>Non</td></tr>";
			}else{
				$template_main .= "<tr><td>permanent</td><td>OUI</td></tr>";
			}
			if( ($Sort_vu->anonyme == 0) ){
				$template_main .= "<tr><td>anonyme</td><td>Non</td></tr>";
			}else{
				$template_main .= "<tr><td>anonyme</td><td>OUI</td></tr>";
			}
			$template_main .= "<tr><td>place</td><td>".span($Sort_vu->place,"poids")." pl</td></tr>";
			if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
				$template_main .= "<tr><td>Prix</td><td>".span($Sort_vu->prix_base,"po")." PO</td></tr>";
                        $template_main .= "<tr><td>Cout en PA au lancement</td><td>".span($Sort_vu->coutPA,"pa")." </td></tr>";
                        $template_main .= "<tr><td>Cout en PI au lancement</td><td>".span($Sort_vu->coutPI,"pi")." </td></tr>";
                        $template_main .= "<tr><td>Cout en PO au lancement</td><td>".span($Sort_vu->coutPO,"po")." </td></tr>";
                        $template_main .= "<tr><td>Cout en PV au lancement</td><td>".span($Sort_vu->coutPV,"pv")." </td></tr>";
			$template_main .= "<tr><td>caracteristique</td><td>".span($Sort_vu->caracteristique,"comp")."</td></tr>";
			$template_main .= "<tr><td>competence</td><td>".span($Sort_vu->competence,"comp")."</td></tr>";

			$template_main .= "<tr><td>";
			$template_main .= "Utilisable uniquement par</td><td>";			
			if ($Sort_vu->EtatTempSpecifique!=null)
				$template_main .= span($Sort_vu->EtatTempSpecifique->nom,"etattemp");
			else $template_main .= "&nbsp;";	
			$template_main .= "</td></tr>";
			
			
			switch ($Sort_vu->TypeCible){
				case 1:			
					if ($Sort_vu->SortDistant==0)
						$majeur=" Mineur: sur 1 PJ se trouvant dans le meme lieu que le lanceur";
					else 	$majeur=" Majeur: sur 1 PJ dans un autre lieu que le lanceur";
					break;
				case 2:
					if ($Sort_vu->SortDistant==0)
						$majeur=" Majeur: Affecte tous les PJs du lieu du lanceur";
					else 	$majeur=" Majeur: Affecte tous les PJs d'un lieu pouvant être différent de celui du lanceur";
					break;
				case 3:
					$majeur="Mineur: sur le lanceur lui meme";
					break;					
			}	
			
			$template_main .= "<tr><td>Type de Magie";
			$template_main .= "</td><td>".$majeur;
			$template_main .= "</td></tr>";
			$template_main .= "<tr><td>";
			$template_main .= "Nécessite les composantes de sort: </td><td>";			
			if ($Sort_vu->composantes!="") {
				$Sort_vu->setObjetsComposantesSort();
				$nb_composantes = count($Sort_vu->ObjetsComposantesSort);
				for($i=0;$i<$nb_composantes;$i++) {
					$temp = $Sort_vu->ObjetsComposantesSort[$i];
					$template_main .= span($temp[0]->nom,"objet");
					if ($temp[1]==1)
						$template_main .= " " . GetMessage("composanteConservee");
					else
						$template_main .= " " . GetMessage("composanteDetruiteAuLancement");
					if ($i<$nb_composantes-1)
						$template_main .= ", ";
				}	
			}	
			else $template_main .= "&nbsp;";	
			$template_main .= "</td></tr>";
			
			$etat = $Sort_vu->provoqueetat;
			if($etat != ""){
				$template_main .= "<tr><td colspan='3'><hr /></td></tr>";
				$temp = explode("|",$etat);
				// count -1 pour supprimer le dernier | 
				for($i=0;$i<count($temp)-1;$i++){
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

		$template_main .= "</table>\n";
//		if(!isset($MJ)){$template_main .= "<a href='sort.$phpExtJeu?for_mj=1&amp;num_sort=".$num_sort."'>Plus de details (".span("MJ","mj")." uniquement)</a>";}
		$template_main .= "</div>";
	} 
} else {
	$template_main .= "Vous ne pouvez pas voir ce sort. Soit vous ne le possedez pas, soit vous n'etes pas MJ";
}
if(!defined("__BARREGENERALE.PHP")){include("../include/barregenerale.".$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>