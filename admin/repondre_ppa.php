<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: repondre_ppa.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:54:20 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $repondre_ppa;
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}



	$SQL = "SELECT ppa.id_ppa, p.id_perso, p.nom, ppa.date_ppa, ppa.detail_ppa, qte_pa, qte_pi 
	        FROM ".NOM_TABLE_REGISTRE." p, ".NOM_TABLE_PPA." ppa 
	        where p.id_perso=ppa.id_perso and ppa.id_mj= " . $MJ->ID;
	$result2 = $db->sql_query($SQL);
	$nb_ppa = $db->sql_numrows($result2);
	if ($nb_ppa>0) {	
		$template_main .= "<table class='detailscenter'>";
		$template_main .= "<tr><td colspan='6' align='center'><span class='c7'>Liste des PPA à traiter</span></td></tr>";
		$template_main .= "<tr><td><span class='c5'>N&deg;</span></td><td><span class='c0'>nom du PJ</span></td><td><span class='c21'>Détail</span></td><td>Qté PA</td><td>Qté PI</td><td><span class='c3'>date du PPA</span></td></tr>";
			
		while($row = $db->sql_fetchrow($result2)){
        		$template_main .= "<tr><td>".$row['id_ppa']."</td><td><span class='c0'>".$row["nom"]."</span></td>";
        		$detail_ppa = $row["detail_ppa"];
        		$template_main .= "<td>$detail_ppa</td>";
        		$template_main .= "<td>".$row['qte_pa']."</td>";
        		$template_main .= "<td>".$row['qte_pi']."</td>";
        		$date_ppa = $row["date_ppa"];				
        		$template_main .= "<td><span class='c12'>".timestampTostring($date_ppa)."</span></td></tr>";
		}
		
		$template_main .= "</table>";
	}

if(($etape=="3") ) {
	$etape="1";
	if (isset($nbFaces))
			$retourDe=LanceDe($nbFaces);
	else 	$retourDe="Vous n'avez pas indiqué combien de faces a le dé";
}	


if(($etape=="1") ) {
        $SQL = "Select * from ".NOM_TABLE_PPA." T1, ".NOM_TABLE_REGISTRE." T2 WHERE T2.id_perso = T1.id_perso and id_ppa = ". $id_cible;
        $result2 = $db->sql_query($SQL);
        $row = $db->sql_fetchrow($result2);
        $template_main .= "<div class ='centerSimple'><table><tr><td><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='$id_cible' />";
	$template_main .= "<input type='hidden' name='id_perso' value='".$row['id_perso']."' />";
	$template_main .= "<br />Réponse adressée à ".$row['nom'] ." pour son PPA: ".$row['detail_ppa']."<br />";
	$template_main .= "<textarea name='msg' cols='50' rows='20'></textarea>";
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "</form></td><td><form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= "<input type='hidden' name='etape' value='3' />";
	$template_main .= "<input type='hidden' name='id_cible' value='$id_cible' />";
	if (isset($retourDe)) {
			$template_main .= "Résultat du jet : ";
			if (isset($nbFaces))
				$template_main .= $retourDe ."/" . $nbFaces;
			else $template_main .= $retourDe;	
   }			
$template_main .= "<br />Lancé de dé <input type='text' name='nbFaces' size='10' value=''/> faces <br />".BOUTON_ENVOYER;
	$template_main .= " </form></td></tr></table></div>";
	
}


if($etape==2) {
	$JOUEUR = new Joueur($id_perso,false,false,false,false,false,false);
	$msg_pj = "**** Message de ".span($MJ->nom." (MJ)","mj")." *******<br />".$msg;
	$msg_mj = "**** Message envoy&eacute; &agrave; ".span($JOUEUR->nom,"pj")." *******<br />".$msg;
	$MJ->OutPut($msg_mj,true);
	$JOUEUR->OutPut($msg_pj,false,true);
	$SQL= "delete from ".NOM_TABLE_PPA." where id_ppa = ".$id_cible;
	$result2 = $db->sql_query($SQL);
	$template_main .= "<br />";
	$MJ->OutPut("PPA correctement traité et effacé",true);
	$etape=0;
}
		

if($etape===0){
	if ($nb_ppa>0) {	
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= "<br /><br />Quel PPA voulez vous traiter ?<br />";
		$SQL = "Select T1.id_ppa as idselect, concat('PPA de ', T2.nom) as labselect from ".NOM_TABLE_PPA." T1, ".NOM_TABLE_REGISTRE." T2 WHERE T2.id_perso = T1.id_perso and id_mj = ". $MJ->ID. " ORDER BY T1.date_ppa ASC";
		$var=faitSelect("id_cible",$SQL,"",-1);
		$template_main .= $var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
	}
	else $template_main .= "<div class ='centerSimple'>Vous n'avez aucun PPA en attente <br /></div>";

}

	if(!defined("__MENU_ADMIN.PHP")){@include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}

?>

