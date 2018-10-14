<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: liste_ppa.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:54:20 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_ppa;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";

	$select = "SELECT ppa.id_ppa, p.nom, ppa.date_ppa, ppa.detail_ppa,  mj.nom nom_mj, qte_pa, qte_pi ";	
	$from = " FROM ".NOM_TABLE_REGISTRE." p, ".NOM_TABLE_PPA." ppa, ".NOM_TABLE_MJ." mj ";
	$where= " where p.id_perso=ppa.id_perso and ppa.id_mj=mj.id_mj ";


	if (!isset($id_proposeMJ))
		$id_proposeMJ="";
	if (!isset($id_proposePJ))
		$id_proposePJ="";
		
	if (isset($id_proposeMJ) && $id_proposeMJ<>"") 
		$where = $where . " and ppa.id_mj = ".$id_proposeMJ;	

	if (isset($id_proposePJ) && $id_proposePJ<>"")
		$where = $where . " and p.id_perso = ".$id_proposePJ;
       	
	$SQL = "Select distinct T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1, ".NOM_TABLE_PPA." T2 where T2.id_perso= T1.id_perso ORDER BY T1.nom ASC";
	$var=faitSelect("id_proposePJ",$SQL,"",$id_proposePJ,array(),array("&nbsp;")," onChange='submit()' ");
        if ($var[0]>1) {
                $template_main .= "filtrer par ";
	        $template_main .= " PJ proposant";		
	        $template_main .= $var[1];
	}
       	
	$SQL = "Select distinct T1.id_mj as idselect, concat(concat(T1.nom,' - '),T1.titre) as labselect from ".NOM_TABLE_MJ." T1, ".NOM_TABLE_PPA." T2 where T2.id_mj= T1.id_mj ORDER BY T1.nom ASC";
	$var=faitSelect("id_proposeMJ",$SQL,"",$id_proposeMJ,array(),array("&nbsp;")," onChange='submit()' ");
	if ($var[0]>1) {        
	        $template_main .= "&nbsp; MJ receveur";
	        $template_main .= $var[1];
        }
        $urlTRI="";

	$template_main .= "</form>";
	$SQL= $select . $from .$where;
	
	if(!isset($tri))
		$SQL = $SQL . " ORDER BY ppa.id_ppa ASC";	
	else {	
	    /* Par nom */
	    if($tri=='par_nom_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.nom ASC";
	        
	    }
	    elseif($tri=='par_nom_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.nom DESC";
	        
	    }
	
	    /* Par nom */
	    elseif($tri=='par_mj_asc')
	    {
	    $SQL = $SQL . " ORDER BY nom_mj ASC";
	        
	    }
	    elseif($tri=='par_mj_desc')
	    {
	    $SQL = $SQL . " ORDER BY nom_mj DESC";
	        
	    }
	    
	    
	    elseif($tri=='par_qte_pi_asc')
	    {
	    $SQL = $SQL . " ORDER BY qte_pi ASC";
	        
	    }
	    elseif($tri=='par_qte_pi_desc')
	    {
	    $SQL = $SQL . " ORDER BY qte_pi DESC";
	        
	    }

	    elseif($tri=='par_qte_pa_asc')
	    {
	    $SQL = $SQL . " ORDER BY qte_pa ASC";
	        
	    }
	    elseif($tri=='par_qte_pa_desc')
	    {
	    $SQL = $SQL . " ORDER BY qte_pa DESC";
	        
	    }	    
	    /* Par Date PPA */
	    
	    elseif($tri=='par_la_asc')
	    {
	    $SQL = $SQL . " ORDER BY date_ppa ASC";
	        
	    }
	    elseif($tri=='par_la_desc')
	    {
	    $SQL = $SQL . " ORDER BY date_ppa DESC";
	        
	    }
        }
        /* Fin de Recupération des variables dans la SQL */
	$result2 = $db->sql_query($SQL);
	if ($db->sql_numrows($result2)) {
                $colspan = $db->sql_numfields($result2);
        	$template_main .= "<table class='detailscenter'>";
                $template_main .= "<tr><td colspan='$colspan' align='center'><span class='c7'>Liste des PPA à traiter (Cliquez sur les titres pour ranger dans l'ordre croissant ou décroissant)</span></td></tr>";
        
        	$template_main .= "<tr><td>";
        	if((!isset($tri)) ||$tri=='par_id_desc')
        	{    
        		$template_main .= "<a href='".NOM_SCRIPT."?tri=par_id_asc".$urlTRI."'><span class='c5'>N&deg;</span></a>";    
        	}
        	else
        	{
        		$template_main .= "<a href='".NOM_SCRIPT."?tri=par_id_desc".$urlTRI."'><span class='c5'>N&deg;</span></a>";
        	}	
        	$template_main .= "</td><td>";
                if((!isset($tri)) ||$tri=='par_nom_asc')
                {    
                    $template_main .= "<a href='".NOM_SCRIPT."?tri=par_nom_desc".$urlTRI."'><span class='c0'>Nom du PJ</span></a>";
                }
                else
                {
                    $template_main .= "<a href='".NOM_SCRIPT."?tri=par_nom_asc".$urlTRI."'><span class='c0'>Nom du PJ</span></a>";
                }
        	
        	$template_main .= "</td><td>";
        
            /* MJ */
            if((!isset($tri)) ||$tri=='par_mj_asc')
            {    
            $template_main .= "<a href='".NOM_SCRIPT."?tri=par_mj_desc".$urlTRI."'>MJ</a>";
            }
            else
            {
            $template_main .= "<a href='".NOM_SCRIPT."?tri=par_mj_asc".$urlTRI."'>MJ</a>";
            }
        
        	$template_main .= "</td><td>";
            /* QtePA */
            if((!isset($tri)) ||$tri=='par_qte_pa_asc')
            {    
            $template_main .= "<a href='".NOM_SCRIPT."?tri=par_qte_pa_desc".$urlTRI."'>Quantité de PA</a>";
            }
            else
            {
            $template_main .= "<a href='".NOM_SCRIPT."?tri=par_qte_pa_asc".$urlTRI."'>Quantité de PA</a>";
            }
        
        	$template_main .= "</td><td>";
            /* QtePI */
            if((!isset($tri)) ||$tri=='par_qte_pi_asc')
            {    
            $template_main .= "<a href='".NOM_SCRIPT."?tri=par_qte_pi_desc".$urlTRI."'>Quantité de PI</a>";
            }
            else
            {
            $template_main .= "<a href='".NOM_SCRIPT."?tri=par_qte_pi_asc".$urlTRI."'>Quantité de PI</a>";
            }
        
        	$template_main .= "</td><td> Détail</td><td>";
        
        	/* date_ppa */
        	if((!isset($tri)) ||$tri=='par_la_asc')
        	{    
        	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_la_desc".$urlTRI."'><span class='c0'>Date de PPA</span></a>";
        	}
        	else
        	{
        	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_la_asc".$urlTRI."'><span class='c0'>Date de PPA</span></a>";
        	}
        	$template_main .= "</td></tr>";
        	while($row = $db->sql_fetchrow($result2)) {
        		$template_main .= "<tr><td><span class='c5'>".$row["id_ppa"]."</span></td>";
        		$template_main .= "<td><span class='c0'>".$row["nom"]."</span></td>";
        		$template_main .= "<td><span class='c0'>".$row["nom_mj"]."</span></td>";
        		$template_main .= "<td><span class='c0'>".$row["qte_pa"]."</span></td>";
        		$template_main .= "<td><span class='c0'>".$row["qte_pi"]."</span></td>";
        		$detail_ppa = $row["detail_ppa"];
        		$template_main .= "<td>$detail_ppa</td>";
        		$date_ppa = $row["date_ppa"];				
        		$template_main .= "<td><span class='c12'>".timestampTostring($date_ppa)."</span></td>";
        		
        		$template_main .= "</tr>";
        	}
            $template_main .= "</table>";
	}
	else $template_main .= "Aucun PPA en attente";
	
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
