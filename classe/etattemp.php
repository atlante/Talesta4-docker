<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: etattemp.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/01/31 12:26:22 $

*/

require_once("../include/extension.inc");
if(!defined("__ETATTEMP.PHP") ) {
	Define("__ETATTEMP.PHP",	0);

	class EtatTemp{

		var $ID;
		var $nom;
		var $Listebonus=array();
		var $rpa;
		var $rpo;
		var $rpv;
		var $rpi;		
		var $Visible;
		var $Existe;
		var $Type;
		var $TypeEstcritereinscription;
		var $Etatutilisableinscription;
		var $Typemodifiableparpj;

		function EtatTemp($id_etattemp){
			global $db;
			$this->ID = $id_etattemp;
			//$SQL = "SELECT * FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$this->ID;
			$SQL = "SELECT * FROM (".NOM_TABLE_ETATTEMPNOM." LEFT JOIN ".NOM_TABLE_ETATTEMP." ON ".
			 NOM_TABLE_ETATTEMPNOM.".id_etattemp = ".NOM_TABLE_ETATTEMP. ".id_etattemp), ".NOM_TABLE_TYPEETAT
			 ." type WHERE type.id_typeetattemp= ".NOM_TABLE_ETATTEMPNOM.".id_typeetattemp and ".NOM_TABLE_ETATTEMPNOM.".id_etattemp = ".$this->ID;

			$requete=$db->sql_query($SQL);
			$nb_comp=$db->sql_numrows($requete);
			$this->Existe = ($nb_comp > 0);
			if($this->Existe){
				$row = $db->sql_fetchrowset($requete);
				$this->nom = ConvertAsHTML($row[0]["nom"]);
				$this->rpa = $row[0]["rpa"];
				$this->rpo = $row[0]["rpo"];
				$this->rpv = $row[0]["rpv"];
				$this->rpi = $row[0]["rpi"];
				$this->Visible = $row[0]["visible"];
				$this->Type = $row[0]["nomtype"];
				$this->TypeEstCritereinscription= $row[0]["critereinscription"];
				$this->Etatutilisableinscription = $row[0]["utilisableinscription"];
				$this->Typemodifiableparpj=$row[0]["modifiableparpj"];
				//$SQL = "SELECT * FROM ".NOM_TABLE_ETATTEMP." WHERE id_etattemp = ".$this->ID;
				//$requete=$db->sql_query($SQL);
				for($i=0;$i<$nb_comp;$i++){
					if ($row[$i]["id_comp"]<>"")
						$this->Listebonus[$row[$i]["id_comp"]] = $row[$i]["bonus"];
				}
			}
			else return null;	
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