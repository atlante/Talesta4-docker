<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: forum_phorum5.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.11 $
$Date: 2010/01/24 19:33:23 $

*/
if (!defined('PHORUM'))
	define( "PHORUM", "5.0.16" );		//! Variable specifique a PHORUM a definir pour qu'il ne voit pas qu'on passe par un chemin special

error_reporting  (E_ALL);	//reaffiche toutes les erreurs (phpBB filtre certaines)
//!	Classe regroupant les fonctions pour PHORUM.
class forumPHORUM {
	var $nomsReservesForum=array(   ///< A Mettre en majuscules
	);
	
	var $champimage=null;  ///<nom de colonne ou est stockee l'image. Ici pas d'avatar => met null pour ne pas avoir de probleme dans les scripts qui attendent quelquechose
	
	
	var $champtypeimage=null; ///<nom de colonne indiquant le type d'image (uploadee, venant de la gallery ou url externe). Ici pas d'avatar => met null pour ne pas avoir de probleme dans les scripts qui attendent quelquechose
				   


	var $nomtableUsers;  ///<nom de la table ou est stocke le pass des membres (pour ne pas afficher ces requetes dans le log)   
	
	var $nomtableAdmins;  ///<nom de la table ou est stocke le pass des admin (pour ne pas afficher ces requetes dans le log)

	var $nomColPasswordtableUsers = "password"; 
	
	var $nomColPasswordtableAdmins = "password ";

	var $image_avatar_remote;
	
	var $image_avatar_local;
	
	var $URLadministrationForum;
	
	var $URLForum;
	
	var $lien_forum;
	
	//
	// Constructor
	//
	function forumPHORUM($lien_forum)
	{
		global $phpEx;		
		
		
		include($lien_forum . "/include/constants.php");
		$phpEx=PHORUM_FILE_EXTENSION;
		global $PHORUM;
		include_once($lien_forum . "/include/db/config.".$phpEx );
		include_once($lien_forum . "/include/db/{$PHORUM['DBCONFIG']['type']}.".$phpEx );
		include($lien_forum . "/include/users.".$phpEx);
		phorum_db_load_settings();
		$this->lien_forum = $lien_forum;
		$this->URLForum=$lien_forum."index.".$phpEx;	
		$this->URLadministrationForum =$lien_forum."admin.".$phpEx;
		$this->image_avatar_remote =null; //pas d'avatar dans phorum ?
		$this->image_avatar_local = null; //pas d'avatar dans phorum ?
		$this->nomtableUsers=$PHORUM["user_table"];
		$this->nomtableAdmins=$PHORUM["user_table"];
		return $this;
	}

	function texteEnvoyeAuGerantGuilde($nomGuilde) {
		$text = "	Vous êtes désormais le responsable de la guilde ".$nomGuilde.".
				A votre charge, gérer les joueurs de cette guilde, les accepter, les exclure ....
				Seuls les pjs que vous incluerez dans la guilde, voient le forum de la guilde
				
				Il faut pour cela, 
					- Se connecter au forum
					- cliquer sur 'Groupes d'utilisateurs'
					- Sélectionner ".$nomGuilde." dans la combo 'Membre du groupe' et appuyer sur le bouton 'Voir les informations'
						Rem: Le groupe est invisible pour qu'il n'apparaisse pas aux joueurs non membres de la guilde";
		return $text;					
	}

	/**
	utilise la fonction de phorum pour verifier qu'il n'y a pas de caracteres incompatibles avec phorum. 
	Renvoie true si $username est accepte. false sinon.
	*/
	function uservalide($username) {
		return true; // en attendant de trouver une fonction dans phorum		
	}	

	/**
	utilise la fonction de phorum pour verifier que le mail est compatible avec phorum	
	et qu'il n'est pas present dans la liste des mails bannis.
	Renvoie true si $username est accepte. false sinon.
	*/
	function emailvalide($email) {
		return true; // en attendant de trouver une fonction dans phorum
		
	}

	function MAJuser($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, $nouveaupass) {
		if ($nouveaupass<>"")
			return $this->MAJuserMotPasseCrypte($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, md5(ConvertAsHTML($nouveaupass)));
                else 
                        return $this->MAJuserMotPasseCrypte($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, $nouveaupass);
		
		/*
		global $db;
		global $PHORUM;
		$SQL="update ".$PHORUM["user_table"]." set username = '".$nouveaunomMembre ."'";
		if ($nouvelemail<>"")
			$SQL .=" , email  = '". $nouvelemail."' ";
		if ($nouveaupass<>"") {
			$SQL .=" ,password = '". md5(ConvertAsHTML($nouveaupass))."',password_temp = '". md5(ConvertAsHTML($nouveaupass))."'";
		}	
		//pas d'avatar
		//if ($ancienne_image<>$imageforum) 		
			//$SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_remote.", ".$this->champimage."= '".$imageforum ."'";
		$SQL .= " where username='".$anciennomMembre."'";
		return $db->sql_query($SQL,"",END_TRANSACTION_JEU);					
		*/
	}

	function MAJuserMotPasseCrypte($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, $nouveaupass) {
		global $db;
		global $PHORUM;
		$SQL="update ".$PHORUM["user_table"]." set username = '".$nouveaunomMembre ."'";
		if ($nouvelemail<>"")
			$SQL .=" , email  = '". $nouvelemail."' ";
		if ($nouveaupass<>"") {
			$SQL .=" ,password = '". $nouveaupass."',password_temp = '". $nouveaupass."'";
		}	
		//pas d'avatar
		//if ($ancienne_image<>$imageforum) 		
			//$SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_remote.", ".$this->champimage."= '".$imageforum ."'";
		$SQL .= " where username='".$anciennomMembre."'";
		return $db->sql_query($SQL,"",END_TRANSACTION_JEU);					
	}
	
	function autoconnect ($nomconnecte) {
		if (!defined('IN_LOGIN'))
			define("IN_LOGIN", true);			
		global $HTTP_COOKIE_VARS;
		global $PHORUM;
		global $db;

		$SQL= "SELECT user_id,password,admin  FROM ".$PHORUM["user_table"]." where username='".$nomconnecte."'";
		if ($result = $db->sql_query($SQL)) {
			$row = $db->sql_fetchrow($result);
	                if ($row!==false) {
        			$cookiepath = $PHORUM["session_path"];
        			$cookiedomain = $PHORUM["session_domain"];
        			$session_id = urlencode( $nomconnecte ) . ":".$row['password'];		
        			$PHORUM["user"]["username"]=$nomconnecte;
        			$PHORUM["user"]["password"]=$row['password'];
        			logdate("session" . PHORUM_SESSION."cookiepath".$cookiepath. "cookiedomain".$cookiedomain);
        		            if(isset($HTTP_COOKIE_VARS["phorum_tmp_cookie"])){
        		                // destroy the temp cookie
        		                setcookie( "phorum_tmp_cookie", "", 0, $PHORUM["session_path"], $PHORUM["session_domain"] );
        		            }			
        			//setcookie(PHORUM_SESSION, $session_id, 0, $cookiepath, $cookiedomain);
        			if ($row['admin']==1) {
        				if (!defined("PHORUM_ADMIN"))
        					define("PHORUM_ADMIN",1);
        				phorum_user_create_session("phorum_admin_session", true);
        			}	
        			else	
        				phorum_user_create_session(PHORUM_SESSION); 
        			/*$SQL = "update " . SESSIONS_TABLE . " set session_user_id = $user_id, session_logged_in = 1, session_page = 0
        				where session_id = '$session_id'";
        			$result = $db->sql_query($SQL);
        			pas de table sessions
        			*/
        			$result=true;
                        }
		}
		return $result;
	}
	
	function CreeCookie () {
		global $PHORUM;
		logdate("session phorum_tmp_cookie cookiepath".$PHORUM["session_path"]. "cookiedomain".$PHORUM["session_domain"]);
	 	setcookie( "phorum_tmp_cookie", "this will be destroyed once logged in", 0, $PHORUM["session_path"], $PHORUM["session_domain"] );
		return null;

	}	

	function CreeSession ($session_id) {
		/*

		global $db;
		$current_time = time();
		//ANONYMOUS est un define de PHPBB dans constants.php
		$SQL = "INSERT INTO " . SESSIONS_TABLE . "
			(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in)
			VALUES ('$session_id', ".ANONYMOUS.", $current_time, $current_time, '$user_ip', -1, 0)";
		$result = $db->sql_query($SQL);
		return $result;
		*/
		return true; // pas de tables sessions
	}	

	function DetruireSession () {
		/*global $db;
		global $HTTP_COOKIE_VARS;
		$cookiename = $board_config['cookie_name'];
		if (isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) && $HTTP_COOKIE_VARS[$cookiename . '_sid']!=null) {
			$SQL = "DELETE FROM " . SESSIONS_TABLE . " 	where session_id = '".$HTTP_COOKIE_VARS[$cookiename . '_sid']."'";
			$result = $db->sql_query($SQL);
		}
		else $result=true;
		return $result;
		*/
		return true; // pas de tables sessions
	}	

	function RecupereSession () {
		global $HTTP_COOKIE_VARS;
		
		if (PHORUM_SESSION) {
			$cookiename = PHORUM_SESSION;
			if (isset($HTTP_COOKIE_VARS[$cookiename]))
				$session= $HTTP_COOKIE_VARS[$cookiename];
			else 	$session="";
		}
		else 	$session="";
		return $session;
	}

	function requeteMJ($filtreid_mj="") {
		//pas d'avatar => normalement non utilisee
		global $PHORUM;
		$SQL = "SELECT mj.* FROM ".NOM_TABLE_MJ." mj,".$PHORUM["user_table"]." u WHERE u.username = mj.nom";
		if ($filtreid_mj<>"")
		 	$SQL.= " and id_mj = ".$filtreid_mj;
		 return $SQL;	
	}	

	function requetePJ($filtreid_pj="") {
		//pas d'avatar => normalement non utilisee
		global $PHORUM;
		$SQL = "SELECT p.* FROM ".NOM_TABLE_REGISTRE." p left join ".$PHORUM["user_table"]." u on u.username = p.nom ";
		if ($filtreid_pj<>"")
		 	$SQL.= " where p.id_perso = ".$filtreid_pj;
		 return $SQL;		
	}	

	function requetePJvisiblesDuLieu($lieu, $pj) {
		//pas d'avatar => normalement non utilisee
		global $PHORUM;
		$SQL1 = "Select P.* FROM ".NOM_TABLE_REGISTRE." P left join ".$PHORUM["user_table"]." u on u.username = P.nom where P.id_perso <> ".$pj." AND P.archive=0 and P.id_lieu = ".$lieu ."
		and dissimule = 0";
		return $SQL1;
	}

	function requetePJcachesConnusDuLieu($lieu, $pj) {
		//pas d'avatar => normalement non utilisee
		global $PHORUM;
		$SQL2 = "Select P.* FROM ".NOM_TABLE_REGISTRE." P,".NOM_TABLE_ENTITECACHEE." E,".NOM_TABLE_ENTITECACHEECONNUEDE ." ECCD 
		,".$PHORUM["user_table"]." u WHERE u.username = P.nom and P.id_perso <> ".$pj." AND P.archive=0 and P.id_lieu = ".$lieu ."
		and P.dissimule = 1 and P.id_perso= E.id_entite and E.id= ECCD.id_entitecachee and E.type=2
		and (ECCD.id_perso is null or ECCD.id_perso = ".$pj.")";
		return $SQL2;
	}

	function requeteDroitsForumsManquantsPourGroupe($nom_groupe) {
		global $PHORUM;
		$SQL="select distinct ".$PHORUM["forums_table"].".forum_id as forum_forum_id,".$PHORUM["groups_table"].".group_id as forum_group_id, 1 from ".$PHORUM["groups_table"].", ".$PHORUM["forums_table"]." left join ".$PHORUM["forum_group_xref_table"]." on ". $PHORUM["forum_group_xref_table"]. ".forum_id =".$PHORUM["forums_table"].".forum_id  where ".$PHORUM["forum_group_xref_table"].".forum_id is null and ".$PHORUM["forums_table"].".name='".$nom_groupe."'";
		return $SQL;
	}


	function requeteMJaAjouterDansGroupeMJs() {
		global $PHORUM;
		$SQL = "SELECT u.user_id as forum_user_id FROM (".$PHORUM["user_table"]." u left join ".$PHORUM["user_group_xref_table"]." ug on u.user_id = ug.user_id) left join ".$PHORUM["groups_table"]." on ug.group_id = ".$PHORUM["groups_table"].".group_id, " .NOM_TABLE_MJ." p where p.nom = u.username and ug.group_id is null";
		return $SQL;
	}
	
	function requetePJaAjouterDansGroupePJs() {
		global $PHORUM;
		$SQL = "SELECT u.user_id as forum_user_id FROM (".$PHORUM["user_table"]." u left join ".$PHORUM["user_group_xref_table"]." ug on u.user_id = ug.user_id) left join ".$PHORUM["groups_table"]." on ug.group_id = ".$PHORUM["groups_table"].".group_id, " .NOM_TABLE_PERSO." p where p.nom = u.username and ug.group_id is null";
		return $SQL;
	}			
	
	function requeteListePJmembres() {
		global $PHORUM;
		$SQL="select u.nom,u.pass,u.email,u.pnj,fu.user_id as forum_user_id, fu.password as forum_password,fu.email as forum_email from " .NOM_TABLE_PERSO." u , ".$PHORUM["user_table"]." fu where u.nom=fu.username ";
	        if(!defined("CREE_MEMBRE_PNJ") ||  CREE_MEMBRE_PNJ==0)
	                $SQL.= " and u.pnj <>1";		
		return $SQL;
	}			
	
	function requeteListePJmanquants() {
		global $PHORUM;
		$SQL = "select u.nom,u.pass,u.email,u.pnj from " .NOM_TABLE_PERSO." u left join  ".$PHORUM["user_table"]." fu on u.nom=fu.username where fu.username is null and u.pnj <=1";
	        if(!defined("CREE_MEMBRE_PNJ") ||  CREE_MEMBRE_PNJ==0)
	                $SQL.= " and u.pnj <>1";		
		return $SQL;
	}				

	function requeteListeMJmanquants() {
		global $PHORUM;
		$SQL = "select u.nom,u.pass,u.email from (" .NOM_TABLE_MJ." u left join  ".$PHORUM["user_table"]." fu on u.nom=fu.username) left join ". NOM_TABLE_REGISTRE." on role_mj = u.id_mj where fu.username is null
		and  role_mj is null";
		return $SQL;
	}				

	function requeteListeMJmembres() {
		global $PHORUM;
		$SQL="select u.nom,u.pass,u.email,fu.user_id as forum_user_id, fu.password as forum_password,fu.email as forum_email from " .NOM_TABLE_MJ." u , ".$PHORUM["user_table"]." fu where u.nom=fu.username ";
		return $SQL;
	}				

	function ajoutePJmanquantsDansGroupe() {
		global $db;
		global $PHORUM;
		$mag=0;
		$groupePJ = $this->GetGroupe_id("PJ_PNJ");
		if ($groupePJ<>-1) {
			$SQL = $this->requetePJaAjouterDansGroupePJs();
			$result_manquants=$db->sql_query($SQL);
			if ($result_manquants!==false) {
				$result2=true;
				$mag=$db->sql_numrows($result_manquants);
				while(($row_manquants = $db->sql_fetchrow($result_manquants)) && ($result2!==false)) {
					$result2=$this->CreeGroupUser($row_manquants['forum_user_id'], $groupePJ);
				}
				if ($result2!==false)
					return $mag;
			}
		}
		return false;	
	}

	function ajouteMJmanquantsDansGroupe() {
		global $db;
		global $PHORUM;
		$groupeMJ = $this->GetGroupe_id("MJ");
		if ($groupeMJ<>-1) {
			$SQL = $this->requeteMJaAjouterDansGroupeMJs();
			$result_manquants=$db->sql_query($SQL);
			if ($result_manquants!==false) {
				$result2=true;
				$mag=$db->sql_numrows($result_manquants);
				while(($row_manquants = $db->sql_fetchrow($result_manquants)) && ($result2!==false)) {
					$result2=$this->CreeGroupUser($row_manquants['forum_user_id'], $groupeMJ);
				}
				if ($result2!==false)
					return $mag;
			}
		}
		return false;	
	}

	function synchroForumJeuPJ() {
		global $db;
		global $PHORUM;
		$SQL=$this->requeteListePJmembres();
		$resultSynchro=$db->sql_query($SQL);
		if ($resultSynchro!==false) {
			$modif=0;
			$result2=true;
			while(($row = $db->sql_fetchrow($resultSynchro)) && ($result2!==false)) {				
				if ($row["pass"]<>$row["forum_password"] || $row["email"] <> $row["forum_email"]) {
					$modif++;
					$result2=$this->MAJuserMotPasseCrypte($row["nom"], $row["email"],"","",$row["nom"], $row["pass"]);
				}
			}	
		}
		else return false;

		if ($result2!==false)
			return $modif;
		else return false;	
	}


	function synchroForumJeuMJ() {
		global $db;
		global $PHORUM;
		$SQL=$this->requeteListeMJmembres();
		$result=$db->sql_query($SQL);
		if ($result!==false) {
			$result2=true;
			$modif=0;
			while(($row = $db->sql_fetchrow($result)) && ($result2!==false)) {				
				if (md5($row["pass"])<>$row["forum_password"] || $row["email"] <> $row["forum_email"]) {
					$modif++;
					$result2=$this->MAJuser($row["nom"], $row["email"],"","",$row["nom"], $row["pass"]);
				}
			}
		}
		else return false;

		if ($result2!==false)
			return $modif;
		else return false;	
	}	

	function cheminRepertoireSmyley(){
		return $lien_forum.'smileys/';
	}

	function ScriptAfficheForum($forum_id){
		global $phpEx;
		return $lien_forum."read".$phpEx."?3,".$forum_id;
	}

	function PrivateMessageAutorise() {
		global $PHORUM;
		if ($PHORUM["enable_pm"]==1)
			return true;
		return false;	
		
	}	

	function ScriptPrivateMessage($membre_id, $membre_nom){		
		global $phpEx;
		//membre_id ne sert a rien pour PHORUM qui fonctionne avec les noms
		return $lien_forum."control.".$phpEx."?3,panel=pm,page=post,to=".$membre_nom;
	}

	function PathimageTemplate(){
		return $lien_forum."templates/".$this->nomTemplateParDefaut()."/images/";
	}	
	
	function URLimagePrivateMessage(){
		return null; //pas d'image
	}	
	
	function URLimageAvatar($imagetype,$imageforum ){
		$url="";	// Pas d'avatar sur phorum ??
		return 	$url;							
	}

	function nomTemplateParDefaut() {
		global $db;
		global $PHORUM;
		return $PHORUM["template"];
	}


	//$type = "PJ" ou "MJ"
	function CreationMembre($nom,$pass,$email,$type="PJ", $imageforum=""){
		global $db;
		global $PHORUM;
		$SQL = "select user_id,password,email from ".$PHORUM["user_table"]." where username = '".ConvertAsHTML($nom) ."'";
		$result=$db->sql_query($SQL);
		if ($result!==false) {
			if ($db->sql_numrows($result)==0) {
				$SQL = "select max(user_id)+1 as max from ".$PHORUM["user_table"];
				$result=$db->sql_query($SQL);
				$row = $db->sql_fetchrow($result);
				$max=$row["max"];
				if ($max=="")
					$max=1;
				// Si on est PJ, le mot de passe est deja crypte md5.		
				if ($type=="PJ")
					$SQL = "INSERT INTO ".$PHORUM["user_table"]." (user_id,active,username,password,email,date_added  ) 
						values ( ".$max.",1,'".ConvertAsHTML($nom)."','".$pass."','".$email."',".time() .")";
				else 	
				$SQL = "INSERT INTO ".$PHORUM["user_table"]." (user_id,active,username,password,email,date_added  ) 
					values ( ".$max.",1,'".ConvertAsHTML($nom)."',md5('".$pass."'),'".$email."',".time() .")";
				$result=$db->sql_query($SQL);
			}	
			else {
				// mise a jour du login de phpbb (priorite aux joueurs). On garde les anciens eventuels messages.
				$row = $db->sql_fetchrow($result);
				$max=$row["user_id"];
				$SQL = "update " . $PHORUM["user_table"]." set password = md5('".$pass."'),active=1, email='".$email."' where  user_id = " .$row["user_id"];
				$result=$db->sql_query($SQL);
			}	
		}
		// efface tous les groupes du user au cas ou 
		if ($result!=false) {
			$SQL = "delete from ".$PHORUM["user_group_xref_table"]. " where user_id =".$max;
			$result = $db->sql_query($SQL);
		}
	
		if ($result!==false) {
			// affectation du group PJ_PNJ au joueur ou MJ pour un MJ
			if ($type=="PJ")
				$nom_groupe = "PJ_PNJ";
			else 	$nom_groupe = "MJ";
			$SQL = "select group_id from ".$PHORUM["groups_table"]." where name='".$nom_groupe."'";
			$result=$db->sql_query($SQL);
			if ($result!==false) {
				$row = $db->sql_fetchrow($result);
				return $this->CreeGroupUser($max, $row['group_id'] );
			}	
		}
		return false;
	}	

	function DeleteMembre($nom){
		global $db;
		global $PHORUM;
		$SQL = "select  user_id from ".$PHORUM["user_table"]. " where username='".ConvertAsHTML($nom)."'";
		$result= $db->sql_query($SQL);
		$row = $db->sql_fetchrow($result);
		if ($result!==false && ($row = $db->sql_fetchrow($result))!==false) {
        		$user_id = $row["user_id"];
        		$SQL = "delete from ".$PHORUM["user_group_xref_table"]. " where user_id =".$user_id;
        		if ($result = $db->sql_query($SQL)) {
        			$SQL = "delete from ".$PHORUM["user_table"]. " where user_id =".$user_id;
        			$result=$db->sql_query($SQL);
        		}
                }
		return $result;			
	}	

	function CreeGroupUser($membre_id, $groupe_id,$typeAffectation="MembreApprouvé"){
		global $db;
		global $PHORUM;	
		if ($typeAffectation=="Modérateur")
			$type = PHORUM_USER_GROUP_MODERATOR;
		else 
		if ($typeAffectation=="MembreEnAttente")
			$type = PHORUM_USER_GROUP_UNAPPROVED;
		else 
		if ($typeAffectation=="MembreApprouvé")
			$type = PHORUM_USER_GROUP_APPROVED;			
		$SQL = "INSERT INTO ".$PHORUM["user_group_xref_table"]."  (user_id,group_id, status) values (". $membre_id.",". $groupe_id.",". $type.")";
		$result=$db->sql_query($SQL,"");
		return $result;
	}
		
	function DonneDroitsForum($forum_id, $groupe_id){
		global $db;
		global $PHORUM;
		$SQL = "INSERT INTO ".$PHORUM["forum_group_xref_table"]."  (group_id , forum_id ,permission)
			 VALUES ('".$groupe_id."','". $forum_id."',PHORUM_USER_ALLOW_READ+
				PHORUM_USER_ALLOW_REPLY+PHORUM_USER_ALLOW_EDIT+PHORUM_USER_ALLOW_NEW_TOPIC+
				PHORUM_USER_ALLOW_ATTACH+PHORUM_USER_ALLOW_MODERATE_MESSAGES+
				PHORUM_USER_ALLOW_MODERATE_USERS+PHORUM_USER_ALLOW_FORUM_PROPERTIES)";		
		$result = $db->sql_query($SQL,"",END_TRANSACTION_JEU);
		return $result;
	}

	function CreeForum($nomForum,$categorieID,$descForum,$visibilite="TousMembres") {
		global $db;
		global $PHORUM;
		$SQL="select max(forum_id)+1 as maxid from ".$PHORUM["forums_table"];
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newForumID= $row2["maxid"];
		if ($newForumID=="")
			$newForumID=1;		
		$SQL="select max(display_order)+10 as maxorder from ".$PHORUM["forums_table"]." where folder_flag =0 and parent_id = '". $categorieID."'";
		$recherche3 = $db->sql_query($SQL);
		$row3 = $db->sql_fetchrow($recherche3);
		$newForumOrder= $row3["maxorder"];
		if ($newForumOrder=="")
			$newForumOrder = 10;	
		switch ($visibilite) {	
			case "TousMembres":	//toute personne membre et connectee
				$droitPub = 0;
				$droitMembres = PHORUM_USER_ALLOW_READ + PHORUM_USER_ALLOW_REPLY + PHORUM_USER_ALLOW_EDIT + PHORUM_USER_ALLOW_NEW_TOPIC + PHORUM_USER_ALLOW_ATTACH;
				break;				
			case "Public":		//tout le monde voit le forum, meme une personne non enregistree
				$droitPub = PHORUM_USER_ALLOW_READ + PHORUM_USER_ALLOW_REPLY + PHORUM_USER_ALLOW_NEW_TOPIC + PHORUM_USER_ALLOW_ATTACH;
				$droitMembres = PHORUM_USER_ALLOW_READ + PHORUM_USER_ALLOW_REPLY + PHORUM_USER_ALLOW_EDIT + PHORUM_USER_ALLOW_NEW_TOPIC + PHORUM_USER_ALLOW_ATTACH;
				break;
			case "Privé":		//seuls certains membres (guilde)
				$droitPub = 0;
				$droitMembres=0;
				break;
			case "Mod":		//moderateurs
				$droitPub = 0;
				$droitMembres=0;
				break;
			default:		//admins seuls
				$droitPub = 0;
				$droitMembres=0;
		}						
		// creation du forum
		$SQL = "INSERT INTO ".$PHORUM["forums_table"]." (forum_id,parent_id,name,description,active,   	  pub_perms,  	  reg_perms   	 )
			 VALUES ('".$newForumID."','".$categorieID."','".ConvertAsHTML($nomForum)."','".ConvertAsHTML($descForum)."','1',".$droitPub.",".$droitMembres ." )";
		if ($result=$db->sql_query($SQL))
			return $newForumID;
		else return false;	
	}

	function CreeGroupe($nomGroupe,$descGroupe,$responsableGroupe, $typeGroupe = "cache") {
		global $db;
		global $PHORUM;
		$SQL="select max(group_id)+1 as maxid  from ".$PHORUM["groups_table"];
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newGroupID= $row2["maxid"];
		if ($newGroupID=="")
			$newGroupID=1;

		if ($typeGroupe=="cache")
			$type = PHORUM_GROUP_REQUIRE_APPROVAL; //pas de groupe cache pour PHORUM
		else 	
		if ($typeGroupe=="ouvert")
			$type = PHORUM_GROUP_OPEN;
		else 	
		if ($typeGroupe=="ferme")
			$type = PHORUM_GROUP_CLOSED;					
		else 	$type = $typeGroupe;	
		//creation du groupe
		$SQL = "INSERT INTO ".$PHORUM["groups_table"]." (group_id, name,  open )
				 VALUES ('".$newGroupID."','".ConvertAsHTML($nomGroupe)."',".$type.")";
		if($db->sql_query($SQL)) {
			if ($responsableGroupe) {
				// relier le gerant au groupe
				if(!($this->CreeGroupUser($responsableGroupe,$newGroupID,"Modérateur")))
					return false;			
			}
			return $newGroupID;			
		}
		else return false;
	}
	
	
	function GetGroupe_id($nomGroupe) {
		global $db;	
		global $PHORUM;
		$SQL = "select group_id from ".$PHORUM["groups_table"]." where name='".$nomGroupe."'";
		$recherche1 = $db->sql_query($SQL);
		if($db->sql_numrows($recherche1)>0 ) {
			$row = $db->sql_fetchrow($recherche1);	
			return $row["group_id"];
		}
		else return -1;		
	}
	
	function GetCategorie_id($nomCategorie) {
		global $db;	
		global $PHORUM;
		$SQL="select forum_id from ".$PHORUM["forums_table"]." where folder_flag=1 and name = '". $nomCategorie."'";
		$recherche1 = $db->sql_query($SQL);
		if($db->sql_numrows($recherche1)>0 ) {
			$row = $db->sql_fetchrow($recherche1);	
			return $row["forum_id"];
		}
		else return -1;	
	}


	function selectMembres($filtreNomMembre="") {
		global $PHORUM;
		global $sep;
		$SQL = "Select user_id as idselect, username as labselect from ".$PHORUM["user_table"];
		if ($filtreNomMembre<>"")
			$SQL.= " where username = '".$filtreNomMembre. "'";	
		$SQL.= " order by username ASC";
		return $SQL;
	}
	
	function updateConfigForum($paramConfig, $valeur ){
		global $db;
		global $PHORUM;
		$SQL = " update ".$PHORUM["settings_table"]." set  data='".$valeur."' where name='".$paramConfig."'";
		$result= $db->sql_query($SQL);
		return $result;			
	}
	
	function creeCategorie($nom ){
		global $db;
		global $PHORUM;
		$SQL="select max(forum_id)+1 as maxid from ".$PHORUM["forums_table"];
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newCategorieID= $row2["maxid"];
		if ($newCategorieID=="")
			$newCategorieID=1;
		$SQL = "INSERT INTO ".$PHORUM["forums_table"]." (forum_id,parent_id,name,description,active,folder_flag)
			 VALUES ('".$newCategorieID."',0,'".ConvertAsHTML($nom)."',' ','1',1)";

		if ($result=$db->sql_query($SQL))
			return $newCategorieID;
		else return false;	

	}	

	function creePrivateMessage($IDEmetteur, $IDReceveur,$nomEmetteur, $nomReceveur, $sujet, $texte) {		
		global $db;
		global $PHORUM;
		global $sep;
		if ($IDEmetteur=="") {
			$SQL=$this->selectMembres($nomEmetteur);
			$recherche = $db->sql_query($SQL);
			$row = $db->sql_fetchrow($recherche);		
			$IDEmetteur = $row['idselect'];
		}
		if ($IDReceveur=="") {		
			$SQL=$this->selectMembres($nomReceveur);
			$recherche = $db->sql_query($SQL);
			$row = $db->sql_fetchrow($recherche);				
			$IDReceveur=$row['idselect'];		
		}

		if ($nomEmetteur=="") {
			$SQL = "Select user_id as idselect, username as labselect from ".$PHORUM["user_table"]. " where user_id = '".$IDEmetteur. "'";	
			$recherche = $db->sql_query($SQL);
			$row = $db->sql_fetchrow($recherche);				
			$nomEmetteur=$row['labselect'];		
		}
		if ($nomReceveur=="") {
			$SQL = "Select user_id as idselect, username as labselect from ".$PHORUM["user_table"]. " where user_id = '".$IDReceveur. "'";	
			$recherche = $db->sql_query($SQL);
			$row = $db->sql_fetchrow($recherche);				
			$nomReceveur=$row['labselect'];		
		}
		
		$SQL="select max(private_message_id)+1 as maxid from ".$PHORUM["private_message_table"];
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newPMID= $row2["maxid"];
		if ($newPMID=="")
			$newPMID=1;
		$SQL = "insert into ".$PHORUM["private_message_table"]." ( private_message_id ,	 from_username,	 to_username,  from_user_id, to_user_id, subject, message)
		values ($newPMID, \"$nomEmetteur\",\"$nomReceveur\", $IDEmetteur, $IDReceveur,\"$sujet\", \"$texte\")";
		if ($result=$db->sql_query($SQL))
			return $newPMID;
		else return false;	
	}	


	function synchroniseMailAdminForumAvecMailAdminJeu( ){
		global $db;

		$SQL="select email from ".NOM_TABLE_MJ ." where id_mj=1";
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$email= $row2["email"];
		return $this->updateConfigForum("system_email_from_address", $email );
	}	

	/**
	remplace l'ancien fichier postInstallPHORUM.sql.
	Interet l'install est completement en php et suppression du probleme d'oubli du passage de l'ex fichier . sql
	Cette fonction est donc appelee des que l'on configure le jeu avec un forum
	*/
	function postInstall() {
		// config generale du forum
		$this->updateConfigForum("dns_lookup",0);
		
		// ne pas envoyer un mail pour la validation des inscriptions au forum
		$this->updateConfigForum("registration_control",PHORUM_REGISTER_INSTANT_ACCESS);

		$this->updateConfigForum("default_language","french");

		//cache les forums qu'on ne doit pas voir parce qu'on a pas les droits dessus
		$this->updateConfigForum("hide_forums",1);
		
		$this->synchroniseMailAdminForumAvecMailAdminJeu();
	
		if (($groupMJ =$this->GetGroupe_id("MJ"))==-1) {
			//le groupe de MJ n'existe pas => on considere que tout cela n'a jamais ete fait.
			//le reste au dessus n'etant que des updates, ce n'est pas grave si c'est fait plusieurs fois	
			$groupMJ = $this->CreeGroupe("MJ","Groupe des MJs",2, PHORUM_GROUP_CLOSED);
			if (($groupPJ =$this->GetGroupe_id("PJ_PNJ"))==-1) 
				$groupPJ = $this->CreeGroupe("PJ_PNJ","Groupe des PJs",2, PHORUM_GROUP_CLOSED);
			
			if (($t1=$this->GetCategorie_id("Communications Hors Jeu"))==-1)
				$t1=$this->creeCategorie("Communications Hors Jeu");
			if ($t1!==false) {
				$t2=$this->CreeForum("Accueil des nouveaux",$t1,"FAQ, Fonctionnement, Règles ....");
				if ($t2!==false) 
					$this->DonneDroitsForum($t2, $groupMJ);
		
				$t2=$this->CreeForum("Demande des joueurs",$t1,"Pour tout problème hors role play rencontré");
				if ($t2!==false) 
					$this->DonneDroitsForum($t2, $groupMJ);
		
				$t2=$this->CreeForum("Annonces",$t1,"Pour toute annonce hors role play (besoin de coopération ..)");
				if ($t2!==false) 					
					$this->DonneDroitsForum($t2, $groupMJ);
			}					
			
			if (($t1=$this->GetCategorie_id("Communications en Jeu"))==-1)
				$t1=$this->creeCategorie("Communications en Jeu");
			if ($t1!==false) {
				$t2=$this->CreeForum("Annonces des MJs",$t1,"Pour toute annonce officielle en jeu");
				if ($t2!==false) 
					$this->DonneDroitsForum($t2, $groupMJ);
		
				$t2=$this->CreeForum("Demande de joueurs",$t1,"Pour toute demande en relation avec le jeu (sauf les problèmes)");
				if ($t2!==false) 
					$this->DonneDroitsForum($t2, $groupMJ);
			}
					
			if (($t1=$this->GetCategorie_id("Magasins"))==-1)
				$t1=$this->creeCategorie("Magasins");
			if ($t1!==false) {
				$t2=$this->CreeForum("HVG -- Forge",$t1,"Forum à propos de la Forge");
				if ($t2!==false) 
					$this->DonneDroitsForum($t2, $groupMJ);
			}
			
			if (($t1=$this->GetCategorie_id("Guildes et Groupements de PJ"))==-1)
				$t1=$this->creeCategorie("Guildes et Groupements de PJ");
	
			if (($t1=$this->GetCategorie_id("MJs"))==-1)
				$t1=$this->creeCategorie("MJs");
	
			if (($t1=$this->GetCategorie_id("Modifs techniques à apporter"))==-1)
				$t1=$this->creeCategorie("Modifs techniques à apporter");
			if ($t1!==false) {
				$t2=$this->CreeForum("Idées",$t1,"Vous voulez émettre une suggestion pour améliorer les fonctionnalités du jeu.... C\'est ici.");
				if ($t2!==false) 
					$this->DonneDroitsForum($t2, $groupMJ);
		
				$t2=$this->CreeForum("Bugs",$t1,"encore une coquille des développeurs. C\'est ici qu\'il faut se plaindre.");
				if ($t2!==false) 
					$this->DonneDroitsForum($t2, $groupMJ);
			}
		}
	}


	function creationPJsManquants() {
		global $db;

		$SQL = $this->requeteListePJmanquants();
		$result_manquants=$db->sql_query($SQL);		
		$mag=0;
		if ($result_manquants) {
			while(	$row_manquants = $db->sql_fetchrow($result_manquants)){
				if ($this->CreationMembre(ConvertAsHTML($row_manquants["nom"]),$row_manquants["pass"],$row_manquants["email"],"PJ"))
					$mag++;
			}
		}
		return $mag;
	}	

	function creationMJsManquants() {
		global $db;

		$SQL = $this->requeteListeMJmanquants();
		$result_manquants=$db->sql_query($SQL);
		$mag=0;
		if ($result_manquants) {
			while(	$row_manquants = $db->sql_fetchrow($result_manquants)){
				if ($this->CreationMembre(ConvertAsHTML($row_manquants["nom"]),$row_manquants["pass"],$row_manquants["email"],"MJ"))
					$mag++;
			}
		}
		return $mag;
	}	

	function donneDroitsMJsSurForum() {
		global $db;

		$SQL=$this->requeteDroitsForumsManquantsPourGroupe("MJ");
		$result_manquants=$db->sql_query($SQL);
		$mag=0;
		if ($result_manquants) {
			while(	$row_manquants = $db->sql_fetchrow($result_manquants)){
				$this->DonneDroitsForum($row_manquants["forum_forum_id"], $row_manquants["forum_groupe_id"]);
				$mag++;
			}
		}
		return $mag;
	}
	
	function synchronyseForumJeu() {
		global $db;		

		$affichage = "creation des login de forum manquants<br />";
		//PJ et pnj
		$mag = $this->creationPJsManquants();

		if ($mag >0)
			$affichage .= "<li>Creation r&eacute;ussie de $mag PJ login de forums manquants<br /></li>";	

		$modif=$this->synchroForumJeuPJ();
		if ($modif >0)
			$affichage .= "<li>Mise &agrave; jour r&eacute;ussie de $modif PJ dans le forum<br /></li>";	

		//PJ et pnj
		$mag=$this->ajoutePJmanquantsDansGroupe();

		if ($mag >0)
			$affichage .= "<li>Affectation r&eacute;ussie de $mag PJ au groupe PJ_PNJ<br /></li>";	

		// MJ
		$mag= $this->creationMJsManquants();
		if ($mag >0)
			$affichage .= "<li>Creation r&eacute;ussie de ".$mag." MJ login de forums manquants<br /></li>";	

		$modif=$this->synchroForumJeuMJ();
		
		if ($modif >0)
			$affichage .= "<li>Mise &agrave; jour r&eacute;ussie de ".$modif." MJ dans le forum<br /></li>";	
			

		//MJ
		$mag=$this->ajouteMJmanquantsDansGroupe();
		if ($mag >0)
			$affichage .= "<li>Affectation r&eacute;ussie de ".$mag." MJ au groupe MJ<br /></li>";	

		$affichage .= "fin de creation des login de forum manquants<br />";	

		$affichage .= "donne les droits de moderateur aux MJ pour tous les forums<br />";	

		$mag=$this->donneDroitsMJsSurForum();
		
		$affichage .= "fin de donne les droits de moderateur aux MJ pour tous les forums<br />";	
		return $affichage;
	}


	
}


?>