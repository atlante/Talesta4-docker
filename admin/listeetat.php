<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: listeetat.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.11 $
$Date: 2006/01/31 12:26:18 $

*/

require_once("../include/extension.inc");
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_etat;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
	$SQLtype = "SELECT distinct nomtype as idselect, nomtype as labselect FROM ".NOM_TABLE_TYPEETAT;
	if (!isset($filtreType))
		$filtreType="";	
	if (!isset($critere))
		$critere="";	
	$varType=faitSelect("filtreType",$SQLtype,"",$filtreType, array(),array("&nbsp;")," onChange='submit()' ");
	$template_main .= "filtrer par type: " . $varType[1];
	$template_main .= ", critre d'inscription: <select name='critere' onChange='submit()'>";
		$temp = array("Oui"=>"Oui", "Non"=>"Non");
		ksort($temp);
		reset($temp);
		$tata = array_keys($temp);
		$template_main .= "<option value=''";
		if( "" == $critere){ $template_main .= " selected='selected'";}
		$template_main .= ">&nbsp;</option>\n";	
		for($i=0;$i<count($temp);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if($tata[$i] == $critere){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
	$template_main .= "</select>";	
	$template_main .= "</form>";	
	$SQL = "SELECT e.*, te.* FROM ".NOM_TABLE_ETATTEMPNOM." e,".NOM_TABLE_TYPEETAT. " te  WHERE e.id_etattemp > 1 and te.id_typeetattemp=e.id_typeetattemp ";
	if(isset($filtreType) && $filtreType<>"")
		$SQL = $SQL . " and nomtype = '". $filtreType."'";
	if(isset($critere)) {
	 	if ($critere=="Oui" )
			$SQL = $SQL . " and critereinscription >0";
	 	elseif ($critere=="Non" )
			$SQL = $SQL . " and critereinscription =0";
	}		
	
	if((!isset($tri)) ||$tri=='par_nom')
		$SQL = $SQL . " ORDER BY nom ";
	elseif($tri=='par_type')
		$SQL = $SQL . " ORDER BY nomtype ";
	else
		$SQL = $SQL ." ORDER BY e.id_etattemp ";
	$result2 = $db->sql_query($SQL);
	$template_main .="<table width='100%'><tr><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_nom&amp;filtreType=$filtreType&amp;critere=$critere\"><span class='c0'>Tri par nom</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_type&amp;filtreType=$filtreType&amp;critere=$critere\"><span class='c0'>Tri par type</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_id&amp;filtreType=$filtreType&amp;critere=$critere\"><span class='c0'>Tri par id</span></a></td></tr></table>";
	$template_main .= "<table width='100%' class='detailscenter'>";
	$template_main .= "<tr><td colspan='5' align='center'>Liste des etats</td></tr>";
	$template_main .= "<tr><td>Numero</td><td>nom</td><td>type</td><td>Critre d'inscription ?</td><td>Modifiable par le pj durant le jeu</td><td>Slectionnable par un PJ  l'inscription ?</td><td>Visible ?</td></tr>";
	//for($i=0;$i<$db->sql_numrows($result2);$i++){
	while($row = $db->sql_fetchrow($result2)){
		$template_main .= "<tr><td>".$row["id_etattemp"]."</td><td><a href=\"javascript:a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat=".$row["id_etattemp"]."')\">".span($row["nom"],"etattemp")."</a></td>";
		$template_main .= "<td>". $row["nomtype"]."</td>";		
		switch($row["critereinscription"]) {
			case 0:
				$temp="Non";
				break;
			case 1:
				$temp="Oui (Choix facultatif)";
				break;
			case 2:
				$temp="Oui (Choix obligatoire)";
				break;
		}
		$template_main .= "<td>".$temp."</td>";
		if ($row['modifiableparpj']>0) 			
			$template_main .= "<td>Oui</td>";
		else $template_main .= "<td>Non</td>";			
		if($row["utilisableinscription"] == 1){ $template_main .= "<td>Oui</td>";} else{ $template_main .= "<td>Non</td>";}
		if($row["visible"] == 1){ $template_main .= "<td>Oui</td>";} else{ $template_main .= "<td>Non</td>";}
		$template_main .= "</tr>";
	}
	$template_main .= "</table>";



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>