<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: listesort.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2010/02/28 22:58:06 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_sort;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
	$SQLtype = "SELECT distinct type as idselect, type as labselect FROM ".NOM_TABLE_MAGIE;
	if (!isset($filtreType))
		$filtreType="";
	//reinitialise le sous type au cas ou choisirait un sous_type sans type puis un type qui ne correspond pas au sous_type
	$varType=faitSelect("filtreType",$SQLtype,"",$filtreType, array(),array("&nbsp;")," onChange='document.forms[0].filtresousType.value=\"\";submit()' ");

	$SQLsoustype = "SELECT distinct sous_type as idselect, sous_type as labselect FROM ".NOM_TABLE_MAGIE;
	if ($filtreType!="") {
		$SQLsoustype .=	" WHERE type = '".$filtreType."'";
	}
	if (!isset($filtresousType))
		$filtresousType="";
	$varsousType=faitSelect("filtresousType",$SQLsoustype,"",$filtresousType, array(),array("&nbsp;")," onChange='submit()' ");


	if (!isset($competence))
		$competence="";
	$template_main .= "filtrer par type: " . $varType[1]. ", sous type: " . $varsousType[1];
	$template_main .= ", comptence: <select name='competence' onChange='submit()'>";
		$temp = $liste_magie;
		ksort($temp);
		reset($temp);
		$tata = array_keys($temp);
		$template_main .= "<option value=''";
		if( "" == $competence){ $template_main .= " selected='selected'";}
		$template_main .= ">&nbsp;</option>\n";	
		for($i=0;$i<count($temp);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if($tata[$i] == $competence){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select>";
	$template_main .= "</form>";
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGIE." WHERE id_magie > 0 ";
	if(isset($filtreType) && $filtreType<>"")
		$SQL = $SQL . " and type = '". $filtreType."'";
	if(isset($filtresousType) && $filtresousType<>"" )
		$SQL = $SQL . " and sous_type = '". $filtresousType."'";
		logdate("competence" . $competence."competence");
	if(isset($competence) && $competence<>"")
		$SQL = $SQL . " and competence = '". $competence."'";
	if((!isset($tri)) ||$tri=='par_nom')
		$SQL = $SQL . " ORDER BY nom ";
	elseif($tri=='par_prix')
		$SQL = $SQL . " ORDER BY prix_base ";
	elseif($tri=='par_type')
		$SQL = $SQL . " ORDER BY type, sous_type ";
	elseif($tri=='par_degats')
		$SQL = $SQL . " ORDER BY degats_min,degats_max ";
	else
		$SQL = $SQL ." ORDER BY id_magie ";	
	$result2 = $db->sql_query($SQL);
	$template_main .="<table width='100%'><tr><td align='center'><a href=\"listesort.$phpExtJeu?tri=par_nom&amp;filtreType=$filtreType&amp;filtresousType=$filtresousType&amp;competence=$competence\"><span class='c0'>Tri par nom</span></a></td><td align='center'><a href=\"listesort.$phpExtJeu?tri=par_degats&amp;filtreType=$filtreType&amp;filtresousType=$filtresousType&amp;competence=$competence\"><span class='c0'>Tri par degats</span></a></td><td align='center'><a href=\"listesort.$phpExtJeu?tri=par_prix&amp;filtreType=$filtreType&amp;filtresousType=$filtresousType&amp;competence=$competence\"><span class='c0'>Tri par prix</span></a></td><td align='center'><a href=\"listesort.$phpExtJeu?tri=par_type&amp;filtreType=$filtreType&amp;filtresousType=$filtresousType&amp;competence=$competence\"><span class='c0'>Tri par type</span></a></td><td align='center'><a href=\"listesort.$phpExtJeu?tri=par_id&amp;filtreType=$filtreType&amp;filtresousType=$filtresousType&amp;competence=$competence\"><span class='c0'>Tri par id</span></a></td></tr></table>";
	
	$template_main .= "<table width='100%' class='details'>";
	$template_main .= "<tr><td colspan='13' align='center'>Liste des sorts</td></tr>";
	$template_main .= "<tr><td align='center'><span class='c5'>N</span></td><td align='center'><span class='c0'>nom</span></td><td align='center'>type</td><td align='center'>Sous-type</td><td align='right'>Cout PA</td><td align='right'>Cout PI</td><td align='right'>Cout PO</td><td align='right'>Cout PV</td><td align='center'><span class='c5'>Dgats</span></td><td align='center'>place</td><td align='center'>charges</td><td align='center'>Carac.</td><td align='center'>Comptence</td><td align='center'>anonyme</td><td align='center'>Perm.</td><td align='center'>Prix</td><td align='center'>Rserv aux</td><td>Sort Distant</td></tr>";
	//for($i=0;$i<$db->sql_numrows($result2);$i++){
	while(	$row = $db->sql_fetchrow($result2)){
		$template_main .= "<tr><td><span class='c5'>".$row["id_magie"]."</span></td>";
		$template_main .= "<td><span class='c0'>".$row["nom"]."</span></td>";
		$template_main .= "<td>".$row["type"]."</td>";
		$template_main .= "<td>".$row["sous_type"]."</td>";
		$coutPA = $row["coutpa"];
		$coutPI = $row["coutpi"];
		if ($coutPA=="") 
		        $coutPA=$liste_pas_actions["Magie"];
		if ($coutPI=="") 
		        $coutPI=$liste_pis_actions["Magie"];		
		$template_main .= "<td align='right'>".$coutPA."</td>";
		$template_main .= "<td align='right'>".$coutPI."</td>";
		$template_main .= "<td align='right'>".$row["coutpo"]."</td>";
		$template_main .= "<td align='right'>".$row["coutpv"]."</td>";

		$degatmin = $row["degats_min"];
		$degatmax = $row["degats_max"];
		$template_main .= "<td><span class='c5'> de $degatmin  $degatmax"."</span></td>";
		$template_main .= "<td>".$row["place"]."</td>";
		$template_main .= "<td>".$row["charges"]."</td>";
		$template_main .= "<td>".$row["caracteristique"]."</td>";
		$template_main .= "<td>".$row["competence"]."</td>";
		$ano = $row["anonyme"];
		
		if ( $ano > 0 ) {
		$template_main .= "<td><span class='c7'>Oui"."</span></td>";
		}
		else {
		$template_main .= "<td>Non</td>";
		}
		$perm = $row["permanent"];
		if ( $perm > 0 ) {
		$template_main .= "<td><span class='c7'>Oui"."</span></td>";
		}
		else {
		$template_main .= "<td>Non</td>";
		}
		$template_main .= "<td>".$row["prix_base"];
		$template_main .= "</td>";
		if ($row["id_etattempspecifique"]<>"") {
			$SQL = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp =".$row["id_etattempspecifique"];
			$result_etat = $db->sql_query($SQL);
			$row_etat = $db->sql_fetchrow($result_etat);
			$template_main .= "<td>". $row_etat["nom"]."</td>";
		}
		else 
		$template_main .= "<td></td>";
		if ( $row["sortdistant"] > 0 ) {
			$template_main .= "<td><span class='c7'>Oui"."</span></td>";
		}
		else {
			$template_main .= "<td>Non</td>";
		}		
		$template_main .="</tr>";
	}
	$template_main .= "</table>";



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>