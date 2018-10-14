<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: voir_lieu.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/02/28 22:58:11 $

*/
 
require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $voir_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


if(!isset($etape)){$etape=0;}


if($etape=="2"){
	$SQL="delete from ".NOM_TABLE_ENTITECACHEECONNUEDE." where id_entitecachee  = ".$id_entite;
	if ($db->sql_query($SQL, "",BEGIN_TRANSACTION_JEU)) {
		$SQL="delete from ".NOM_TABLE_ENTITECACHEE." where id = ".$id_entite;
		if ($db->sql_query($SQL)) {
			$SQL = "delete from ".NOM_TABLE_PERSOOBJET." where id_clef = ".$id_clef_objet;
			$db->sql_query($SQL,"",END_TRANSACTION_JEU);
		}	
		
	}
	$template_main .= $db->erreur;
	$etape="1";
}




if($etape=="1")
{
	$pos = strpos($id_cible, $sep);
	$tmp=substr($id_cible, $pos+strlen($sep)); 
	$pos2 = strpos($tmp, $sep);
	$libelle=substr($id_cible, $pos+strlen($sep), $pos2); 
	$cheminfichieraudio=substr($tmp, $pos2+strlen($sep)); 
	$id_cible=substr($id_cible, 0,$pos); 

	if ($MJ->wantmusic && $cheminfichieraudio!="")
		if(substr($cheminfichieraudio,0,4)=="http" || file_exists("../lieux/sons/".$cheminfichieraudio)) 
			$template_main .="<embed src='../lieux/sons/". $cheminfichieraudio."' loop='1' hidden autostart='true' />";

/*	$temp="";
	$ext_image="";
	if(file_exists("../lieux/vues/view".$id_cible.".jpg")){
		$ext_image  =".jpg";
	}
	else if(file_exists("../lieux/vues/view".$id_cible.".gif")){
		$ext_image  =".gif";
	}
	else if(file_exists("../lieux/vues/view".$id_cible.".png")){
		$ext_image  =".png";
	} 

	if($ext_image<>""){
		$temp[0]= "<img src='../lieux/vues/view".$id_cible.$ext_image."' height='194' width='400' border='0' alt='image du lieu' />";
	} else {
		$temp[0]= "<img src='../lieux/vues/noview.png' height='194' width='400' border='0' alt=\"Pas d'image de lieu\" />";
	}
	
	if(file_exists("../lieux/descriptions/desc_".$id_cible.".txt")){
		$temp[1]= "../lieux/descriptions/desc_".$id_cible.".txt";
	} else {
		$temp[1]= "../lieux/descriptions/nodesc.txt";
	}
	$content_array = file($temp[1]);
	$content = implode("", $content_array);
	$temp[1]= nl2br($content);
*/
        $temp=	afficheImageLieu($id_cible);
	$template_main .= makeTableau(2, "center", "container", $temp,"","100%",0);
	//menu de modification
	$template_main .="<br /><table width='100%'><tr><td width='33%' height='24' align='center'>";
	if($MJ->aDroit($liste_flags_mj["ModifierDescLieu"])){
		$template_main .= "<form name='modifier_desc_lieu.$phpExtJeu' action='modifier_desc_lieu.$phpExtJeu' method='post'>
		<input type='hidden' name='id_cible' value='$id_cible' />
		<input type='hidden' name='etape' value='1' />
		<input type='hidden' name='precedent' value='voir_lieu.$phpExtJeu' />
		<input type='submit' name='Modifier la description du lieu' value='Modifier la description du lieu' />
		</form>";
	}
	else 	$template_main .= "&nbsp;";
	$template_main .= "</td>";
	$template_main .="<td width='33%' height='24' align='center'>";
	if($MJ->aDroit($liste_flags_mj["CacherObjet"]))
		$template_main .= "<form name='cacher_objet.$phpExtJeu' action='cacher_objet.$phpExtJeu' method='post'>
		<input type='hidden' name='id_lieu' value='$id_cible' />
		<input type='hidden' name='nom_lieu' value='$libelle' />
		<input type='hidden' name='precedent' value='voir_lieu.$phpExtJeu' />
		<input type='submit' name='placer un objet' value='placer un objet' />
		</form>";
	else 	$template_main .= "&nbsp;";
	$template_main .= "</td>";
	$template_main .="<td width='33%' height='24' align='center'>";
	if($MJ->aDroit($liste_flags_mj["ModifierLieu"]))
		$template_main .= "<form name='modifier_lieu.$phpExtJeu' action='modifier_lieu.$phpExtJeu' method='post'>
		<input type='hidden' name='etape' value='1' />
		<input type='hidden' name='id_cible' value='$id_cible' />
		<input type='hidden' name='precedent' value='voir_lieu.$phpExtJeu' />
		<input type='submit' name='Modifer les caract&eacute;ristiques du lieu' value='Modifer les caract&eacute;ristiques du lieu' />
		</form>";
	else 	$template_main .= "&nbsp;";
	$template_main .= "</td></tr></table>";
	$template_main .= "<br />&nbsp;<br />";
	//personnes presentes dans ce lieu 
	$template_main .="<br /><table width='100%'><tr><td width='25%' valign='top'>";
	$SQL_perso = "SELECT nom, id_perso, case when dissimule =1 then 'Non' else 'Oui' end as visible FROM ".NOM_TABLE_PERSO." WHERE id_lieu=".$id_cible." ORDER BY nom";
	$result_perso = $db->sql_query($SQL_perso);
	if($db->sql_numrows($result_perso) == 0) $template_main .="<span class='c1'>Il n'y a personne en ce lieu</span><br />";
	else {
		$template_main .="<span class='c1'>Liste des personnes présentes dans ce lieu:</span><br /><table class='details'><tr><td>nom</td><td>Visible</td></tr>";
		while($row_perso = $db->sql_fetchrow($result_perso)) {
			$nom_perso=$row_perso["nom"];
			$id_perso=$row_perso["id_perso"];
			$visible=$row_perso["visible"];
			$template_main .= "<tr><td><a href=\"javascript:a('../game/voir_desc.$phpExtJeu?id_perso=".$id_perso."&amp;for_mj=1')\"><span class='c0'>$nom_perso</span></a></td><td>$visible</td></tr>";
		}
		$template_main .= "</table>";
	}
	$template_main .="</td><td width='25%' valign='top'>";
	//gestion des objets sur le sol 
	$SQL_objet_sol = "Select distinct T3.id as t3id, T3.nom, T3.id_entite, case when T1.id is not null and T1.id_perso is null then 1 else 0 end as visible from ".NOM_TABLE_ENTITECACHEE." T3 left join ".NOM_TABLE_ENTITECACHEECONNUEDE."   T1 
		 on T3.id = T1.id_entitecachee WHERE T3.type = 1 and T3.id_lieu = ". $id_cible;
	
	$result_objet_sol = $db->sql_query($SQL_objet_sol);
	if($db->sql_numrows($result_objet_sol) == 0) $template_main .="<span class='c1'>Il n'y a pas d'objet sur le sol</span><br />";
	else if($db->sql_numrows($result_objet_sol) > 0) 
		//si objet sur le sol
		{
		$template_main .="<span class='c1'>Il y a un(des) objet(s) sur le sol :</span><br /><table class='details'><tr><td>nom</td><td>Visible</td>";
		if($MJ->aDroit($liste_flags_mj["SupprimerObjet"]))
			$template_main .= "<td>Supprimer l'objet</td>";
		$template_main .= "</tr>";
		//detail des objets:
		$i=0;
		while(	$row_objet_sol = $db->sql_fetchrow($result_objet_sol)) {
			$template_main .= "<tr><td><a href=\"javascript:a('../bdc/objet.$phpExtJeu?num_obj=".$row_objet_sol["id_entite"]."&amp;for_mj=1')\">".$row_objet_sol["nom"]."</a></td><td align='center'>";
			if ($row_objet_sol["visible"]) $template_main .= "Oui"; else $template_main .= "Non";
			$template_main .= "</td>";
			if($MJ->aDroit($liste_flags_mj["SupprimerObjet"]))
				$template_main .= "<td align='center'><form name='form$i' id='form$i' method='post' action='".NOM_SCRIPT."'>
				<input type='checkbox' name='box' value='$i' onclick='submit();' />
				<input type='hidden' name='id_entite' value='".$row_objet_sol["t3id"]."' />
				<input type='hidden' name='id_clef_objet' value='".$row_objet_sol["id_entite"]."' />
				<input type='hidden' name='etape' value='2' />
				<input type='hidden' name='id_cible' value='".$id_cible.$sep.$libelle.$sep.$cheminfichieraudio."' />	 </form></td>";				
			$template_main .= "</tr>";
			$i++;
		}
		$template_main .= "</table>";
		}
	
	$template_main .="</td><td width='25%' valign='top'>";
	//gestion des chemins du lieu 
	$SQL_chemin = "select concat(case when c.id_lieu_1 =". $id_cible." then 'vers ' else 'de ' end,l1.nom) as nom, c.type, case when e.ID is null then 1 else 0 end as visible  from ".NOM_TABLE_LIEU." l1, ".NOM_TABLE_CHEMINS." c left join ".NOM_TABLE_ENTITECACHEE." e 
	on c.id_clef=e.id_entite and e.type=0
	where ( c.id_lieu_1=l1.id_lieu and c.id_lieu_2=". $id_cible.")
	or ( c.id_lieu_2=l1.id_lieu and c.id_lieu_1=". $id_cible.")";
	
	$result_chemin = $db->sql_query($SQL_chemin);
	if($db->sql_numrows($result_chemin) == 0) 
		$template_main .="<span class='c1'>Il n'y a pas de chemin partant ou venant de ce lieu</span><br />";
	else if($db->sql_numrows($result_chemin) > 0) {	//si objet sur le sol
			$template_main .="<span class='c1'>Il y a un(des) chemin(s):</span><br /><table class='details'><tr><td>nom</td><td>Visible</td><td></td></tr>";
			//detail des chemin:
			$i=0;
			while(	$row_chemin = $db->sql_fetchrow($result_chemin)) {
					$template_main .= "<tr><td>".$row_chemin["nom"]."</td><td align='center'>";
					if ($row_chemin["visible"]) $template_main .= "Oui"; else $template_main .= "Non";
					$template_main .= "</td><td align='center'></td></tr>";
					$i++;
			}
			$template_main .= "</table>";
		}
	
	$template_main .="</td><td width='25%' valign='top'>";
	//gestion des quetes publiques du lieu 
	$SQL_quetes = "Select *  FROM ". NOM_TABLE_QUETE ." T2 WHERE T2.id_lieu = ".$id_cible;
	$result_quetes=$db->sql_query($SQL_quetes);
        	
	if($db->sql_numrows($result_quetes) == 0) 
		$template_main .="<span class='c1'>Il n'y a pas de quetes publiques dans ce lieu</span><br />";
	else if($db->sql_numrows($result_quetes) > 0) {	
			$template_main .="<span class='c1'>Il y a une(des) quete(s) publique(s):</span><br /><table class='details'><tr><td>nom</td><td>Type</td><td></td></tr>";
			$i=0;
			while(	$rowQuetes = $db->sql_fetchrow($result_quetes)) {
					$template_main .= "<tr><td>".$rowQuetes["nom_quete"]."</td><td align='center'>";
					$template_main .= $liste_type_quete[$rowQuetes["type_quete"]];
					$template_main .= "</td><td align='center'></td></tr>";
					$i++;
			}
			$template_main .= "</table>";
		}	
	
	$template_main .="</td></tr></table>";
}

if($etape===0){

	if(!isset($msg)){$msg='';}
	$template_main .= "<div class ='centerSimple'>";

	$SQL = "SELECT concat(concat(concat(concat(concat(concat(id_lieu,'$sep'),trigramme),'-'),nom),'$sep'),ifnull( cheminfichieraudio , '' )) as idselect, concat(concat(trigramme,'-'),nom) as labselect FROM ".NOM_TABLE_LIEU." ORDER BY trigramme, nom";
	$var=faitSelect("id_cible",$SQL,"",-1);
	if ($var[0]>0) {
		$template_main .="<form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= "Quel lieu voulez vous voir ?<br />";
		$template_main .= $var[1]."<br />";
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form>";
	}	
	else $template_main .= "Aucun lieu";
	$template_main .= "</div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
