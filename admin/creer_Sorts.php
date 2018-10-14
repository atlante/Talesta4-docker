<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_Sorts.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.9 $
$Date: 2010/02/28 22:58:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_sorts;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$effetsMinInf=0;
$effetsMinSup=10;
$coeffPrix = 30;

if(!(isset($etape))){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Ce script sert à créer un jeu de données pour les tests du moteur. <br />";
	$template_main .= "Pour chaque école de magie (type de sort) et pour chaque sous type de sorts (soin, paralysie ...),<br />";
	$template_main .= "Il va créer 3 niveaux de Sorts (novice, intermédiaire et confirmé). <br />"; 
	$template_main .= "Les effetsMin sont dépandants du niveau (entre \$effetsMinInf (qui est à $effetsMinInf) et \$effetsMinSup (qui est à $effetsMinSup), effetsMax = EffetsMin+4 <br />";
	$template_main .= ", le prix de ces sorts est de niveau donné * \$coeffPrix (actuellement à $coeffPrix), .<br />";
	$template_main .= "\$effetsMinInf, \$effetsMinSup, \$coeffPrix peuvent être modifiés pour être adaptés à votre jeu .<br />";
	//$template_main .= " <b>Le script creer_etatCarac doit avoir été utilisé avant celui-ci. </b><br />";
	$template_main .= "<input type='submit' value='Création' /><input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>";
	
	$template_main .= "</div>";	
}



else if ($etape=="1") {
        
        if($MJ->aDroit($liste_flags_mj["CreerMagie"])){
        /*
        	//creation des sorts bonus
        	$SQL ="Select id_typeetattemp  from ".NOM_TABLE_TYPEETAT."  where nomtype='Divers'";
        	$result = $db->sql_query($SQL);
        	if($db->sql_numrows($result)== 0){
        	   $template_main .= "Aucun type d'etat nommé Divers";	
        	}	
        	else {
        	}
        */
        
        	//creation des sorts soins, paralysie, attaque, transfert
        
        	$place=1;
        	$charges=-1;		
        	$provoqueetatValue="";
        	$permanent=1;
        	$anonyme=0;
        	$image="";
        	$id_etattempspecifique="";
        	$caracteristique="Intelligence";
        	$typecible=2;
        	$chaine2="";
        	$composantesValue="";
        	$chaine="";
        	$coutpo=0;
        	$coutpv=0;
        	$coutpi=-2;
        	$coutpa=0;		
        	for ($sortdistant = 0; $sortdistant<2; $sortdistant++) {
        		foreach($liste_type_cible as $typecible => $valeurCible) {
        			switch ($typecible){
        				case 1:	
        					$desc = ", la cible étant un PJ (lanceur ou autre)";		
        					if ($sortdistant==0) 
        						$majeur="";
        					else 	$majeur=" sur 1 PJ distant";
        					break;
        				case 2:
        					$desc = ", la cible étant une zône";
        					if ($sortdistant==0)
        						$majeur=" sur Zone";
        					else 	$majeur=" sur Zone distante";
        					break;
        				case 3:
        					$desc = ", la cible étant le lanceur uniquement";
        					$majeur=" sur soi";
        					break;					
        			}	
        			//sort non distant sur 1 personne => pas majeur
        			/*if (($typecible==1||$typecible==3) && $sortdistant==0)
        				break;
        			*/
        			//pas de sort distant sur le lanceur lui meme
        			if ($typecible==3 && $sortdistant==1)
        				break;
        
        			for($effetsMin=$effetsMinInf;$effetsMin<=$effetsMinSup;$effetsMin+=5){
        				$degats_min=$effetsMin;
        				$degats_max=$effetsMin+4;
        				$prix_base=($effetsMin+1)*$coeffPrix;
        				if ($effetsMin ==$effetsMinInf)
        					$niveau = "novice";
        				else 	
        				if ($effetsMin ==$effetsMinSup)
        					$niveau = "confirmé";
        				else $niveau = "intermédiaire";	
        				foreach($liste_magie as $competence => $numeroCompetence) {
        					$type = $competence;
        					foreach($liste_stype_sorts as $sous_type => $soustypeNum) {
        						
        						if (($sous_type=="Teleport Self" && $typecible==2) 
        							|| ($sous_type=="Resurrection" && $typecible==3) 
        							|| ($sous_type=="Teleport" && $typecible==3) 
        							|| (($sous_type=="Paralysie"||$sous_type=="Attaque"||$sous_type=="Transfert") && $typecible==3) 
        							|| ($sous_type=="Teleport Self" && $sortdistant==1) 
        							|| ($sous_type=="Teleport Self" && $typecible==1) 
        						) {
        							//pas de sort d'autoteleportation sur une zone 
        							// ni de sort de resurrection sur soi meme
        							// ni de sort d'attaque sur soi
        							// ni sort d'autoteleportation en distant (le lanceur ne peut etre distant de lui meme)
        							// ni sort de teleportation avec typecible=3
        							// ni sort d'autoteleportation avec typecible= 1
        						}
        						else {
        							$description="Sort ".$majeur." de ".$sous_type. " du type ".$competence." de niveau ".$niveau;
        							if ($sortdistant==1)
        								$description .= " et pouvant être lancé à distance";
        							$description .=$desc;
        							$nom=$sous_type. " ".$majeur." ".$niveau." du type ".$competence;
        							include "./creer_magie.".$phpExtJeu;		
        							$template_main .= "<br />";
        						}	
        
        					}
        				}
        			}			
        		}
        	}
        	// fin de creation des sorts soins, paralysie, attaque, transfert
        }
        else $template_main .= GetMessage("droitsinsuffisants");

}

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>