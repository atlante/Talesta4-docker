<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: dbTalesta.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.12 $
$Date: 2010/06/23 22:19:35 $

*/


class sql_dbTalesta extends sql_dbJeu
{

	var $sql_time = 0; 
	var $fichier_install="";
	var $erreur="";
	/**
	 Affiche les variables de la classe mere pour memoire
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $in_transaction = 0;
	*/
	//
	// Constructor
	//
	function sql_dbTalesta($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{
		$starttime = gettime();
		global $dbmsJeu;
		$result = parent::sql_dbJeu($sqlserver, $sqluser, $sqlpassword, $database, $persistency);
		$this->fichier_install="../include/db/Talesta4.".$dbmsJeu.".sql";
		$endtime = gettime();
		$this->sql_time += $endtime - $starttime;
		
		return $result;
	}

	//
	// Other base methods
	//
	function sql_close()
	{
		$starttime = gettime();
		$result = parent::sql_close();
		$endtime = gettime();
	
		$this->sql_time += $endtime - $starttime;

		return $result;
	}

	//
	// Base query method
	//
	function sql_query($query = "", $messageErreur="", $transaction = FALSE)
	{
		global $forum;
		global $phpExtJeu;

		$starttime = gettime();
		$result = parent::sql_query($query, $transaction);
		if ($query!="" && (defined("DEBUG_MODE") && (DEBUG_MODE==2 || DEBUG_MODE==3)))	{		
			if (NOM_SCRIPT<>"install.".$phpExtJeu) {
				if ( ( (strrpos($query,NOM_TABLE_REGISTRE) ||strrpos($query,NOM_TABLE_MJ)||strrpos($query,NOM_TABLE_INSCRIPTION)
				 ) &&   (strrpos($query, "pass")!==FALSE))
				 || 
				 ( defined('IN_FORUM') && (IN_FORUM==1)  
				 	//table des admins
				 	&& ((strrpos($query,$forum->nomtableAdmins) &&   (strrpos($query, $forum->nomColPasswordtableAdmins." =")!==FALSE) )
				 	    //table des joueurs
				 	    ||(strrpos($query,$forum->nomtableUsers) &&   (strrpos($query, $forum->nomColPasswordtableUsers." =")!==FALSE) )
					   )
					 )
				     ) { 
						//N'affiche pas les requetes avec les mots de passe dans le log
						//Ni les updates, ni les insert, ni les select a la connection
						logDate("Requete avec mot de passe => cachee");
					}
					else logDate($query);	
			}		
		}				
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		if ($result===false) {
			$tmp=$this->sql_error();
			if ($messageErreur=="" )
				$messageErreur = GetMessage("ProblemeSQL");
			$this->erreur = $messageErreur." ".$query." ".$tmp["code"] . ": " . $tmp["message"];
			if ((defined("DEBUG_MODE") && DEBUG_MODE==1))	
				logDate("ERREUR :".$this->erreur,E_USER_WARNING,1);
			else {
				logDate("ERREUR :".$this->erreur,E_USER_WARNING);					
			}	
			$this->erreur=ConvertAsHTML($this->erreur);
			$result=false;
		}	
		else $this->erreur="";
		return $result;
	}

	//
	// Other query methods
	//
	function sql_numrows($query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_numrows($query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;
	}
	function sql_affectedrows()
	{
		$starttime = gettime();
		$result = parent::sql_affectedrows();
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_numfields($query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_numfields($query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_fieldname($offset, $query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_fieldname($offset, $query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_fieldtype($offset, $query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_fieldtype($offset, $query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_fetchrow($query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_fetchrow($query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_fetchrowset($query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_fetchrowset($query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_fetchfield($field, $rownum, $query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_rowseek($rownum, $query_id = 0)
	{
		$starttime = gettime();
		$result = parent::sql_rowseek($rownum, $query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;	
	}
	function sql_nextid()
	{
		$starttime = gettime();
		$result = parent::sql_nextid();
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;
	}
	function sql_freeresult($query_id = 0)
	{
		$starttime = gettime();

		$result = parent::sql_freeresult($query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;
	}
	function sql_error($query_id = 0)
	{
		$starttime = gettime();

		$result = parent::sql_error($query_id);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
		return $result;
	}

	function prefixe_table($prefixe) {
		$starttime = gettime();

		$result = parent::prefixe_table($prefixe);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
	}


	function sql_export($repertoireExport) {
		$starttime = gettime();
		$result = parent::sql_export($repertoireExport);
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;
	}

	function versionServer() {
		$starttime = gettime();
		$version= parent::versionServer(); 
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;	
		return 	$version;
	}	

 	function versionServerDiscriminante() {
		$starttime = gettime();
		$version= parent::versionServerDiscriminante(); 
		$endtime =  gettime();
		$this->sql_time += $endtime - $starttime;	
		return 	$version;
 	}


} // class sql_dbJeu

?>