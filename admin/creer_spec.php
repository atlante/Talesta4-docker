<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: creer_spec.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.14 $
$Date: 2006/01/31 12:26:17 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_spec;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}
if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["CreerSpec"])){
		$SQL = "INSERT INTO ".NOM_TABLE_SPECNOM." (nom,rpa,rpo,rpv,rpi) VALUES ('".ConvertAsHTML($nom_spec)."','".$rpa."','".$rpo."','".$rpv."','".$rpi."')";
		if ($result = $db->sql_query($SQL)) {
			$result_id= $db->sql_nextid();
			if(isset($comp)){
				$toto = array_keys($comp);
				$tata = array_values($comp);
				$debutSQL = "INSERT INTO ".NOM_TABLE_SPEC." (id_spec,id_comp,bonus) VALUES ";
				for($i=0;$i<count($comp)&&($result!==false);$i++){
					if($tata[$i] != 0){
						$SQL = $debutSQL . "('".$result_id."','".$toto[$i]."','".$tata[$i]."')";
						$result=$db->sql_query($SQL);
					}
				}
			}
			if ($result!==false)
				$MJ->OutPut("Sp&eacute;cialisation ".span(ConvertAsHTML($nom_spec),"specialite")." correctement cr&eacute;ee",true);
		}
		$MJ->OutPut($db->erreur);	
	}	
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
	unset($comp);
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "nom de la sp&eacute;cialisation : <input type='text' name='nom_spec' value='' size='15' /><br />";
	$template_main .= "rpo : <input type='text' name='rpo' value='0' size='4' /> rpa : <input type='text' name='rpa' value='0' size='4' /> 
	rpv : <input type='text' name='rpv' value='0' size='4' />rpi : <input type='text' name='rpi' value='0' size='4' /> <br />";
	$template_main .= "Visible par les tiers :".faitOuiNon("Visible")."<br />";
	include('forms/status.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>