<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: lieu.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.17 $
$Date: 2010/05/15 08:44:03 $

*/

	if (NOM_SCRIPT==("supprimer_lieu.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }
	
$template_main .= "<table class='detailscenter' width='60%'>";
	$template_main .= "<tr><td colspan='2'><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_lieu.htm')\">Aide</a></td></tr>";
	$template_main .= "<tr><td>nom du lieu : </td><td><input type='text' $readonly maxlength='50' name='nom' value='".ConvertAsHTML($nom)."' size='35' /></td></tr>";
	$template_main .= "<tr><td>trigramme : </td><td><input type='text' $readonly name='trigramme' value='".$trigramme."' size='5' maxlength='3' /></td></tr>";
	$template_main .= "<tr><td>id_forum (inutile ?) : </td><td><input type='text' $readonly maxlength='10' name='id_forum' value='".$id_forum."' size='5' /></td></tr>";
	$template_main .= "<tr><td>Difficulté de se cacher : </td><td><input type='text' maxlength='2' $readonly name='difficultedesecacher' value='".$difficultedesecacher."' size='5' /></td></tr>";
	$template_main .= "<tr><td>Accessible par Teleport: </td><td>".faitOuiNon("accessible_telp",$disabled,$accessible_telp)."</td></tr>";
	$template_main .= "<tr><td>Fichier audio: (URL externe (http://....) ou fichier dans le répertoire lieux/sons/</td><td><input type='text' $readonly maxlength='50' name='cheminfichieraudio' value='".$cheminfichieraudio."' size='50' /></td></tr>";
/*	$template_main .= "<tr><td>Type Mime du fichier audio: </td><td>";
			$liste_typemime_audio=array(
			"0"=>"audio/x-wav",
			"1"=>"audio/mp3"
		);


			$var=faitSelect("typemimefichieraudio","","",0,array(),$liste_typemime_audio);
			$template_main .= $var[1]. "</td></tr>"; */

	$SQL = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1 WHERE T1.id_etattemp > 1 ORDER BY T1.nom ASC";
	
	$template_main .= "<tr><td>Apparitions de monstres possibles: </td><td>".faitOuiNon("apparition_monstre",$disabled,$apparition_monstre)."</td></tr>";
	$template_main .= "<tr><td>Type de lieu (critère pour les apparitions automatiques de monstres)</td><td>
                <select $disabled name='type_lieu_apparition'>";
		$toto = array_keys($liste_type_lieu_apparition);
		$tata = array_values($liste_type_lieu_apparition);
		$nb=count($liste_type_lieu_apparition);
		for($i=0;$i<$nb;$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $type_lieu_apparition){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select>&nbsp;";
	$template_main .= "</td></tr>";	
	$template_main .= "<tr><td>Accessible uniquement par</td><td>";
	$var=faitSelect("id_etattempspecifique",$SQL,$disabled,$id_etattempspecifique, array(), array("&nbsp;"));	
	$template_main .= $var[1];
	$template_main .= "</td></tr>";				
	//$template_main .= "<tr><td>Provoque Etat (<a href=\"javascript:a('listeetat.'.$phpExtJeu)\">Liste</a>) : </td><td><input type='text' $readonly name='provoqueetat' value='".$provoqueetat."' size='35' maxlength='100' /></td></tr>";
	$tata= array_keys($liste_flags_lieux);
	$toto = array_values($liste_flags_lieux);
	$nb_flags_lieux=count($liste_flags_lieux);
	for($i=0;$i<$nb_flags_lieux;$i++){
		$template_main .= "<tr><td>Peut: ".$tata[$i]."</td><td>";
		if (!isset($flags[$toto[$i]]))
		        $flags[$toto[$i]]="0";
		$template_main .= faitOuiNon("flags[".$toto[$i]."]",$disabled,$flags[$toto[$i]]);
		$template_main .= "</td></tr>";
	}

	$template_main .= "<tr><td>Image du lieu: </td><td>";
/*	$ext_image="";

	if (NOM_SCRIPT==("supprimer_lieu.".$phpExtJeu) || NOM_SCRIPT==("modifier_lieu.".$phpExtJeu)) {
		if(file_exists("../lieux/vues/view".$id_cible.".jpg")){
			$ext_image  =".jpg";
		}
		else if(file_exists("../lieux/vues/view".$id_cible.".gif")){
			$ext_image  =".gif";
		}
		else if(file_exists("../lieux/vues/view".$id_cible.".png")){
			$ext_image  =".png";
		} 
	}
	if($ext_image!=""){
		$template_main.= "<img src='../lieux/vues/view".$id_cible.$ext_image."' height='200' width='400' border='0' alt='image du lieu' />";
		$template_main.= "<input type='hidden' size='20' maxlength='50' name='suppImage' value='0' />";
		if (NOM_SCRIPT==("modifier_lieu.".$phpExtJeu)) {
		        $template_main.= "\n\t<input type='button' name='Supprimer' value=\"Supprimer l'image\" alt=\"Supprimer l'image\" onclick=\"alert('N\'oubliez pas de valider pour supprimer l\'image');formLieu.suppImage.value=1;\" />";
		}        
	} else {
		$template_main.= "<img src='../lieux/vues/noview.png' height='200' width='400' border='0' alt=\"Pas d'image de lieu\" />";
	}
*/
        if (NOM_SCRIPT==("supprimer_lieu.".$phpExtJeu) || NOM_SCRIPT==("modifier_lieu.".$phpExtJeu)) {
                $temp=	afficheImageLieu($id_cible);
                $template_main.= $temp[0];
        }
        
	if (NOM_SCRIPT==("modifier_lieu.".$phpExtJeu)) {
	        if (strstr($temp[0], "noview")===FALSE) 
	                $template_main.= "\n\t<input type='button' name='Supprimer' value=\"Supprimer l'image\" alt=\"Supprimer l'image\" onclick=\"alert('N\'oubliez pas de valider pour supprimer l\'image');formLieu.suppImage.value=1;\" />";
        }
        if (NOM_SCRIPT!=("supprimer_lieu.".$phpExtJeu)) {
                $template_main.= "<input name='fichierImage' id='fichierImage' value='' size='20' type='file' />";
        }
	$template_main .= "</td></tr>";	
	$template_main .= "</table>";
	$template_main.= "<input type='hidden' size='20' maxlength='50' name='suppImage' value='0' />";
?>