<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: creer_question.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.10 $
$Date: 2010/05/15 08:55:10 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_qcm;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"question","reponse1","reponse2","reponse3","reponse4","bonne"
		);
		
if(!isset($etape)){
	$etape=0;
}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["CreerQuestion"])){
		if($valider==1) { //  création
//			$SQL="select nom from ".NOM_TABLE_MJ." where nom ='". $nom."'";
			//$recherche1 = $db->sql_query($SQL);	
			$SQL="select question from ".NOM_TABLE_QCM." where question ='". ConvertAsHTML($question)."'";
			$recherche2 = $db->sql_query($SQL);	
/*			$SQL="select reponse1 from ".NOM_TABLE_QCM." where reponse1 ='". $reponse1."'";
			$recherche3 = $db->sql_query($SQL);	
			$SQL="select reponse2 from ".NOM_TABLE_QCM." where reponse2 ='". $reponse2."'";
			$recherche4 = $db->sql_query($SQL);	
			$SQL="select reponse3 from ".NOM_TABLE_QCM." where reponse3 ='". $reponse3."'";
			$recherche5 = $db->sql_query($SQL);	
			$SQL="select reponse4 from ".NOM_TABLE_QCM." where reponse4 ='". $reponse4."'";
			$recherche6 = $db->sql_query($SQL);	
			$SQL="select bonne from ".NOM_TABLE_QCM." where bonne ='". $bonne."'";
			$recherche7 = $db->sql_query($SQL);	
*/

			//if(($db->sql_numrows($recherche2)==0) && ($db->sql_numrows($recherche3)==0) && ($db->sql_numrows($recherche4)==0) && ($db->sql_numrows($recherche5)==0) && ($db->sql_numrows($recherche6)==0) && ($db->sql_numrows($recherche7)==0)) {
			if($db->sql_numrows($recherche2)==0) {
				$SQL = "INSERT INTO ".NOM_TABLE_QCM." (";
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
				$SQL=$SQL . $SQL2 .") VALUES (" . $SQL3.")";
				if($db->sql_query($SQL,"",END_TRANSACTION_JEU)) {
					$MJ->Output("La question ".span(ConvertAsHTML($question),"pj")." correctement inscrite",true);
					$etape=0;
				}	
				else 	{
					$MJ->Output($db->erreur);
					$etape=-1;
				}	
			}
			else {
				$MJ->Output("La question ".span(ConvertAsHTML($question),"mj")." est déjà existante",true);		
				$etape=-1;
			}	
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");

}

if($etape===0) {
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = '';
	}
}

if($etape==-1){
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++) {
    	$$liste_champs[$i] = ConvertAsHTML($$liste_champs[$i]);
    }    
}
if($etape==0||$etape==-1){

	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$lieu='';
	include('forms/question.form.'.$phpExtJeu);
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>