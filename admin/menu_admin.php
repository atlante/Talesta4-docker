<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: menu_admin.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.24 $
$Date: 2010/02/28 22:58:06 $

*/

require_once("../include/extension.inc");
if(!defined("__MENU_ADMIN.PHP") ) {
	Define("__MENU_ADMIN.PHP",	0);

	$script = basename($HTTP_SERVER_VARS['PHP_SELF']);
	if (isset($PERSO) && $PERSO->roleMJ!="") {
		if(!defined("__MENU_JEU.PHP")){include('../game/menu_jeu.'.$phpExtJeu);}
	}	

	if (!isset($act)) {
		switch ($script) {
			case "status.".$phpExtJeu:  
			case "fa_pj.".$phpExtJeu: 
			case "inscrire.".$phpExtJeu:  
			case "modifier_spec_pj.".$phpExtJeu:  
			case "modifier_etat_pj.".$phpExtJeu:
			case "modifier_info_pj.".$phpExtJeu:  
			case "modifier_xp.".$phpExtJeu:  
			case "modifier_inv.".$phpExtJeu:
			case "modifier_grim.".$phpExtJeu:  
			case "modifier_quete_pj.".$phpExtJeu:
			case "parler_pj.".$phpExtJeu:  
			case "creerPNJ.".$phpExtJeu:
			case "supprimer_pj.".$phpExtJeu:  
			case "cimetiere.".$phpExtJeu:  
			case "teleporter.".$phpExtJeu:	 
			case "registre.".$phpExtJeu:	
			case "supprimerdroits_pj.".$phpExtJeu:
			case "donnerdroits_pj.".$phpExtJeu:
			case "modifierdroits_pj.".$phpExtJeu:
			case "modifier_quete_pj.".$phpExtJeu:
			case "listeActionsTracees.".$phpExtJeu:
				$act='gestionPJs';
				break;

			case "liste_ppa.".$phpExtJeu: 
			case "repondre_ppa.".$phpExtJeu: 
				$act='gestionPPAs';
				break;

				
			case "fa_mj.".$phpExtJeu: 
			case "modifier_mj.".$phpExtJeu: 
			case "parler_mj.".$phpExtJeu:
			case "fa_mj.".$phpExtJeu:  
			case "supprimer_mj.".$phpExtJeu: 
			case "creer_mj.".$phpExtJeu:
				$act='gestionMJs';
				break;		

			case "creerBestiaire.".$phpExtJeu: 
			case "supprimerBestiaire.".$phpExtJeu: 
			case "modifier_info_Bestiaire.".$phpExtJeu:
			case "creerMonstre.".$phpExtJeu: 
			case "modifier_spec_Bestiaire.".$phpExtJeu:  
			case "modifier_etat_Bestiaire.".$phpExtJeu:
			case "modifier_info_Bestiaire.".$phpExtJeu:  
			case "modifier_xp_Bestiaire.".$phpExtJeu:  
			case "modifier_inv_Bestiaire.".$phpExtJeu:			
				$act='gestionBestiaire';
				break;	

			case "creer_lieu.".$phpExtJeu: 
			case "supprimer_lieu.".$phpExtJeu: 
			case "modifier_lieu.".$phpExtJeu:
			case "modifier_desc_lieu.".$phpExtJeu:  
			case "parler_lieu.".$phpExtJeu: 
			case "voir_lieu.".$phpExtJeu:
			case "registrelieux.".$phpExtJeu:
			case "ApparitionMonstre.".$phpExtJeu:
				$act='gestionLieu';
				break;		

			
			 case "forum.".$phpExtJeu:
			 case "gestion_news.".$phpExtJeu :
			 case "news_csl_news.".$phpExtJeu:
			 case "news_add_news.".$phpExtJeu:
			 case "news_mod_news.".$phpExtJeu:			
			 case "news_del_news.".$phpExtJeu:
			 case "news_del_com.".$phpExtJeu:
			 case "news_wrp_index.".$phpExtJeu:
			 case "news_wrp_archives.".$phpExtJeu:
			 case "news_wrp_news.".$phpExtJeu:
			 case "news_wrp_comment.".$phpExtJeu:
			 case "news_wrp_coment_one.".$phpExtJeu:
				$act='gestionNews';
				break;		

			case "creer_spec.".$phpExtJeu:
			case "supprimer_spec.".$phpExtJeu:
			case "modifier_spec.".$phpExtJeu:
				$act='gestionSpec';
				break;	

			case "creer_etat.".$phpExtJeu:
			case "supprimer_etat.".$phpExtJeu:
			case "modifier_etat.".$phpExtJeu:
			case "creer_typeetat.".$phpExtJeu:
			case "listeetat.".$phpExtJeu:
			case "creer_etatCarac.".$phpExtJeu:
			case "modifier_typeetat.".$phpExtJeu:
			case "supprimer_typeetat.".$phpExtJeu:
				$act='gestionEtat';
				break;
		
			case "creer_chemin.".$phpExtJeu:
			case "supprimer_chemin.".$phpExtJeu:
			case "modifier_chemin.".$phpExtJeu:
			case "registrechemin.".$phpExtJeu:
			case "creer_map.".$phpExtJeu:
			case "voir_map.".$phpExtJeu:
				$act='gestionChemin';							
				break;			

			case "supprimer_magie.".$phpExtJeu:
			case "creer_magie.".$phpExtJeu:
			case "modifier_magie.".$phpExtJeu:
			case "listesort.".$phpExtJeu:
			case "creer_Sorts.".$phpExtJeu:
				$act='gestionSort';							
				break;			

			case "supprimer_objet.".$phpExtJeu:
			case "creer_objet.".$phpExtJeu:
			case "modifier_objet.".$phpExtJeu:
			case "listeobjet.".$phpExtJeu:
			case "cacher_objet.".$phpExtJeu:
			case "creer_Livre.".$phpExtJeu:
			case "creer_Armures.".$phpExtJeu:
			case "creer_Munitions.".$phpExtJeu:
				$act='gestionObjet';							
				break;			
			
			case "modifier_mag_magique.".$phpExtJeu:
			case "modifier_quincaillerie.".$phpExtJeu:
			case "modifier_armurerie.".$phpExtJeu:
			case "modifier_lieuapprentissage.".$phpExtJeu:
			case "modifier_productionNaturelle.".$phpExtJeu:
			case "listeMagasin.".$phpExtJeu:
				$act='gestionBoutique';							
				break;	

			case "supprimer_question.".$phpExtJeu:
			case "creer_question.".$phpExtJeu:
			case "modifier_question.".$phpExtJeu:
			case "liste_qcm.".$phpExtJeu:
				$act='gestionQCM';							
				break;	
				
			case "voirFichierLog.".$phpExtJeu:
			case "purgeFichierLog.".$phpExtJeu:
				$act='gestionLogs';							
				break;	
								
			case "creer_quete.".$phpExtJeu:
			case "creer_Quetes.".$phpExtJeu:
			case "supprimer_quete.".$phpExtJeu:
			case "modifier_quete.".$phpExtJeu:
			case "listequete.".$phpExtJeu:
			case "historique_quete.".$phpExtJeu:
				$act='gestionQuete';
				break;					
			default:
				$act="";
		}	
	}	




	  $rep = "../include/db/";
	  $dir = opendir($rep);
	  $debutFichier = "UpdateTalesta4.".$dbmsJeu;
	  $trouve=FALSE;
	  if ($dir != FALSE) {
		  while((FALSE !==($file = readdir($dir))) && ($trouve==FALSE)) {
		  	if (strpos($file,$debutFichier)!==FALSE && strpos($file,$debutFichier)==0) {
			  	$trouve=TRUE;
			}
		  }
		  closedir($dir);
	 } 	  
	 else $template_main .= "Impossible d'accder  '". $rep ."'";

	if (! isset($liens_menu)) 
		$liens_menu=array();	
	
	array_push ($liens_menu,
		array(1,"../admin/config.".$phpExtJeu,GetMessage("configGene"),"" ,$MJ->ID ==1), 
		array(1,"../admin/maj_version_talesta.".$phpExtJeu."?action=miseajour","Installation d'une nouvelle version de talesta","" ,$MJ->ID ==1 && $trouve && file_exists("./maj_version_talesta.".$phpExtJeu)), 
		array(1,"../admin/fa.".$phpExtJeu,"FA Mjcal","" ,-1),
		
		array(1,"../admin/menu.$phpExtJeu?act=gestionPPAs",GetMessage("gestionPPAS"),"" ,$MJ->ID==1 || $MJ->dispo_pour_ppa==1));

	if ($act=='gestionPPAs')			
		array_push ($liens_menu,
			array(2,"../admin/repondre_ppa.".$phpExtJeu,"PPAs  traiter","" ,true),
			array(2,"../admin/liste_ppa.".$phpExtJeu,"Liste des PPAs","" ,true));

        array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionPJs",GetMessage("gestionPJS"),"" ,$MJ->aDroit($liste_flags_mj["Status"])||
					$MJ->aDroit($liste_flags_mj["FAs"])||
					$MJ->aDroit($liste_flags_mj["InscrirePJ"])||
					$MJ->aDroit($liste_flags_mj["ModifierSpecPJ"])||
					$MJ->aDroit($liste_flags_mj["ModifierEtatPJ"])||
					$MJ->aDroit($liste_flags_mj["ModifierInfoPJ"])||
					$MJ->aDroit($liste_flags_mj["ModifierXP"])||
					$MJ->aDroit($liste_flags_mj["ModifierInv"])||
					$MJ->aDroit($liste_flags_mj["ModifierGrim"])||
					$MJ->aDroit($liste_flags_mj["ParlerPJ"])||
					$MJ->aDroit($liste_flags_mj["InscrirePJ"])		||
					$MJ->aDroit($liste_flags_mj["SupprimerPJ"])		||
					$MJ->aDroit($liste_flags_mj["SupprimerPJ"])		||
					$MJ->aDroit($liste_flags_mj["DeplacerPJ"])||
					$MJ->aDroit($liste_flags_mj["Registre"]) ||
					$MJ->aDroit($liste_flags_mj["ModifierQuetePJ"])
					)
	);
	
	if ($act=='gestionPJs')			
		array_push ($liens_menu,
			array(2,"../admin/status.".$phpExtJeu,"Fiches de Perso","" ,$MJ->aDroit($liste_flags_mj["Status"])),
			array(2,"../admin/fa_pj.".$phpExtJeu,"FA des PJs","" ,$MJ->aDroit($liste_flags_mj["FAs"])),
			array(2,"../admin/inscrire.".$phpExtJeu,"Inscrire PJ","" ,$MJ->aDroit($liste_flags_mj["InscrirePJ"])),
			array(2,"../admin/modifier_spec_pj.".$phpExtJeu,"Modifier Spec PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierSpecPJ"])),
			array(2,"../admin/modifier_etat_pj.".$phpExtJeu,"Modifier Etats PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierEtatPJ"])),
			array(2,"../admin/modifier_info_pj.".$phpExtJeu,"Modifier Infos PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierInfoPJ"])),
			array(2,"../admin/modifier_xp.".$phpExtJeu,"Modifier XP PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierXP"])),
			array(2,"../admin/modifier_inv.".$phpExtJeu,"Modifier Inv PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierInv"])),
			array(2,"../admin/modifier_grim.".$phpExtJeu,"Modifier Grim PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierGrim"])),
			array(2,"../admin/modifier_quete_pj.".$phpExtJeu,"Modifier Quetes PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierQuetePJ"])),
			array(2,"../admin/parler_pj.".$phpExtJeu,"Parler a un PJ","" ,$MJ->aDroit($liste_flags_mj["ParlerPJ"])),
			array(2,"../admin/creerPNJ.".$phpExtJeu,"Cr&eacute;er PNJ","" ,$MJ->aDroit($liste_flags_mj["InscrirePJ"])),		
			array(2,"../admin/supprimer_pj.".$phpExtJeu,"Supprimer un PJ/PNJ","" ,$MJ->aDroit($liste_flags_mj["SupprimerPJ"])),		
			array(2,"../admin/cimetiere.".$phpExtJeu,"Liste des PJ/PNJ morts","" ,$MJ->aDroit($liste_flags_mj["SupprimerPJ"])),		
			array(2,"../admin/teleporter.".$phpExtJeu,"d&eacute;placer PJ/PNJ","" ,$MJ->aDroit($liste_flags_mj["DeplacerPJ"])),
			array(2,"../admin/registre.".$phpExtJeu,"Registre","" ,$MJ->aDroit($liste_flags_mj["Registre"])),
			array(2,"../admin/donnerdroits_pj.".$phpExtJeu,"Donner droits MJ  un PJ","" ,$MJ->aDroit($liste_flags_mj["CreerMJ"]) && $MJ->aDroit($liste_flags_mj["DonnerDroitsMJauxPJs"])),
			array(2,"../admin/supprimerdroits_pj.".$phpExtJeu,"Supprimer droits MJ  un PJ","" ,$MJ->aDroit($liste_flags_mj["SupprimerMJ"]) && $MJ->aDroit($liste_flags_mj["SupprimerDroitsMJauxPJs"])),
			array(2,"../admin/modifierdroits_pj.".$phpExtJeu,"Modifier droits MJ  un PJ","" ,$MJ->aDroit($liste_flags_mj["ModifierMJ"]) && $MJ->aDroit($liste_flags_mj["ModifierDroitsMJauxPJs"])),
			array(2,"../admin/listeActionsTracees.".$phpExtJeu,"Liste des actions traces","" ,true)

		);




	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionBestiaire",GetMessage("gestionMonstres"),"", $MJ->aDroit($liste_flags_mj["CreerBestiaire"]) || $MJ->aDroit($liste_flags_mj["ModifierSpecPJ"])
		                || $MJ->aDroit($liste_flags_mj["ModifierEtatPJ"]) || $MJ->aDroit($liste_flags_mj["ModifierBestiaire"]) || $MJ->aDroit($liste_flags_mj["ModifierXP"]) || $MJ->aDroit($liste_flags_mj["ModifierInv"]) 
		                || $MJ->aDroit($liste_flags_mj["ModifierGrim"]) ||   $MJ->aDroit($liste_flags_mj["SupprimerBestiaire"]) ||   $MJ->aDroit($liste_flags_mj["InscrirePJ"])  )
	);

	if ($act=='gestionBestiaire')		
		array_push ($liens_menu,
			array(2,"../admin/creerBestiaire.".$phpExtJeu,"Crer Bestiaire","" ,$MJ->aDroit($liste_flags_mj["CreerBestiaire"])),
			array(2,"../admin/modifier_spec_Bestiaire.".$phpExtJeu,"Modifier Spec Bestiaire","" ,$MJ->aDroit($liste_flags_mj["ModifierSpecPJ"])),
			array(2,"../admin/modifier_etat_Bestiaire.".$phpExtJeu,"Modifier Etats Bestiaire","" ,$MJ->aDroit($liste_flags_mj["ModifierEtatPJ"])),
			array(2,"../admin/modifier_info_Bestiaire.".$phpExtJeu,"Modifier Infos Bestiaire","" ,$MJ->aDroit($liste_flags_mj["ModifierBestiaire"])),
			array(2,"../admin/modifier_xp_Bestiaire.".$phpExtJeu,"Modifier XP Bestiaire","" ,$MJ->aDroit($liste_flags_mj["ModifierXP"])),
			array(2,"../admin/modifier_inv_Bestiaire.".$phpExtJeu,"Modifier Inv Bestiaire","" ,$MJ->aDroit($liste_flags_mj["ModifierInv"])),
			array(2,"../admin/modifier_grim_Bestiaire.".$phpExtJeu,"Modifier Grim Bestiaire","" ,$MJ->aDroit($liste_flags_mj["ModifierGrim"])),
			array(2,"../admin/supprimerBestiaire.".$phpExtJeu,"Supprimer Bestiaire","" ,$MJ->aDroit($liste_flags_mj["SupprimerBestiaire"])),
			array(2,"../admin/creerMonstre.".$phpExtJeu,"Placer Monstre dans lieu","" ,$MJ->aDroit($liste_flags_mj["InscrirePJ"]))

		);

	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionMJs",GetMessage("gestionMJS"),"", true /*,$MJ->aDroit($liste_flags_mj["CreerMJ"])||
					$MJ->aDroit($liste_flags_mj["SupprimerMJ"])||
					$MJ->aDroit($liste_flags_mj["ModifierMJ"])||
					$MJ->aDroit($liste_flags_mj["ParlerMJ"])*/
					)
	);

	if ($act=='gestionMJs')		
		array_push ($liens_menu,
			array(2,"../admin/fa_mj.".$phpExtJeu,"Fa des MJ","" ,($MJ->ID==1)),
			array(2,"../admin/creer_mj.".$phpExtJeu,"Creer un MJ","" ,$MJ->aDroit($liste_flags_mj["CreerMJ"])),
			array(2,"../admin/supprimer_mj.".$phpExtJeu,"Supprimer un MJ","" ,$MJ->aDroit($liste_flags_mj["SupprimerMJ"])),
			array(2,"../admin/modifier_mj.".$phpExtJeu,GetMessage("modifMJ"),"" ,true),
			array(2,"../admin/parler_mj.".$phpExtJeu,"Parler a un MJ","" ,$MJ->aDroit($liste_flags_mj["ParlerMJ"]))
		);

	array_push ($liens_menu,					
		array(1,"../admin/menu.$phpExtJeu?act=gestionNews",GetMessage("gestionNews"),"" ,(defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])) ||(defined("IN_FORUM") && (IN_FORUM==1)) )
	);
		
	if ($act=='gestionNews')			
		array_push ($liens_menu,
			//array(2,"../news/admin.".$phpExtJeu,"Gestion des news","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_csl_news.".$phpExtJeu,"Consultation des news","" , defined("IN_NEWS") && (IN_NEWS==1)),
			array(2,"../admin/news_add_news.".$phpExtJeu,"Ajouter une news","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_mod_news.".$phpExtJeu,"Modifier une news","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_del_news.".$phpExtJeu,"Supprimer une news","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_del_com.".$phpExtJeu,"Supprimer un commentaire","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_wrp_index.".$phpExtJeu,"Modification de index.html","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_wrp_archives.".$phpExtJeu,"Modification des archives.html","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_wrp_news.".$phpExtJeu,"Modification de news.html","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_wrp_comment.".$phpExtJeu,"Modification de comment.".$phpExtJeu,"" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../admin/news_wrp_coment_one.".$phpExtJeu,"Modification de coment_one.html","" , defined("IN_NEWS") && (IN_NEWS==1) && $MJ->aDroit($liste_flags_mj["Gestion News"])),
			array(2,"../main/forum.$phpExtJeu?admin=1","Gestion du forum","" ,($MJ->ID==1) && defined("IN_FORUM") && (IN_FORUM==1))		
		);

	array_push ($liens_menu,					
		array(1,"../admin/menu.$phpExtJeu?act=gestionQCM",GetMessage("gestionQCM"),"" ,($MJ->aDroit($liste_flags_mj["CreerQuestion"]) ||$MJ->aDroit($liste_flags_mj["ModifierQuestion"]) ||$MJ->aDroit($liste_flags_mj["SupprimerQuestion"])))
	);
	
	if ($act=='gestionQCM')			
		array_push ($liens_menu,
			array(2,"../admin/creer_question.".$phpExtJeu,"Creer Question","" ,$MJ->aDroit($liste_flags_mj["CreerQuestion"])),
			array(2,"../admin/modifier_question.".$phpExtJeu,"Modifier Question","" ,$MJ->aDroit($liste_flags_mj["ModifierQuestion"])),
			array(2,"../admin/supprimer_question.".$phpExtJeu,"Supprimer Question","" ,$MJ->aDroit($liste_flags_mj["SupprimerQuestion"])),
			array(2,"../admin/liste_qcm.".$phpExtJeu,"liste des questions","" ,1)
		);

	array_push ($liens_menu,					
		array(1,"../admin/menu.$phpExtJeu?act=gestionBoutique",GetMessage("gestionMagasins"),"" ,$MJ->aDroit($liste_flags_mj["ModifierMagasinMagique"])||
				$MJ->aDroit($liste_flags_mj["ModifierQuincaillerie"])||
				$MJ->aDroit($liste_flags_mj["ModifierArmurerie"])||
				$MJ->aDroit($liste_flags_mj["ModifierLieuCompetences"]) ||
				$MJ->aDroit($liste_flags_mj["ModifierProductionNaturelle"]) || 
				$MJ->aDroit($liste_flags_mj["listeMagasins"])
				)
	);

	if ($act=='gestionBoutique')
		array_push ($liens_menu,
			array(2,"../admin/modifier_mag_magique.".$phpExtJeu,"Modifier Magasin Magique","" ,$MJ->aDroit($liste_flags_mj["ModifierMagasinMagique"])),
			array(2,"../admin/modifier_quincaillerie.".$phpExtJeu,"Modifier Quincaillerie","" ,$MJ->aDroit($liste_flags_mj["ModifierQuincaillerie"])),
			array(2,"../admin/modifier_armurerie.".$phpExtJeu,"Modifier Armurerie","" ,$MJ->aDroit($liste_flags_mj["ModifierArmurerie"])),
			array(2,"../admin/modifier_lieuapprentissage.".$phpExtJeu,"Modifier Lieu de comp&eacute;tences","" ,$MJ->aDroit($liste_flags_mj["ModifierLieuCompetences"])),
			array(2,"../admin/modifier_productionNaturelle.".$phpExtJeu,"Modifier Lieu de produits Naturels","" ,$MJ->aDroit($liste_flags_mj["ModifierProductionNaturelle"])),
			array(2,"../admin/listeMagasin.".$phpExtJeu,"Liste des Magasins","" ,$MJ->aDroit($liste_flags_mj["listeMagasins"]))
		);
	

	array_push ($liens_menu,					
		array(1,"../admin/menu.$phpExtJeu?act=gestionChemin", GetMessage("gestionChemins"),"",$MJ->aDroit($liste_flags_mj["CreerChemin"])||
				$MJ->aDroit($liste_flags_mj["SupprimerChemin"])||
				$MJ->aDroit($liste_flags_mj["ModifierChemin"]) ||
				$MJ->aDroit($liste_flags_mj["RegistreLieux"]) 
				)
	);	
	
	if ($act=='gestionChemin')
		array_push ($liens_menu,
			array(2,"../admin/creer_chemin.".$phpExtJeu,"Creer Chemin","" ,$MJ->aDroit($liste_flags_mj["CreerChemin"])),
			array(2,"../admin/supprimer_chemin.".$phpExtJeu,"Supprimer Chemin","" ,$MJ->aDroit($liste_flags_mj["SupprimerChemin"])),
			array(2,"../admin/modifier_chemin.".$phpExtJeu,"Modifier Chemin","" ,$MJ->aDroit($liste_flags_mj["ModifierChemin"])),
			array(2,"../admin/registrechemin.".$phpExtJeu,"Table des Chemins","" ,$MJ->aDroit($liste_flags_mj["RegistreLieux"])),
			array(2,"../admin/creer_map.".$phpExtJeu,"Crer Fichier Graphviz","" ,$MJ->aDroit($liste_flags_mj["CreerCarte"])), 
			array(2,"../admin/voir_map.".$phpExtJeu,"Voir carte","" ,$MJ->aDroit($liste_flags_mj["VoirCarte"]) && file_exists("../lieux/vues/map.cmap") && file_exists("../lieux/vues/map.gif")) 
		);		
	
	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionEtat", GetMessage("gestionEtats"),"",$MJ->aDroit($liste_flags_mj["CreerEtat"])||
				$MJ->aDroit($liste_flags_mj["SupprimerEtat"])||
				$MJ->aDroit($liste_flags_mj["ModifierEtat"])||
				$MJ->aDroit($liste_flags_mj["listeEtats"])
				)
	);
	
	if ($act=="gestionEtat")
		array_push ($liens_menu,
			array(2,"../admin/creer_typeetat.".$phpExtJeu,"Creer Type d'Etat Temp","" ,$MJ->aDroit($liste_flags_mj["CreerEtat"])),
			array(2,"../admin/modifier_typeetat.".$phpExtJeu,"Modifier Type d'Etat Temp","" ,$MJ->aDroit($liste_flags_mj["CreerEtat"])),
			array(2,"../admin/supprimer_typeetat.".$phpExtJeu,"Supprimer Type d'Etat Temp","" ,$MJ->aDroit($liste_flags_mj["CreerEtat"])),
			array(2,"../admin/creer_etat.".$phpExtJeu,"Creer Etat Temp","" ,$MJ->aDroit($liste_flags_mj["CreerEtat"])),
			array(2,"../admin/creer_etatCarac.".$phpExtJeu,"Creer Etats des carac","" ,$MJ->aDroit($liste_flags_mj["CreerEtat"])),
			array(2,"../admin/supprimer_etat.".$phpExtJeu,"Supprimer Etat Temp","",$MJ->aDroit($liste_flags_mj["SupprimerEtat"])),
			array(2,"../admin/modifier_etat.".$phpExtJeu,"Modifier Etat Temp","" ,$MJ->aDroit($liste_flags_mj["ModifierEtat"])),
			array(2,"../admin/listeetat.".$phpExtJeu,"Table des Etats Temps","" ,$MJ->aDroit($liste_flags_mj["listeEtats"]))			
		);		
	
	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionLieu",GetMessage("gestionLieux"),"" ,$MJ->aDroit($liste_flags_mj["CreerLieu"])||
				$MJ->aDroit($liste_flags_mj["SupprimerLieu"])||
				$MJ->aDroit($liste_flags_mj["ModifierLieu"])||
				$MJ->aDroit($liste_flags_mj["ModifierDescLieu"])||
				$MJ->aDroit($liste_flags_mj["ParlerLieu"])||
				$MJ->aDroit($liste_flags_mj["VoirLieu"])||
				$MJ->aDroit($liste_flags_mj["RegistreLieux"])
				)
	);			
	
	if ($act=="gestionLieu")
		array_push ($liens_menu,
			array(2,"../admin/creer_lieu.".$phpExtJeu,"Creer Lieu","" ,$MJ->aDroit($liste_flags_mj["CreerLieu"])),
			array(2,"../admin/supprimer_lieu.".$phpExtJeu,"Supprimer Lieu","" ,$MJ->aDroit($liste_flags_mj["SupprimerLieu"])),
			array(2,"../admin/modifier_lieu.".$phpExtJeu,"Modifier Lieu","" ,$MJ->aDroit($liste_flags_mj["ModifierLieu"])),
			array(2,"../admin/modifier_desc_lieu.".$phpExtJeu,"Modifier Desc Lieu","" ,$MJ->aDroit($liste_flags_mj["ModifierDescLieu"])),
			array(2,"../admin/parler_lieu.".$phpExtJeu,"Parler a un Lieu","" ,$MJ->aDroit($liste_flags_mj["ParlerLieu"])),
			array(2,"../admin/voir_lieu.".$phpExtJeu,"Voir un Lieu","" ,$MJ->aDroit($liste_flags_mj["VoirLieu"])),
			array(2,"../admin/registrelieux.".$phpExtJeu,"Table des Lieux","" ,$MJ->aDroit($liste_flags_mj["RegistreLieux"])),
			array(2,"../admin/ApparitionMonstre.".$phpExtJeu,"Apparition de monstre","" ,$MJ->aDroit($liste_flags_mj["InscrirePJ"]))
	);		
	
	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionObjet", GetMessage("gestionOBJ"),"",$MJ->aDroit($liste_flags_mj["CreerObjet"])||
				$MJ->aDroit($liste_flags_mj["SupprimerObjet"])||
				$MJ->aDroit($liste_flags_mj["ModifierObjet"])||
				$MJ->aDroit($liste_flags_mj["listeObjets"])
				)
	);			

	if ($act=="gestionObjet") 
		array_push ($liens_menu,		
			array(2,"../admin/creer_objet.".$phpExtJeu,"Creer Objet","" ,$MJ->aDroit($liste_flags_mj["CreerObjet"])),
			array(2,"../admin/supprimer_objet.".$phpExtJeu,"Supprimer Objet","" ,$MJ->aDroit($liste_flags_mj["SupprimerObjet"])),
			array(2,"../admin/modifier_objet.".$phpExtJeu,"Modifier Objet","" ,$MJ->aDroit($liste_flags_mj["ModifierObjet"])),
			array(2,"../admin/cacher_objet.".$phpExtJeu,"Placer/Cacher Objet","" ,$MJ->aDroit($liste_flags_mj["ModifierObjet"])),
			array(2,"../admin/listeobjet.".$phpExtJeu,"Table des objets","" ,$MJ->aDroit($liste_flags_mj["listeObjets"])),
			array(2,"../admin/creer_Livre.".$phpExtJeu,"Crer les livres","" ,$MJ->aDroit($liste_flags_mj["CreerObjet"])),
			array(2,"../admin/creer_Armures.".$phpExtJeu,"Crer les armures","" ,$MJ->aDroit($liste_flags_mj["CreerObjet"])),
			array(2,"../admin/creer_Munitions.".$phpExtJeu,"Crer les munitions","" ,$MJ->aDroit($liste_flags_mj["CreerObjet"]))
	);	
	
	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionSort", GetMessage("gestionMagie"),"",$MJ->aDroit($liste_flags_mj["CreerMagie"])||
				$MJ->aDroit($liste_flags_mj["SupprimerMagie"])||
				$MJ->aDroit($liste_flags_mj["ModifierMagie"])||
				$MJ->aDroit($liste_flags_mj["listeSorts"])
				)
	);		

	 if ($act=='gestionSort')	
		array_push ($liens_menu,		
			array(2,"../admin/creer_magie.".$phpExtJeu,"Creer Sort","" ,$MJ->aDroit($liste_flags_mj["CreerMagie"])),
			array(2,"../admin/supprimer_magie.".$phpExtJeu,"Supprimer Sort","" ,$MJ->aDroit($liste_flags_mj["SupprimerMagie"])),
			array(2,"../admin/modifier_magie.".$phpExtJeu,"Modifier Sort","" ,$MJ->aDroit($liste_flags_mj["ModifierMagie"])),
			array(2,"../admin/listesort.".$phpExtJeu,"Table des sorts","" ,$MJ->aDroit($liste_flags_mj["listeSorts"])),
			array(2,"../admin/creer_Sorts.".$phpExtJeu,"Crer des sorts de test","" ,$MJ->aDroit($liste_flags_mj["CreerMagie"]))
	);
	
	
		
	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionSpec","Gestion des Spcialisations","" ,$MJ->aDroit($liste_flags_mj["CreerSpec"])||
				$MJ->aDroit($liste_flags_mj["SupprimerSpec"])||
				$MJ->aDroit($liste_flags_mj["ModifierSpec"])||
				$MJ->aDroit($liste_flags_mj["listeSpecs"])
				)
	);
	

	 if ($act=="gestionSpec")	
		array_push ($liens_menu,		
			array(2,"../admin/creer_spec.".$phpExtJeu,"Creer Spec","" ,$MJ->aDroit($liste_flags_mj["CreerSpec"])),
			array(2,"../admin/supprimer_spec.".$phpExtJeu,"Supprimer Spec","" ,$MJ->aDroit($liste_flags_mj["SupprimerSpec"])),
			array(2,"../admin/modifier_spec.".$phpExtJeu,"Modifier Spec","" ,$MJ->aDroit($liste_flags_mj["ModifierSpec"]))
		);


	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionQuete",GetMessage("gestionQuetes"),"" ,$MJ->aDroit($liste_flags_mj["CreerQuete"])||
				$MJ->aDroit($liste_flags_mj["SupprimerQuete"])||
				$MJ->aDroit($liste_flags_mj["ModifierQuete"])||
				$MJ->aDroit($liste_flags_mj["listeQuetes"])
				)
	);

	 if ($act=="gestionQuete")	
		array_push ($liens_menu,		
			array(2,"../admin/creer_quete.".$phpExtJeu,"Creer Quete","" ,$MJ->aDroit($liste_flags_mj["CreerQuete"])),
			array(2,"../admin/supprimer_quete.".$phpExtJeu,"Supprimer Quete","" ,$MJ->aDroit($liste_flags_mj["SupprimerQuete"])),
			array(2,"../admin/modifier_quete.".$phpExtJeu,"Modifier Quete","" ,$MJ->aDroit($liste_flags_mj["ModifierQuete"])),
			array(2,"../admin/listequete.".$phpExtJeu,"Liste des Quetes","" ,$MJ->aDroit($liste_flags_mj["listeQuetes"])),
			array(2,"../admin/historique_quete.".$phpExtJeu,"Detail des Quetes","" ,$MJ->aDroit($liste_flags_mj["listeQuetes"])),
			array(2,"../admin/creer_Quetes.".$phpExtJeu,"Creer Jeu de tests Quetes","" ,$MJ->aDroit($liste_flags_mj["CreerQuete"]))
		);

	array_push ($liens_menu,
		array(1,"../admin/menu.$phpExtJeu?act=gestionLogs",GetMessage("gestionLogs"),"" ,($MJ->aDroit($liste_flags_mj["PurgerLogs"]) || $MJ->aDroit($liste_flags_mj["VoirLogs"])) && defined("FICHIER_LOG") && file_exists(FICHIER_LOG)
				)
	);
	
	 if ($act=="gestionLogs")	
		array_push ($liens_menu,		
                        array(2,"../admin/voirFichierLog.".$phpExtJeu,GetMessage("voirLogs"),"" ,($MJ->aDroit($liste_flags_mj["VoirLogs"])) && defined("FICHIER_LOG") && file_exists(FICHIER_LOG) ),
	        	array(2,"../admin/purgeFichierLog.".$phpExtJeu,GetMessage("purgerLogs"),"" ,($MJ->aDroit($liste_flags_mj["PurgerLogs"])) && defined("FICHIER_LOG") && file_exists(FICHIER_LOG) )
		);
						
	array_push ($liens_menu,					
		array(1,"../admin/creerGuilde.".$phpExtJeu,"Cr&eacute;er une guilde","" ,$MJ->aDroit($liste_flags_mj["CreerLieu"]) && $MJ->aDroit($liste_flags_mj["CreerChemin"]) && (defined("IN_FORUM") && (IN_FORUM==1))),
		array(1,"../admin/scripts_SA.".$phpExtJeu,"Scripts Divers","" ,$MJ->ID ==1),
		array(1,"../admin/chargeDansTable.".$phpExtJeu,"Chargement de donnes","" ,$MJ->ID ==1),
		array(1,"../admin/logout.".$phpExtJeu,"Deconnexion","" ,-1)
	);

		
	include('../include/menu.template.'.$phpExtJeu);

if(!defined("__BARREGENERALE.PHP"))
	{include("../include/barregenerale.".$phpExtJeu);}

}
?>