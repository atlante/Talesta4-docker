<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: recompenseQuete.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:44:02 $

*/

require_once("../include/extension.inc");
if(!defined("__RECOMPENSEQUETE.PHP") ) {
	Define("__RECOMPENSEQUETE.PHP",	0);

	class RecompenseQuete{
		
		var $id_quete;
		var $id_recompenseQuete;
		var $type_recompense;
		var $recompenseID;		///< l'ID de la recompense
		var $recompense;		///< l'instance de la recompense si besoin
		
		function RecompenseQuete($id_quete, $id_recompenseQuete,$type_recompense, $recompense){
			global $db;
			$this->id_quete = $id_quete;
			$this->id_recompenseQuete = $id_recompenseQuete;
			$this->type_recompense = $type_recompense;
			switch ($this->type_recompense) {
				case 1: //gain d'XP
				$this->recompenseID = $recompense;
				$this->recompense=null;
				break;
				case 2: //gain de PO
				$this->recompenseID = $recompense;
				$this->recompense=null;
				break;
				case 3: //gain d'objet
				$this->recompenseID = $recompense;
				$this->recompense= new Objet($recompense);
				break;
				case 4: //gain de sort
				$this->recompenseID = $recompense;
				$this->recompense= new Magie($recompense);
				break;
				case 5: //gain de competence
				$this->recompenseID = $recompense;
				$this->recompense=null;
				break;
				case 6: //gain d'etat temp
				$this->recompenseID = $recompense;
				$this->recompense=new EtatTemp($recompense);
				break;
			}
		}

	}

}
?>