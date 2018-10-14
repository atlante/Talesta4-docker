<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: specialite.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.14 $
$Date: 2006/01/31 12:26:23 $

*/

require_once("../include/extension.inc");
if(!defined("__SPECIALITE.PHP") ) {
	Define("__SPECIALITE.PHP",	0);

	class Specialite{

		var $ID;
		var $nom;
		var $Listebonus=array();
		var $rpa;
		var $rpo;
		var $rpv;
		var $rpi;
		var $Visible;

		function Specialite($id_spec){
			global $db;
			$this->ID = $id_spec;
			$SQL = "SELECT * FROM ".NOM_TABLE_SPECNOM." LEFT JOIN ".NOM_TABLE_SPEC." ON ".
			 NOM_TABLE_SPECNOM.".id_spec = ".NOM_TABLE_SPEC. ".id_spec WHERE ".NOM_TABLE_SPECNOM.".id_spec = ".$this->ID;
			$requete=$db->sql_query($SQL);
			$nb_comp=$db->sql_numrows($requete);
			if ($nb_comp>0) {
				$row = $db->sql_fetchrowset($requete);
				$this->nom = ConvertAsHTML($row[0]["nom"]);
				$this->rpa = $row[0]["rpa"];
				$this->rpo = $row[0]["rpo"];
				$this->rpv = $row[0]["rpv"];
				$this->rpi = $row[0]["rpi"];
				$this->Visible = $row[0]["visible"];
				//$SQL = "SELECT * FROM ".NOM_TABLE_SPEC." WHERE id_spec = ".$this->ID;
				//$requete=$db->sql_query($SQL);
				for($i=0;$i<$nb_comp;$i++){
					if ($row[$i]["id_comp"]<>"")
						$this->Listebonus[$row[$i]["id_comp"]] = $row[$i]["bonus"];
				}
			}
			else {
				$this->Detruire($id_clef);
				return null;
			}	
		}

		function Detruire($id_clef){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOSPEC." WHERE id_clef = ".id_clef;
			return $db->sql_query($SQL);
		}		

		function Getbonus($IDComp){
			if(isset($this->Listebonus[$IDComp])){return $this->Listebonus[$IDComp];} else {return 0;}
		}

		function EstVisible(){
			return ($this->Visible == 1);
		}

	}
}

?>