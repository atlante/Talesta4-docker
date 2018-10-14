<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: etattemppj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.10 $
$Date: 2006/01/31 12:26:22 $

*/

require_once("../include/extension.inc");
if(!defined("__ETATTEMPPJ.PHP") ) {
	Define("__ETATTEMPPJ.PHP",	0);

	class EtatTempPJ extends EtatTemp{

		var $id_clef;
		var $Fin;

		function EtatTempPJ($id_etattemp,$id_clef=-1, $Fin=0){
			$this->EtatTemp($id_etattemp);
			$this->id_clef = $id_clef;
			if($this->Existe){
				if($id_etattemp != 1){
					//$SQL = "SELECT * FROM ".NOM_TABLE_PERSOETATTEMP." WHERE id_clef = ".$this->id_clef;
					//$requete=$db->sql_query($SQL);
					$this->Fin = $Fin;
				} else {
					$this->Fin = -1;
				}
			}
			else {
				$this->Detruire($id_clef);
				return null;
			}	
			
		}
		

		// au cas, ou on aurait supprime un etattemp detenu par le perso
		function Detruire($id_clef){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOETATTEMP." WHERE id_clef = ".$id_clef;
			return $db->sql_query($SQL);
		}		
	}
}

?>