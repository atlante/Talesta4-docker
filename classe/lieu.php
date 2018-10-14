<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: lieu.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.21 $
$Date: 2006/09/04 20:43:50 $

*/

require_once("../include/extension.inc");
if(!defined("__LIEU.PHP") ) {
	Define("__LIEU.PHP",	0);

	class Lieu{
		
		var $ID;			///< identifiant du lieu
		var $nom;			///< nom du lieu
		var $flags;			///< le paramtrage du lieu en ce qui concerne les actions faisables dans le lieu	
		var $trigramme;			///< trigramme du lieu
		var $provoqueetat;
		var $Chemins;			///< la collection de tous les chemins qui partent de ce lieu
		var $Magasins;			///< la collection de tous les magasins qui sont dans ce lieu
		var $difficultedesecacher;	///< utilis pour reussite_secacher, compar avec 1d10=> 10 se cacher est trs dur
		var $cheminfichieraudio;	///< le chemin d'accs au fichier audio que l'on entend dans le lieu
		//var $typemimefichieraudio;
		var $EtatTempSpecifique;	///< l'etat temp a avoir pour pouvoir acceder au lieu
		var $apparitionMonstre;         ///< booleen qui indique si des monstres peuvent apparaitre dans ce lieu a l'arrive d'un PJ
                var $type_lieu_apparition;      ///< type de lieu pour les apparitions de monstres (cf. $liste_type_lieu_apparition dans include/const)
                var $possedeQuetesPubliques;    ///< boolean qui indique s'il y a des PJs/MJs dans ce lieu qui proposent des quetes publiques
                
		// Le constructeur
		function Lieu($id_lieu,$principal=false){
			global $db;
			$this->ID = $id_lieu;
			$SQL = "Select * FROM ".NOM_TABLE_LIEU." WHERE id_lieu = ".$this->ID;
			$requete=$db->sql_query($SQL);
			$row = $db->sql_fetchrow($requete);
			$this->nom = $row["nom"];
			$this->flags = $row["flags"];
			$this->trigramme = $row["trigramme"];
			$this->provoqueetat = $row["provoqueetat"];
			$this->Chemins = null;
			$this->Magasins = null;
			$this->difficultedesecacher = $row["difficultedesecacher"];
			$this->cheminfichieraudio = $row["cheminfichieraudio"];
			//$this->typemimefichieraudio = $row["typemimefichieraudio"];
			if ($row["id_etattempspecifique"]<>"" && $row["id_etattempspecifique"]<>"0") 
				$this->EtatTempSpecifique= new EtatTemp($row["id_etattempspecifique"]);
			else 	$this->EtatTempSpecifique=null;

                        $this->apparitionMonstre = $row["apparition_monstre"];
                        $this->type_lieu_apparition = $row["type_lieu_apparition"];
                        
			if($principal){
				global $liste_types_magasins;
				$oldtype=null;
				$oldsoustype=null;
				$SQL = "Select distinct id_lieu,type, pointeur, id_zone,stockmax,quantite, remisestock,derniereremise FROM ".NOM_TABLE_MAGASIN." WHERE id_lieu = ".$this->ID." ORDER BY type";
				$requete=$db->sql_query($SQL);
				$j=-1;
				while($row = $db->sql_fetchrow($requete)){
					$traite=false;
					if ($row["type"]!=$oldtype || $row["type"]==$liste_types_magasins["Produits Naturels"]) {
						$oldtype=$row["type"];	
						$temp = new Magasin($row["id_lieu"],$row["type"],$row["pointeur"],$row["id_zone"], $row["stockmax"],$row["quantite"],$row["remisestock"],$row["derniereremise"]);					
						if ($row["type"]==$liste_types_magasins["Produits Naturels"]) {
							if ($temp->sous_type == $oldsoustype) {
								//meme magasin
								array_push ($this->Magasins[$j]->Items, $temp->Items[0] );
								$traite=true;
							}	
							else {
								//bien un magasin different => traite dans ajout								
							}	
						}	
						if ($traite==false) {
							$j++;
							$this->Magasins[$j] = new Magasin($row["id_lieu"],$row["type"],$row["pointeur"],$row["id_zone"], $row["stockmax"],$row["quantite"],$row["remisestock"],$row["derniereremise"]);
							$oldsoustype=$this->Magasins[$j]->sous_type;
						}
					}	
					else {
						$this->Magasins[$j]->AddItem($row["pointeur"], $row["stockmax"],$row["quantite"],$row["remisestock"],$row["derniereremise"]);
						
					}	
				}
				$SQL = "Select * FROM ".NOM_TABLE_CHEMINS." WHERE id_lieu_1 = ".$this->ID;
				$requete=$db->sql_query($SQL);
				$i=0;
				while ($row = $db->sql_fetchrow($requete)) {
					$this->Chemins[$i] = new Chemin($row["id_clef"],$this,$row["id_lieu_2"],$row["type"],$row["difficulte"],$row["pass"],$row["distance"],$row["id_lieu_1"]);
					$i++;
				}

				$SQL = "Select 1 as c FROM ". NOM_TABLE_QUETE ." T2 WHERE T2.id_lieu = ".$this->ID;
				$requete=$db->sql_query($SQL);
                                $row = $db->sql_fetchrow($requete);
                                $this->possedeQuetesPubliques = $row['c'];

			}
			
		}
		
		function possedeQuetesPubliquesDispos() {
		        if ($this->possedeQuetesPubliques)
		                return true;
		}		

		function permet($num){
			return $this->flags[$num] == 1;
		}


		function descLieuPJs($id_perso,$nom, $PV,$PA, $PI, $dissimule,$Avatar,$totalPJS) {
			$chaine=span($nom,"pj");
			global $phpExtJeu;
			if($PV <= 0){
				$chaine=span($chaine." (M)","mort");
			}						
			else if($PA < 0 && $PI < 0){
				$chaine=span($chaine." (P)","paralys");
			}
			if($dissimule > 0){
				$chaine=span($chaine." (D)","dissimul");
			}						

			$tmp = '<a href="javascript:a(\'voir_desc.'.$phpExtJeu.'?id_perso='.$id_perso.'\');">';
			if (defined("AFFICHE_AVATAR_FORUM") && AFFICHE_AVATAR_FORUM && defined("AFFICHE_NB_MAX_AVATAR") && (AFFICHE_NB_MAX_AVATAR==-1 || AFFICHE_NB_MAX_AVATAR> $totalPJS) ) {
				if ($Avatar=="") 
					$Avatar = "../pjs/PasAvatar.png";
				$tmp .= "<img src='". $Avatar ."' border='0' height='80' alt=\"".$nom."\" /><br />";
			}
			$tmp.=$chaine."</a>";
			return $tmp;			
		}


		function listePJs($IDSoi=-1){
				global $db;
				global $forum;
				$temp="";
				if(defined("IN_FORUM")&& IN_FORUM==1  && $forum->champimage<>null)  {
					$SQL1 = $forum->requetePJvisiblesDuLieu($this->ID,$IDSoi);
					$SQL2 = $forum->requetePJcachesConnusDuLieu($this->ID,$IDSoi);
				}
				else {
					$SQL1 = "Select * FROM ".NOM_TABLE_REGISTRE." WHERE id_perso <> ".$IDSoi." AND archive=0 and id_lieu = ".$this->ID ."
					and dissimule = 0";
					$SQL2 = "Select P.* FROM ".NOM_TABLE_REGISTRE." P,".NOM_TABLE_ENTITECACHEE." E,".NOM_TABLE_ENTITECACHEECONNUEDE ." ECCD 
					WHERE P.id_perso <> ".$IDSoi." AND P.archive=0 and P.id_lieu = ".$this->ID ."
					and P.dissimule = 1 and P.id_perso= E.id_entite and E.id= ECCD.id_entitecachee and E.type=2
					and (ECCD.id_perso is null or ECCD.id_perso = ".$IDSoi.")";
				}
				$requeteVisibles=$db->sql_query($SQL1);
				$requeteCachesVus=$db->sql_query($SQL2);
				$compteur=0;
				$totalPJS = $db->sql_numrows($requeteVisibles) + $db->sql_numrows($requeteCachesVus);
				$imageforum="";
				//for($i=0;$i<$db->sql_numrows($requeteVisibles);$i++){
				while($row = $db->sql_fetchrow($requeteVisibles)){
					if(defined("IN_FORUM")&& IN_FORUM==1  && $forum->champimage<>null)  {
						$imageforum = $forum->URLimageAvatar($row["$forum->champtypeimage"],$row["$forum->champimage"] );
					}	
					
					$temp[$compteur]=$this->descLieuPJs($row["id_perso"],$row["nom"], $row["pv"],$row["pa"],$row["pi"], $row["dissimule"],$imageforum,$totalPJS);
					$compteur++;
				}

				//for($i=0;$i<$db->sql_numrows($requeteCachesVus);$i++){
				while($row = $db->sql_fetchrow($requeteCachesVus)){
					if(defined("IN_FORUM")&& IN_FORUM==1  && $forum->champimage<>null)  {
						$imageforum = $forum->URLimageAvatar($row["$forum->champtypeimage"],$row["$forum->champimage"] );
		
					}	

					$temp[$compteur]=$this->descLieuPJs($row["id_perso"],$row["nom"], $row["pv"],$row["pa"],$row["pi"], $row["dissimule"],$imageforum,$totalPJS);
					$compteur++;
				}
				if($compteur == 0){return null;} else {return $temp;}
		}

		function aChemin($type){
			for($i=0;$i<count($this->Chemins);$i++){
				if($this->Chemins[$i]->type == $type){return true;}
			}
			return false;
		}


		/** \brief fonction retournant la requete a lancer pour trouver les PJ/PNJ d'un lieu distant de celui du PJ.
		contrairement a listePJsDuLieuDuPerso de la classe joueur, le perso n'est pas present dans ce lieu
		On ne traite donc pas les connaissances des choses cachees de la meme facon
			filtres:
				$vivant 0 ramene tous les PJs morts ou non
					1 ramene tous les PJs vivants	
					-1 ramene tous les PJs morts	
				$archive true	ramene tous les PJs meme archives
					 false ramene les PJs non archives
				$cache 0 ramene tous les PJs caches ou non
					1 ramene tous les PJs visibles

		*/
		function listePJsDuLieu($vivant, $archive, $cache) {
			global $db;
			if ($cache) { 
				$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE." P 
			        WHERE  P.dissimule = 0 and P.id_lieu = ".$this->ID;
			}	        
			else 
				$SQL = "Select distinct P.id_perso as idselect, P.nom as labselect FROM ".NOM_TABLE_REGISTRE."  P 
			        WHERE  P.id_lieu = ".$this->ID;
			if ($vivant==1)
				$SQL .= " AND P.PV >0 ";
			elseif ($vivant==-1)
				$SQL .= " AND P.PV <=0 ";
			if (!$archive)
				$SQL .= " AND P.archive=0 ";
			return $SQL;
		}	


		function aMagasin($type, $sous_type=array()){
			$nb_sous_type = count($sous_type);
			$trouve=false;
			for($i=0;$i<count($this->Magasins);$i++){
				if($this->Magasins[$i]->type == $type) {
					if ($nb_sous_type==0)
						$trouve=true;
					else {	
						$j=0;
						$trouve=false;
						while ((!$trouve) && ($j<$nb_sous_type)) {
							if ($this->Magasins[$i]->sous_type == $sous_type[$j])
								$trouve=true;
							else {
								$j++;
							}		
						}
					}
				}	
			}
			return $trouve;
		}
		
	}

}
?>