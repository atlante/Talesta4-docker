<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: forum_phpBB2.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2010/01/24 19:33:23 $

*/
if (!defined('IN_PHPBB'))
	define("IN_PHPBB",1) ;		///< Variable specifique a PHPBB a definir pour qu'il ne voit pas qu'on passe par un chemin special

error_reporting  (E_ALL);	//reaffiche toutes les erreurs (phpBB filtre certaines)
//!	Classe regroupant les fonctions pour PHPBB
class forumPHPBB {
	
	var $nomsReservesForum=array(   ///< A Mettre en majuscules
		"ANONYMOUS"	///< user public de PHPBB
	);
	
	var $champimage="user_avatar";  ///<nom de colonne ou est stockee l'image
	
	
	var $champtypeimage="user_avatar_type"; ///<nom de colonne indiquant le type d'image (uploadee, venant de la gallery ou url externe)


	var $nomtableUsers = "";  ///<nom de la table ou est stocke le pass des membres (pour ne pas afficher ces requetes dans le log)   
	
	var $nomtableAdmins = "";  ///<nom de la table ou est stocke le pass des admin (pour ne pas afficher ces requetes dans le log)
	
	var $nomColPasswordtableUsers = "user_password"; 
	
	var $nomColPasswordtableAdmins = "user_password ";
	
	var $image_avatar_remote;
	
	var $image_avatar_local;
	
	var $URLadministrationForum;
	
	var $URLForum;
	
	var $lien_forum;
	
	//
	// Constructor
	//
	function forumPHPBB($lien_forum )
	{
		global $phpEx;
		global $board_config;
		global $db;
		global $user_ip; //$user_ip vient de PHPBB et ne sert que pour lui
		global $HTTP_GET_VARS;
		global $HTTP_POST_VARS;
		global $HTTP_COOKIE_VARS;
		global $HTTP_SERVER_VARS;
		global $HTTP_SESSION_VARS;
		global $HTTP_ENV_VARS;
		global $HTTP_POST_FILES;
		$phpbb_root_path = $lien_forum;
		require($lien_forum. "extension.inc"); //recupere l'extension des scripts php pour le forum
		require($lien_forum . "common.".$phpEx);
		error_reporting  (E_ALL);	//reaffiche toutes les erreurs (phpBB filtre certaines)		

		$this->nomtableUsers = USERS_TABLE;
		$this->nomtableAdmins = USERS_TABLE;
		$languePHPBB = $board_config['default_lang'];
		
		require($phpbb_root_path."language/lang_".$languePHPBB."/lang_main.".$phpEx);
		
		$userdata = session_pagestart($user_ip, PAGE_LOGIN);		
		//init_userprefs($userdata);	
		$this->lien_forum = $phpbb_root_path;
		$this->URLForum=$lien_forum."index.".$phpEx;	
		$this->URLadministrationForum =$phpbb_root_path."admin/index.".$phpEx;
		$this->image_avatar_remote =USER_AVATAR_REMOTE ; //define dans constants qui indique que l'avatar est sur une url externe (via http....)
		$this->image_avatar_local = USER_AVATAR_UPLOAD;
		
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
	utilise la fonction de phpbb pour verifier qu'il n'y a pas de caracteres incompatibles avec phpbb. 
	(Phpbb ne supporte pas les logins avec des ", des ' et plusieurs autres) et verifie que le nom saisi n'existe pas deja
	(nb: comme cela est deja code auparavant, je l'ai laisse pour le moment) et qu'il n'est pas present dans la liste des noms interdits.
	Renvoie true si $username est accepte. false sinon.
	*/
	function uservalide($username) {
		global $phpEx;
		require_once($this->lien_forum."includes/functions_validate.".$phpEx);
		$tmp = validate_username($username);
		return (!$tmp['error']);
		
	}	

	/**
	utilise la fonction de phpbb pour verifier que le mail est compatible avec phpbb	
	et qu'il n'est pas present dans la liste des mails bannis.
	Renvoie true si $username est accepte. false sinon.
	*/
	function emailvalide($email) {
		global $phpEx;
		global $board_config;
		require_once($this->lien_forum."includes/functions_validate.".$phpEx);
		require_once($this->lien_forum . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx);
		$tmp = validate_email($email);
		return (!$tmp['error']);
		
	}

	function MAJuser($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, $nouveaupass) {
		
		if ($nouveaupass<>"")
			return $this->MAJuserMotPasseCrypte($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, md5(ConvertAsHTML($nouveaupass)));
                else 
                        return $this->MAJuserMotPasseCrypte($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, $nouveaupass);
		/*global $db;
		$SQL="update ".USERS_TABLE." set username = '".$nouveaunomMembre ."'";
		if ($nouvelemail<>"")
			$SQL .=" , user_email = '". $nouvelemail."' ";
		if ($nouveaupass<>"")
			$SQL .=" ,user_password = '". md5(ConvertAsHTML($nouveaupass))."'";
		//if ($ancienne_image<>$imageforum) 
		//	$SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_remote.", ".$this->champimage."= '".$imageforum ."'";					
		if ($ancienne_image<>$imageforum) {			
			if (substr($imageforum,0,4)=="http") 
				$SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_remote.", ";
			else $SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_local.", ";	
			$SQL.= $this->champimage."= '".$imageforum ."'";
		}	
		$SQL .= " where username='".$anciennomMembre."'";
		return $db->sql_query($SQL,"",END_TRANSACTION_JEU);					
		*/
	}


	function MAJuserMotPasseCrypte($nouveaunomMembre, $nouvelemail,$imageforum,$ancienne_image,$anciennomMembre, $nouveaupass) {
		global $db;
		$SQL="update ".USERS_TABLE." set username = '".$nouveaunomMembre ."'";
		if ($nouvelemail<>"")
			$SQL .=" , user_email = '". $nouvelemail."' ";
		if ($nouveaupass<>"")
			$SQL .=" ,user_password = '". $nouveaupass."'";
		/*if ($ancienne_image<>$imageforum) 
			$SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_remote.", ".$this->champimage."= '".$imageforum ."'";
		*/
		if ($ancienne_image<>$imageforum ) {			
			if (substr($imageforum,0,4)=="http") 
				$SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_remote.", ";
			else $SQL .= ",".$this->champtypeimage." = ".$this->image_avatar_local.", ";	
			$SQL.= $this->champimage."= '".$imageforum ."'";
		}		
		$SQL .= " where username='".$anciennomMembre."'";
		return $db->sql_query($SQL,"",END_TRANSACTION_JEU);					
	}


	
	function autoconnect ($nomconnecte) {
		if (!defined('IN_LOGIN'))
			define("IN_LOGIN", true);
		global $board_config;
		global $HTTP_COOKIE_VARS;
		global $db;
		$SQL= "SELECT user_id as forum_user_id, user_rank FROM ".USERS_TABLE." where username='".$nomconnecte."'";
		if ($result = $db->sql_query($SQL)) {
			$row = $db->sql_fetchrow($result);
			if ($row!==false) {
        			$user_id = $row["forum_user_id"];
        	
        			$cookiename = $board_config['cookie_name'];
        			$cookiepath = $board_config['cookie_path'];
        			$cookiedomain = $board_config['cookie_domain'];
        			$cookiesecure = $board_config['cookie_secure'];
        			$session_id = $HTTP_COOKIE_VARS[$cookiename . '_sid'];
        			setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);
        			if ($row["user_rank"]>=1)
        				$SQL = "update " . SESSIONS_TABLE . " set session_admin=1, session_user_id = $user_id, session_logged_in = 1, session_page = 0
        					where session_id = '$session_id'";
        			else
        				$SQL = "update " . SESSIONS_TABLE . " set session_user_id = $user_id, session_logged_in = 1, session_page = 0
        					where session_id = '$session_id'";
        			$result = $db->sql_query($SQL);
                        }
		}
		return $result;
	}
	
	function CreeCookie () {
		global $board_config;
		global $user_ip; //$user_ip vient de PHPBB et ne sert que pour lui
		global $HTTP_COOKIE_VARS;
		$session_id = md5(uniqid($user_ip));
		$cookiename = $board_config['cookie_name'];
		$cookiepath = $board_config['cookie_path'];
		$cookiedomain = $board_config['cookie_domain'];
		$cookiesecure = $board_config['cookie_secure'];
		setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);
		return $session_id;

	}	

	function CreeSession ($session_id) {
		global $db;
		global $user_ip; //$user_ip vient de PHPBB et ne sert que pour lui
		$current_time = time();
		//ANONYMOUS est un define de PHPBB dans constants.php
		$SQL = "INSERT INTO " . SESSIONS_TABLE . "
			(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in)
			VALUES ('$session_id', ".ANONYMOUS.", $current_time, $current_time, '$user_ip', -1, 0)";
		$result = $db->sql_query($SQL);
		return $result;
	}	

	function DetruireSession () {
		global $db;
		global $board_config;
		global $HTTP_COOKIE_VARS;
		$cookiename = $board_config['cookie_name'];
		if (isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) && $HTTP_COOKIE_VARS[$cookiename . '_sid']!=null) {
			$SQL = "DELETE FROM " . SESSIONS_TABLE . " 	where session_id = '".$HTTP_COOKIE_VARS[$cookiename . '_sid']."'";
			$result = $db->sql_query($SQL);
		}
		else $result=true;
		return $result;
	}	

	function RecupereSession () {
		global $board_config;
		global $HTTP_COOKIE_VARS;
		if (isset($board_config['cookie_name'])) {
			$cookiename = $board_config['cookie_name'];
			if (isset($HTTP_COOKIE_VARS[$cookiename . '_sid']))
				$session= $HTTP_COOKIE_VARS[$cookiename . '_sid'];
			else 	$session="";
		}
		else 	$session="";
		return $session;
	}

	function requeteMJ($filtreid_mj="") {
		$SQL = "SELECT mj.*, u.".$this->champimage.", u.".$this->champtypeimage." FROM ".NOM_TABLE_MJ." mj,".USERS_TABLE." u WHERE u.username = mj.nom";
		if ($filtreid_mj<>"")
		 	$SQL.= " and id_mj = ".$filtreid_mj;
		 return $SQL;	
	}	

	function requetePJ($filtreid_pj="") {
		$SQL = "SELECT p.*, u.".$this->champimage.", u.".$this->champtypeimage." FROM ".NOM_TABLE_REGISTRE." p left join ".USERS_TABLE." u on u.username = p.nom ";
		if ($filtreid_pj<>"")
		 	$SQL.= " where p.id_perso = ".$filtreid_pj;
		 return $SQL;		
	}	

	function requetePJvisiblesDuLieu($lieu, $pj) {
		$SQL1 = "Select P.*,  u.".$this->champimage.",u.".$this->champtypeimage." FROM ".NOM_TABLE_REGISTRE." P left join ".USERS_TABLE." u on u.username = P.nom where P.id_perso <> ".$pj." AND P.archive=0 and P.id_lieu = ".$lieu ."
		and dissimule = 0";
		return $SQL1;
	}

	function requetePJcachesConnusDuLieu($lieu, $pj) {
		$SQL2 = "Select P.*,  u.".$this->champimage.",u.".$this->champtypeimage." FROM (".NOM_TABLE_REGISTRE." P left join ".USERS_TABLE." u on u.username = P.nom),".NOM_TABLE_ENTITECACHEE." E,".NOM_TABLE_ENTITECACHEECONNUEDE ." ECCD 
		  where P.id_perso <> ".$pj." AND P.archive=0 and P.id_lieu = ".$lieu ."
		and P.dissimule = 1 and P.id_perso= E.id_entite and E.id= ECCD.id_entitecachee and E.type=2
		and (ECCD.id_perso is null or ECCD.id_perso = ".$pj.")";
		return $SQL2;
	}

	function requeteDroitsForumsManquantsPourGroupe($nom_groupe) {
		$SQL="select distinct ".FORUMS_TABLE.".forum_id as forum_forum_id,".GROUPS_TABLE.".group_id as forum_groupe_id, 1 from ".GROUPS_TABLE.", ".FORUMS_TABLE." left join ".AUTH_ACCESS_TABLE." on ". AUTH_ACCESS_TABLE. ".forum_id =".FORUMS_TABLE.".forum_id  where ".AUTH_ACCESS_TABLE.".forum_id is null and group_name='".$nom_groupe."'";
		return $SQL;
	}


	function requeteMJaAjouterDansGroupeMJs() {
		$SQL = "SELECT u.user_id as forum_user_id FROM (".USERS_TABLE." u left join ".USER_GROUP_TABLE." ug on u.user_id = ug.user_id) left join ".GROUPS_TABLE." on ug.group_id = ".GROUPS_TABLE.".group_id, " .NOM_TABLE_MJ." p where p.nom = u.username and ug.group_id is null";
		return $SQL;
	}
	
	function requetePJaAjouterDansGroupePJs() {
		$SQL = "SELECT u.user_id as forum_user_id FROM (".USERS_TABLE." u left join ".USER_GROUP_TABLE." ug on u.user_id = ug.user_id) left join ".GROUPS_TABLE." on ug.group_id = ".GROUPS_TABLE.".group_id, " .NOM_TABLE_PERSO." p where p.nom = u.username and ug.group_id is null";
		return $SQL;
	}			
	
	function requeteListePJmembres() {
		$SQL="select u.nom,u.pass,u.email,u.pnj,user_id as forum_user_id, fu.user_password as forum_password,fu.user_email as forum_email, fu.".$this->champimage." as forum_image from " .NOM_TABLE_PERSO." u , ".USERS_TABLE." fu where u.nom=fu.username and pnj<2";
	        if(!defined("CREE_MEMBRE_PNJ") ||  CREE_MEMBRE_PNJ==0)
	                $SQL.= " and u.pnj <>1";
		return $SQL;
	}			
	
	function requeteListePJmanquants() {
		$SQL = "select u.nom,u.pass,u.email,u.pnj from " .NOM_TABLE_PERSO." u left join  ".USERS_TABLE." fu on u.nom=fu.username where fu.username is null and u.pnj <=1";
	        if(!defined("CREE_MEMBRE_PNJ") ||  CREE_MEMBRE_PNJ==0)
	                $SQL.= " and u.pnj <>1";
		return $SQL;
	}				

	function requeteListeMJmanquants() {
		$SQL = "select u.nom,u.pass,u.email/*,u.img_avatar*/ from (" .NOM_TABLE_MJ." u left join  ".USERS_TABLE." fu on u.nom=fu.username) left join ". NOM_TABLE_REGISTRE." on role_mj = u.id_mj  where fu.username is null
		and role_mj is null";
		return $SQL;
	}				

	function requeteListeMJmembres() {
		$SQL="select u.nom,u.pass,u.email/*,u.img_avatar*/,user_id as forum_user_id, fu.user_password as forum_password,fu.user_email as forum_email, fu.".$this->champimage." as forum_image from " .NOM_TABLE_MJ." u , ".USERS_TABLE." fu where u.nom=fu.username ";
		return $SQL;
	}				

	function ajoutePJmanquantsDansGroupe() {
		global $db;
		$mag=0;
		$groupePJ = $this->GetGroupe_id("PJ_PNJ");
		if ($groupePJ<>-1) {
			$SQL = $this->requetePJaAjouterDansGroupePJs();
			$result_manquants=$db->sql_query($SQL);
			if ($result_manquants!==false) {
				$result2=true;
				$mag=$db->sql_numrows($result_manquants);
				while(($row_manquants = $db->sql_fetchrow($result_manquants)) && $result2!==false) {
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

		$groupeMJ = $this->GetGroupe_id("MJ");
		if ($groupeMJ<>-1) {
			$SQL = $this->requeteMJaAjouterDansGroupeMJs();
			$result_manquants=$db->sql_query($SQL);
			if ($result_manquants!==false) {
				$result2=true;
				$mag=$db->sql_numrows($result_manquants);
				while(($row_manquants = $db->sql_fetchrow($result_manquants)) && $result2!==false) {
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
		$SQL=$this->requeteListePJmembres();
		$resultSynchro=$db->sql_query($SQL);
		if ($resultSynchro!==false) {
			$modif=0;
			$result2=true;
			while(($rowSynchro = $db->sql_fetchrow($resultSynchro)) && ($result2!==false)) {	
				if ($rowSynchro["pass"]<>$rowSynchro["forum_password"] || $rowSynchro["email"] <> $rowSynchro["forum_email"]) {
					$modif++;
					$result2=$this->MAJuserMotPasseCrypte($rowSynchro["nom"], $rowSynchro["email"],"","",$rowSynchro["nom"], $rowSynchro["pass"]);
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
		return $this->lien_forum.'images/smiles/';
	}

	function ScriptAfficheForum($forum_id){
		global $phpEx;
		return $this->lien_forum."viewforum.".$phpEx."?".POST_FORUM_URL."=".$forum_id;
	}

	function PrivateMessageAutorise() {
		global $board_config;
		if ($board_config['privmsg_disable']==0)
			return true;
		return false;	
		
	}	

	function ScriptPrivateMessage($membre_id, $membre_nom){		
		global $phpEx;
		//membre_nom ne sert a rien pour PHPBB qui fonctionne avec les ID
		return $this->lien_forum."privmsg.".$phpEx."?mode=post&amp;".POST_USERS_URL."=".$membre_id;
	}

	function PathimageTemplate(){
		global $board_config;
		$languePHPBB = $board_config['default_lang'];
		return $this->lien_forum."templates/".$this->nomTemplateParDefaut()."/images/lang_".$languePHPBB."/";
	}	
	
	function URLimagePrivateMessage(){
		$file = $this->PathimageTemplate() ."icon_pm.gif";		
		if (file_exists($file)===FALSE) {
			$file= $this->lien_forum."templates/".$this->nomTemplateParDefaut()."/images/lang_english/icon_pm.gif";	
		}	
		return $file;
	}	
	
	function URLimageAvatar($imagetype,$imageforum ){
		switch($imagetype)
		{
			case 0:    //0 valeur vide par defaut de PHPBB
				$url="";
				break;
			case $this->image_avatar_remote:    //2
				$url= $imageforum;
				break;

			case USER_AVATAR_UPLOAD: //1
				global $board_config;
				$chemin_avatar_uploades = $board_config['avatar_path'];
				$url= $this->lien_forum.$chemin_avatar_uploades."/". $imageforum;
				break;

			case USER_AVATAR_GALLERY: //3
				global $board_config;
				$chemin_avatar_uploades = $board_config['avatar_gallery_path'];
				$url=$this->lien_forum.$chemin_avatar_uploades."/". $imageforum;
				break;
		}
		return 	$url;							
	}

	function nomTemplateParDefaut() {
		global $db;
		global $board_config;
		$valeur="";
		$SQL = "SELECT template_name FROM " . THEMES_TABLE . " WHERE themes_id = ". $board_config['default_style'];
		$result = $db->sql_query($SQL);	
		if ($result!==false) {
		$row = $db->sql_fetchrow($result);
			$valeur= $row["template_name"];
		}
		return $valeur;	
	}


	//$type = "PJ" ou "MJ"
	function CreationMembre($nom,$pass,$email,$type="PJ", $imageforum=""){
		global $db;
		global $board_config;
		$SQL = "select user_id,user_password,user_email from ".USERS_TABLE." where username = '".ConvertAsHTML($nom) ."'";
		$result=$db->sql_query($SQL);
		if ($result!==false) {
			if ($db->sql_numrows($result)==0) {
				$SQL = "select max(user_id)+1 as max from ".USERS_TABLE;
				$result=$db->sql_query($SQL);
				$row = $db->sql_fetchrow($result);
				$max=$row["max"];
				if ($max=="")
					$max=1;				
				// Si on est PJ, le mot de passe est deja crypte md5.		
				if ($type=="PJ")
					$SQL = "INSERT INTO ".USERS_TABLE." (user_id,user_active,username,user_password,user_email, user_regdate,user_lang, user_dateformat,user_attachsig, user_notify_pm, user_popup_pm, user_timezone,".$this->champtypeimage.", ".$this->champimage." ) 
						values ( ".$max.",1,'".ConvertAsHTML($nom)."','".$pass."','".$email."',".time().",'".$board_config['default_lang']."', '".$board_config['default_dateformat']."',1,1,1 ,'".$board_config['board_timezone']."','".$this->image_avatar_remote."','".$imageforum ."')";
				else 	
				$SQL = "INSERT INTO ".USERS_TABLE." (user_id,user_active,username,user_password,user_email, user_regdate,user_lang, user_dateformat,user_attachsig, user_notify_pm, user_popup_pm, user_timezone,".$this->champtypeimage.", ".$this->champimage." ) 
					values ( ".$max.",1,'".ConvertAsHTML($nom)."',md5('".$pass."'),'".$email."',".time().",'".$board_config['default_lang']."', '".$board_config['default_dateformat']."',1,1,1 ,'".$board_config['board_timezone']."','".$this->image_avatar_remote."','".$imageforum ."')";
				$result=$db->sql_query($SQL);
			}	
			else {
				// mise a jour du login de phpbb (priorite aux joueurs). On garde les anciens eventuels messages.
				$row = $db->sql_fetchrow($result);
				$max=$row["user_id"];
				$SQL = "update " . USERS_TABLE." set user_password = md5('".$pass."'),user_active=1, user_email='".$email."', user_timezone ='".$board_config['board_timezone']."' where  user_id = " .$row["user_id"];
				$result=$db->sql_query($SQL);
			}	
		}
		// efface tous les groupes du user au cas ou 
		if ($result!==false) {
			$SQL = "delete from ".USER_GROUP_TABLE. " where user_id =".$max;
			$result = $db->sql_query($SQL);
		}
		if ($result!==false) {
			// affectation du group PJ_PNJ au joueur ou MJ pour un MJ
			if ($type=="PJ")
				$nom_groupe = "PJ_PNJ";
			else 	$nom_groupe = "MJ";
			$SQL = "select group_id from ".GROUPS_TABLE." where group_name='".$nom_groupe."'";
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
		$SQL = "select  user_id from ".USERS_TABLE. " where username='".ConvertAsHTML($nom)."'";
		$result= $db->sql_query($SQL);
		if ($result!==false && ($row = $db->sql_fetchrow($result))!==false) {
		        $user_id = $row["user_id"];
		        $SQL = "delete from ".USER_GROUP_TABLE. " where user_id =".$user_id;
		        if ($result = $db->sql_query($SQL)) {
        			$SQL = "delete from ".USERS_TABLE. " where user_id =".$user_id;
	        		$result=$db->sql_query($SQL);
		        }
		}        
		return $result;			
	}	

	function CreeGroupUser($membre_id, $groupe_id, $typeAffectation= "MembreApprouvé"){
		global $db;
		if ($typeAffectation=="Modérateur")
			$typeAffectation = "MembreApprouvé";		
		if ($typeAffectation=="MembreEnAttente")
			$type = 1;
		else 
		if ($typeAffectation=="MembreApprouvé")
			$type = 0;
		$SQL = "INSERT INTO ".USER_GROUP_TABLE."  (user_id,group_id, user_pending)	values (". $membre_id.",". $groupe_id.",".$type.")";
		$result=$db->sql_query($SQL,"");
		return $result;
	}
		
	function DonneDroitsForum($forum_id, $groupe_id){
		global $db;

		$SQL = "INSERT INTO ".AUTH_ACCESS_TABLE."  (group_id , forum_id ,
			auth_view,  auth_read , auth_post,  auth_reply, auth_edit ,
			auth_delete,  auth_sticky,  auth_announce,  auth_vote , auth_pollcreate,auth_mod)
			 VALUES ('".$groupe_id."','". $forum_id."',1,1,1,1,1,1,1,1,1,1,1)";		
		$result = $db->sql_query($SQL,"",END_TRANSACTION_JEU);
		return $result;
	}

	function CreeForum($nomForum,$categorieID,$descForum, $visibilite="TousMembres") {
		global $db;

		$SQL="select max(forum_id)+1 as maxid from ".FORUMS_TABLE;
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newForumID= $row2["maxid"];
		if ($newForumID=="")
			$newForumID=1;
		$SQL="select max(forum_order)+10 as maxorder from ".FORUMS_TABLE." where cat_id = '". $categorieID."'";
		$recherche3 = $db->sql_query($SQL);
		$row3 = $db->sql_fetchrow($recherche3);
		$newForumOrder= $row3["maxorder"];
		if ($newForumOrder=="")
			$newForumOrder = 10;
		switch ($visibilite) {	
			case "TousMembres":	//toute personne membre et connectee
				$droit = AUTH_REG;
				break;				
			case "Public":		//tout le monde voit le forum, meme une personne non enregistree
				$droit = AUTH_ALL;
				break;
			case "Privé":		//seuls certains membres (guilde)
				$droit = AUTH_ACL;
				break;
			case "Mod":		//moderateurs
				$droit = AUTH_MOD;
				break;
			default:		//admins seuls
				$droit=AUTH_ADMIN;
		}		
		// creation du forum		
		$SQL = "INSERT INTO ".FORUMS_TABLE." (forum_id,cat_id,forum_name,forum_desc,forum_order, 
			auth_view,  auth_read , auth_post,  auth_reply , auth_edit,  auth_delete , auth_sticky,  auth_announce,  auth_vote , auth_pollcreate )
			 VALUES ('".$newForumID."','".$categorieID."','".ConvertAsHTML($nomForum)."','".ConvertAsHTML($descForum)."','".$newForumOrder."', $droit,$droit,$droit,$droit,$droit,$droit,$droit,".AUTH_MOD.",$droit,".AUTH_MOD.")";
		if ($result=$db->sql_query($SQL))
			return $newForumID;
		else return false;	
	}

	function CreeGroupe($nomGroupe,$descGroupe,$responsableGroupe, $typeGroupe = "cache") {
		global $db;
		$SQL="select max(group_id)+1 as maxid  from ".GROUPS_TABLE;
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newGroupID= $row2["maxid"];
		if ($newGroupID=="")
			$newGroupID=1;		
		if ($typeGroupe=="cache")
			$type = GROUP_HIDDEN;
		else 	
		if ($typeGroupe=="ouvert")
			$type = GROUP_OPEN;
		else 	
		if ($typeGroupe=="ferme")
			$type = GROUP_CLOSED;	
		else 	$type = $typeGroupe;	
		//creation du groupe
		$SQL = "INSERT INTO ".GROUPS_TABLE." (group_id,  group_type , group_name,  group_description,  group_moderator , group_single_user )
				 VALUES ('".$newGroupID."','".$type."','".ConvertAsHTML($nomGroupe)."','".ConvertAsHTML($descGroupe)."','".$responsableGroupe."',0)";
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
		$SQL = "select group_id from ".GROUPS_TABLE." where group_name='".$nomGroupe."'";
		$recherche1 = $db->sql_query($SQL);
		if($db->sql_numrows($recherche1)>0 ) {
			$row = $db->sql_fetchrow($recherche1);	
			return $row["group_id"];
		}
		else return -1;		
	}
	
	function GetCategorie_id($nomCategorie) {
		global $db;	

		$SQL="select cat_id from ".CATEGORIES_TABLE." where cat_title = '". $nomCategorie."'";
		$recherche1 = $db->sql_query($SQL);
		if($db->sql_numrows($recherche1)>0 ) {
			$row = $db->sql_fetchrow($recherche1);	
			return $row["cat_id"];
		}
		else return -1;	
	}


	function selectMembres($filtreNomMembre="") {
		$SQL = "Select user_id as idselect, username as labselect from ".USERS_TABLE;
		if ($filtreNomMembre<>"")
			$SQL.= " where username = '".$filtreNomMembre. "'";	
		$SQL.= " order by username ASC";
		return $SQL;
	}
	

	function updateConfigForum($paramConfig, $valeur ){
		global $db;
		$SQL = " update ".CONFIG_TABLE." set config_value='".$valeur."' where config_name='".$paramConfig."'";
		$result= $db->sql_query($SQL);
		return $result;			
	}	

	function creeCategorie($nom ){
		global $db;

		$SQL="select max(cat_id)+1 as maxid from ".CATEGORIES_TABLE;
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newCategorieID= $row2["maxid"];
		if ($newCategorieID=="")
			$newCategorieID=1;
		$SQL = "insert into ".CATEGORIES_TABLE." (cat_id, cat_title, cat_order) values (".$newCategorieID.",'".$nom."',".$newCategorieID.")";
		if ($result=$db->sql_query($SQL))
			return $newCategorieID;
		else return false;	

	}	


	
	function creePrivateMessage($IDEmetteur, $IDReceveur,$nomEmetteur, $nomReceveur, $sujet, $texte) {			
		global $db;
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
		$SQL="select max(privmsgs_id )+1 as maxid from ".PRIVMSGS_TABLE;
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$newPMID= $row2["maxid"];
		if ($newPMID=="")
			$newPMID=1;
		$SQL = "insert into ".PRIVMSGS_TABLE." ( privmsgs_id  ,	   privmsgs_from_userid ,  privmsgs_to_userid , privmsgs_subject )
		values ($newPMID,  $IDEmetteur, $IDReceveur,\"$sujet\")";
		if ($result=$db->sql_query($SQL)) {
			$SQL = "insert into ".PRIVMSGS_TEXT_TABLE." (  privmsgs_text_id ,    privmsgs_text )
			values (".$newPMID.", \"". $texte."\")";
			if ($result=$db->sql_query($SQL)) 
				return $newPMID;
			else return false;		
		}	
		else return false;	
	}	

	function synchroniseMailAdminForumAvecMailAdminJeu(){
		global $db;

		$SQL="select email from ".NOM_TABLE_MJ ." where id_mj=1";
		$recherche2 = $db->sql_query($SQL);
		$row2 = $db->sql_fetchrow($recherche2);
		$email= $row2["email"];
		
		return $this->updateConfigForum("board_email", $email );

	}	


	/**
	remplace l'ancien fichier postInstallPHPBB.sql.
	Interets l'install est completement en php et suppression du probleme d'oubli du passage de l'ex fichier . sql
	Cette fonction est donc appelee des que l'on configure le jeu avec un forum
	*/
	function postInstall() {
		$this->updateConfigForum("default_dateformat", 'd/m/Y H:i:s' );
		
		$this->updateConfigForum("board_timezone", '2.00' );
		$this->updateConfigForum("board_disable", FORUM_UNLOCKED );
		$this->updateConfigForum("allow_smilies", '1' );
		$this->updateConfigForum("allow_namechange", '0' );

		$this->updateConfigForum("allow_avatar_local", '1' );
		$this->updateConfigForum("allow_avatar_remote", '1' );
		$this->updateConfigForum("allow_avatar_upload", '1' );		
		$this->updateConfigForum("default_lang", 'french' );
		$this->updateConfigForum("allow_avatar_remote", '1' );
		
		//les pjs sont crees automatiquement, l'admin du forum ne valide pas ni le joueur
 		$this->updateConfigForum("require_activation",USER_ACTIVATION_NONE);
 
		// Au cas ou on voudrait faire le disable_registration_FR_v1.1.0.txt
		$this->updateConfigForum("registration_status", '1' );
		$this->updateConfigForum("registration_closed", "Les inscriptions aux forum sont automatiques lors de l''inscription au jeu" );

		$this->synchroniseMailAdminForumAvecMailAdminJeu();

		if (($groupMJ =$this->GetGroupe_id("MJ"))==-1) {
			//le groupe de MJ n'existe pas => on considere que tout cela n'a jamais ete fait.
			//le reste au dessus n'etant que des updates, ce n'est pas grave si c'est fait plusieurs fois	
			$groupMJ = $this->CreeGroupe("MJ","Groupe des MJs",2, GROUP_CLOSED);
			if (($groupPJ =$this->GetGroupe_id("PJ_PNJ"))==-1) 
				$groupPJ = $this->CreeGroupe("PJ_PNJ","Groupe des PJs",2, GROUP_CLOSED);
			
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