<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: chargeDansTable.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2010/02/28 22:58:02 $

*/

require_once("../include/extension.inc");
require_once("../include/fct_installUpdate.".$phpExtJeu);
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $chargeSQL;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}

function writeSQL($fichierSQL) {
        global $phpExtJeu;
        global $template_main;
        global $db;
        global $nb_test;
        global $nb_test_ok;
        if (file_exists($fichierSQL)) { 
        	$file=file_get_contents($fichierSQL);
        	$file=str_replace($db->delimiter." ",$db->delimiter,$file);
        	$file=str_replace($db->delimiter."\t",$db->delimiter,$file);
        	//passage en mode unix
        	$file=str_replace("\r","",$file);
        	$requetes=explode($db->delimiter."\n",$file);
        	$i=0;
        	foreach ($requetes as $requete) {
        		$requete= trim($requete);					
        		if ($requete<>"") {
        			$lignesrequete=explode("\n",$requete);
        			$commentaire=1;
        			foreach ($lignesrequete as $lignerequete) {
        				if (trim($lignerequete)<>"" && substr($lignerequete, 0, strlen($db->comment))!=$db->comment) {
        					$commentaire=0;
        				}	
        			}		
        			//teste si c'est un commentaire
        			if ($commentaire==0) {
        				//$requete=str_replace('tlt_',$config["table_prefix"],$requete);
        				test(str_replace("\n","<br />",$requete.$db->delimiter), $db->sql_query($requete),"",0);
        			}	
        		}	
        	}						
        	
        	$template_main .="<p>$nb_test_ok instructions se sont executées correctement sur $nb_test.</p><br />";
        }
        else $template_main .= "Fichier '".$fichierSQL."' inexistant";
       
}        


if($etape==1){
	if (isset($HTTP_POST_FILES['fichierSQL']['tmp_name']) && isset($HTTP_POST_FILES['fichierSQL']['name'])&& $HTTP_POST_FILES['fichierSQL']['tmp_name']!="") {
	        writeSQL($HTTP_POST_FILES['fichierSQL']['tmp_name']);
	}	
	else $template_main .= "Probleme lors de l'upload du fichier";
}

if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."'  enctype='multipart/form-data'  method='post'>";
	$template_main.= "Fichier à importer <input name='fichierSQL' id='fichierSQL' value='' size='50' type='file' />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "</form></div>";
}

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>