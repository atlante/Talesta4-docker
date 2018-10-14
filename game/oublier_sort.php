<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: oublier_sort.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.18 $
$Date: 2006/04/18 11:07:53 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $oublier_sort;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	

if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	
	$SQL=$PERSO->listeSorts(array(), array());
	$var= faitSelect("id_sort",$SQL,"");
	if ($var[0]>0) {
		$template_main .= "Quel sort voulez vous oublier ?<br />".$var[1];
		$template_main .= "<br /><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer l\'objet selectiionn&eacute; (irreversible) ?')\" />";
	} else $template_main .= "Vous n'avez aucun sort m&eacute;moris&eacute; <br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if( (isset($id_sort)) && ($PERSO->ModPA($liste_pas_actions["OublierSort"])) && ($PERSO->ModPI($liste_pis_actions["OublierSort"]))){
		$Sort = null;
		$sortir = $PERSO->etreCache(0);
		if ($sortir) {
			$mess = GetMessage("semontrer_01");
				$valeursCache=array();
				$valeursCache[0]= $PERSO->nom;
				$mess_spect = GetMessage("semontrer_spect",$valeursCache);
		}	
		else {
			$mess="";	
			$mess_spect="";
		}
		$nbSorts = count($PERSO->Sorts);
		$i=0;
		while ($Sort==null && $i<$nbSorts) {
			if($PERSO->Sorts[$i]->id_clef == $id_sort){
				$Sort = $PERSO->Sorts[$i];
		}
			else {
				$i++;
			}	
		}
		if ($Sort !=null) {
		$valeurs[0]=$Sort->nom;
		$Sort->Oublier();
		$mess = GetMessage("oublier_sort",$valeurs);
		if ($PERSO->SortPrefere == $id_sort) {
			$PERSO->SupprimerSortPrefere();		
			$mess = $mess . " Ceci tait votre sort prfr. Pensez  reconfigurer votre personnage.";	
		}	
		$PERSO->OutPut($mess,true);
		}
		else $template_main .= GetMessage("noparam");
		
	} else {
		if( (!isset($id_sort))  ){
			$template_main .= GetMessage("noparam");
		} else {
			if ($PERSO->RIP())
				$template_main .= GetMessage("nopvs");
			else	
			if ($PERSO->Archive)
				$template_main .= GetMessage("archive");
			else	
			$template_main .= GetMessage("nopas");
		}
	}
	$template_main .= "<br /><p>&nbsp;</p>";
}

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>