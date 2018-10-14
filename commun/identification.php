<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: identification.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.16 $
$Date: 2006/02/04 09:20:20 $

*/

require_once("../include/extension.inc");
//if(!defined("__BDD.PHP")){include('../include/bdd.'.$phpExtJeu);}


function connectionMJ($idsess,$id_mj,$perm="") {
	global $HTTP_SESSION_VARS;
	
	$SessionTempMJ = new SessionLeymMJ($idsess);
	//$idsessMJ=$idsess;
	logdate("sette les sessions MJ");
	$HTTP_SESSION_VARS['idsessMJ']=$idsess; //sert si on est en php < 4.1.0
	if (isset($_SESSION))			  // mais il faut conserver celles-ci aussi	
		$_SESSION['idsessMJ']=$idsess;  // en php > car meme si on gere HTTP_SESSION_VARS grace au script issu de phpbb
						  // HTTP_SESSION_VARS n'est qu'une sorte de synonyme qui s'efface a chaque script.	
	$MJ = new MJ($id_mj);	
	if($perm<>""){
		$SessionTempMJ->creerSession($idsess,0,1,$id_mj);
	} else {
		$SessionTempMJ->creerSession($idsess,3600,0,$id_mj);
	}
	return $MJ;
}

if (teste("Admin","1")) {if(!defined("__SESSIONLEYMMJ.PHP")){include("../identification/SessionLeymMJ.".$phpExtJeu);}}
else if(!defined("__SESSIONLEYM.PHP")){include("../identification/SessionLeym.".$phpExtJeu);}	

if ( (teste("Admin","1") && (!isset($id_mj))) || ( (! teste("Admin","1")) && (!isset($id_joueur)))) {
	include("../classe/liste.".$phpExtJeu);
	$inter0=ConvertAsHTML(trim($x0));
	$inter1=ConvertAsHTML(trim($x1));
	if (teste("Admin","1"))
		$SQL="SELECT * FROM ".NOM_TABLE_MJ." where nom='".$inter0."' AND pass='".$inter1."'";
	else 
		$SQL="SELECT * FROM ".NOM_TABLE_REGISTRE." where nom='".$inter0."' AND pass='".md5($inter1)."'";	
	$result = $db->sql_query($SQL);
		
	$nb_lignes = $db->sql_numrows($result);
	logdate("select nom/pass".$nb_lignes);
	if($nb_lignes!=0){		
		if(!isset($perm))	$perm="";
		if (teste("Admin","1")) {
			/*
			$SessionTemp = new SessionLeymMJ($idsess);
			$idsessMJ=$idsess;
			logdate("sette les sessions MJ");
			$HTTP_SESSION_VARS['idsessMJ']=$idsessMJ; //sert si on est en php < 4.1.0
			if (isset($_SESSION))			  // mais il faut conserver celles-ci aussi	
				$_SESSION['idsessMJ']=$idsessMJ;  // en php > car meme si on gere HTTP_SESSION_VARS grace au script issu de phpbb
			$row = $db->sql_fetchrow($result);	  // HTTP_SESSION_VARS n'est qu'une sorte de synonyme qui s'efface a chaque script.
			$id_mj = $row["id_mj"];			
			$MJ = new MJ($id_mj);			
			*/
			$row = $db->sql_fetchrow($result);
			$id_mj = $row["id_mj"];
			$idsessMJ = uniqid("sid",true);
			$MJ=connectionMJ($idsessMJ, $id_mj,$perm);
		}	
		else {
			$idsess = uniqid("sid",true);
			$SessionTemp = new SessionLeym($idsess);		
			$HTTP_SESSION_VARS['idsess']=$idsess;
			if (isset($_SESSION))
				$_SESSION['idsess']=$idsess;
			$row = $db->sql_fetchrow($result);
			$id_joueur =$row["id_perso"];
			$PERSO = new Joueur($id_joueur,true,true,true,true,true,true);
			if($perm!=""){
				$SessionTemp->creerSession($idsess,0,1,$id_joueur);
			} else {
				$SessionTemp->creerSession($idsess,3600,0,$id_joueur);
			}			
			if ($PERSO->roleMJ!="") {
				$idsessMJ = uniqid("sid",true);
				if(!defined("__SESSIONLEYMMJ.PHP")){include("../identification/SessionLeymMJ.".$phpExtJeu);}
				$MJ=connectionMJ($idsessMJ,$PERSO->roleMJ,$perm);
			}	
		}
	 } else {
		$sessionfoiree= true;
		logdate("sessionfoiree");
	 }
}
?>