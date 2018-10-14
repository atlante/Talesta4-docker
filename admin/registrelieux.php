<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: registrelieux.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2010/02/28 22:58:09 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	$typeMag = array_keys($liste_types_magasins);
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
	$SQLtrigramme = "SELECT distinct trigramme as idselect, trigramme as labselect FROM ".NOM_TABLE_LIEU;
	if (!isset($filtreTrigramme))
		$filtreTrigramme="";	//reinitialise le sous type au cas ou choisirait un sous_type sans type puis un type qui ne correspond pas au sous_type
			
	$varTrigramme=faitSelect("filtreTrigramme",$SQLtrigramme,"",$filtreTrigramme, array(),array("&nbsp;")," onChange='submit()' ");
	$template_main .= "filtrer par trigramme: " . $varTrigramme[1];
	$template_main.= "</form>";

	
	$SQL = "SELECT * FROM ".NOM_TABLE_LIEU." WHERE id_lieu > 0 ";
	if(isset($filtreTrigramme) && $filtreTrigramme<>"")
		$SQL = $SQL . " and trigramme = '". $filtreTrigramme."'";	
	if((!isset($tri)) ||$tri=='par_trigramme')
		$SQL = $SQL . " ORDER BY trigramme ";
	elseif($tri=='par_nom')
		$SQL = $SQL . " ORDER BY nom ";
	else
		$SQL = $SQL ." ORDER BY id_lieu ";

	$result2 = $db->sql_query($SQL);
	$template_main .="<table width='100%'><tr><td align='center'><a href=\"registrelieux.$phpExtJeu?tri=par_nom&amp;filtreTrigramme=$filtreTrigramme\"><span class='c0'>Tri par nom</span></a></td><td align='center'><a href=\"registrelieux.$phpExtJeu?tri=par_trigramme&amp;filtreTrigramme=$filtreTrigramme\"><span class='c0'>Tri par trigramme</span></a></td><td align='center'><a href=\"registrelieux.$phpExtJeu?tri=par_id&amp;filtreTrigramme=$filtreTrigramme\"><span class='c0'>Tri par id</span></a></td></tr></table>";
	$template_main .= "<table width='100%' class='detailscenter'>";
	$template_main .= "<tr><td colspan='7' align='center'><span class='c7'>Table des lieux</span></td></tr>";
	$template_main .= "<tr><td><span class='c5'>N°</span></td><td><span class='c0'>nom du Lieu</span></td><td><span class='c7'>trigramme</span></td><td>Type de lieu (critère pour les apparitions automatiques de monstres)</td><td>Accessible uniquement par</td><td>Liste des persos présents</td><td>Magasins dans le lieu</td></tr>";
	while($row = $db->sql_fetchrow($result2)){
		$template_main .= "<tr><td><span class='c5'>".$row["id_lieu"]."</span></td>";
		$template_main .= "<td><span class='c0'>".$row["nom"]."</span></td>";
		$template_main .= "<td><span class='c7'>".$row["trigramme"]."</span></td>";
		$template_main .= "<td>".$liste_type_lieu_apparition[$row["type_lieu_apparition"]]."</td>";
		if ($row["id_etattempspecifique"]<>"" && $row["id_etattempspecifique"]!=0) {
			$SQL_etattemp = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp =".$row["id_etattempspecifique"];
			$result_etattemp = $db->sql_query($SQL_etattemp);
			$row_etattemp = $db->sql_fetchrow($result_etattemp);
			$template_main .= "<td>".ConvertAsHTML($row_etattemp['nom'])."</td>";
		}	
		else $template_main .= "<td>&nbsp;</td>";
		//recup des perso dans les lieux
		$template_main .="<td>";
		$SQL_perso = "SELECT * FROM ".NOM_TABLE_PERSO." WHERE id_lieu =".$row["id_lieu"];
		$result_perso = $db->sql_query($SQL_perso);
		//for($p=0;$p<$db->sql_numrows($result_perso);$p++)	{
		while($row_perso = $db->sql_fetchrow($result_perso)){
			$template_main .="".$row_perso["nom"];
			if ($row_perso["dissimule"]==1) 
				$template_main .= " (Caché) ";
			if ($row_perso["pv"]<=0) 
				$template_main .= " (Mort) ";
			$template_main .= "<br />";
			}
		$template_main .="</td>";	
		//recup des magasins dans les lieux
		$template_main .="<td>";
		$SQL_magasin = "SELECT distinct type FROM ".NOM_TABLE_MAGASIN." WHERE id_lieu =".$row["id_lieu"];
		$result_magasin = $db->sql_query($SQL_magasin);
		while($row_magasin = $db->sql_fetchrow($result_magasin)){
			$template_main .="".$typeMag[$row_magasin["type"]];
			$template_main .= "<br />";
			}
		$template_main .="</td>";	
		$template_main .= "</tr>";
	}
    $template_main .= "</table>";
	
	

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
