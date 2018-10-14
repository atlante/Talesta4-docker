<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: SessionLeymMJ.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.13 $
$Date: 2010/01/24 17:44:16 $

*/

require_once("../include/extension.inc");
if(!defined("__SESSIONLEYMMJ.PHP") ) {
	Define("__SESSIONLEYMMJ.PHP",	0);
	//if(!defined("__CONST.PHP")){include('../include/const.'.$phpExtJeu);}

class SessionLeymMJ{
	var $ID;
	var $Valide;
	var $id_mj;
	var $Infini;
	var $sessionIDphpBB;

	function SessionLeymMJ($id){
		global $HTTP_SERVER_VARS,$db;
		$this->ID = $id;
		$IP = substr($HTTP_SERVER_VARS["REMOTE_ADDR"],0,9);
		$SQL = "SELECT T2.*,T1.permanent FROM ".NOM_TABLE_SESSIONS." T1, ".NOM_TABLE_MJ." T2 WHERE T1.id_joueur = T2.id_mj AND T1.idsession = '".$this->ID."' AND T1.ip LIKE '".$IP."%' AND T1.pj = 0 AND (T1.datestart > (".time()." - T1.duree) OR T1.permanent = 1)";
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
			$this->id_mj = $row["id_mj"];
			$this->Infini = $row["permanent"];
		}
		//$SQL = "DELETE FROM ".NOM_TABLE_SESSIONS." WHERE DateStart < (".time()." - Duree) AND permanent = 0";
		//$db->sql_query($SQL);
	}

	function existe(){
		global $db;
		if( ($this->Valide > 0) ){
 			$SQL = "UPDATE ".NOM_TABLE_MJ." SET lastaction = '".time()."' WHERE id_mj='".$this->id_mj."' ";
			return $db->sql_query($SQL,"",END_TRANSACTION_JEU);
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
		$this->ID = $id;
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
		
		$SQL = "DELETE FROM ".NOM_TABLE_SESSIONS." WHERE id_joueur=".$numero." AND pj = 0 ";
		if ($result=$db->sql_query($SQL)) {
			$SQL = "INSERT INTO ".NOM_TABLE_SESSIONS." (idsession,ip,datestart,lastaction,duree,permanent,id_joueur,pj) VALUES ('".$id."','".$HTTP_SERVER_VARS['REMOTE_ADDR']."','".time()."','".time()."','".$duree."','".$permanent."',".$numero.",0)";
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
				$SQL = "UPDATE ".NOM_TABLE_MJ." SET lastaction = '".(time()-1000)."' WHERE id_mj='".$this->id_mj."' ";
				$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
			}
		}
		$template_main .= $db->erreur;
		return $result;
	}
}

}
?>