<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_mj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.24 $
$Date: 2010/05/15 08:55:13 $

*/

require_once("../include/extension.inc");if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_mj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nom","flags","pass","titre","email","wantmail","dispo_pour_ppa"
	);

if (defined("IN_FORUM")&& IN_FORUM==1)
	$imagetype=$forum->image_avatar_remote;

if(!isset($etape)){$etape=0;}
if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["CreerMJ"])){
		$fl = "";
		for($i=0;$i<count($flags);$i++){
			$fl .= $flags[$i];
		}
		$fl .= '000000000000000000000';
		$flags = $fl;
		$nom=ConvertAsHTML($nom);

		$SQL="select nom from ".NOM_TABLE_MJ." where nom = '". $nom."'";
		$recherche1 = $db->sql_query($SQL);
		$SQL="select nom from ".NOM_TABLE_PERSO." where nom = '". $nom."'";
		$recherche2 = $db->sql_query($SQL);
		$SQL="select nom from ".NOM_TABLE_INSCRIPTION." where nom = '". $nom."'";
		$recherche3 = $db->sql_query($SQL);
		
		if(($db->sql_numrows($recherche3)==0) && ($db->sql_numrows($recherche1)==0) && ($db->sql_numrows($recherche2)==0)) {

			if(!(defined("IN_FORUM")&& IN_FORUM==1 && (in_array (strtoupper($nom), $forum->nomsReservesForum)))) {
				$erreur="";
				if( (!isset($email)) || (!verif_email($email))){
					$erreur .= "Adresse Mail incorrect <br />";
				}
				
				if ($erreur=="") {		
				
					$SQL = "INSERT INTO ".NOM_TABLE_MJ." (";
					$SQL2="";
					$SQL3="";
					$nbchamps = count($liste_champs);
					for($i=0;$i<$nbchamps;$i++){
						if ($$liste_champs[$i]<>"") {
							if ($SQL2<>"")  {
								$SQL2.=",";
								$SQL3.=",";	
							}
							$SQL2.=$liste_champs[$i];
							$SQL3.="'".ConvertAsHTML($$liste_champs[$i])."'";	
						}	
					}
					$SQL=$SQL . $SQL2 .",lastaction) VALUES (" . $SQL3.",'".time()."')";
					if ($result=$db->sql_query($SQL)) {
						$result_id = $db->sql_nextid();
						if(file_exists("../fas/mj_".$result_id.".fa"))
							if (unlink ("../fas/mj_".$result_id.".fa")===false)
								$template_main .= "Impossible d'effacer le fichier '../fas/mj_".$result_id.".fa'";
						
						if(defined("IN_FORUM")&& IN_FORUM==1) {
							//include(CHEMIN_FORUM."config.".$phpExtJeu);
							//include(CHEMIN_FORUM."includes/constants.".$phpExtJeu);
							$result=$forum->CreationMembre($nom,$pass,$email,"MJ",$imageforum);
						}						
						if ($result) {
							$MJ->OutPut("MJ ".span(ConvertAsHTML($nom),"mj")." correctement cree",true);
							$etape=0;
						}	
						else {
							$MJ->OutPut($db->erreur);
							$etape=-1;
						}
					}
					else {
						$MJ->OutPut($db->erreur);
						$etape=-1;
					}
				}
				else {
					$MJ->OutPut($erreur,true);		
					$etape=-1;
				}
			}
			else {
				$MJ->OutPut("MJ ".span(ConvertAsHTML($nom),"mj")." est d&eacute;j&agrave; utilis&eacute; pour PHPBB",true);		
				$etape=-1;
			}
		}	
		else {
			$MJ->OutPut("MJ ".span(ConvertAsHTML($nom),"mj")." est d&eacute;j&agrave; utilis&eacute;",true);		
			$etape=-1;
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}
if($etape==0||$etape==-1){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	if ($etape==0) {
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = '';
		}
	$imageforum="";
	for($i=0;$i<count($liste_flags_mj);$i++)
		$flags[$i]=0;			
	}
	else {
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++) {
    	$$liste_champs[$i] = ConvertAsHTML($$liste_champs[$i]);
    }    
  }

	include('forms/mj.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>