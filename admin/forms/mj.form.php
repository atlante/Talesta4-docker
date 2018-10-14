<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: mj.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.17 $
$Date: 2006/02/21 18:27:49 $

*/

	if (NOM_SCRIPT==("supprimer_mj.".$phpExtJeu) || NOM_SCRIPT==("supprimerdroits_pj.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }

	if (NOM_SCRIPT==("modifier_mj.".$phpExtJeu) && $id_cible==$MJ->ID && $MJ->ID <>1)
		{ $disabled2= " disabled='disabled'  "; }
	else  $disabled2= "";
	
$template_main .= "<table class='detailscenter'>";

	if (NOM_SCRIPT==("donnerdroits_pj.".$phpExtJeu)) {
		$template_main .= "<tr><td>nom du MJ : </td><td><input type='text' name='nom' $readonly value='".$nom."' size='35' maxlength='25' /></td></tr>\n";
		$template_main .= "<tr><td>pass : </td><td><input type='text' name='pass' $readonly value='".$pass."' size='35' maxlength='50' /></td></tr>\n";
		$template_main .= "<tr><td>email : </td><td><input type='text' name='email' $readonly value='".$email."' size='35' maxlength='50' /></td></tr>\n";
	}	
	else {
		$template_main .= "<tr><td>nom du MJ : </td><td><input type='text' name='nom' $readonly value='".$nom."' size='35' maxlength='25' /></td></tr>\n";
		$template_main .= "<tr><td>pass : </td><td><input type='text' name='pass' $readonly value='".$pass."' size='35' maxlength='50' /></td></tr>\n";
		$template_main .= "<tr><td>email : </td><td><input type='text' name='email' $readonly value='".$email."' size='35' maxlength='50' /></td></tr>\n";
	}
	$template_main .= "<tr><td>Prevenir des Messages FA par email : </td><td>".faitOuiNon("wantmail",$disabled,$wantmail)."</td></tr>\n";
	$template_main .= "<tr><td>titre : </td><td><input type='text' name='titre' $readonly value='".$titre."' size='35' maxlength='25' /></td></tr>\n";

	$template_main .= "<tr><td>Entendre les fonds sonores des lieux</td><td>".faitOuiNon("wantmusic",$disabled,$MJ->wantmusic)."</td></tr>";

	$template_main .= "<tr><td>Disponible pour les PPA</td><td>".faitOuiNon("dispo_pour_ppa",$disabled,$MJ->dispo_pour_ppa)."</td></tr>";

	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage<>null ) {
		$template_main .= "<tr><td>image du MJ: </td><td><input type='text' $readonly  maxlength='100' name='imageforum' value='"; 
		$template_main .= $imageforum;
		$template_main .="' size='40' />";
		if ($imageforum<>"") {
			$template_main .= "<img src='";
			$template_main .= $forum->URLimageAvatar($imagetype,$imageforum );						
			$template_main .= "' alt='Avatar' border='0' />";		 
		}	
		 $template_main .= "</td></tr>\n";
	}
	else {
		$imageforum="";
		$template_main .= "<input type='hidden' name='imageforum' value='". $imageforum."' size='40' />";
	}	
	$tata= array_keys($liste_flags_mj);
	$toto = array_values($liste_flags_mj);
	for($i=0;$i<count($liste_flags_mj);$i++){
		$template_main .= "<tr><td>Peut: ".$tata[$i]."</td><td>";
			$template_main .= faitOuiNon("flags[".$toto[$i]."]",$disabled.$disabled2,$flags[$toto[$i]]);
		$template_main .= "</td></tr>\n";
	}
	
	
	$template_main .= "</table>\n";
?>