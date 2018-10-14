<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: infospj.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.24 $
$Date: 2010/05/15 08:45:08 $

*/

	if (NOM_SCRIPT==("supprimer_bestiaire.".$phpExtJeu)||NOM_SCRIPT==("supprimer_monstre.".$phpExtJeu)||NOM_SCRIPT==("supprimer_pj.".$phpExtJeu)|| NOM_SCRIPT==("cimetiere.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }


	$template_main .="<input type='hidden' name='pnj' value='".$pnj."' />";
	$template_main .= "<table class='detailscenter'>";
	$template_main .= "<tr><td colspan='2'><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_pnj.htm')\">Aide</a></td></tr>";
	$template_main .= "<tr><td>nom du PJ : </td><td><input type='text' $readonly name='nom' value='".$nom."' size='50' maxlength='25' /></td></tr>";
	//if ($pnj>=1) 
		$template_main .= "<tr><td>Mot de passe (laisser vide pour conserver tel quel)</td><td><input type='text' $readonly name='pass' value='' size='35' maxlength='50' /></td></tr>";
	if ($pnj<=1) 
		$template_main .= "<tr><td>email du PJ : </td><td><input type='text' name='email' $readonly value='".$email."' size='50' maxlength='80' /></td></tr>\n";
        else $email="";
	$valeurVide="";
	$SQLType = "select id_typeetattemp, nomtype, critereinscription from ".NOM_TABLE_TYPEETAT." where critereinscription>=1";
        $resultType = $db->sql_query($SQLType);
	while(	$rowType = $db->sql_fetchrow($resultType)) {
		if ($pnj) 
			$SQL ="Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp AND T2.nomtype  = '".$rowType['nomtype']."' ORDER BY T1.id_etattemp";
		else 	$SQL ="Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp AND T2.nomtype  = '".$rowType['nomtype']."' and utilisableinscription=1 ORDER BY T1.id_etattemp";
		$id_etat = "id_".preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
		if ($rowType['critereinscription']==2)
			$valeurNulle=array();
		else 	$valeurNulle=array("&nbsp;");
		if (isset(${$id_etat}))
			$var = faitSelect($id_etat,$SQL,$disabled, ${$id_etat},array(),$valeurNulle);
		else 	$var = faitSelect($id_etat,$SQL,$disabled, "",array(),$valeurNulle);

		if (($rowType['critereinscription']==2 && $var[0]>0)
		|| ($rowType['critereinscription']==1 && $var[0]>1)) {	
			$template_main .= "<tr><td>".$rowType['nomtype']."</td>";
			$template_main .= "<td>";
			$template_main .= $var[1];	
			$template_main .= "</td></tr>";
		}
		else $valeurVide.="<input type='hidden' name='$id_etat' value='' />";

	}    
	
	$template_main .= "<tr><td>PA du PJ : </td><td><input type='text'$readonly  name='pa' value='".$pa."' size='4' maxlength='4' /></td></tr>\n";
	$template_main .= "<tr><td>PO du PJ : </td><td><input type='text' $readonly name='po' value='".$po."' size='4' maxlength='4' /></td></tr>\n";
	if ($pnj<=1) 
	        $template_main .= "<tr><td>PO en banque du PJ : </td><td><input type='text' $readonly name='banque' value='".$banque."' size='4' maxlength='4' /></td></tr>\n";
	$template_main .= "<tr><td>PV du PJ : </td><td><input type='text' $readonly name='pv' value='".$pv."' size='4' maxlength='4' /></td></tr>\n";
	$template_main .= "<tr><td>PI du PJ : </td><td><input type='text' $readonly name='pi' value='".$pi."' size='4' maxlength='4' /></td></tr>\n";
	if ($pnj<>2) {
		$template_main .= "<tr><td>Lieu Actuel :</td><td>";
		$SQL = "SELECT id_lieu as idselect, concat(concat(trigramme,'-'),nom) as labselect FROM ".NOM_TABLE_LIEU." ORDER BY trigramme, nom";
		$var=faitSelect("id_lieu",$SQL,$disabled ,$id_lieu);
		$template_main .= $var[1];	
		$template_main .= "</td></tr>\n";
	}	
	$template_main .= "<tr><td>Dissimulé dans le Lieu :</td><td>".faitOuiNon("dissimule",$disabled ,$dissimule)."</td></tr>";
	$template_main .= "<tr><td>Intervalle de Remise des PA: </td><td>Toutes les <input type='text' $readonly name='interval_remisepa' value='";if (basename($HTTP_SERVER_VARS['PHP_SELF'])=="creerpnj.".$phpExtJeu) $template_main .= INTERVAL_REMISEPA; else $template_main .= $interval_remisepa; $template_main .= "' size='4' /> heures</td></tr>\n";
	$template_main .= "<tr><td>Intervalle de Remise des PI: </td><td>Toutes les <input type='text' $readonly name='interval_remisepi' value='";if (basename($HTTP_SERVER_VARS['PHP_SELF'])=="creerpnj.".$phpExtJeu) $template_main .= INTERVAL_REMISEPI; else $template_main .= $interval_remisepi; $template_main .="' size='4' /> heures</td></tr>\n";


	$template_main .= "<tr><td>R&eacute;action lors d'une aggression ou d'un vol</td><td><select $disabled name='reaction'>";
		$toto = array_values($liste_reactions);
		$tata = array_keys($liste_reactions);
		for($i=0;$i<count($liste_reactions);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if ($tata[$i]== $reaction) $template_main .= " selected='selected'";			
			$template_main .= ">".$toto[$i]."</option>\n";	
		}
	$template_main .= "</select></td></tr>";
	
	if ($pnj) {
		$template_main .= "<tr><td>Relation (Attitude lors d'une arriv&eacute;e d'un PJ)</td><td><select $disabled name='relation'>";
		$toto = array_values($liste_relations);
		$tata = array_keys($liste_relations);
		for($i=0;$i<count($liste_relations);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if ($tata[$i]== $relation) $template_main .= " selected='selected'";			
			$template_main .= ">".$toto[$i]."</option>\n";	
		}
		$template_main .= "</select></td></tr>";

		$template_main .= "<tr><td>Action surprise lors d'une arriv&eacute;e d'un PJ</td><td><select $disabled name='actionsurprise'>";
		$toto = array_values($liste_ActionSurprise);
		$tata = array_keys($liste_ActionSurprise);
		for($i=0;$i<count($liste_ActionSurprise);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if ($tata[$i]== $actionsurprise) $template_main .= " selected='selected'";			
			$template_main .= ">".$toto[$i]."</option>\n";	
		}
		$template_main .= "</select></td></tr>";

		$template_main .= "<tr><td>Pourcentage pour que le PJ/PNJ voit un PJ arriver dans le lieu (et declenche son actionSurprise) : </td><td><input type='text'$readonly  name='pourcentage_reaction' value='".$pourcentage_reaction."' size='4' maxlength='4' /></td></tr>\n";

		
		// le if pour eviter d'afficher cela dans creer pnj alors qu'on a pas encore de sort
		if (NOM_SCRIPT<>"creerPNJ.".$phpExtJeu && NOM_SCRIPT<>"creerBestiaire.".$phpExtJeu) {
			$id='id_clef';
			$idname1='nom';
			$sql="select ".NOM_TABLE_PERSOMAGIE.".$id,$idname1 from ".NOM_TABLE_PERSOMAGIE.",".NOM_TABLE_MAGIE." where ".NOM_TABLE_PERSOMAGIE.".id_magie=".NOM_TABLE_MAGIE.".id_magie and ".NOM_TABLE_PERSOMAGIE.".id_perso = ".$id_cible." order by $idname1";
			$results = $db->sql_query($sql);
			$numrows=$db->sql_numrows($results);  
			if ($numrows>0) {  			    
			    $template_main .= "<tr><td>Sort pr&eacute;f&eacute;r&eacute;</td><td><select $disabled name='sortprefere'>  "; 
			    $x=0;  
			    while ($x<$numrows){
				    $row = $db->sql_fetchrow($results);
				    $theId=$row[$id];  
				    $theName1=$row[$idname1];  
				     
				    $template_main .= "<option value=\"$theId\"";
				    if ($theId==$sortprefere) $template_main .= " selected='selected'";
				    $template_main .= ">$theName1 </option>\n";  
				    $x++;  
				    } 
				$template_main .= "	</select></td></tr>";
			} 	
		}		
			
		$template_main .= "<tr><td> Phrase Pr&eacute;f&eacute;r&eacute;e</td>";
		$template_main .= "<td><textarea name='phrasepreferee' rows='10' $readonly cols='40'>";
		$template_main .= $phrasepreferee;
		$template_main .="</textarea></td></tr>";
	} 	


	$template_main .= "<tr><td>description</td>";
	$template_main .= "<td><textarea name='description' cols='40' $readonly rows='10'>";
	$template_main .= $description;
	$template_main .= "</textarea></td></tr>";

	$template_main .= "<tr><td>Background</td>";
	$template_main .= "<td><textarea name='background' cols='40' $readonly rows='10'>";
	$template_main .= $background;
	$template_main .= "</textarea></td></tr>";

	if(defined("IN_FORUM")&& IN_FORUM==1) {
		$template_main .= "<tr><td>image du PJ: </td><td><input type='text' $readonly name='imageforum' maxlength='100' value='"; 
		$template_main .= $imageforum;
		$template_main .="' size='40' />";
		if ($imageforum<>"") {
			$template_main .= "<img src='";
			$template_main .= $forum->URLimageAvatar($imagetype,$imageforum );						
			$template_main .= "' alt='Avatar' border='0' />";		 
		}			
		 $template_main .= "</td></tr>\n";
	}
	else $imageforum="";

	$template_main .= "<tr><td>Commentaires entre MJs</td>";
	$template_main .= "<td><textarea name='commentaires_mj' cols='40' $readonly rows='10'>";
	$template_main .= $commentaires_mj;
	$template_main .= "</textarea></td></tr>";

	$template_main .= "</table>";
	$template_main .= $valeurVide;
?>