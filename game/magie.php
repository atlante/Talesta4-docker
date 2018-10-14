<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: magie.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 17:44:02 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $magie;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	

if(!isset($etape)){
	$opt = false;
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	if($PERSO->PossedeSortOffensif()){
		$SQL=$PERSO->listeSorts(array(), array('Paralysie','Attaque','Transfert'));
		$var= faitSelect("id_sort_att",$SQL,"");
		if ($var[0]>0) {
			$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true,1);
			$var1=faitSelect("id_cible_att",$SQL,"");
			if ($var1[0]>0) {
				$template_main .= "<input type='radio' name='typeact' value='attaque' />Lancer le sort offensif ";
				$template_main .= $var[1];
				$template_main .= " Sur ";
				$template_main .= $var1[1];		
				$template_main .= "<br /><hr /><br />";
				$opt=true;
			} 
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id_sort_att' value='' />";
		$template_main .= "<input type='hidden' name='id_cible_att' value='' />";
	}

	if($PERSO->PossedeSortSoin()){
		$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true, 0);
		$var1=faitSelect("id_cible_soin",$SQL,"");
		if ($var1[0]>0) {
			$template_main .= "<input type='radio' name='typeact' value='soin' />Lancer le sort de soin ";
			$SQL=$PERSO->listeSorts(array(), array('Soin'));
			$var=faitSelect("id_sort_soin",$SQL,"");
			$template_main .= $var[1];
			$template_main .= " Sur ";
			$template_main .= $var1[1];
			$template_main .= "<br /><hr /><br />";
			$opt = true;
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id_sort_soin' value='' />";
		$template_main .= "<input type='hidden' name='id_cible_soin' value='' />";
	}
	
	if($PERSO->PossedeSortResurrection()){
		$SQL = $PERSO->listePJsDuLieuDuPerso(-1, false, true, 0);
		$var1=faitSelect("id_cible_resurrection",$SQL,"");
		if ($var1[0]>0) {
			$template_main .= "<input type='radio' name='typeact' value='Resurrection' />Lancer le sort de resurrection ";
			$SQL=$PERSO->listeSorts(array(), array('Resurrection'));
			$var=faitSelect("id_sort_resurrection",$SQL,"");
			$template_main .= $var[1];
			$template_main .= " Sur ";
			$template_main .= $var1[1];
			$template_main .= "<br /><hr /><br />";
			$opt = true;
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id_sort_resurrection' value='' />";
		$template_main .= "<input type='hidden' name='id_cible_resurrection' value='' />";
	}	
	if($PERSO->PossedeSortTeleport()){
		$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true, 0);		
		$var1=faitSelect("id_cible_tel",$SQL,"");
		if ($var1[0]>0) {
			$opt=true;
			$template_main .= "<input type='radio' name='typeact' value='teleport' />Lancer le sort de teleportation ";
			$SQL=$PERSO->listeSorts(array(), array('Teleport'));
			$var=faitSelect("id_sort_tel",$SQL,"");
			$template_main .= $var[1];
			$template_main .= " Sur ";
			$template_main .= $var1[1];
			$template_main .= " Pour l'envoyer &agrave; ";
			$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 WHERE T1.accessible_telp = 1 ORDER BY trigramme, nom";
			$var=faitSelect("id_lieu_tel",$SQL,"",$PERSO->Lieu->ID);
			$template_main .= $var[1];
			$template_main .= "<br /><hr /><br />";
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id_sort_tel' value='' />";
		$template_main .= "<input type='hidden' name='id_cible_tel' value='' />";
		$template_main .= "<input type='hidden' name='id_lieu_tel' value='' />";
	}
			
	if($PERSO->PossedeSortTeleportSelf()){
		$opt=true;
		$template_main .= "<input type='radio' name='typeact' value='autoteleport' />Se teleporter en ";
		$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 WHERE T1.accessible_telp = 1 ORDER BY trigramme, nom";
		$var=faitSelect("id_lieu_autotel",$SQL,"",$PERSO->Lieu->ID);
		$template_main .= $var[1];

		$template_main .= " grace au sort ";
		$SQL=$PERSO->listeSorts(array(), array('Teleport Self'));
		$var= faitSelect("id_sort_autotel",$SQL,"");
		$template_main .= $var[1];
		$template_main .= "<br /><hr /><br />";
	}
	else {
		$template_main .= "<input type='hidden' name='id_lieu_autotel' value='' />";		
		$template_main .= "<input type='hidden' name='id_sort_autotel' value='' />";		
	}
	
	if($opt){$template_main .= BOUTON_ENVOYER;}else{$template_main .= "vous ne possedez aucun sort permettant de faire de la magie Mineure";}
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	$etape=0;	
	if ($typeact=="Resurrection")
		$lancementValide=magie ($PERSO,$typeact,$id_sort_att,$id_cible_att,$id_sort_resurrection,$id_cible_resurrection,$id_sort_tel,$id_cible_tel, $id_lieu_tel,$id_sort_autotel, $id_lieu_autotel, false,true,false);
	else $lancementValide=magie ($PERSO,$typeact,$id_sort_att,$id_cible_att,$id_sort_soin,$id_cible_soin,$id_sort_tel,$id_cible_tel, $id_lieu_tel,$id_sort_autotel, $id_lieu_autotel, false,true,false);
	if ($lancementValide) {
		traceAction("Magie", $PERSO, $Sort->nom, $ADVERSAIRE,$typeact,  "");
	}	
	
	$template_main .= "<br /><p>&nbsp;</p>";

}

	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>