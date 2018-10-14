<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: quetepj.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:44:02 $

*/

require_once("../include/extension.inc");
if(!defined("__QUETEPJ.PHP") ) {
	Define("__QUETEPJ.PHP",	0);

	class QuetePJ extends Quete{
		
		var $id_persoquete;
		var $etat;		///< etat de la quete
		var $debut;		///< date de demarrage de la quete
		var $fin;		///< date de fin de la quete

		function QuetePJ($id_quete,$id_persoquete,$etat, $debut, $fin){
			//$retour=$this->Quete($id_quete);
			$retour=parent::Quete($id_quete);
			if (! $retour) {
				$this->id_persoquete = $id_persoquete;
				$this->etat = $etat;
				$this->debut = $debut;
				$this->fin = $fin;
			}
			else {
				$this->Detruire2($id_quete);
				return null;
			}	
		}

		// au cas, ou on aurait supprime une quete detenue par le perso
		// supprime toutes les quetes identiques des PERSO
		function Detruire2($id_quete){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_PERSO_QUETE." WHERE id_quete = ".$id_quete;
			if ($db->sql_query($SQL))
				return true;
			else return false;
		}
		
		function EvolutionQuete($id_persoquete, $nouvelEtat){
			global $db;
			$SQL = "update ".NOM_TABLE_PERSO_QUETE." set etat= ".$nouvelEtat ." WHERE id_persoquete = ".$id_persoquete;
			if ($db->sql_query($SQL))
				return true;
			else return false;
		}		
		
		function echecTemps($id_persoquete) {
			EvolutionQuete($id_persoquete, 9);				
			
			
		}	
		
    
		/**
		*    retourne la description utilise dans la fiche de PJ
		*    
		*/
		function description(){
			//global $phpExtJeu;
			//global $liste_type_quete;
			global $liste_etat_quete;
			//global $PERSO;
			
			$temp= parent::description();
			$temp[4]= date("d/M/Y H:i:s",$this->debut);
			if ($this->fin != -1)
			        $temp[5]= date("d/M/Y H:i:s",$this->fin);
			else $temp[5]= "illimite";
			$temp[9]=span($liste_etat_quete[$this->etat],"etat_quete");
			return $temp;
		}	
		
	}

}

?>