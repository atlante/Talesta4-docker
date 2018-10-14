<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: listeActionsTracees.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2010/02/28 22:58:05 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_actionTracees;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	

if (isset($HTTP_POST_VARS['etape']) && (!isset($HTTP_GET_VARS['etape'])) 
        && (!isset($_GET['etape'])) && ($HTTP_POST_VARS['etape']=="deleteALL")
        && $MJ->aDroit($liste_flags_mj["SupprimerActionsTracees"])
        ) {
        $SQL="truncate table ".NOM_TABLE_TRACE_ACTIONS;
        $result2 = $db->sql_query($SQL);
        
}        


	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
	if (!isset($filtreType))
		$filtreType="";
        $template_main .= "filtrer par ";
	if (isset($liste_actions_tracees) && is_array($liste_actions_tracees)) {
        	ksort($liste_actions_tracees);
        	reset($liste_actions_tracees);
        	$toto = array_keys($liste_actions_tracees);
        	$tata = array_keys($liste_actions_tracees);
        	$template_main .= "type: <select name='filtreType' onChange='submit()'>";
        	$template_main .= "<option value=''";
        	if( "" == $filtreType){ $template_main .= " selected='selected'";}
        	$template_main .= ">&nbsp;</option>\n";	
        	$nbTypeQuete = count($liste_actions_tracees);
        	for($i=0;$i<$nbTypeQuete;$i++){
        		$template_main .= "<option value='".$toto[$i]."'";
        		if($toto[$i] == $filtreType){ $template_main .= " selected='selected'";}
        		$template_main .= ">".$tata[$i]."</option>\n";	
        	}
        	$template_main .= "</select>,";
        }

	$template_main .= "Acteur:";

	if (!isset($id_proposePJ))
		$id_proposePJ="";
	if (!isset($filtreType))
		$filtreType="";
	
	$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ORDER BY T1.nom ASC";
	$var=faitSelect("id_proposePJ",$SQL,"",$id_proposePJ,array(),array("&nbsp;")," onChange='submit()' ");
	$template_main .= $var[1];
	
	$template_main .= "</form>";
	
	
	$SQL = " select q.*,  p.nom proposant, l.nom lieu from ".NOM_TABLE_TRACE_ACTIONS." q, ".  NOM_TABLE_PERSO  ." p , ".  NOM_TABLE_LIEU  ." l where l.id_lieu = q.id_lieu and q.id_acteur = p.id_perso";
	$SQLwhere="";
	if(isset($filtreType) && $filtreType<>"")
		$SQLwhere = $SQLwhere . " and action = '". $filtreType."'";

	if(isset($id_proposePJ) && $id_proposePJ<>"")
		$SQLwhere = $SQLwhere . " and id_acteur = '". $id_proposePJ."'";
	if((!isset($tri)) ||$tri=='par_nom_lieu')
		$SQLorder =  " ORDER BY lieu ";
	elseif($tri=='par_duree')
		$SQLorder =  " ORDER BY heure_action ";
	elseif($tri=='par_type')
		$SQLorder =  " ORDER BY action ";
	elseif($tri=='par_proposant')
	 $SQLorder =  " ORDER BY proposant ";
	else
		$SQLorder = " ORDER BY id_trace ";


	if (isset($id_proposePJ) && $id_proposePJ<>"")
		$SQLwhere = $SQLwhere . " and id_acteur = ".$id_proposePJ;
	$SQL =  $SQL . $SQLwhere  . $SQLorder;

	$result2 = $db->sql_query($SQL);	
	$template_main .="<table width='100%'><tr><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_nom_lieu&amp;filtreType=$filtreType&amp;id_proposePJ=$id_proposePJ\"><span class='quete'>Tri par lieu</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_duree&amp;filtreType=$filtreType&amp;id_proposePJ=$id_proposePJ\"><span class='c0'>Tri par date</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_proposant&amp;filtreType=$filtreType&amp;id_proposePJ=$id_proposePJ\"><span class='c0'>Tri par acteur</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_type&amp;filtreType=$filtreType&amp;id_proposePJ=$id_proposePJ\"><span class='c0'>Tri par action</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_id&amp;filtreType=$filtreType&amp;id_proposePJ=$id_proposePJ\"><span class='c0'>Tri par id</span></a></td></tr></table>";
	
	$template_main .= "<table width='100%' class='details'>";
	$template_main .= "<tr><td colspan='14' align='center'>Liste des actions</td></tr>";
	$template_main .= "<tr><td align='center'><span class='c5'>N&deg;</span></td><td align='center'><span class='c0'>Action</span></td><td align='center'>Auteur</td><td align='center'><span class='c5'>Lieu </span></td><td align='center'><span class='c5'>Détail </span></td><td align='center'><span class='c5'>Date</span></td></tr>";
	while($row = $db->sql_fetchrow($result2)){
		$template_main .= "<tr><td><span class='c5'>".$row["id_trace"]."</span></td>";
		$template_main .= "<td>".$row["action"]."</td>";
		$template_main .= "<td align='right'>".$row["proposant"]."</td>";		
		$template_main .= "<td align='right'>".$row["lieu"]."</td>";
		$template_main .= "<td><span class='c5'>".$row['detail']."</span></td>";
		$template_main .= "<td><span class='c5'>".faitDate($row['heure_action'],true)."</span></td>";
		
		$template_main .= "</tr>";
	}
	$template_main .= "</table>";

        if($MJ->aDroit($liste_flags_mj["SupprimerActionsTracees"])){
                $template_main .= "<form action='".NOM_SCRIPT."' method='post'><input type='hidden' name='etape' value='deleteALL' /><input type='submit' value='Supprimer toutes les actions tracees' onclick=\"return confirm('".GetMessage("ConfirmerSupprimerToutesActions")."')\" /></form>";
        }        



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>