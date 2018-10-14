<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: groupe.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2010/05/15 16:02:55 $

*/

//script fourni par Uriel. Merci a lui
$chaine_defaut = "&nbsp;&nbsp;&nbsp;";
require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);

function Calcul_fatigue($PA_ACT, $PA_MAX) {
	if ($PA_ACT == 0) return GetMessage("calculFatigue01");
	if ($PA_ACT <= ($PA_MAX / 5)) return GetMessage("calculFatigue02");
	if ($PA_ACT <= ($PA_MAX / 2)) return GetMessage("calculFatigue03");
	return GetMessage("calculFatigue04");
	
}

if(isset($for_mj)){
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(isset($MJ)){
	$peutvoir=true;
	$Groupe_vu = new Groupe($num_groupe, true);
}
else { 
  if(isset($PERSO)){
         if ($PERSO->Groupe == $num_groupe) {
          	$Groupe_vu = new Groupe($num_groupe, true);
  	        $peutvoir=true;
  	     }        
       	 else $peutvoir=false;
  }
  else $peutvoir=false;
}
if($peutvoir){
	if ($Groupe_vu==false)
		$template_main .= GetMessage("groupeInexistant");
	else {	
		$template_main .= "<div class ='centerSimple'>Nom du Groupe : ".span($Groupe_vu->nom,"etattemp")."<br />";
		$template_main .= "Nombre de personnes : ".span($Groupe_vu->nb,"po")."<br />";
		$template_main .= "<br /> Membres de ce groupe : <br />" ;
		$template_main .= "</div>";
		for($i=0;$i<$Groupe_vu->nb;$i++){
			$temp[($i*2)]=span($Groupe_vu->Persos[$i]->nom,"pj");
			//$temp[($i*4)+1]=span($Groupe_vu->Persos[$i]->Race,"race");
			//$temp[($i*4)+2]=span($Groupe_vu->Persos[$i]->Sexe,"race");
			if ($Groupe_vu->Persos[$i]->Lieu->ID==$PERSO->Lieu->ID) {
				$temp[($i*2)+1]=Calcul_Fatigue($Groupe_vu->Persos[$i]->PA,($Groupe_vu->Persos[$i]->GetPAMax()+$Groupe_vu->Persos[$i]->GetRPA()));
			}	
			else 	$temp[($i*2)+1]=GetMessage("voirPJGroupeKO");
		}

	$premiere_ligne=array(
			"&nbsp;",
			makeTableau(2, "", "details", $temp,"nowrap","",1)
	);

		$template_main .= makeTableau(1, "center","container", $premiere_ligne,"","100%",0,true);
		$template_main .= "<br />&nbsp;";
	}
} else {
	$template_main .= GetMessage("voirGroupeKO");
}
if(!defined("__BARREGENERALE.PHP")){$template_main .= "</div>";include("../include/barregenerale.".$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
