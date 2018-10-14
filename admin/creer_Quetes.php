<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_Quetes.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/02/28 22:58:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_quetes;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$bonusMin=1;
$bonusMax=5;
$coeffPrix = 30;
$coeffDegats = 5;

if(!(isset($etape))){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Ce script sert  crer un jeu de donnes pour les tests du moteur. <br />";
	$template_main .= "Il va crer des Quetes.<br />";
	$template_main .= "<input type='submit' value='Cration' /><input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>";
	
	$template_main .= "</div>";	
}

else if ($etape=="1") {

	$detail_type_quetePO="";
	$detail_type_queteOBJ="";
	$detail_type_queteLieu="";
	$detail_type_queteSort="";
	$detail_type_quetePJ="";
	$recompenseSort	="";
	$recompenseMontant="";
	$recompenseOBJ="";
	$recompenseComp="";
	$recompenseEtat="";
	$recompenses="";	
	$type_recompense="";
	$recompense="";
	$punitions="";
	$punitionSort	="";
	$punitionMontant="";
	$punitionOBJ="";
	$punitionComp="";
	$punitionEtat="";	
	$type_punition="";
	
        $id_etattempspecifique="";
	$id_lieu="";
	$SQL = "Select T1.id_magie  from ".NOM_TABLE_MAGIE." T1 where T1.id_magie >1";
	$result = $db->sql_query($SQL);
	$rowMagie = $db->sql_fetchrow($result);	
        $chaineRecompenses2 = "2|50;4|".$rowMagie['id_magie'].";"; 
	
	$id_proposeMJ=1;
	$id_proposePJ=1;
	
	$textereussite = "Felicitations, j'avais parie sur toi";
	$texteechec = "decidement, tu n'es qu'un bon a rien";
        $num_quete = 0;
	for ($duree_quete=-1; $duree_quete<=3;$duree_quete=$duree_quete+4) {
	
	
	
	for ($public=0; $public<2;$public++) {
				foreach($liste_type_propose_quete as $proposepartype => $libelleproposepartype) {
					foreach($liste_type_quete as $type_quete => $libelleTypeQuete) {
						$texteproposition = " il faut " . $libelleTypeQuete." ";
	                                        $num_quete ++;					
            $refuspossible=$num_quete%2;
            $validationquete=$refuspossible%2;                                 				
            $abandonpossible=$num_quete%2;
            $cyclique=$abandonpossible%2;
            $proposant_anonyme= $num_quete%2;
						switch($type_quete) {
							case 1: //Lieu
							case 7: //Lieu
								$SQL = "Select T1.id_lieu , T1.nom from ".NOM_TABLE_LIEU." T1 where T1.id_lieu >1";
								$result = $db->sql_query($SQL);
								$nb=$db->sql_numrows($result);
								for ($i=0;$i<=($num_quete%$nb);$i++)
								        $rowLieu = $db->sql_fetchrow($result);
								$detail_type_quete = $rowLieu['id_lieu'];
								$detail_lib_quete = $rowLieu['nom'];	
								$detail_type_queteLieu=$rowLieu['id_lieu'];						
								break;
							case 2: //PJ
							case 4: //PJ
							case 5: //PJ
								$SQL = "Select T1.id_perso , T1.nom  from ".NOM_TABLE_REGISTRE." T1 where T1.PV>0 and id_lieu >0";
								$resultPJ = $db->sql_query($SQL);
								$nb=$db->sql_numrows($resultPJ);
								logdate( " nb" . $nb ." num_quete%nb" .$num_quete%$nb);
								for ($i=0;$i<=($num_quete%$nb);$i++)
        								$rowPJ = $db->sql_fetchrow($resultPJ);
								$detail_type_quete = $rowPJ['id_perso'];
								$detail_lib_quete = $rowPJ['nom'];
								$detail_type_quetePJ = $detail_type_quete;
								break;
							case 3:	//objet
								$SQL = "SELECT id_objet, nom  FROM ".NOM_TABLE_OBJET. " where id_objet >1";
								$result = $db->sql_query($SQL);
								$nb=$db->sql_numrows($result);
								for ($i=0;$i<=($num_quete%$nb);$i++)
        								$rowOBJ = $db->sql_fetchrow($result);
								$detail_type_quete = $rowOBJ['id_objet'];
								$detail_lib_quete = $rowOBJ['nom'];
								$chaineRecompenses2 = $chaineRecompenses2 . "3|".$rowOBJ['id_objet'].";";
								$detail_type_queteOBJ= $detail_type_quete;
								break;
							case 6: //PO
								$detail_type_quete = 50;
								$detail_lib_quete = "50 Pieces d'or";
								$detail_type_quetePO=$detail_type_quete;
								break;
						}
							
							$SQL = "Select T1.id_lieu , T1.nom from ".NOM_TABLE_LIEU." T1 where T1.id_lieu >1";
							if ($type_quete==1)
							        $SQL= $SQL . " and id_lieu <> " . $detail_type_queteLieu;
							$result = $db->sql_query($SQL);
							$nb=$db->sql_numrows($result);
							for ($i=0;$i<($num_quete%$nb);$i++)
							        $rowLieu = $db->sql_fetchrow($result);
							$id_lieu = $rowLieu['id_lieu'];							
							$texteproposition .=$detail_lib_quete;
						        $nom_quete = $libelleTypeQuete . " " .$detail_lib_quete;
							include "./creer_quete.".$phpExtJeu;		
							$template_main .= "<br />";
						}
					}
                		}			
	}
}


if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>