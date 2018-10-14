<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: magasin.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/01/24 16:36:41 $

*/

require_once("../include/extension.inc");
if(!defined("__MAGASIN.PHP") ) {
	Define("__MAGASIN.PHP",	0);

	class Magasin{
		
		var $ID_Zone;
		var $type;
		var $id_lieu;
		var $Items;
		var $sous_type="";

		function Magasin($id_lieu_Magasin,$type, $pointeur, $ID_Zone,$stockmax,$quantite, $remisestock,$derniereremise){
			$this->id_lieu = $id_lieu_Magasin;
			$this->type = $type;
			//$SQL = "SELECT ID_Zone, pointeur FROM ".NOM_TABLE_MAGASIN." WHERE id_lieu =".$this->id_lieu." AND type = ".$this->type;
			//$requete=$db->sql_query($SQL);
			$this->ID_Zone = $ID_Zone;
			$this->Items= array();
			$this->AddItem($pointeur,$stockmax,$quantite, $remisestock,$derniereremise);
		}
	
		function AddItem($pointeur,$stockmax,$quantite, $remisestock,$derniereremise) {
			global $liste_types_magasins;
			global $liste_comp_full;

			switch($this->type){
				case $liste_types_magasins["Magasin Magique"]:
				case $liste_types_magasins["Magasin Magique-Recharge"]:{
					array_push ($this->Items, new Magie($pointeur));
					break;
				}
				case $liste_types_magasins["Lieu d'apprentissage"]:{
					array_push ($this->Items, array_search($pointeur, $liste_comp_full));
					break;
				}	
				default:{
					$objet=new ObjetMagasin($pointeur, $this->id_lieu,$stockmax,$quantite, $remisestock,$derniereremise);
					array_push ($this->Items, $objet );
					if ($this->type== $liste_types_magasins["Produits Naturels"]) {
						if ($this->sous_type=="")
							$this->sous_type = $objet->Soustype;
					}	
				}
			}
		}

	}
}

?>