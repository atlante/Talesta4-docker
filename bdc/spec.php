<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: spec.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.20 $
$Date: 2006/06/20 20:14:46 $

*/

require_once("../include/extension.inc");$chaine_defaut = "&nbsp;&nbsp;&nbsp;";

include('../include/http_get_post.'.$phpExtJeu);


if(isset($for_mj)){
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($num_spec)){$num_spec = 1;}
$peutvoir=false;
if(isset($MJ)|| isset($for_mj)){
	$peutvoir=true;
	$Spec_vu = new Specialite($num_spec);
}
if(isset($PERSO)){
	$trouve=false;
	$i=0;
	$nbSpec=count($PERSO->Specs);
	while($i<$nbSpec && $peutvoir==false){
		if ($num_spec == $PERSO->Specs[$i]->ID) {
			$peutvoir=true;
			$Spec_vu = $PERSO->Specs[$i];
		}	
		else $i++;
	}
}
if($peutvoir){
	if ($Spec_vu == null)
		$template_main .= "Cette spcialit n'existe pas";
	else {			
		
		$template_main .= "<div class ='centerSimple'>nom de la sp&eacute;cialisation : ".span($Spec_vu->nom,"specialite")."<br />";
		$template_main .= "rpo : ".span($Spec_vu->rpo,"po").", rpa : ".span($Spec_vu->rpa,"pa").", rpv : ".span($Spec_vu->rpv,"pv").", rpi : ".span($Spec_vu->rpi,"pi")."<br />";
		if($Spec_vu->Visible == 0){
			$template_main .= span("Invisible","mj")." par les tiers<br />";
		} else {
			$template_main .= span("Visible","po")." par les tiers<br />";
		}
		$template_main .= "</div>";
		if ($Spec_vu->Listebonus<>array())
			foreach($Spec_vu->Listebonus as $k => $v) {
	 		   if ($v >=0)
	 		   	$comp[$k]=span("+".$v,"bonus");
	 		   else 	
				$comp[$k]=span($v,"malus");
			}
			
	
	
		$toto = array_keys($liste_caracs);
		$tata = array_values($liste_caracs);
		for($i=0;$i<count($liste_caracs);$i++){
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
		
		$toto = array_keys($liste_competences);
		$tata = array_values($liste_competences);
		for($i=0;$i<count($liste_competences);$i++){
			if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=$chaine_defaut;}
			$temp[($i*3)]=GetImage($toto[$i]);
			$temp[($i*3)+1]=$toto[$i];
			$temp[($i*3)+2]=$comp[$tata[$i]];
		}
		unset ($toto); unset($tata);
		$toto = array_keys($liste_magie);
		$tata = array_values($liste_magie);
		for($i=0;$i<count($liste_magie);$i++){
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
		for($i=0;$i<count($liste_artisanat);$i++){
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

		unset($temp);unset($temp2);unset($tata);unset($toto);
		
		$template_main .= makeTableau(4, "center","container", $premiere_ligne,"","100%",0,true);
		$template_main .= "<br />&nbsp;";
		$template_main .= makeTableau(4, "","container", $deuxieme_ligne,"","100%",0,true);
		$template_main .= "<br />&nbsp;";
		$template_main .= makeTableau(4, "","container", $deuxiemeBis_ligne,"","100%",0,true);
		$template_main .= "<br />&nbsp;";

	}
} else {
	$template_main .= "Vous ne pouvez pas voir cette sp&eacute;cialisation. Soit vous ne la poss&eacute;dez pas, soit vous n'&ecirc;tes pas MJ";
}
if(!defined("__BARREGENERALE.PHP")){$template_main .= "</div>";include("../include/barregenerale.".$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>