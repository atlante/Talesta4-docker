<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: groupe.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.12 $
$Date: 2006/09/04 20:44:39 $

*/

require_once("../include/extension.inc");
if(!defined("__GROUPE.PHP") ) {
	Define("__GROUPE.PHP",	0);

	//if(!defined("__CONST.PHP")){include('../include/const.'.$phpExtJeu);}

	class Groupe{
		var $ID;
		var $nom;
		var $nb;
		var $Persos;
						
		function Groupe($id_groupe,$principal=false){
			$this->ID = $id_groupe;
			$this->Persos=array();
			return $this->UpdateFromDB($principal);			
		}

		function UpdateFromDB($principal){
			global $db;
			global $PERSO;
			//Charge le groupe a partir de la base de donnee
			$SQL = "Select * FROM ".NOM_TABLE_GROUPE." WHERE id_groupe = ".$this->ID;
			if ($requete = $db->sql_query($SQL)) {
        			if ($row = $db->sql_fetchrow($requete))
        			        $this->nom = $row["nom"];
        			if($principal){
        			        $nb_pj_groupe=0;
        				//ajout du order par PA pour que les tests sur les modpa des membres du groupe s'arretent plus vite
        				$SQL = "SELECT id_perso FROM ".NOM_TABLE_REGISTRE." WHERE id_groupe = ".$this->ID ." order by pa,pi";
        				if ($requete=$db->sql_query($SQL)) {        				
                				while($row = $db->sql_fetchrow($requete)) {
                				        if (isset($PERSO) && $row["id_perso"] == $PERSO->ID)
                				                $this->Persos[$nb_pj_groupe]=$PERSO;        
                					else $this->Persos[$nb_pj_groupe] = new Joueur($row["id_perso"],false,$principal,$principal,$principal,false,false, false);
                					$nb_pj_groupe++;
                				}	
                                        }
        				$this->nb=$nb_pj_groupe;		
        			}
                        }
                        return $requete;
		}
	
	}
}
?>