<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creerGuilde.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/02/28 22:58:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_guilde;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=5;}

$difficulte="";
$distance="";

if($etape=="10"){
	if($MJ->aDroit($liste_flags_mj["CreerLieu"]) && $MJ->aDroit($liste_flags_mj["CreerChemin"])){
		if($valider==1) { //  cr&eacute;ation
			if(defined("IN_FORUM")&& IN_FORUM==1) {
				//include(CHEMIN_FORUM."config.".$phpExtJeu);
				//include(CHEMIN_FORUM."includes/constants.".$phpExtJeu);

				$Guildes="Guildes et Groupements de PJ";
				$cat_id = $forum->GetCategorie_id($Guildes);
				if($cat_id==-1) {
					$MJ->OutPut("Aucune cat&eacute;gorie '".$Guildes."'",true);		
					$etape=-5;
				}
				else {
					$newForumID=$forum->CreeForum(ConvertAsHTML($nomGuilde),$cat_id,ConvertAsHTML($DescGuilde),"Priv");
	
					//creation de la guilde
					$newGroupID= $forum->CreeGroupe($nomGuilde,$DescGuilde,$gerant);
					//creation de l'autorisation pour le groupe
					$forum->DonneDroitsForum($newForumID, $newGroupID);

					//donner les droits aux MJ					
					$groupeMJ = $forum->GetGroupe_id("Groupe des MJs");
					$forum->DonneDroitsForum($newForumID, $groupeMJ);
					
				}
			}	
			$chaine	="";
			include("creer_lieu.".$phpExtJeu);
			

			include("creer_chemin.".$phpExtJeu);
			if ($etape!=-5) {
				$result = true;
				if(defined("IN_FORUM")&& IN_FORUM==1) 
					$result=$forum->creePrivateMessage("",$gerant,$MJ->nom, "", "Vous tes grant de la guilde ". $nomGuilde, $forum->texteEnvoyeAuGerantGuilde($nomGuilde));
				if ($result) {				
					if(defined("IN_FORUM")&& IN_FORUM==1) 
						$MJ->OutPut("Guilde ".span(ConvertAsHTML($nomGuilde),"pj")." correctement cr&eacute;&eacute;e. Grant prvenu par PM.",true);
					else $MJ->OutPut("Guilde ".span(ConvertAsHTML($nomGuilde),"pj")." correctement cr&eacute;&eacute;e.",true);	
				}	
				$etape=5;
			}	
			
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}


if($etape==5||$etape==-5){
	
	$liste_champs=array(
			"nom","flags","trigramme","accessible_telp","id_forum","provoqueetat","id_lieu_1","id_lieu_2","type","difficulte","pass","distance","difficultedesecacher","cheminfichieraudio"
			//, "typemimefichieraudio"					
			, "id_etattempspecifique","apparition_monstre","type_lieu_apparition"
		);

	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = '';
	}

	for($i=0;$i<count($liste_flags_lieux);$i++)
		$flags[$i]=0;	
	
	$provoqueetatValue="";	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$gerant='';
	include('forms/guilde.form.'.$phpExtJeu);
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='10' />";
	
	$template_main .= "</form>";
	//etats temporaires du lieu
	include ("./forms/objet2.form.".$phpExtJeu);

	$template_main .= "</div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>