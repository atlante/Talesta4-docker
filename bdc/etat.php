<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: etat.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.19 $
$Date: 2010/01/24 16:36:51 $

*/

require_once("../include/extension.inc");
$chaine_defaut = "&nbsp;&nbsp;&nbsp;";
include('../include/http_get_post.'.$phpExtJeu);
$peutvoir=false;

if(isset($for_mj)){
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($num_etat)){$num_etat = 1;}
if(isset($MJ) || isset($for_mj)){
	$peutvoir=true;
	$EtatTemp_vu = new EtatTemp($num_etat);
}
else 
if(isset($PERSO)){
	$trouve=false;
	$peutvoir=false;
	$i=0;
	$nb=count($PERSO->EtatsTemp);
	while($i<$nb && $peutvoir==false){
		if ($num_etat == $PERSO->EtatsTemp[$i]->ID) {
			$peutvoir=true;
			$EtatTemp_vu = $PERSO->EtatsTemp[$i];
		}	
		else $i++;
	}
}
if($peutvoir){
	if ($EtatTemp_vu==false)
		$template_main .= GetMessage("EtatInexistant");
	else {	
		$template_main .= "<div class ='centerSimple'>nom de l'etat temporaire : ".span($EtatTemp_vu->nom,"etattemp")."<br />";
		$template_main .= "rpo : ".span($EtatTemp_vu->rpo,"po").", rpa : ".span($EtatTemp_vu->rpa,"pa").", rpv : ".span($EtatTemp_vu->rpv,"pv").", rpi : ".span($EtatTemp_vu->rpi,"pi")."<br />";
		if($EtatTemp_vu->Visible == 0){
			$template_main .= span("Invisible","mj")." par les tiers<br />";
		} else {
			$template_main .= span("Visible","po")." par les tiers<br />";
		}
		if(defined("PAGE_ADMIN")) {
			 if ($EtatTemp_vu->Etatutilisableinscription)  {
			 	$template_main .= GetMessage("EtatPJ")."<br />";			 
			 }	
			 else   {
			 	$template_main .= GetMessage("EtatPNJ")."<br />";			 
			 }	
		}	
		$template_main .= "</div>";
		if ($EtatTemp_vu->Listebonus<>array())
			foreach($EtatTemp_vu->Listebonus as $k => $v) {
	 		   if ($v >=0)
	 		   	$comp[$k]=span("+".$v,"bonus");
	 		   else 	
				$comp[$k]=span($v,"malus");
			}
		
	$toto = array_keys($liste_caracs);
	$tata = array_values($liste_caracs);
	$temp=array();
	$nbCarac=count($liste_caracs);
	for($i=0;$i<$nbCarac;$i++){
		if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=$chaine_defaut;}
		$temp[($i*3)]=GetImage($toto[$i]);
		$temp[($i*3)+1]=$toto[$i];
		$temp[($i*3)+2]=$comp[$tata[$i]];
	}
	$premiere_ligne=array(
			"&nbsp;",
			"&nbsp;",
			"&nbsp;",
			makeTableau(6, "", "details", $temp,"nowrap","",1)
	);
	unset($temp);unset($toto);unset($tata);
	$temp=array();
	$toto = array_keys($liste_competences);
	$tata = array_values($liste_competences);
	$nbComp=count($liste_competences);
	for($i=0;$i<$nbComp;$i++){
		if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=$chaine_defaut;}
		$temp[($i*3)]=GetImage($toto[$i]);
		$temp[($i*3)+1]=$toto[$i];
		$temp[($i*3)+2]=$comp[$tata[$i]];
	}
	unset ($toto); unset($tata);
	$toto = array_keys($liste_magie);
	$tata = array_values($liste_magie);
	$temp2=array();
	$nbMagie=count($liste_magie);
	for($i=0;$i<$nbMagie;$i++){
		if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=$chaine_defaut;}
		$temp2[($i*3)]=GetImage($toto[$i]);
		$temp2[($i*3)+1]=$toto[$i];
		$temp2[($i*3)+2]=$comp[$tata[$i]];
	}
	
	$deuxieme_ligne=array(
			"&nbsp;",
			makeTableau(9, "", "details", $temp,"nowrap","",1),
			makeTableau(3, "", "details", $temp2,"nowrap","",1),
			"&nbsp;"
	);
	
	unset($tata);unset($toto);
	
	$toto = array_keys($liste_artisanat);
	$tata = array_values($liste_artisanat);
	$temp3=array();
	$nbArtisanat=count($liste_artisanat);
	for($i=0;$i<$nbArtisanat;$i++){
		if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=$chaine_defaut;}
		$temp3[($i*3)]=GetImage($toto[$i]);
		$temp3[($i*3)+1]=$toto[$i];
		$temp3[($i*3)+2]=$comp[$tata[$i]];
	}
	
	$deuxiemeBis_ligne=array(
			"&nbsp;",
			makeTableau(12, "", "details", $temp3,"nowrap","",1),
			"&nbsp;"
	);
	
	unset($temp);unset($temp2);unset($temp3);unset($tata);unset($toto);
	
	$template_main .= makeTableau(4, "center","container", $premiere_ligne,"","100%",0,true);
	$template_main .= "<br />&nbsp;";
	$template_main .= makeTableau(4, "","container", $deuxieme_ligne,"","100%",0,true);
	$template_main .= "<br />&nbsp;";
	$template_main .= makeTableau(4, "","container", $deuxiemeBis_ligne,"","100%",0,true);
	$template_main .= "<br />&nbsp;";
	}
} else {
	$template_main .= GetMessage("EtatInvisible");
}
if(!defined("__BARREGENERALE.PHP")){include("../include/barregenerale.".$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>