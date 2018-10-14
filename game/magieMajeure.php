<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: magieMajeure.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2010/01/24 17:44:02 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $magiemaj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	

if(!isset($etape)){
	$opt = false;
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	if($PERSO->PossedeSortOffensif(2)){
		$SQL=$PERSO->listeSorts(array(), array('Paralysie','Attaque','Transfert'),2);
		$var= faitSelect("id2_sort_att",$SQL,"");
		if ($var[0]>0) {
			$SQL = $PERSO->listeLieuxProches();
			$var1=faitSelect("id2_cible_att",$SQL,"",null,array(),array(array($PERSO->Lieu->ID.$sep.$PERSO->Lieu->trigramme .'-'.$PERSO->Lieu->nom, $PERSO->Lieu->trigramme .'-'.$PERSO->Lieu->nom)));
			if ($var1[0]>0) {
				$template_main .= "<input type='radio' name='typeact' value='attaque2' />Lancer le sort offensif de zone";
				$template_main .= $var[1];
				$template_main .= " Sur ";
				$template_main .= $var1[1];		
				$template_main .= "<br /><hr /><br />";
				$opt=true;
			} 
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id2_sort_att' value='' />";
		$template_main .= "<input type='hidden' name='id2_cible_att' value='' />";
	}

	if($PERSO->PossedeSortSoin(2)){
		$SQL = $PERSO->listeLieuxProches();
		$var1=faitSelect("id2_cible_soin",$SQL,"",null,array(),array(array($PERSO->Lieu->ID.$sep.$PERSO->Lieu->trigramme .'-'.$PERSO->Lieu->nom, $PERSO->Lieu->trigramme .'-'.$PERSO->Lieu->nom)));
		if ($var1[0]>0) {
			$template_main .= "<input type='radio' name='typeact' value='soin2' />Lancer le sort de soin de zone ";
			$SQL=$PERSO->listeSorts(array(), array('Soin'),2);
			$var=faitSelect("id2_sort_soin",$SQL,"");
			$template_main .= $var[1];
			$template_main .= " Sur ";
			$template_main .= $var1[1];
			$template_main .= "<br /><hr /><br />";
			$opt = true;
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id2_sort_soin' value='' />";
		$template_main .= "<input type='hidden' name='id2_cible_soin' value='' />";
	}
	
	if($PERSO->PossedeSortTeleport(2)){
		$SQL = $PERSO->listeLieuxProches();
		$var1=faitSelect("id2_cible_tel",$SQL,"");
		if ($var1[0]>0) {
			$opt=true;
			$template_main .= "<input type='radio' name='typeact' value='teleport2' />Lancer le sort de teleportation de zone";
			$SQL=$PERSO->listeSorts(array(), array('Teleport'),2);
			$var=faitSelect("id2_sort_tel",$SQL,"");
			$template_main .= $var[1];
			$template_main .= " Sur ";
			$template_main .= $var1[1];
			$template_main .= " Pour l'envoyer &agrave; ";
			$SQL = "Select T1.id2_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 WHERE T1.accessible_telp = 1 ORDER BY trigramme, nom";
			$var=faitSelect("id2_lieu_tel",$SQL,"",$PERSO->Lieu->ID);
			$template_main .= $var[1];
			$template_main .= "<br /><hr /><br />";
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id2_sort_tel' value='' />";
		$template_main .= "<input type='hidden' name='id2_cible_tel' value='' />";
		$template_main .= "<input type='hidden' name='id2_lieu_tel' value='' />";
	}
	
	/* Pas de sort distant pour le moment
	if($PERSO->PossedeSortOffensif(3)){
		$SQL=$PERSO->listeSorts(array(), array('Paralysie','Attaque','Transfert'),3);
		$var= faitSelect("id3_sort_att",$SQL,"");
		if ($var[0]>0) {
			$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE."  P WHERE  P.pv>0 and P.archive=0 and P.id_perso <> $PERSO->ID";
			$var1=faitSelect("id3_cible_att",$SQL,"");
			if ($var1[0]>0) {
				$template_main .= "<input type='radio' name='typeact' value='attaque3' />Lancer le sort offensif à distance";
				$template_main .= $var[1];
				$template_main .= " Sur ";
				$template_main .= $var1[1];		
				$template_main .= "<br /><hr /><br />";
				$opt=true;
			} 
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id3_sort_att' value='' />";
		$template_main .= "<input type='hidden' name='id3_cible_att' value='' />";
	}

	if($PERSO->PossedeSortSoin(3)){
		$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE."  P WHERE  P.pv>0 and P.archive=0";		$var1=faitSelect("id3_cible_soin",$SQL,"");
		if ($var1[0]>0) {
			$template_main .= "<input type='radio' name='typeact' value='soin3' />Lancer le sort de soin à distance";
			$SQL=$PERSO->listeSorts(array(), array('Soin'),3);
			$var=faitSelect("id3_sort_soin",$SQL,"");
			$template_main .= $var[1];
			$template_main .= " Sur ";
			$template_main .= $var1[1];
			$template_main .= "<br /><hr /><br />";
			$opt = true;
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id3_sort_soin' value='' />";
		$template_main .= "<input type='hidden' name='id3_cible_soin' value='' />";
	}
	
	if($PERSO->PossedeSortTeleport(3)){
		$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE."  P WHERE  P.pv>0 and P.archive=0";
		$var1=faitSelect("id3_cible_tel",$SQL,"");
		if ($var1[0]>0) {
			$opt=true;
			$template_main .= "<input type='radio' name='typeact' value='teleport3' />Lancer le sort de teleportation à distance";
			$SQL=$PERSO->listeSorts(array(), array('Teleport'),3);
			$var=faitSelect("id3_sort_tel",$SQL,"");
			$template_main .= $var[1];
			$template_main .= " Sur ";
			$template_main .= $var1[1];
			$template_main .= " Pour l'envoyer &agrave; ";
			$SQL = "Select T1.id3_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 WHERE T1.accessible_telp = 1 ORDER BY trigramme, nom";
			$var=faitSelect("id3_lieu_tel",$SQL,"",$PERSO->Lieu->ID);
			$template_main .= $var[1];
			$template_main .= "<br /><hr /><br />";
		}
	}
	else {
		$template_main .= "<input type='hidden' name='id3_sort_tel' value='' />";
		$template_main .= "<input type='hidden' name='id3_cible_tel' value='' />";
		$template_main .= "<input type='hidden' name='id3_lieu_tel' value='' />";
	}
	*/		
	$template_main .= "<input type='hidden' name='id2_lieu_autotel' value='' />";		
	$template_main .= "<input type='hidden' name='id2_sort_autotel' value='' />";		
	$template_main .= "<input type='hidden' name='id3_lieu_autotel' value='' />";		
	$template_main .= "<input type='hidden' name='id3_sort_autotel' value='' />";		

	
	if($opt) {$template_main .= BOUTON_ENVOYER;}
	else {$template_main .= "vous ne possedez aucun sort permettant de faire de la magie majeure";}
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	$etape=0;
	$temp = str_split($typeact, strlen($typeact)-1);	
	$typeact = $temp[0];
	logdate("typeact" . $typeact);
	if ($temp[1]=="2")
		magieZoneLocal ($PERSO,$typeact,$id2_sort_att,$id2_cible_att,$id2_sort_soin,$id2_cible_soin,$id2_sort_tel,$id2_cible_tel, $id2_lieu_tel,$id2_sort_autotel, $id2_lieu_autotel, false,true,false);
	else 	magieZoneLocal ($PERSO,$typeact,$id3_sort_att,$id3_cible_att,$id3_sort_soin,$id3_cible_soin,$id3_sort_tel,$id3_cible_tel, $id3_lieu_tel,$id3_sort_autotel, $id3_lieu_autotel, false,true,false);
	$template_main .= "<br /><p>&nbsp;</p>";

}

	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>