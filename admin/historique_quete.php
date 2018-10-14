<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: historique_quete.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:53:18 $

*/

require_once("../include/extension.inc");
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $historique_Quete;
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


if(!isset($etape)){$etape=0;}


if($etape==1){
        
		$pos = strpos($id_cible, $sep);
		$libelle=substr($id_cible, $pos+strlen($sep)); 
		$id_cible=substr($id_cible, 0,$pos); 
		$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
		if (!isset($filtreetat))
			$filtreetat="";
	
		$template_main .= "filtrer par etat: <select name='filtreetat' onChange='submit()'>";
		ksort($liste_etat_quete);
		reset($liste_etat_quete);
		$toto = array_keys($liste_etat_quete);
		$tata = array_values($liste_etat_quete);
	
		$template_main .= "<option value=''";
		if( "" == $filtreetat){ $template_main .= " selected='selected'";}
		$template_main .= ">&nbsp;</option>\n";	
		$nbEtatsQuete = count($liste_etat_quete);
		for($i=0;$i<$nbEtatsQuete;$i++){
			$template_main .= "<option value='".$toto[$i]."'";
			if($toto[$i] == $filtreetat){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select>";
	/*
		if (!isset($proposeparetat))
			$proposeparetat="";
		$template_main .= ",Proposant : <select name='proposeparetat' onchange='selectionneMJ_PJ();submit();'>";
		$toto = array_keys($liste_etat_propose_quete);
		$tata = array_values($liste_etat_propose_quete);
		$template_main .= "\t<option value=''>&nbsp;</option>";
		for($i=0;$i<count($liste_etat_propose_quete);$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $proposeparetat){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select>&nbsp;";
	
		if (!isset($id_proposeMJ))
			$id_proposeMJ="";
	*/	if (!isset($id_proposePJ))
			$id_proposePJ="";
	/*	if (!isset($cyclique))
			$cyclique="";
	
		$SQL = "Select T1.id_mj as idselect, concat(concat(T1.nom,' - '),T1.titre) as labselect from ".NOM_TABLE_MJ." T1 ORDER BY T1.nom ASC";
		$var=faitSelect("id_proposeMJ",$SQL,"",$id_proposeMJ,array(),array("&nbsp;")," onChange='submit()' ");
		$template_main .= $var[1];
		$template_main .= "&nbsp;";		
	*/	
		$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ORDER BY T1.nom ASC";
		$var=faitSelect("id_proposePJ",$SQL,"",$id_proposePJ,array(),array("&nbsp;")," onChange='submit()' ");
		$template_main .= ", par perso " . $var[1];
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "<input type='hidden' name='id_cible' value='$id_cible' />";
		$template_main .= "</form>";
		
		$SQL1 = " select q.*, concat('MJ ', m.nom) proposant, perso.nom PJ,pq.id_persoquete, pq.debut, pq.fin, pq.etat from ".NOM_TABLE_QUETE." q, ".  NOM_TABLE_MJ  ." m, ".NOM_TABLE_PERSO_QUETE ." pq , ".  NOM_TABLE_PERSO  ." perso where q.proposepar = m.id_mj and proposepartype=1 and perso.id_perso = pq.id_perso and pq.id_quete = q.id_quete and q.id_quete = ".$id_cible ;
		$SQL2 = " select q.*, concat( 'PJ ', p.nom) proposant, perso.nom PJ,pq.id_persoquete, pq.debut, pq.fin, pq.etat from ".NOM_TABLE_QUETE." q, ".  NOM_TABLE_PERSO  ." p, ".NOM_TABLE_PERSO_QUETE ." pq , ".  NOM_TABLE_PERSO  ." perso   where q.proposepar = p.id_perso and proposepartype=2 and perso.id_perso = pq.id_perso and pq.id_quete = q.id_quete and q.id_quete = ".$id_cible ;
		$SQLwhere="";
		if(isset($filtreetat) && $filtreetat<>"")
			$SQLwhere = $SQLwhere . " and etat_quete = '". $filtreetat."'";
		if (isset($id_proposePJ)&& $id_proposePJ<>"")	
			$SQLwhere = $SQLwhere . " and pq.id_perso = '". $id_proposePJ."'";
	
/*		if(isset($cyclique) && $cyclique<>"")
			$SQLwhere = $SQLwhere . " and cyclique = '". $cyclique."'";
*/		if((!isset($tri)) ||$tri=='par_nom')
			$SQLorder =  " ORDER BY PJ ";
		elseif($tri=='par_fin')
			$SQLorder =  " ORDER BY fin ";
		elseif($tri=='par_etat')
			$SQLorder =  " ORDER BY etat_quete ";
		elseif($tri=='par_debut')
			$SQLorder =  " ORDER BY debut ";
		elseif($tri=='par_etat')
		 $SQLorder =  " ORDER BY etat ";
		else
			$SQLorder = " ORDER BY id_persoquete ";
	
		 $SQL =  $SQL1 . $SQLwhere . " union " . $SQL2 . $SQLwhere  . $SQLorder;
		$result2 = $db->sql_query($SQL);
		$template_main .="<table width='100%'><tr><td align='center'><a href=\"".NOM_SCRIPT."?tri=par_nom&amp;filtreetat=$filtreetat&amp;id_proposePJ=$id_proposePJ&amp;etape=$etape&amp;id_cible=$id_cible\"><span class='quete'>Tri par nom</span></a></td>
				<td align='center'><a href=\"".NOM_SCRIPT."?tri=par_debut&amp;filtreetat=$filtreetat&amp;id_proposePJ=$id_proposePJ&amp;etape=$etape&amp;id_cible=$id_cible\"><span class='c0'>Tri par date de debut</span></a></td>
				<td align='center'><a href=\"".NOM_SCRIPT."?tri=par_fin&amp;filtreetat=$filtreetat&amp;id_proposePJ=$id_proposePJ&amp;etape=$etape&amp;id_cible=$id_cible\"><span class='c0'>Tri par date de fin</span></a></td>
				<td align='center'><a href=\"".NOM_SCRIPT."?tri=par_etat&amp;filtreetat=$filtreetat&amp;id_proposePJ=$id_proposePJ&amp;etape=$etape&amp;id_cible=$id_cible\"><span class='c0'>Tri par etat</span></a></td>
				<td align='center'><a href=\"".NOM_SCRIPT."?tri=par_id&amp;filtreetat=$filtreetat&amp;id_proposePJ=$id_proposePJ&amp;etape=$etape&amp;id_cible=$id_cible\"><span class='c0'>Tri par id</span></a></td></tr></table>";
		
		$template_main .= "<table width='100%' class='details'>";
		$template_main .= "<tr><td colspan='14' align='center'>Liste des PJ ayant fait la quete ".span($libelle,"quete")."</td></tr>";
		$template_main .= "<tr><td align='center'><span class='c5'>N&deg;</span></td><td align='center'><span class='c0'>nom du PJ</span></td><td align='center'>etat de sa quete</td><td align='center'><span class='c5'>Date de debut</span></td><td align='center'><span class='c2'>Date de fin</span></td></tr>";
		while($row = $db->sql_fetchrow($result2)){
			$template_main .= "<tr><td><span class='c5'>".$row["id_persoquete"]."</span></td>";
			$template_main .= "<td><span class='quete'>".$row["PJ"]."</span></td>";
			$template_main .= "<td>".$liste_etat_quete[$row["etat"]]."</td>";
			$template_main .= "<td>".$row["debut"]."</td>";
			$template_main .= "<td>".$row["fin"]."</td>";
			
			$template_main .= "</tr>";
		}
		$template_main .= "</table>";
}


if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= GetMessage("historiqueQuete")."<br />";
	$SQL ="Select concat(concat(T1.id_quete,'$sep'),T1.nom_quete) as idselect, nom_quete as labselect from ".NOM_TABLE_QUETE." T1 ORDER BY T1.nom_quete ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}


if($etape>=-1 && $etape <=1){
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}
?>