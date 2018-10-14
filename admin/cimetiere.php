<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: cimetiere.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.23 $
$Date: 2010/02/28 22:58:02 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $cimetiere;
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}

if(($etape=="1") || ($etape==2)) {
	include('./supprimer_pj.'.$phpExtJeu);
}
	

if($etape===0){

	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage!=null)  
		$SQL = "SELECT p.*, u.".$forum->champimage.",u.".$forum->champtypeimage." FROM ".NOM_TABLE_REGISTRE." p left join ".$forum->nomtableUsers." u on u.username = p.nom where p.pv<1 and p.pnj <>2";
	else 	$SQL = "SELECT * FROM ".NOM_TABLE_PERSO." WHERE pv<1 ";
	$result2 = $db->sql_query($SQL);
	$nb_morts = $db->sql_numrows($result2);
	if ($nb_morts>0) {	
		$template_main .= "<table class='detailscenter'>";
		$template_main .= "<tr><td colspan='6' align='center'><span class='c7'>Liste des PJs morts</span></td></tr>";
		$template_main .= "<tr><td><span class='c5'>N&deg;</span></td><td><span class='c0'>nom</span></td><td><span class='c21'>E-mail</span></td><td><span class='c3'>N&deg;Lieu</span></td>
		<td><span class='c12'>PV</span></td><td><span class='c12'>Derni&egrave;re Remise PA</span></td></tr>";
			
		//for($i=0;$i<$db->sql_numrows($result2);$i++){
		while($row = $db->sql_fetchrow($result2)){
			$template_main .= "<tr><td><span class='c5'>".$row["id_perso"]."</span></td>";
			$template_main .= "<td><span class='c0'>".$row["nom"]."</span></td>";
			$mail = $row["email"];
			$template_main .= "<td><a href='mailto:$mail'>$mail</a></td>";
			$template_main .= "<td><span class='c3'>".$row["id_lieu"]."</span></td>";
			$template_main .= "<td><span class='c3'>".$row["pv"]."</span></td>";
/*			$time = time();
			$remise = $row["derniere_remisepa"];
			$remise2 = $row["lastaction"];
			$total = $time - $remise; 
			$heure = ($total - ($total % 3600) ) / 3600;
	        	$secondes_restantes = $total % 3600;
	        	$minutes = ($secondes_restantes - $secondes_restantes % 60 ) / 60;
			$jour = ($total - ($total % 86400) ) / 86400;
			$heure2 = $heure / 24;
			$template_main .= "<td><span class='c3'>".$row["pv"]."</span></td>";
			if ($heure >= 24 )
			{ 
			$template_main .= "<td><span class='c12'>$jour J";
			}
			else
			{
			$template_main .= "<td><span class='c12'>$heure h $minutes min";
			}*/
			$template_main .= "<td><span class='c12'>".timestampTostring($row["derniere_remisepa"])."</span></td></tr>";
		}
		
		$template_main .= "</table>";
	}

	if ($nb_morts>0) {	
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= "<br /><br />".GetMessage("questionPJSupprime")."<br />";
		//$SQL = "SELECT * FROM ".NOM_TABLE_PERSO." WHERE PV<1 ";
		$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1  WHERE pv<1 and pnj<>2 ORDER BY T1.nom ASC";
		$var=faitSelect("id_cible",$SQL,"",-1);
		$template_main .= $var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form>";
               //$template_main .= "<form action='".NOM_SCRIPT."' method='post'><input type='hidden' name='etape' value='deleteALL' /><input type='submit' value='".GetMessage("supprimerMonstresMorts")."' onclick=\"return confirm('".GetMessage("ConfirmerSupprimerTousMonstresMorts")."')\" /></form>";
		$template_main .= "</div>";
	}
	else $template_main .= "<div class ='centerSimple'>".GetMessage("aucunMort")."<br /></div>";

}

	if(!defined("__MENU_ADMIN.PHP")){@include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}

?>

