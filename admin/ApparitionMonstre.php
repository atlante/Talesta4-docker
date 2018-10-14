<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: ApparitionMonstre.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/02/28 22:58:01 $

*/

require_once("../include/extension.inc");
if(!Defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!Defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!Defined("SESSION_POUR_MJ")) Define("SESSION_POUR_MJ", 1);
if(!Defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $apparitionMonstre;
if(!Defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["InscrirePJ"])){
		$result=true;
		if(isset($del)){
			$totodel = array_keys($del);
			$tatadel = array_values($del);
			$SQL = "DELETE FROM ".NOM_TABLE_APPARITION_MONSTRE." WHERE id_perso = '".$id_cible."' AND (";
			$nb_del=count($del);
			for($i=0;$i<$nb_del&&$result;$i++){
				if($tatadel[$i] == "on"){
					$SQL.= " id_apparitionmonstre = '".$totodel[$i]."' OR";
				}
			}
			$SQL = substr($SQL,0,strlen($SQL)-2).")";;
			$result=$db->sql_query($SQL);
			
		}
		
		if(isset($chance_apparition) && $result){
			$toto = array_keys($chance_apparition);
			$tata = array_values($chance_apparition);	
			$oldtata = array_values($old_chance_apparition);	        
			$nb_chance_apparition=count($chance_apparition);
			for($i=0;($i<$nb_chance_apparition) && $result;$i++){
			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
			        if ((!isset($del)) || (!array_key_exists ( $toto[$i], $del))) {
			                if ($oldtata[$i]<>  $tata[$i]) {
        					$SQL = "UPDATE ".NOM_TABLE_APPARITION_MONSTRE." SET chance_apparition = '".$tata[$i]."' WHERE id_perso = '".$id_cible."' AND id_apparitionmonstre = '".$toto[$i]."'";
        					$result=$db->sql_query($SQL);
                                        }
				}
			}	
		}

		if(isset($nb_max_lieu) && $result){
			$toto = array_keys($nb_max_lieu);
			$tata = array_values($nb_max_lieu);
			$nb_nb_max_lieu=count($nb_max_lieu);
			$oldtata = array_values($old_nb_max_lieu);
			for($i=0;($i<$nb_nb_max_lieu) && $result;$i++){
			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
			        if ((!isset($del)) || (!array_key_exists ( $toto[$i], $del))) {
			                if ($oldtata[$i]<>  $tata[$i]) {
				                $SQL = "UPDATE ".NOM_TABLE_APPARITION_MONSTRE." SET nb_max_lieu = ".$tata[$i]." WHERE id_perso = '".$id_cible."' AND id_apparitionmonstre = '".$toto[$i]."'";
					        $result=$db->sql_query($SQL);
					}
                                }
			}
		}


		if(isset($nb_max_apparition) && $result){
			$toto = array_keys($nb_max_apparition);
			$tata = array_values($nb_max_apparition);
			$nb_nb_max_apparition=count($nb_max_apparition);
			$oldtata = array_values($old_nb_max_apparition);
			for($i=0;($i<$nb_nb_max_apparition) && $result;$i++){
			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
			        if ((!isset($del)) || (!array_key_exists ( $toto[$i], $del))) {
			                if ($oldtata[$i]<>  $tata[$i]) {
				                $SQL = "UPDATE ".NOM_TABLE_APPARITION_MONSTRE." SET nb_max_apparition = ".$tata[$i]." WHERE id_perso = '".$id_cible."' AND id_apparitionmonstre = '".$toto[$i]."'";
					        $result=$db->sql_query($SQL);
					}        
                                }
			}
		}

		//La on a efface et mis a jour
		if(isset($Itype_lieu_apparition) && $Itype_lieu_apparition!="" && $Ichance_apparition!="" &&$result){
			$SQL = "INSERT INTO ".NOM_TABLE_APPARITION_MONSTRE." (id_perso,	id_typelieu,chance_apparition,nb_max_lieu,nb_max_apparition) VALUES ('".$id_cible."','".$Itype_lieu_apparition."',$Ichance_apparition,$Inb_max_lieu,$Inb_max_apparition)";
			$result=$db->sql_query($SQL);
		}
				
		//Ici on ajoute
		if ($result) {
			$valeurs=array();
			$valeurs[1]= $nom;	
			$MJ->OutPut(GetMessage("modifLieuApparitionOK",$valeurs),true);
		}	
		else $template_main .= $db->erreur;	
		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$id_cible=$id_cible.$sep.$nom;
	$etape=1;
}

if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$libelle=ConvertAsHTML(substr($id_cible, $pos+strlen($sep))); 
	$id_cible=substr($id_cible, 0,$pos); 
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
/*	$SQL = "SELECT * FROM ".NOM_TABLE_APPARITION_MONSTRE." T1 WHERE T1.id_perso = ".$id_cible;
	$resultPJQ = $db->sql_query($SQL);
	if($db->sql_numrows($resultPJQ) > 0){
		$ListLieu = null;
		$compteur=0;
		$template_main .= "Lieu d'apparition de ".span($libelle,"pj")."<br />";
		$i=0;
		while($rowLieuPJ = $db->sql_fetchrow($resultPJQ)) {
				$ListLieu[$compteur]=new LieuPJ($rowLieuPJ["id_lieu"],$rowLieuPJ["id_persoLieu"], $rowLieuPJ["etat"], $rowLieuPJ["debut"], $rowLieuPJ["fin"]);
				if ($ListLieu[$compteur]!=null)
					$compteur++;					
				$i++;	
		}
		include('forms/ApparitionMonstre.form.'.$phpExtJeu);
		
	} else {
		$template_main .= span($libelle,"pj")." n'apparait dans aucun lieu";
	}
*/
        include('forms/ApparitionMonstre.form.'.$phpExtJeu);
	$template_main .= "<input type='hidden' name='nom' value='".$libelle."' />";
	$template_main .= '<input type="hidden" name="chaine" value="" />';
	include('forms/ApparitionMonstre2.form.'.$phpExtJeu);
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les Lieux eventuellement selectionnes ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	
	
	$template_main .= "</form>";
	$template_main .= "</div>";
}

if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel monstre voulez vous faire apparaitre automatiquement ?<br />";
	$SQL = "Select concat(concat(T1.id_perso,'$sep'),T1.nom) as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 where T1.pnj=2 ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
/*
	$template_main .= "<br />Dans quel Lieu voulez vous le faire apparatre ?<br />";
	$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
	$var2= faitSelect("id_lieu",$SQL,"",-1);
	$template_main .= $var2[1];
*/	
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!Defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!Defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>