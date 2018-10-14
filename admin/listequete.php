<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: listequete.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/02/28 22:58:06 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_quete;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	

if (isset($HTTP_POST_VARS['etape']) && (!isset($HTTP_GET_VARS['etape'])) 
        && (!isset($_GET['etape'])) && ($HTTP_POST_VARS['etape']=="deleteALL")
        && $MJ->aDroit($liste_flags_mj["SupprimerQuete"])
        ) {
        $SQL="truncate table ".NOM_TABLE_PERSO_QUETE;
        if ($result2 = $db->sql_query($SQL)) {
                $SQL="truncate table ".NOM_TABLE_RECOMPENSE_QUETE;
                if ($result2 = $db->sql_query($SQL)) {
                        $SQL="truncate table ".NOM_TABLE_QUETE;
                        if ($result2 = $db->sql_query($SQL)) {
                        }         
                }        
                
        }        
        
}        



	$template_main .="<script type='text/javascript'>

		function selectionneMJ_PJ(){
			/*if (document.forms[0].init.value=='')
				document.forms[0].init.value='1';
			*/var propose = document.forms[0].proposepartype.options[document.forms[0].proposepartype.selectedIndex].value;
			if (propose!='') {
				if (propose=='1') {
					document.forms[0].id_proposeMJ.style.display= 'inline';
					//cache pj
					document.forms[0].id_proposePJ.style.display= 'none';
				}
				else {
					document.forms[0].id_proposeMJ.style.display= 'none';
					//montre pj
					document.forms[0].id_proposePJ.style.display= 'inline';
				}
				/*document.forms[0].submit();*/
			}
			else {
				document.forms[0].id_proposePJ.style.display= 'none';
				document.forms[0].id_proposeMJ.style.display= 'none';
			}
			
		}
	
	</script>";
	
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
	if (!isset($filtreType))
		$filtreType="";

	$template_main .= "filtrer par type: <select name='filtreType' onChange='submit()'>";
	ksort($liste_type_quete);
	reset($liste_type_quete);
	$toto = array_keys($liste_type_quete);
	$tata = array_values($liste_type_quete);

	$template_main .= "<option value=''";
	if( "" == $filtreType){ $template_main .= " selected='selected'";}
	$template_main .= ">&nbsp;</option>\n";	
	$nbTypeQuete = count($liste_type_quete);
	for($i=0;$i<$nbTypeQuete;$i++){
		$template_main .= "<option value='".$toto[$i]."'";
		if($toto[$i] == $filtreType){ $template_main .= " selected='selected'";}
		$template_main .= ">".$tata[$i]."</option>\n";	
	}
	$template_main .= "</select>";

	if (!isset($proposepartype))
		$proposepartype="";
	$template_main .= ",Proposant : <select name='proposepartype' onchange='selectionneMJ_PJ();submit();'>";
	$toto = array_keys($liste_type_propose_quete);
	$tata = array_values($liste_type_propose_quete);
	$template_main .= "\t<option value=''>&nbsp;</option>";
	for($i=0;$i<count($liste_type_propose_quete);$i++){
		$template_main .= "\t<option value='".$toto[$i]."'";
		if($toto[$i] == $proposepartype){ $template_main .= " selected='selected'";}
		$template_main .= ">".$tata[$i]."</option>\n";	
	}
	$template_main .= "</select>&nbsp;";

	if (!isset($id_proposeMJ))
		$id_proposeMJ="";
	if (!isset($id_proposePJ))
		$id_proposePJ="";
	if (!isset($cyclique))
		$cyclique="";

	$SQL = "Select T1.id_mj as idselect, concat(concat(T1.nom,' - '),T1.titre) as labselect from ".NOM_TABLE_MJ." T1 ORDER BY T1.nom ASC";
	$var=faitSelect("id_proposeMJ",$SQL,"",$id_proposeMJ,array(),array("&nbsp;")," onChange='submit()' ");
	$template_main .= $var[1];
	$template_main .= "&nbsp;";		
	$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ORDER BY T1.nom ASC";
	$var=faitSelect("id_proposePJ",$SQL,"",$id_proposePJ,array(),array("&nbsp;")," onChange='submit()' ");
	$template_main .= $var[1];
	
	$template_main .= "</form>";
	
	$template_main .="<script type='text/javascript'>
		selectionneMJ_PJ();
	</script>";		
	
	$SQL1 = " select q.*, concat('MJ ', m.nom) proposant from ".NOM_TABLE_QUETE." q, ".  NOM_TABLE_MJ  ." m  where q.proposepar = m.id_mj and proposepartype=1";
	$SQL2 = " select q.*, concat( 'PJ ', p.nom) proposant from ".NOM_TABLE_QUETE." q, ".  NOM_TABLE_PERSO  ." p  where q.proposepar = p.id_perso and proposepartype=2";
	$SQLwhere="";
	if(isset($filtreType) && $filtreType<>"")
		$SQLwhere = $SQLwhere . " and type_quete = '". $filtreType."'";

	if(isset($cyclique) && $cyclique<>"")
		$SQLwhere = $SQLwhere . " and cyclique = '". $cyclique."'";
	if((!isset($tri)) ||$tri=='par_nom')
		$SQLorder =  " ORDER BY nom_quete ";
	elseif($tri=='par_duree')
		$SQLorder =  " ORDER BY duree_quete ";
	elseif($tri=='par_type')
		$SQLorder =  " ORDER BY type_quete ";
	elseif($tri=='par_public')
		$SQLorder =  " ORDER BY public ";
	elseif($tri=='par_proposant')
	 $SQLorder =  " ORDER BY proposant ";
	else
		$SQLorder = " ORDER BY id_quete ";

	if(isset($proposepartype) && $proposepartype<>"" ) {
		if ($proposepartype=="1") {
			if (isset($id_proposeMJ) && $id_proposeMJ<>"") 
				$SQLwhere = $SQLwhere . " and q.proposepar = ".$id_proposeMJ." and proposepartype=1";			
			$SQL =  $SQL1 . $SQLwhere . $SQLorder;
		}
		else {
			if (isset($id_proposePJ) && $id_proposePJ<>"")
				$SQLwhere = $SQLwhere . " and q.proposepar = ".$id_proposePJ." and proposepartype=2";
			$SQL =  $SQL2 . $SQLwhere  . $SQLorder;
		}
	}
   	else $SQL =  $SQL1 . $SQLwhere . " union " . $SQL2 . $SQLwhere  . $SQLorder;
	$result2 = $db->sql_query($SQL);	
	$template_main .="<table width='100%'><tr><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_nom&amp;filtreType=$filtreType&amp;proposepartype=$proposepartype&amp;cyclique=$cyclique\"><span class='quete'>Tri par nom</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_duree&amp;filtreType=$filtreType&amp;proposepartype=$proposepartype&amp;cyclique=$cyclique\"><span class='c0'>Tri par duree</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_proposant&amp;filtreType=$filtreType&amp;proposepartype=$proposepartype&amp;cyclique=$cyclique\"><span class='c0'>Tri par proposant</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_type&amp;filtreType=$filtreType&amp;proposepartype=$proposepartype&amp;cyclique=$cyclique\"><span class='c0'>Tri par type</span></a></td><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_id&amp;filtreType=$filtreType&amp;proposepartype=$proposepartype&amp;cyclique=$cyclique\"><span class='c0'>Tri par id</span></a></td></tr></table>";
	
	$template_main .= "<table width='100%' class='details'>";
	$template_main .= "<tr><td colspan='14' align='center'>Liste des quetes</td></tr>";
	$template_main .= "<tr><td align='center'><span class='c5'>N&deg;</span></td><td align='center'><span class='c0'>nom</span></td><td align='center'>type</td><td align='center'><span class='c5'>Durée (en j)</span></td><td align='right'><span class='c2'>Publique ?</span></td><td align='right'>Refus Possible ? </td><td align='center'>cyclique ?</td><td align='center'><span class='c5'>Proposée par</span></td><td>Réservé aux</td></tr>";
	while($row = $db->sql_fetchrow($result2)){
		$template_main .= "<tr><td><span class='c5'>".$row["id_quete"]."</span></td>";
		$template_main .= "<td><span class='quete'>".$row["nom_quete"]."</span></td>";
		$template_main .= "<td>".$liste_type_quete[$row["type_quete"]]."</td>";
		if ($row["duree_quete"]==-1)
			$duree = "illimitée";
		else $duree = $row["duree_quete"];
		$template_main .= "<td><span class='c5'>".$duree."</span></td>";
		$ano = $row["public"];
		
		if ( $ano > 0 ) {
		$template_main .= "<td><span class='c7'>Oui</span></td>";
		}
		else {
			$template_main .= "<td>Non</td>";
		}

		$ano = $row["refuspossible"];
		
		if ( $ano > 0 ) {
		$template_main .= "<td><span class='c7'>Oui</span></td>";
		}
		else {
			$template_main .= "<td>Non</td>";
		}


		$ano = $row["cyclique"];
		
		if ( $ano > 0 ) {
		$template_main .= "<td><span class='c7'>Oui</span></td>";
		}
		else {
			$template_main .= "<td>Non</td>";
		}
		$template_main .= "<td align='right'>".$row["proposant"]."</td>";		

	
  		if ($row["id_etattempspecifique"]<>"") {
			$SQL = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp =".$row["id_etattempspecifique"];
			$result = $db->sql_query($SQL);
			$row_etat = $db->sql_fetchrow($result);
			$template_main .= "<td>". $row_etat["nom"]."</td>";
		}
		else 
		$template_main .= "<td>&nbsp;</td>";	
		$template_main .= "</tr>";
	}
	$template_main .= "</table>";

        if($MJ->aDroit($liste_flags_mj["SupprimerQuete"])){
                $template_main .= "<form action='".NOM_SCRIPT."' method='post'><input type='hidden' name='etape' value='deleteALL' /><input type='submit' value='Supprimer toutes les quetes' onclick=\"return confirm('".GetMessage("ConfirmerSupprimerToutesQuetes")."')\" /></form>";
        }        



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>