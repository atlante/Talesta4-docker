<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: objetmagasin.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.3 $
$Date: 2010/01/24 16:36:42 $

*/

require_once("../include/extension.inc");
if(!defined("__OBJETMAG.PHP") ) {
	Define("__OBJETMAG.PHP",	0);

	class ObjetMagasin extends Objet{

		var $stockmax;		///< quantite Max de l'objet pour le magasin (-1 pour illimit => ne gere pas le stock)
		var $quantite;		///< quantite de l'objet (diminue avec la vente a un PJ... )
		var $remisestock;	///< temps au bout duquel la quantite revient a stockmax (-1 pour jamais)
		var $derniereremise;
		var $id_lieu;

		function ObjetMagasin($id_objet,$id_lieu,$stockmax=-1,$quantite=-1, $remisestock="",$derniereremise="" ){
			$retour=$this->Objet($id_objet);
			if (! $retour) {
				$this->stockmax = $stockmax;
				$this->quantite= $quantite;
				$this->remisestock = $remisestock;
				$this->derniereremise = $derniereremise;
				$this->id_lieu=$id_lieu;
				if ($this->stockmax>=0 && $quantite <$this->stockmax && $this->remisestock >=0)
					$this->Remise();
			}
			else {
				$this->Detruire2($id_objet);
				return null;
			}	
		}


		function Remise(){
			global $db;
			$now= time();
			if ($now >= $this->derniereremise + $this->remisestock* 3600) {
				$this->quantite= $this->stockmax;
				$this->derniereremise = $now;
				$SQL = "UPDATE ".NOM_TABLE_MAGASIN." SET quantite= ".$this->quantite .",  derniereremise = ".$this->derniereremise." WHERE pointeur = ".$this->ID." and id_lieu=".$this->id_lieu;
				if ($db->sql_query($SQL))
					return true;
				else return false;
			}
			return true;
		}

		function DiminueQuantite($nb_munitions=1){
			global $db;
			if($this->stockmax>=0 || $this->quantite >=0){
				if($this->quantite >= $nb_munitions){
					$this->quantite -= abs($nb_munitions);
					$this->quantite = Max(0,$this->quantite);
					//on n'efface l'objet quand sa quantite =0 que s'il est sans remise de stock
					if ($this->quantite >0 || $this->remisestock!=-1 )
					$SQL = "UPDATE ".NOM_TABLE_MAGASIN." SET quantite = ".$this->quantite." WHERE pointeur = ".$this->ID." and id_lieu=".$this->id_lieu;
					else         $SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE pointeur = ".$this->ID." and id_lieu=".$this->id_lieu;
					if ($db->sql_query($SQL))
						return true;
					else return false;
				} else {
					return false;
				}
			}
			return true;
		}


		function AugmenteQuantite($nb_munitions=1){
			global $db;
			if($this->quantite!=$this->stockmax  && $this->quantite!=-1){
				if($nb_munitions>0){
					if($this->stockmax>=0)
					$this->quantite = Min($this->stockmax, $nb_munitions+$this->quantite);
					else 	$this->quantite = $nb_munitions+$this->quantite;
					$this->quantite = Max(0,$this->quantite);
					$SQL = "UPDATE ".NOM_TABLE_MAGASIN." SET quantite = ".$this->quantite." WHERE pointeur = ".$this->ID." and id_lieu=".$this->id_lieu;
					if ($db->sql_query($SQL))
						return true;
					else return false;
				} else {
					return false;
				}
			}
			return true;
		}

		// au cas, ou on aurait supprime un objet 
		// supprime tous les objets identiques des magasins
		function Detruire2($id_objet){
			global $db;
			$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE pointeur = ".$id_objet;
			if ($db->sql_query($SQL))
				return true;
			else return false;
		}
		
	}
}

?>