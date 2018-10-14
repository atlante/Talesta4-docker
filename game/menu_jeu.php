<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: menu_jeu.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.25 $
$Date: 2010/01/24 17:44:03 $

*/

require_once("../include/extension.inc");
if(!defined("__MENU_JEU.PHP") ) {
	Define("__MENU_JEU.PHP",	0);

	$script = basename($HTTP_SERVER_VARS['PHP_SELF']);	
	$liens_menu=array();
	if (!isset($act)) {
		switch ($script) {
			case "status.".$phpExtJeu:
			case "mod_info.".$phpExtJeu:
			case "archiver.".$phpExtJeu:
				$act='gestionCompte';
				break;
			case "fa.".$phpExtJeu:
			case "parler.".$phpExtJeu:			
			case "crier.".$phpExtJeu:			
			case "attaquer.".$phpExtJeu:
			case "fouillercadavre.".$phpExtJeu:			
			case "donner_objet.".$phpExtJeu:
			case "donner_argent.".$phpExtJeu:
			case "voler.".$phpExtJeu:
			case "magie.".$phpExtJeu:
			case "soin_objet.".$phpExtJeu:
			case "reveler_entitecachee.".$phpExtJeu:
			case "desengagement.".$phpExtJeu:
				$act='ActionsPJS';
				break;				
			case "dep_lx.".$phpExtJeu:
			case "manger.".$phpExtJeu:
			case "lire.".$phpExtJeu:
			case "proposer.".$phpExtJeu:
			case "fouillerlieu.".$phpExtJeu:
			case "secacher.".$phpExtJeu:
			case "secacher.".$phpExtJeu:
			case "creer_groupe.".$phpExtJeu:
			case "entrer_groupe.".$phpExtJeu:
			case "quitter_groupe.".$phpExtJeu:
				$act='SelfAction';
				break;	
			case "oublier_sort.".$phpExtJeu:
			case "abandonner_objet.".$phpExtJeu:
			case "recuperer_objet.".$phpExtJeu:
			case "enleverarmure.".$phpExtJeu:
			case "mettrearmure.".$phpExtJeu:
			case "recharger_objet.".$phpExtJeu:
				$act='GestionInv';							
				break;			
	                case "voirQueteLieu.".$phpExtJeu:			
			case "repondreQuete.".$phpExtJeu:
			case "terminer_quete.".$phpExtJeu:
			case "gerer_quete_proposee.".$phpExtJeu:
				$act='GestionQuetes';							
				break;	
			case "combiner_objets.".$phpExtJeu:
			case "reparation.".$phpExtJeu:
			case "recolte.".$phpExtJeu:
			case "artisanat.".$phpExtJeu:
				$act='Artisanat';							
				break;			

			case "banque.".$phpExtJeu:
			case "armurerie.".$phpExtJeu:
			case "quincaillerie.".$phpExtJeu:
			case "magasinmag.".$phpExtJeu:
			case "apprendre.".$phpExtJeu:
				$act='Magasin';							
				break;	

			default:
				$act="";
		}	
	}	

	
	$liens_menu=array(	
		array(1,"../game/menu.".$phpExtJeu,"Situation","",-1),
		array(1,"../game/menu.".$phpExtJeu."?act=gestionCompte","Gestion du compte","" ,-1),
		array(2,"../game/status.".$phpExtJeu,"Fiche de Perso","" ,$act=='gestionCompte'),
		array(2,"../game/mod_info.".$phpExtJeu,"Modifier Infos","" ,$act=='gestionCompte'),
		array(2,"../game/archiver.".$phpExtJeu,"Archiver (".span("0","pa")."/".span("0","pi").")","" ,$act=='gestionCompte' && (!$PERSO->Archive)),		
		array(2,"../game/archiver.".$phpExtJeu,"D&eacute;sarchiver (".span("0","pa")."/".span("0","pi").")","" ,$act=='gestionCompte'&& $PERSO->Archive),		
		array(1,"../game/menu.".$phpExtJeu."?act=ActionsPJS","Actions avec les autres PJ","" ,true),
		array(2,"../game/fa.".$phpExtJeu,"Fichier d'Action","" , $act=='ActionsPJS'),
		array(2,"../game/parler.".$phpExtJeu,"Parler (".span(abs($liste_pas_actions["Parler"]),"pa")."/".span(abs($liste_pis_actions["Parler"]),"pi") .")","" ,$act=='ActionsPJS' && $PERSO->Lieu->permet($liste_flags_lieux["Parler"])),
		array(2,"../game/crier.".$phpExtJeu,"Crier (".span(abs($liste_pas_actions["Crier"]),"pa")."/".span(abs($liste_pis_actions["Crier"]),"pi") .")","" ,$act=='ActionsPJS' && $PERSO->Lieu->permet($liste_flags_lieux["EntendreCriExterieur"]) && $PERSO->Lieu->permet($liste_flags_lieux["Parler"])),
		array(2,"../game/attaquer.".$phpExtJeu,"Attaquer (".span(abs($liste_pas_actions["Attaquer"]),"pa")."/".span(abs($liste_pis_actions["Attaquer"]),"pi").")","" ,$act=='ActionsPJS' && $PERSO->Lieu->permet($liste_flags_lieux["Attaquer"])),
		array(2,"../game/desengagement.".$phpExtJeu,"Se désengager (".span(abs($liste_pas_actions["Attaquer"]),"pa")."/".span(abs($liste_pis_actions["Attaquer"]),"pi").")","",$act=='ActionsPJS' &&($PERSO->Engagement==1) && defined("ENGAGEMENT") && ENGAGEMENT==1),
		array(2,"../game/fouillercadavre.".$phpExtJeu,"Fouiller Cadavre (".span(abs($liste_pas_actions["FouillerCadavre"]),"pa")."/".span(abs($liste_pis_actions["FouillerCadavre"]),"pi").")","" ,$PERSO->Lieu->permet($liste_flags_lieux["FouillerCadavre"]) && $act=='ActionsPJS'),
		array(2,"../game/donner_objet.".$phpExtJeu,"Donner Objet (".span(abs($liste_pas_actions["DonnerObjet"]),"pa")."/".span(abs($liste_pis_actions["DonnerObjet"]),"pi").")","",$act=='ActionsPJS'),
		array(2,"../game/donner_argent.".$phpExtJeu,"Donner Argent (".span(abs($liste_pas_actions["DonnerArgent"]),"pa")."/".span(abs($liste_pis_actions["DonnerArgent"]),"pi").")","",$act=='ActionsPJS'),
		array(2,"../game/voler.".$phpExtJeu,"Voler un PJ (".span(abs($liste_pas_actions["VolerPJ"]),"pa")."/".span(abs($liste_pis_actions["VolerPJ"]),"pi").")","" ,$act=='ActionsPJS' && $PERSO->Lieu->permet($liste_flags_lieux["Voler"])),
		array(2,"../game/soin_objet.".$phpExtJeu,"Utiliser Objet sur PJ (".span(abs($liste_pas_actions["SoinObjet"]),"pa")."/".span(abs($liste_pis_actions["SoinObjet"]),"pi").")","" ,$act=='ActionsPJS' && $PERSO->Lieu->permet($liste_flags_lieux["SoignerAvecObjet"])),
		array(2,"../game/reveler_entitecachee.".$phpExtJeu,"Montrer cachette (".span(abs($liste_pas_actions["MontrerCachette"]),"pa")."/".span(abs($liste_pis_actions["MontrerCachette"]),"pi").")","" ,$act=='ActionsPJS' && ($PERSO->ConnaitLieuxSecrets || $PERSO->ConnaitObjetsSecrets || $PERSO->ConnaitPersosSecrets)),
		array(2,"../game/magie.".$phpExtJeu,"Magie (".span("X","pa")."/".span("X","pi").")","" ,$act=='ActionsPJS' && $PERSO->Lieu->permet($liste_flags_lieux["Magie"])),
		array(1,"../game/menu.".$phpExtJeu."?act=SelfAction","Actions sur soi","" ,-1),
		array(2,"../game/dep_lx.".$phpExtJeu,"Se Deplacer (".span("X","pa")."/".span("X","pi").")","",($PERSO->ConnaitLieuxSecrets ||$PERSO->Lieu->aChemin($liste_types_chemins["Lieu Guilde"])||$PERSO->Lieu->aChemin($liste_types_chemins["Lieu Escalader"])||$PERSO->Lieu->aChemin($liste_types_chemins["Lieu Nager"])||$PERSO->Lieu->aChemin($liste_types_chemins["Lieu Entrer"]) || $PERSO->Lieu->aChemin($liste_types_chemins["Lieu Passage"]) || $PERSO->Lieu->aChemin($liste_types_chemins["Lieu Aller"]) || $PERSO->Lieu->aChemin($liste_types_chemins["Lieu Peage"])) && $act=='SelfAction'),
		array(2,"../game/manger.".$phpExtJeu,"Se Nourrir (".span(abs($liste_pas_actions["Manger"]),"pa")."/".span(abs($liste_pis_actions["Manger"]),"pi").")","" ,$act=='SelfAction' && $PERSO->Lieu->permet($liste_flags_lieux["Manger"])),
		array(2,"../game/lire.".$phpExtJeu,"Lire un Livre (".span(abs($liste_pas_actions["Lire"]),"pa")."/".span(abs($liste_pis_actions["Lire"]),"pi").")","" ,$act=='SelfAction' && $PERSO->Lieu->permet($liste_flags_lieux["Lire"])),
		array(2,"../game/proposer.".$phpExtJeu,"Proposer Action (".span("X","pa")."/".span("X","pi").")","",$act=='SelfAction' ),
		array(2,"../game/fouillerlieu.".$phpExtJeu,"Fouiller Lieu (".span(abs($liste_pas_actions["FouillerLieu"]),"pa")."/".span(abs($liste_pis_actions["FouillerLieu"]),"pi").")","" ,$act=='SelfAction' && $PERSO->Lieu->permet($liste_flags_lieux["FouillerLieu"])),
		array(2,"../game/secacher.".$phpExtJeu,"Se Cacher (".span(abs($liste_pas_actions["SeCacher"]),"pa")."/".span(abs($liste_pis_actions["SeCacher"]),"pi").")","" , defined("SECACHER") &&  SECACHER==1 && $PERSO->Lieu->permet($liste_flags_lieux["SeCacher"])&& ($PERSO->dissimule==0) && $act=='SelfAction' ),
		array(2,"../game/secacher.".$phpExtJeu,"Sortir de l'ombre (".span(abs(0),"pa")."/".span(abs(0),"pi").")","" , ($PERSO->dissimule==1) && $act=='SelfAction' ),
		array(2,"../game/creer_groupe.".$phpExtJeu,"Cr&eacute;er un groupe (".span("0","pa")."/".span("0","pi").")","" ,($PERSO->Groupe=="") && $act=='SelfAction' && (defined("GROUPE_PJS") && GROUPE_PJS==1)),
		array(2,"../game/entrer_groupe.".$phpExtJeu,"Entrer dans un groupe (".span("0","pa")."/".span("0","pi").")","" ,($PERSO->Groupe=="")&& $act=='SelfAction' && (defined("GROUPE_PJS") && GROUPE_PJS==1)),
		array(2,"../game/quitter_groupe.".$phpExtJeu,"Quitter groupe (".span("0","pa")."/".span("0","pi").")","" ,($PERSO->Groupe<>"")&& $act=='SelfAction' && (defined("GROUPE_PJS") && GROUPE_PJS==1)),
		array(1,"../game/menu.".$phpExtJeu."?act=GestionInv","Gestion de l'inventaire et du grimoire","" ,-1),
		array(2,"../game/oublier_sort.".$phpExtJeu,"Oublier Sort (".span(abs($liste_pas_actions["OublierSort"]),"pa")."/".span(abs($liste_pis_actions["OublierSort"]),"pi").")","",$act=='GestionInv'),
		array(2,"../game/abandonner_objet.".$phpExtJeu,"Laisser Objet(".span(abs($liste_pas_actions["AbandonnerObjet"]),"pa")."/".span(abs($liste_pis_actions["AbandonnerObjet"]),"pi").")","",$act=='GestionInv'),
		array(2,"../game/recuperer_objet.".$phpExtJeu,"Ramasser Objet(".span(abs($liste_pas_actions["AbandonnerObjet"]),"pa")."/".span(abs($liste_pis_actions["AbandonnerObjet"]),"pi").")","",$act=='GestionInv'),
		array(2,"../game/enleverarmure.".$phpExtJeu,"Enlever Arme/Armure (".span(abs($liste_pas_actions["EnleverArmure"]),"pa")."/".span(abs($liste_pis_actions["EnleverArmure"]),"pi").")","" ,$act=='GestionInv'),
		array(2,"../game/mettrearmure.".$phpExtJeu,"Mettre Arme/Armure (".span(abs($liste_pas_actions["MettreArmure"]),"pa")."/".span(abs($liste_pis_actions["MettreArmure"]),"pi").")","" ,$act=='GestionInv'),
		array(2,"../game/recharger_objet.".$phpExtJeu,"Recharger Arme (".span(abs($liste_pas_actions["RechargerObjet"]),"pa")."/".span(abs($liste_pis_actions["RechargerObjet"]),"pi").")","" ,$act=='GestionInv'),
		array(1,"../game/menu.".$phpExtJeu."?act=GestionQuetes","Gestion des quetes","" ,-1),
		array(2,"../game/voirQueteLieu.".$phpExtJeu,"Repondre à une annonce (".span("0","pa")."/".span("0","pi").")","" ,$act=='GestionQuetes' && $PERSO->Lieu->possedeQuetesPubliquesDispos()),
		array(2,"../game/repondreQuete.".$phpExtJeu,"Repondre à une quete (".span("0","pa")."/".span("0","pi").")","" ,$act=='GestionQuetes'),
		array(2,"../game/terminer_quete.".$phpExtJeu,"Terminer une quete (".span("0","pa")."/".span("0","pi").")","" ,$act=='GestionQuetes'),
		array(2,"../game/gerer_quete_proposee.".$phpExtJeu,"Gérer vos offres(".span("0","pa")."/".span("0","pi").")","" ,$act=='GestionQuetes' && $PERSO->pnj==1),
		array(1,"../game/menu.".$phpExtJeu."?act=Artisanat","Artisanat","" ,-1),
		
		array(2,"../game/combiner_objets.".$phpExtJeu,"Combiner objets (".span(abs($liste_pas_actions["CombinerObjets"]),"pa")."/".span(abs($liste_pis_actions["CombinerObjets"]),"pi").")","",$act=='Artisanat'),
		array(2,"../game/reparation.".$phpExtJeu."?action=ArmeMelee","Réparer une arme de mêlée (".span(abs($liste_pas_actions["ReparerObjet"]),"pa")."/".span(abs($liste_pis_actions["ReparerObjet"]),"pi").")","",$act=='Artisanat'),
		array(2,"../game/reparation.".$phpExtJeu."?action=ArmeJet","Réparer une arme de jet (".span(abs($liste_pas_actions["ReparerObjet"]),"pa")."/".span(abs($liste_pis_actions["ReparerObjet"]),"pi").")","",$act=='Artisanat'),
		array(2,"../game/reparation.".$phpExtJeu."?action=Armure","Réparer une armure (".span(abs($liste_pas_actions["ReparerObjet"]),"pa")."/".span(abs($liste_pis_actions["ReparerObjet"]),"pi").")","",$act=='Artisanat'),
		array(2,"../game/recolte.".$phpExtJeu."?action=Miner","Miner (".span(abs($liste_pas_actions["Miner"]),"pa")."/".span(abs($liste_pis_actions["Miner"]),"pi").")","",$PERSO->Lieu->aMagasin($liste_types_magasins["Produits Naturels"],array("Metal")) && $act=='Artisanat'),
		array(2,"../game/recolte.".$phpExtJeu."?action=Cueillir","Cuellir (".span(abs($liste_pas_actions["Cueillir"]),"pa")."/".span(abs($liste_pis_actions["Cueillir"]),"pi").")","",$PERSO->Lieu->aMagasin($liste_types_magasins["Produits Naturels"],array("Vegetaux","Nourriture","Dopant","Stimulant","Consistant","Vitaminant","Revigorant","Rare")) && $act=='Artisanat'),
		array(2,"../game/recolte.".$phpExtJeu."?action=Scier","Scier (".span(abs($liste_pas_actions["Scier"]),"pa")."/".span(abs($liste_pis_actions["Scier"]),"pi").")","",$PERSO->Lieu->aMagasin($liste_types_magasins["Produits Naturels"],array("Bois")) && $act=='Artisanat'),
		array(2,"../game/recolte.".$phpExtJeu."?action=Pierre","Carriere (".span(abs($liste_pas_actions["Carriere"]),"pa")."/".span(abs($liste_pis_actions["Carriere"]),"pi").")","",$PERSO->Lieu->aMagasin($liste_types_magasins["Produits Naturels"],array("Pierre")) && $act=='Artisanat'),
		array(2,"../game/artisanat.".$phpExtJeu."?action=Ebenisterie","Artisan:Bois (".span(abs($liste_pas_actions["CreerObjet"]),"pa")."/".span(abs($liste_pis_actions["CreerObjet"]),"pi").")","", $act=='Artisanat'),
		array(2,"../game/artisanat.".$phpExtJeu."?action=Metallurgie","Artisan:Metal (".span(abs($liste_pas_actions["CreerObjet"]),"pa")."/".span(abs($liste_pis_actions["CreerObjet"]),"pi").")","", $act=='Artisanat'),
		array(2,"../game/artisanat.".$phpExtJeu."?action=Maconnerie","Artisan:Pierre (".span(abs($liste_pas_actions["CreerObjet"]),"pa")."/".span(abs($liste_pis_actions["CreerObjet"]),"pi").")","", $act=='Artisanat'),
		array(2,"../game/artisanat.".$phpExtJeu."?action=Tissage","Artisan:Tissage (".span(abs($liste_pas_actions["CreerObjet"]),"pa")."/".span(abs($liste_pis_actions["CreerObjet"]),"pi").")","", $act=='Artisanat'),
		array(2,"../game/artisanat.".$phpExtJeu."?action=Brasserie","Artisan:Brasserie (".span(abs($liste_pas_actions["CreerObjet"]),"pa")."/".span(abs($liste_pis_actions["CreerObjet"]),"pi").")","", $act=='Artisanat'),
		array(2,"../game/artisanat.".$phpExtJeu."?action=Cuir","Artisan:Cuir (".span(abs($liste_pas_actions["CreerObjet"]),"pa")."/".span(abs($liste_pis_actions["CreerObjet"]),"pi").")","", $act=='Artisanat'),
		array(1,"../game/menu.".$phpExtJeu."?act=Magasin","Achats/Dépenses","" ,$PERSO->Lieu->aMagasin($liste_types_magasins["Lieu d'apprentissage"])||$PERSO->Lieu->aMagasin($liste_types_magasins["Magasin Magique"]) || $PERSO->Lieu->aMagasin($liste_types_magasins["Magasin Magique-Recharge"])
			|| $PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie"]) || $PERSO->Lieu->aMagasin($liste_types_magasins["Quincaillerie"])|| $PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie-Repare"]) || $PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie-Recharge"]) ||$PERSO->Lieu->permet($liste_flags_lieux["Banque"]) ),
		array(2,"../game/banque.".$phpExtJeu,"Banque (".span(abs($liste_pas_actions["Banque"]),"pa")."/".span(abs($liste_pis_actions["Banque"]),"pi").")","" ,$PERSO->Lieu->permet($liste_flags_lieux["Banque"])&& $act=="Magasin"),
		array(2,"../game/armurerie.".$phpExtJeu,"Armurerie (".span(abs($liste_pas_actions["Armurerie"]),"pa")."/".span(abs($liste_pis_actions["Armurerie"]),"pi").")","" ,($PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie"]) || $PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie-Repare"]) || $PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie-Recharge"]))&& $act=="Magasin"),
		array(2,"../game/quincaillerie.".$phpExtJeu,"Quincaillerie (".span(abs($liste_pas_actions["Quincaillerie"]),"pa")."/".span(abs($liste_pis_actions["Quincaillerie"]),"pi").")","" ,$PERSO->Lieu->aMagasin($liste_types_magasins["Quincaillerie"])&& $act=="Magasin"),
		array(2,"../game/magasinmag.".$phpExtJeu,"Magasin Magique (".span(abs($liste_pas_actions["MagasinMagique"]),"pa")."/".span(abs($liste_pis_actions["MagasinMagique"]),"pi").")","" ,( $PERSO->Lieu->aMagasin($liste_types_magasins["Magasin Magique"]) || $PERSO->Lieu->aMagasin($liste_types_magasins["Magasin Magique-Recharge"]))&& $act=="Magasin" ),
		array(2,"../game/apprendre.".$phpExtJeu,"Apprendre (".span("X","pa")."/".span("X","pi").")","", $PERSO->Lieu->aMagasin($liste_types_magasins["Lieu d'apprentissage"])&& $act=="Magasin"),
		array(1,'../game/logout.'.$phpExtJeu,"Deconnexion","", -1),
	);
	
	if ($PERSO->roleMJ!="")
		include('../admin/menu_admin.'.$phpExtJeu);
	
	include('../include/menu.template.'.$phpExtJeu);


}


if(!defined("__BARRESTATUS.PHP")){
	include("../game/barrestatus.".$phpExtJeu);}
?>