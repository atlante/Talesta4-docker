<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: registre.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2010/05/15 08:49:32 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__HTTPGETPOST.PHP")) {include('../include/http_get_post.'.$phpExtJeu);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}


	$liste_type_pj=array(
		"-1"=>"&nbsp;",
		"0"=>"PJ (Joueur)",
		"1"=>"PNJ" ,
		"2"=>"Modele de monstre",
		"3"=>"monstre" 
	);

if (isset($typePJ)) {
	switch(	$typePJ) {
	        case 0:
		case 1:
			$titrepage = $reg_perso;
			break;
		case 2:
			$titrepage = $reg_modele;
			$filtrePJ=2;
			break;
		case 3:
			$titrepage = $reg_monstres;
			$filtrePJ=3;
			break;
	}		
}
else $titrepage = $reg_perso;

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";

	if (!isset($filtrePJ))
		$filtrePJ="-1";

	$valeurPJ = array_search($filtrePJ, $liste_type_pj);
	if ($valeurPJ===FALSE)
		$valeurPJ="-1";	

	$select = "SELECT p.id_perso, p.nom,  p.email, p.ip_joueur, p.lastaction, 
	p.derniere_remisepa,p.derniere_remisepi , p.pnj";
	$from = " FROM ".NOM_TABLE_REGISTRE." p ";
	$where= " where 1=1 ";

	if ($valeurPJ!=2) {
	        $select.= ", l.nom as nom_lieu, l.trigramme ";
	        $from .=" , ".NOM_TABLE_LIEU." l ";
	        $where.=" and p.id_lieu=l.id_lieu  ";
	}        
	else $select.= ", null as nom_lieu, null as trigramme ";
	
	$nbEtats=0;
	$SQLType = "select id_typeetattemp, nomtype from ".NOM_TABLE_TYPEETAT." where critereinscription=2";
        $resultType = $db->sql_query($SQLType);
        $etattemp = array ();
        $urlTRI="";
       	$template_main .= "filtrer par ";
	while(	$rowType = $db->sql_fetchrow($resultType)) {
		$nomTypeVariabilise=strtolower(preg_replace("/[^(a-zA-Z0-9_)]/","",$rowType['nomtype']));
		$select .= ", e".$nbEtats.".nom as ".$nomTypeVariabilise;
		$from .= ", ". NOM_TABLE_PERSOETATTEMP. " pe".$nbEtats.",".NOM_TABLE_ETATTEMPNOM." e".$nbEtats;
		$where .= " and pe".$nbEtats.".id_perso = p.id_perso and pe".$nbEtats.".id_etattemp = e".$nbEtats.".id_etattemp and e".$nbEtats.".id_typeetattemp = ".$rowType['id_typeetattemp'];
		array_push($etattemp, $nomTypeVariabilise);
		
		$SQLetats = "SELECT e.nom as idselect, e.nom as labselect FROM ".NOM_TABLE_ETATTEMPNOM." e WHERE e.id_etattemp > 1 and e.id_typeetattemp = '".$rowType['id_typeetattemp']."'";
		if (!isset(${"filtre".$nomTypeVariabilise}))
			${"filtre".$nomTypeVariabilise}="";
		$varType=faitSelect("filtre".$nomTypeVariabilise,$SQLetats,"",${"filtre".$nomTypeVariabilise}, array(),array("&nbsp;")," onChange='submit()' ");
		$template_main .= $rowType['nomtype']. ": " . $varType[1]. ", ";
		if(isset(${"filtre".$nomTypeVariabilise}) && ${"filtre".$nomTypeVariabilise}<>"")
			$where = $where . " and e".$nbEtats.".nom = '". ${"filtre".$nomTypeVariabilise}."' ";
		$nbEtats++;	
		$urlTRI.="&amp;filtre".$nomTypeVariabilise."=".${"filtre".$nomTypeVariabilise};
	}

	$template_main .=" Type de PJ ";

	
	$var=faitSelect("filtrePJ","","",$liste_type_pj[$valeurPJ],array(),$liste_type_pj," onChange='submit()' ");
	$template_main .= $var[1];
	if(isset($valeurPJ) && $valeurPJ<>"-1")
		$where = $where . " and p.pnj = '". $valeurPJ."' ";	
	
	$template_main .= "</form>";
	$SQL= $select . $from .$where;
	
	if(!isset($tri))
		$SQL = $SQL . " ORDER BY p.id_perso ASC";	
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
	    elseif($tri=='par_pnj_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.pnj ASC";
	        
	    }
	    elseif($tri=='par_pnj_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.pnj DESC";
	        
	    }
	
	
	    
	    /* Par E-mail */
	    
	    elseif($tri=='par_mail_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.email ASC";
	        
	    }
	    elseif($tri=='par_mail_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.email DESC";
	        
	    }
	    
	    /* Par Nom du lieu */
	    
	    elseif($tri=='par_lieu_asc')
	    {
	    $SQL = $SQL . " ORDER BY nom_lieu ASC";
	        
	    }
	    elseif($tri=='par_lieu_desc')
	    {
	    $SQL = $SQL . " ORDER BY nom_lieu DESC";
	        
	    }
	    
	    /*     Par Dernire remise */
	    
	    elseif($tri=='par_dr_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.derniere_remisepa ASC";
	        
	    }
	    elseif($tri=='par_dr_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.derniere_remisepa DESC";
	        
	    }
	    
	    /*     Par Dernire remise */
	    
	    elseif($tri=='par_drpi_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.derniere_remisepi ASC";
	        
	    }
	    elseif($tri=='par_drpi_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.derniere_remisepi DESC";
	        
	    }
	        
	    
	    /* Par Date connexion */
	    
	    elseif($tri=='par_la_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.lastaction ASC";
	        
	    }
	    elseif($tri=='par_la_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.lastaction DESC";
	        
	    }
	    
	    /* Par IP */
	    elseif($tri=='par_ip_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.ip_joueur ASC";
	        }
	    elseif($tri=='par_ip_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.ip_joueur DESC";
	        }
	    /* Par ID */
	    elseif($tri=='par_id_desc')
	    {
	    $SQL = $SQL . " ORDER BY p.id_perso DESC";
	        }
	    elseif($tri=='par_id_asc')
	    {
	    $SQL = $SQL . " ORDER BY p.id_perso ASC";
	      }
	
	    else {
	    	$i=0;
	    	$trouve=false;
	    	while ( (!$trouve) && $i <$nbEtats) {
	    	//foreach (C as $key => $value) {
		    $nomTypeVariabilise=preg_replace("/[^(a-zA-Z0-9_)]/","",$etattemp[$i]);
		    logDate( "tri $tri ///// nomTypeVariabilise par_".$nomTypeVariabilise."_asc ou _desc");
		    if($tri=='par_'.$nomTypeVariabilise.'_asc')
		    {    
	    		$SQL = $SQL . " ORDER BY e".$i.".nom asc";
	    		$trouve=true;
		    }
		    elseif($tri=='par_'.$nomTypeVariabilise.'_desc')
		    {
	    		$SQL = $SQL . " ORDER BY e".$i.".nom desc";
	    		$trouve=true;
		    }
		    else 
		    	$i++;
		}    
	     }	
	}

    /* Fin de Recupration des variables dans la SQL */
	$result2 = $db->sql_query($SQL);

    $colspan = $db->sql_numfields($result2);
	$template_main .= "<table class='detailscenter'>";
    $template_main .= "<tr><td colspan='$colspan' align='center'><span class='c7'>Registre (Cliquez sur les titres pour ranger dans l'ordre croissant ou dcroissant)</span></td></tr>";

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
    $template_main .= "<a href='".NOM_SCRIPT."?tri=par_nom_desc".$urlTRI."'><span class='c0'>Nom</span></a>";
    }
    else
    {
    $template_main .= "<a href='".NOM_SCRIPT."?tri=par_nom_asc".$urlTRI."'><span class='c0'>Nom</span></a>";
    }


	foreach ($etattemp as $value) {
	    if((!isset($tri)) ||$tri=='par_'.preg_replace("/[^(a-zA-Z0-9_)]/","",$value).'_asc')
	    {    
	    $template_main .= "<td><a href='".NOM_SCRIPT."?tri=par_".preg_replace("/[^(a-zA-Z0-9_)]/","",$value)."_desc".$urlTRI."'><span class='c7'>
	    $value</span></a>";
	    }
	    else
	    {
	    $template_main .= "<td><a href='".NOM_SCRIPT."?tri=par_".preg_replace("/[^(a-zA-Z0-9_)]/","",$value)."_asc".$urlTRI."'><span class='c7'>$value</span></a>";
	    }
	    $template_main .= "</td>";
	}	
	$template_main .= "<td>";
	/* E-Mail */
	if((!isset($tri)) ||$tri=='par_mail_asc')
	{    
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_mail_desc".$urlTRI."'><span class='c21'>E-Mail</span></a>";
	}
	else
	{
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_mail_asc".$urlTRI."'><span class='c21'>E-Mail</span></a>";
	}
	$template_main .= "</td><td>";
    /* PNJ */
    if((!isset($tri)) ||$tri=='par_pnj_asc')
    {    
    $template_main .= "<a href='".NOM_SCRIPT."?tri=par_pnj_desc".$urlTRI."'>PJ/PNJ</a>";
    }
    else
    {
    $template_main .= "<a href='".NOM_SCRIPT."?tri=par_pnj_asc".$urlTRI."'>PJ/PNJ</a>";
    }
	$template_main .= "</td><td>";
	/* Lieu */
	if((!isset($tri)) ||$tri=='par_lieu_asc')
	{    
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_lieu_desc".$urlTRI."'><span class='c3'>Nom du Lieu</span></a>";
	}
	else
	{
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_lieu_asc".$urlTRI."'><span class='c3'>Nom du Lieu</span></a>";
	}
	$template_main .= "</td><td>";
	/* Dernire remise PA*/
	if((!isset($tri)) ||$tri=='par_dr_asc')
	{    
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_dr_desc".$urlTRI."'><span class='c12'>Dernire remise PA</span></a>";
	}
	else
	{
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_dr_asc".$urlTRI."'><span class='c12'>Dernire remise PA</span></a>";
	}

	$template_main .= "</td><td>";
	/* Dernire remise PI*/
	if((!isset($tri)) ||$tri=='par_drpi_asc')
	{    
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_drpi_desc".$urlTRI."'><span class='c12'>Dernire remise PI</span></a>";
	}
	else
	{
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_drpi_asc".$urlTRI."'><span class='c12'>Dernire remise PI</span></a>";
	}
	$template_main .= "</td><td>";
	/* Date conexion */
	if((!isset($tri)) ||$tri=='par_la_asc')
	{    
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_la_desc".$urlTRI."'><span class='c0'>Date de Connexion</span></a>";
	}
	else
	{
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_la_asc".$urlTRI."'><span class='c0'>Date de Connexion</span></a>";
	}
	$template_main .= "</td><td>";
	if((!isset($tri)) ||$tri=='par_ip_asc')
	{    
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_ip_desc".$urlTRI."'><span class='c5'>IP</span></a>";
	}
	else
	{
	$template_main .= "<a href='".NOM_SCRIPT."?tri=par_ip_asc".$urlTRI."'><span class='c5'>IP</span></a>";
	}
	$template_main .= "</td></tr>";
	while($row = $db->sql_fetchrow($result2)) {
		$template_main .= "<tr><td><span class='c5'>".$row["id_perso"]."</span></td>";
		$template_main .= "<td><span class='c0'>".$row["nom"]."</span></td>";
		foreach ($etattemp as $value) {
 			 $template_main .= "<td><span class='c7'>".$row[preg_replace("/[^(a-zA-Z0-9_)]/","",$value)]."</span></td>";
		}
		$mail = $row["email"];
		$template_main .= "<td><a href='mailto:$mail'>$mail</a></td>";
		$template_main .= "<td>";
	        $template_main .= $liste_type_pj[$row["pnj"]];
		$template_main .="</td>";
		//$id_lieu=$row["id_lieu"];
		$template_main .= "<td><span class='c3'>".$row["trigramme"]." - ".$row["nom_lieu"]."</span></td>";
		$remise = $row["derniere_remisepa"];
		$remise2 = $row["lastaction"];
				
		$template_main .= "<td><span class='c12'>".timestampTostring($remise)."</span></td>";
		$template_main .= "<td><span class='c12'>".timestampTostring($row["derniere_remisepi"])."</span></td>";
		//ip et date
		$template_main .= "<td><span class='c0'>".timestampTostring($remise2)."</span></td>";
		
		$template_main .= "<td><span class='c5'>";
		if ($row["ip_joueur"]) $template_main .= $row["ip_joueur"];
		else $template_main .= "&nbsp;";
		$template_main .= "</span></td>";
		$template_main .= "</tr>";
	}
    $template_main .= "</table>";
	
	
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
