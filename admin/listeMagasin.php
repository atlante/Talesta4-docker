<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: listeMagasin.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2006/02/04 11:48:10 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_mag;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

function detailMag2($id_lieu,$nomLieu,$type) {
		global $liste_types_magasins;
		global $liste_comp_full;
		global $db;
		global $sep;
		$temp="";
		$toto = array_keys($liste_types_magasins);

		if ($type ==6)
			$SQL="SELECT T1.* FROM  ".NOM_TABLE_MAGASIN." T1 WHERE T1.id_lieu = ".$id_lieu ." AND T1.type = ".$type ;			
		else if ($type <> 1 && $type<>5) 
			$SQL="SELECT T2.* FROM  ".NOM_TABLE_MAGASIN." T1,".NOM_TABLE_OBJET." T2 WHERE T1.pointeur = T2.id_objet AND T1.id_lieu = ".$id_lieu ." AND T1.type = ".$type ." order by T2.type,T2.sous_type";		
		else 
			$SQL="SELECT T2.* FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_MAGIE." T2 WHERE T1.pointeur = T2.id_magie AND T1.id_lieu = ".$id_lieu ." AND T1.type = ".$type." order by T2.type,T2.sous_type";	
		
		$result_type = $db->sql_query($SQL);
		if ($db->sql_numrows($result_type)>0) {

			$temp .="<tr><td rowspan=".$db->sql_numrows($result_type).">&nbsp;";
			switch($type){
				case 0: //armurerie
					/* ne fonctionne pas. Je n'arrive pas a faire passer la variable de session => admin n'est plus identifie
					$id_cible=$id_lieu.$sep.$nomLieu;
					$chemin = "/../admin/modifier_armurerie.php";
					$temp .= "<a href='../include/post.php?id_cible=$id_cible&chemin=$chemin'>".$toto[$type]."</a>";
					*/$temp .=$toto[$type];
					break;
				default:
					//logdate("'" .$type ."' est un type de magasin non prevu dans listeMagasin. Impossible de lier avec l'action");
					$temp .= $toto[$type];		
			}	
			
			
			$temp.="</td>";
			$nbtype=0;
			//while ($nbtype < $db->sql_numrows($result_type)) {
			while(	$row = $db->sql_fetchrow($result_type)){
				if ($nbtype>0) 
					$temp .= " <tr>";
				if ($type ==6)	{
					$ListeObj= array_search($row["pointeur"], $liste_comp_full); 
					$temp .= "<td>".GetImage($ListeObj)."</td><td>". $ListeObj."  </td></tr> ";
				}	
				else 
					$temp .= "<td>". $row["type"]."/". $row["sous_type"] ."</td><td>&nbsp; ". span($row["nom"],"objet"). " </td></tr> ";
				$nbtype++;
			}
		}
		return $temp;	
}

function detailMag($id_lieu,$nomLieu) {
	$temp="";
	$temp.=detailMag2($id_lieu,$nomLieu,0);
	$temp.=detailMag2($id_lieu,$nomLieu,3);
	$temp.=detailMag2($id_lieu,$nomLieu,4);
	$temp.=detailMag2($id_lieu,$nomLieu,1);
	$temp.=detailMag2($id_lieu,$nomLieu,5);
	$temp.=detailMag2($id_lieu,$nomLieu,2);
	$temp.=detailMag2($id_lieu,$nomLieu,6);	
	$temp.=detailMag2($id_lieu,$nomLieu,7);	
	return $temp;
}		

if ($MJ->aDroit($liste_flags_mj["listeMagasins"])) {

	$template_main .= "
	<div class ='centerSimple'>
	";
	$template_main .= "
	<br />&nbsp; Les magasins
	<br />&nbsp;";
		
		$SQL = "select distinct l.id_lieu as id_lieu, concat(concat(trigramme,' -- '), l.nom) as lieu, id_forum, l.nom from ".NOM_TABLE_LIEU." l ,".NOM_TABLE_MAGASIN." m where m.id_lieu=l.id_lieu ";
		$result_mag = $db->sql_query($SQL);
		$mag=0;
		while(	$row = $db->sql_fetchrow($result_mag)) {
			$template_main .="<table class='details' width='90%'> 
			<tr><td colspan='3' align='left'>&nbsp; Lieu ";
			$temp=span($row["lieu"],"lieu");
			if(defined("IN_FORUM") && IN_FORUM==1 && $row["id_forum"]<>0) 
				$temp =" <a href = '".$forum->ScriptAfficheForum($row["id_forum"])."'> ".$temp."</a>";
			$template_main .= $temp;	
			$template_main .= "</td></tr> ";
			$template_main .=detailMag($row["id_lieu"],$row["nom"]);
			$template_main .= "</table><br /><p>&nbsp; </p>";
	
		$mag++;
		}
	
	$template_main .= "</div>";
}
else $template_main .= GetMessage("droitsinsuffisants");

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>

