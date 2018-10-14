<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: registrechemin.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.6 $
$Date: 2006/02/23 22:34:32 $

*/
 
require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1); 
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);} 
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_chemin;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);} 
	$typeChemins = array_keys($liste_types_chemins);

	if (!isset($filtreType))
		$filtreType="";
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "filtrer par type: <select name='filtreType' onChange='submit()'>";
	ksort($typeChemins);
	reset($typeChemins);
	$template_main .= "<option value=''";
	if( "" == $filtreType){ $template_main .= " selected='selected'";}
	$template_main .= ">&nbsp;</option>\n";	
	$nb_typesChemin= count($typeChemins);
	for($i=0;$i<$nb_typesChemin;$i++){
		$template_main .= "<option value='".$i."'";
		if("$i" == $filtreType){ $template_main .= " selected='selected'";}
		$template_main .= ">".$typeChemins[$i]."</option>\n";	
	}
	$template_main .= "</select>";
	
	
	$SQLtrigramme = "SELECT distinct trigramme as idselect, trigramme as labselect FROM ".NOM_TABLE_LIEU;
	if (!isset($filtretrigrammearrivee))
		$filtretrigrammearrivee="";
	if (!isset($filtretrigrammedepart))
		$filtretrigrammedepart="";
		
	$vartrigrammedepart=faitSelect("filtretrigrammedepart",$SQLtrigramme,"",$filtretrigrammedepart, array(),array("&nbsp;")," onChange='submit()' ");
	$vartrigrammearrivee=faitSelect("filtretrigrammearrivee",$SQLtrigramme,"",$filtretrigrammearrivee, array(),array("&nbsp;")," onChange='submit()' ");
	$template_main .=", trigramme du lieu de départ : " . $vartrigrammedepart[1];
	$template_main .=", trigramme du lieu de d'arrivée: " . $vartrigrammearrivee[1];
	
   	$template_main .= "</form>"; 
      $SQL = "Select T1.id_clef as idselect,T1.type as type, T2.trigramme as trigrammedepart, T3.trigramme as trigrammearrivee,concat(concat(T2.trigramme,'-'),T2.nom) as labselect1, concat(concat(T3.trigramme,'-'),T3.nom) as labselect2,T1.difficulte as difficulte,T1.pass as pass,T1.distance as distance from ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2,".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu";
	if(isset($filtreType) && $filtreType<>"")
		$SQL = $SQL . " and type = '". $filtreType."'";
	if(isset($filtretrigrammedepart) && $filtretrigrammedepart<>"")
		$SQL = $SQL . " and T2.trigramme = '". $filtretrigrammedepart."'";
	if(isset($filtretrigrammearrivee) && $filtretrigrammearrivee<>"")
		$SQL = $SQL . " and T3.trigramme = '". $filtretrigrammearrivee."'";
	if((!isset($tri)) ||$tri=='par_id')
		$SQL = $SQL . " ORDER BY id_clef ";
	elseif($tri=='par_type')
		$SQL = $SQL . " ORDER BY type ";
	elseif($tri=='par_trigrammeDepart')
		$SQL = $SQL . " ORDER BY trigrammedepart ";
	elseif($tri=='par_trigrammeArrivee')
		$SQL = $SQL . " ORDER BY trigrammearrivee ";


   $result2 = $db->sql_query($SQL);
   $template_main .="<table width='100%'><tr><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_trigrammeDepart&amp;filtreType=$filtreType&amp;filtretrigrammearrivee=$filtretrigrammearrivee&amp;filtretrigrammedepart=$filtretrigrammedepart\"><span class='c0'>Tri par trigramme du lieu de départ</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_trigrammeArrivee&amp;filtreType=$filtreType&amp;filtretrigrammearrivee=$filtretrigrammearrivee&amp;filtretrigrammedepart=$filtretrigrammedepart\"><span class='c0'>Tri par trigramme du lieu d'arrivée</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_type&amp;filtreType=$filtreType&amp;filtretrigrammearrivee=$filtretrigrammearrivee&amp;filtretrigrammedepart=$filtretrigrammedepart\"><span class='c0'>Tri par type</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_id&amp;filtreType=$filtreType&amp;filtretrigrammearrivee=$filtretrigrammearrivee&amp;filtretrigrammedepart=$filtretrigrammedepart\"><span class='c0'>Tri par id</span></a></td></tr></table>";
       
   $template_main .= "<table width='100%' class='details'>"; 
   $template_main .= "<tr><td colspan='7' align='center'><span class='c7'>Table des chemins</span></td></tr>"; 
   $template_main .= "<tr><td><span class='c5'>Numéro</span></td>   <td><span class='c0'>Départ</span></td>   <td><span class='c0'>Arrivée</span></td>"; 
   $template_main .= "<td><span class='c5'>type</span></td>   <td><span class='c0'>difficulte</span></td>   <td><span class='c7'>pass </span></td>   <td><span class='c7'>distance </span></td>"; 
   //for($i=0;$i<$db->sql_numrows($result2);$i++){ 
	while($row2 = $db->sql_fetchrow($result2)) {
	      $template_main .= "<tr><td><span class='c5'>".$row2["idselect"]."</span></td>"; 
	      $template_main .= "<td><span class='c0'>".$row2["labselect1"]."</span></td>"; 
	      $template_main .= "<td><span class='c0'>".$row2["labselect2"]."</span></td>"; 
	      //$template_main .= "<td><span class='c5'>".$typeChemins[$row2["type"]]."</span></td>"; 
	      $template_main .= "<td><span class='c5'>".array_search($row2["type"], $liste_types_chemins)."</span></td>"; 
	      $template_main .= "<td><span class='c0'>";
	      if ($row2["difficulte"]!="") $template_main .= $row2["difficulte"]; else $template_main .= "&nbsp;";
	      $template_main .= "</span></td>"; 
	      $template_main .= "<td><span class='c7'>";
	      if ($row2["pass"]!="") $template_main .= $row2["pass"]; else $template_main .= "&nbsp;";
	      $template_main .= "</span></td>"; 
	      $template_main .= "<td><span class='c7'>";
	      if ($row2["distance"]!="") $template_main .= $row2["distance"]; else $template_main .= "&nbsp;";
	      $template_main .= "</span></td>"; 
	      $template_main .= "</tr>"; 
   } 
    $template_main .= "</table>"; 
    
    

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);} 
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);} 
?> 
