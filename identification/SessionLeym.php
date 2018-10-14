<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: SessionLeym.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/01/24 17:44:16 $

*/

require_once("../include/extension.inc");
if(!defined("__SESSIONLEYM.PHP") ) {
	Define("__SESSIONLEYM.PHP",	0);
	//if(!defined("__CONST.PHP")){include('../include/const.'.$phpExtJeu);}

class SessionLeym{
	var $ID;
	var $Valide;
	var $id_joueur;
	var $Infini;
	var $sessionIDphpBB;
	
	function SessionLeym($id){
		global $HTTP_SERVER_VARS;
		global $db;
		$this->ID = $id;
		$IP = substr($HTTP_SERVER_VARS['REMOTE_ADDR'],0,9);
		$SQL = "SELECT T2.*,T1.permanent FROM ".NOM_TABLE_SESSIONS." T1, ".NOM_TABLE_REGISTRE.
		" T2 WHERE T1.id_joueur = T2.id_perso AND T1.idsession = '".$this->ID."' AND T1.ip LIKE '".$IP."%' 
		AND T1.pj = 1 AND (T1.datestart > (".time()." - T1.duree) OR T1.permanent = 1)";
		$requete=$db->sql_query($SQL);
		$this->Valide = $db->sql_numrows($requete);
		if($this->Valide > 0){
			$row=$db->sql_fetchrow();
			if(defined("IN_FORUM")&& IN_FORUM==1) {
				global $forum;
				if ($forum->RecupereSession()<>"")
					$this->sessionIDphpBB = $forum->RecupereSession();
				else unset($this->sessionIDphpBB);

			}			
			$this->id_joueur = $row["id_perso"];
			$this->Infini = $row["permanent"];
		}
		$SQL = "DELETE FROM ".NOM_TABLE_SESSIONS." WHERE datestart < (".time()." - Duree) AND permanent = 0";
		$db->sql_query($SQL,"",END_TRANSACTION_JEU);
	}

	function existe(){
		global $db;
		if( ($this->Valide > 0) ){
			//rduction des requetes => transferes 
			//$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET lastaction = '".time()."', WHERE id_perso='".$this->id_joueur."' ";
			//$db->sql_query($SQL);
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOETATTEMP." WHERE fin > -1 AND fin < ".time() ." AND id_perso= ".$this->id_joueur;
			$db->sql_query($SQL,"",END_TRANSACTION_JEU);
			return true;
		} else {
			return false;
		}
	}

	function estInfini(){
		return ($this->Infini > 0);
	}

	function creerSession($id,$duree,$permanent,$numero){
		global $HTTP_SERVER_VARS;
		global $db;
		global $template_main;
		if(defined("IN_FORUM")&& IN_FORUM==1) {
				global $forum;
				if ($forum->RecupereSession()<>"")
					$this->sessionIDphpBB = $forum->RecupereSession();
				else unset($this->sessionIDphpBB);
		}			
		$this->ID = $id;
		$this->id_joueur = $numero;
		$this->Infini = $permanent;
		$this->Valide = true;
		
		$SQL = "DELETE FROM ".NOM_TABLE_SESSIONS." WHERE pj=1 AND id_joueur='".$numero."' ";
		if ($result=$db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU)) {
			$SQL = "INSERT INTO ".NOM_TABLE_SESSIONS." (idsession,ip,datestart,lastaction,duree,permanent,id_joueur,pj) VALUES ('".$id."','".$HTTP_SERVER_VARS['REMOTE_ADDR']."','".time()."','".time()."','".$duree."','".$permanent."','".$numero."',1)";
			$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
		}
		$template_main .= $db->erreur;
		return $result;		
	}

	function detruire(){
		global $db;
		global $template_main;
		$SQL = "DELETE FROM ".NOM_TABLE_SESSIONS." WHERE idsession='".$this->ID."' ";
		if ($result=$db->sql_query($SQL)) {
			if(defined("IN_FORUM")&& IN_FORUM==1) {
				global $forum;
				$result=$forum->DetruireSession ();
			}	
			if ($result) {		
				$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET lastaction = '".(time()-1000)."' WHERE id_perso='".$this->id_joueur."' ";
				$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
			}	
		}	
		$template_main .= $db->erreur;
		return $result;		
	}

}

}
?>