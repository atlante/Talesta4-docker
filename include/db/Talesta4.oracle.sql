--  oracle ver10.0.1
--
--  host: localhost   database: talesta4
--  --------------------------------------------------------
--  Script migré de celui de MYSQL.
--  donc sans mention de tablespace et de notion de stockage
--  ou de droits et d'utilisateurs

--Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  
--
--$RCSfile: Talesta4.oracle.sql,v $
--$Revision: 1.15 $
--$Date: 2010/01/24 19:33:34 $


CREATE OR REPLACE PROCEDURE adm_patch (
   p_command     IN   VARCHAR2,
   p_err_1       IN   NUMBER DEFAULT 0,
   p_err_2       IN   NUMBER DEFAULT 0,
   p_err_3       IN   NUMBER DEFAULT 0,
   p_err_4       IN   NUMBER DEFAULT 0,
   p_err_5       IN   NUMBER DEFAULT 0,
   p_flag_show   IN   NUMBER DEFAULT 0
)
AUTHID CURRENT_USER
IS
-- +--------------------------------------------------------------------------+
-- | ADM_PATCH                                                                |
-- +-----------------------------+----------------------------+---------------+
-- | Creation Date : 27/04/2004  |  Author : MDE              | Version : 1.0 |
-- +-----------------------------+----------------------------+---------------+
-- | Cette procedure est dediée au passage de patch sur une base de données   |
-- | Elle prend une commande en entrée, l'execute et eventuellement filtre    |
-- | les erreurs non significatives                                           |
-- |    ORA-00942: table or view does not exist                               |
-- |                                                                          |
-- | Cette fonction est definie avec l'option 'AUTHID CURRENT_USER' donc      |
-- | elle est executée avec l'identité de l'appellant.                        |
-- |                                                                          |
-- | PARAMETRES :                                                             |
-- |    - p_command   : commande devant etre executée par EXECUTE IMMEDIATE   |
-- |    - p_err_1     : Premier code erreur autorisé                          |
-- |    - p_err_2     : Second code erreur autorisé                           |
-- |    - p_err_3     : Troisiemme code erreur autorisé                       |
-- |    - p_err_4     : Quatrieme code erreur autorisé                        |
-- |    - p_err_5     : Cinquieme code erreur autorisé                        |
-- |    - p_flag_show : booleen demandant un affichage detaillé.              |
-- |                    [0: Affichage mini, 1: Affichage detaillé]            |
-- +--------------------------------------------------------------------------+
   v_cmd       VARCHAR2 (4000);
   v_command   VARCHAR2 (4000);
-- p_flag_continue NUMBER(1) := 0;
   v_length    NUMBER;
   v_pos       NUMBER;
BEGIN
   -- Manipulation de la chaine en entrée
   -- -----------------------------------
   -- Suppression des Espaces non necessaires
   -- Suppression des retour chariot
   v_command := REPLACE (p_command, CHR (10), ' ');
   --adm_display (v_command, p_flag_show);
   v_command := REPLACE (v_command, CHR (13), ' ');
   v_command := REPLACE (v_command, CHR (9), ' ');
   v_command := REPLACE (v_command, '    ', ' ');
   v_command := REPLACE (v_command, '   ', ' ');
   v_command := REPLACE (v_command, '  ', ' ');
   v_command := LTRIM (RTRIM (v_command));
   --adm_display (v_command, p_flag_show);
   --
   -- Gestion d'un eventuel caractere ';' en fin de chaine
   v_length := LENGTH (v_command);
   v_pos := INSTR (v_command, ';', -1, 1);

   -- Si il n'y a pas de ';' dans la chaine
   IF v_pos = 0 THEN
      -- On ne fait rien. Il n'y a pas de risque
      -- p_flag_continue = 1;
      NULL;
   -- Sinon c'est qu'il y a des ';' dans la chaine
   ELSE
      -- Si le caractère ';' est en fin de chaine
      IF v_pos = v_length THEN
         --p_flag_continue = 1;
         -- On enleve le caractre ';' en fin de chaine
         v_command := SUBSTR (v_command, 1, v_length - 1);
         v_command := RTRIM (v_command);
      ELSE
         -- On continue. On verra bien ce qu'il se passe
         -- p_flag_continue = 1;
         NULL;
      END IF;
   END IF;

   -- Composition dynamique du block PL/SQL
   -- -------------------------------------
   v_cmd := '';
   v_cmd := v_cmd || 'DECLARE ' || CHR (10);
   v_cmd := v_cmd || '   excep_bidon EXCEPTION ;' || CHR (10);

   IF p_err_1 <> 0 THEN
      v_cmd := v_cmd || '   excep_01   EXCEPTION ;' || CHR (10);
      v_cmd :=
              v_cmd || '   PRAGMA EXCEPTION_INIT (excep_01, -' || TO_CHAR (SIGN (p_err_1) * p_err_1) || ' );'
              || CHR (10);
   END IF;

   IF p_err_2 <> 0 THEN
      v_cmd := v_cmd || '   excep_02   EXCEPTION ;' || CHR (10);
      v_cmd :=
              v_cmd || '   PRAGMA EXCEPTION_INIT (excep_02, -' || TO_CHAR (SIGN (p_err_2) * p_err_2) || ' );'
              || CHR (10);
   END IF;

   IF p_err_3 <> 0 THEN
      v_cmd := v_cmd || '   excep_03   EXCEPTION ;' || CHR (10);
      v_cmd :=
              v_cmd || '   PRAGMA EXCEPTION_INIT (excep_03, -' || TO_CHAR (SIGN (p_err_3) * p_err_3) || ' );'
              || CHR (10);
   END IF;

   IF p_err_4 <> 0 THEN
      v_cmd := v_cmd || '   excep_04   EXCEPTION ;' || CHR (10);
      v_cmd :=
              v_cmd || '   PRAGMA EXCEPTION_INIT (excep_04, -' || TO_CHAR (SIGN (p_err_4) * p_err_4) || ' );'
              || CHR (10);
   END IF;

   IF p_err_5 <> 0 THEN
      v_cmd := v_cmd || '   excep_05   EXCEPTION ;' || CHR (10);
      v_cmd :=
              v_cmd || '   PRAGMA EXCEPTION_INIT (excep_05, -' || TO_CHAR (SIGN (p_err_5) * p_err_5) || ' );'
              || CHR (10);
   END IF;

   v_cmd := v_cmd || 'BEGIN ' || CHR (10);
   v_cmd := v_cmd || '   EXECUTE IMMEDIATE ''' || v_command || ''';' || CHR (10);
   v_cmd := v_cmd || 'EXCEPTION ' || CHR (10);
   v_cmd := v_cmd || '   WHEN excep_bidon THEN ' || CHR (10);
   v_cmd := v_cmd || '      NULL;' || CHR (10);

   IF p_err_1 <> 0 THEN
      v_cmd := v_cmd || '   WHEN excep_01 THEN ' || CHR (10);
      v_cmd := v_cmd || '      NULL;' || CHR (10);
   END IF;

   IF p_err_2 <> 0 THEN
      v_cmd := v_cmd || '   WHEN excep_02 THEN ' || CHR (10);
      v_cmd := v_cmd || '      NULL;' || CHR (10);
   END IF;

   IF p_err_3 <> 0 THEN
      v_cmd := v_cmd || '   WHEN excep_03 THEN ' || CHR (10);
      v_cmd := v_cmd || '      NULL;' || CHR (10);
   END IF;

   IF p_err_4 <> 0 THEN
      v_cmd := v_cmd || '   WHEN excep_04 THEN ' || CHR (10);
      v_cmd := v_cmd || '      NULL;' || CHR (10);
   END IF;

   IF p_err_5 <> 0 THEN
      v_cmd := v_cmd || '   WHEN excep_05 THEN ' || CHR (10);
      v_cmd := v_cmd || '      NULL;' || CHR (10);
   END IF;

   v_cmd := v_cmd || 'END;' || CHR (10);

   --adm_display (v_cmd, p_flag_show);

   -- Execution du block PL/SQL
   -- -------------------------
   EXECUTE IMMEDIATE v_cmd;
EXCEPTION
   WHEN OTHERS THEN
      DECLARE
         err_num   NUMBER;
         err_msg   VARCHAR2 (100);
         v_mess    VARCHAR2 (254);
      BEGIN
         err_num := SQLCODE;
         err_msg := SUBSTR (SQLERRM, 1, 100);
         v_mess := '### ' || err_num || ' : ' || err_msg;
         -- DBMS_OUTPUT.put_line (v_mess);
         DBMS_OUTPUT.put_line ('>>>' || SUBSTR (v_command, 1, 248) || '<<<');
         raise_application_error (-20001, v_mess);
      END;
END adm_patch;
/


CREATE OR REPLACE PROCEDURE adm_trigger_insert (nomtable varchar2 )
AUTHID CURRENT_USER
IS
-- triggers en INSERT pour remplir la pk avec la SEQUENCE
-- sauf pour tlt_sessions qui a un id special
-- c'est pas propre OK, mais je n'ai trouve que ca pour remplacer
-- les n° auto de mysql et postgresql sans rien a avoir changer au code
v_trigger varchar2(2000);
begin
	if  nomtable is null then
		for pk in (
			select ucc.column_name, ucc.TABLE_name from user_constraints uc, user_cons_columns ucc
			where uc.constraint_type='P' and uc.status='ENABLED'
			and lower(uc.TABLE_name) like lower('tlt_%')
			and uc.constraint_name = ucc.constraint_name
			and uc.owner = ucc.owner
			and ucc.TABLE_name = uc.TABLE_name
			and lower(ucc.TABLE_name) <> lower('tlt_sessions')
			and 1 = (select count(ucc.column_name) from user_cons_columns ucc where uc.constraint_name = ucc.constraint_name and ucc.TABLE_name = uc.TABLE_name group by uc.TABLE_name)
			order by ucc.TABLE_name
		)
		loop
			 v_trigger:= 'CREATE sequence seq_'||pk.TABLE_name || ' nocache ';
			 adm_patch(v_trigger, 955);
			 v_trigger:= 'CREATE or replace trigger tri_'||pk.TABLE_name||' before INSERT on '||pk.TABLE_name||' for each row
				 declare v_id number;
				begin
				 select seq_'||pk.TABLE_name||'.nextval into v_id from dual;
				 :new.'||pk.column_name||':= v_id;
				end;';
			execute immediate v_trigger;
		end loop;
	else
		for pk in (
			select ucc.column_name, ucc.TABLE_name from user_constraints uc, user_cons_columns ucc
			where uc.constraint_type='P' and uc.status='ENABLED'
			and lower(uc.TABLE_name) = lower(nomtable)
			and uc.constraint_name = ucc.constraint_name
			and uc.owner = ucc.owner
			and ucc.TABLE_name = uc.TABLE_name
			and lower(ucc.TABLE_name) <> lower('tlt_sessions')
			and 1 = (select count(ucc.column_name) from user_cons_columns ucc where uc.constraint_name = ucc.constraint_name and ucc.TABLE_name = uc.TABLE_name group by uc.TABLE_name)
		)
		loop
			 v_trigger:= 'CREATE sequence seq_'||pk.TABLE_name || ' nocache ';
			 adm_patch(v_trigger, 955);
			 v_trigger:= 'CREATE or replace trigger tri_'||pk.TABLE_name||' before INSERT on '||pk.TABLE_name||' for each row
				 declare v_id number;
				begin
				 select seq_'||pk.TABLE_name||'.nextval into v_id from dual;
				 :new.'||pk.column_name||':= v_id;
				end;';
			execute immediate v_trigger;
		end loop;

	end if;
end;
/


-- function now pour sysdate

create or replace function now
return date
is 
begin
return sysdate;
end;
/

--fonction substring pour substr
create or replace function substring(str varchar2,offset number, longueur number)
return varchar2 
is begin
   return substr(str,offset,longueur);
end;
/

-- function md5 pour les mots de passe
CREATE or replace function md5(input_string varchar2) return varchar2
is
raw_input raw(128) := utl_raw.cast_to_raw(input_string);
decrypted_raw raw(2048);
error_in_input_buffer_length exception;
begin
sys.dbms_obfuscation_toolkit.md5(input => raw_input,checksum => decrypted_raw);
return lower(rawtohex(decrypted_raw));
end;
/

call adm_patch('DROP TABLE tlt_n_commentaires',942)
/

call adm_patch('DROP TABLE tlt_n_commentaires',942)
/
 
call adm_patch('DROP TABLE  tlt_n_config',942)
/
 
call adm_patch('DROP TABLE tlt_n_news',942)
/

call adm_patch('DROP TABLE tlt_chemins cascade constraints',942)
/

 
call adm_patch('DROP TABLE tlt_typeetattemp cascade constraints',942)
/


call adm_patch('DROP TABLE tlt_comp cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_entitecachee cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_entitecacheeconnuede cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_etattemp cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_etattempnom cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_inscriptions cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_lieu cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_mj cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_magie cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_objets cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_perso cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_persoetattemp cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_persomagie cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_persoobjets cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_persospec cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_sessions cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_spec cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_specnom cascade constraints',942)
/

call adm_patch('DROP TABLE tlt_zone cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_archive cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_groupe cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_qcm cascade constraints',942)
/
 
call adm_patch('DROP TABLE tlt_engagement cascade constraints',942)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_chemins',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_comp',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_etattemp',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_etattempnom',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_inscriptions',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_lieu',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_mj',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_magie',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_objets',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_perso',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_persoetattemp',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_persomagie',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_persoobjets',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_persospec',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_spec',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_specnom',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_zone',2289)
/

call adm_patch('DROP SEQUENCE seq_tlt_archive',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_groupe',2289)
/

call adm_patch('DROP SEQUENCE seq_tlt_entitecacheeconnuede',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_entitecachee',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_sessions',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_n_commentaires',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_qcm',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_n_config',2289)
/

call adm_patch('DROP SEQUENCE seq_tlt_n_news',2289)
/
 
call adm_patch('DROP SEQUENCE seq_tlt_typeetattemp',2289)
/

--
--  TABLE structure for TABLE 'tlt_lieu'
--

CREATE SEQUENCE seq_tlt_lieu nocache
/


CREATE TABLE tlt_lieu (
  id_lieu number not null,
  nom varchar2(50) not null ,
  flags varchar2(50) not null,
  trigramme varchar2(3) not null ,
  accessible number(1)  default 1 not null ,
  id_forum number  default 0 not null,
  provoqueetat varchar2(100) null,
  constraint pk_tlt_lieu primary key (id_lieu)
)
/
 
--
--  dumping data for TABLE 'tlt_lieu'
--

INSERT into tlt_lieu (id_lieu,nom, flags, trigramme, accessible, id_forum)
values(seq_tlt_lieu.nextval, 'lieu de depart', '00000000000000000000000000000', 'spe', 0, 0)
/
 
--
--  TABLE structure for TABLE 'tlt_chemins'
--

CREATE SEQUENCE seq_tlt_chemins nocache
/
 
CREATE TABLE tlt_chemins (
  id_clef number not null,
  id_lieu_1 number not null,
  id_lieu_2 number not null,
  type number(3) default 0 not null ,
  difficulte number(3) default 0 null ,
  pass varchar2(50) null,
  distance number(3) default 0 null,
  constraint pk_tlt_chemins primary key (id_clef)
)
/
 
--
--  dumping data for TABLE 'tlt_chemins'
--



--
--  TABLE structure for TABLE 'tlt_inscriptions'
--

CREATE SEQUENCE seq_tlt_inscriptions nocache
/

CREATE TABLE tlt_inscriptions (
  id number  not null ,
  nom varchar2(50) not null ,
  pass varchar2(50) not null ,
  email varchar2(80) not null ,
  race varchar2(50) not null ,
  sexe number(1) default 0 not null,
  description  varchar2(2000)  not null,
  constraint pk_tlt_inscriptions primary key (id)
)
/

--
--  dumping data for TABLE 'tlt_inscriptions'
--



--
--  TABLE structure for TABLE 'tlt_mj'
--
CREATE SEQUENCE seq_tlt_mj nocache
/

CREATE TABLE tlt_mj (
  id_mj number  not null ,
  nom varchar2(250) not null ,
  pass varchar2(50) not null ,
  titre varchar2(25) not null ,
  flags varchar2(80) null,
  email varchar2(80) not null ,
  lastaction number  default 0 not null,
  fanonlu number(1)  default 0,
  wantmail number(1) default 0 not null,
  constraint pk_tlt_mj primary key (id_mj)
)
/
 
--
--  dumping data for TABLE 'tlt_mj'
--

INSERT INTO tlt_mj (id_mj,nom, pass, titre, flags, email, lastaction, fanonlu, wantmail)
 values(seq_tlt_mj.nextval,'admin', 'votremotdepasse', 'MJ supreme', '111111111111111111111111111111111111111111111111', 'votreemail', 1051302306,1,0)
/

--
--  TABLE structure for TABLE 'tlt_magie'
--

CREATE SEQUENCE seq_tlt_magie nocache
/
 
CREATE TABLE tlt_magie (
  id_magie number not null,
  type varchar2(50) not null ,
  sous_type varchar2(50) not null ,
  nom varchar2(64) not null ,
  degats_min number default 0 not null,
  degats_max number default 0 not null,
  anonyme number(1) default 0 not null,
  prix_base number default 0 not null,
  description varchar2(255) not null,
  image varchar2(50) null ,
  permanent number(1) default 0 not null,
  place number(1) default 0 not null,
  charges number(1) default 0 not null,
  caracteristique varchar2(50) not null ,
  competence varchar2(50) not null ,
  provoqueetat varchar2(100) null,
  constraint pk_tlt_magie primary key (id_magie)
)
/


--
--  dumping data for TABLE 'tlt_magie'
--

INSERT into tlt_magie (id_magie,type, sous_type, nom, degats_min, degats_max, anonyme, prix_base, description, permanent, place, charges, caracteristique, competence)
values(seq_tlt_magie.nextval,'Air', 'Soin', 'sort depart - a editer', 1, 2, 0, 32, 'sort de soins minimes',  1, 1, -1, 'Intelligence', 'Air')
/
 
--
--  TABLE structure for TABLE 'tlt_objets'
--
CREATE SEQUENCE seq_tlt_objets nocache
/

CREATE TABLE tlt_objets (
  id_objet number not null,
  type varchar2(50) not null ,
  sous_type varchar2(50) not null ,
  nom varchar2(64) not null ,
  degats_min number  null,
  degats_max number default 0 null,
  anonyme number(1)  default 0 not null,
  durabilite number default -1 not null,
  prix_base number default 0 not null,
  description varchar2(255) not null ,
  poids number default 0 not null,
  image varchar2(50) null ,
  permanent number(1) default 0 not null,
  munitions number default -1 not null,
  caracteristique varchar2(50) null,
  competence varchar2(50) null,
  provoqueetat varchar2(100) null,
  competencespe varchar2(50) null,
  constraint pk_tlt_objets primary key (id_objet)
)
/
 
--
--  dumping data for TABLE 'tlt_objets'
--

INSERT into tlt_objets (id_objet, type, sous_type, nom, degats_min, degats_max, anonyme, durabilite, prix_base, description, poids, permanent, munitions, caracteristique, competence)
values(seq_tlt_objets.nextval,'ArmeMelee', 'Arts Martiaux', 'objet de depart - a editer', 1, 2, 0, -1, 0, 'un coup de poing', 0,  1, -1, 'Force', 'Arts Martiaux')
/

 
--
--  TABLE structure for TABLE 'tlt_perso'
--
CREATE SEQUENCE seq_tlt_perso nocache
/

CREATE TABLE tlt_perso (
  id_perso number not null,
  nom varchar2(50) not null ,
  pass varchar2(50) not null ,
  race varchar2(50) not null ,
  sexe number(1) default 0 not null ,
  pa number default 0 not null,
  pv number default 0 not null,
  po number default 0 not null,
  banque number default 0 not null,
  id_lieu number not null,
  email varchar2(80) default null,
  interval_remise number default 72 not null,
  derniere_remise number default 0 not null,
  lastaction number default 0 not null ,
  fanonlu number(1) default 0 not null ,
  wantmail number(1) default 0 not null,
  constraint pk_tlt_perso primary key (id_perso)
)
/
 

--
--  dumping data for TABLE 'tlt_perso'
--

--
--  TABLE structure for TABLE 'tlt_etattempnom'
--
CREATE SEQUENCE seq_tlt_etattempnom nocache
/

CREATE TABLE tlt_etattempnom (
  id_etattemp number  not null,
  nom varchar2(50) not null ,
  rpa number default 0 not null,
  rpv number default 0 not null,
  rpo number default 0 not null,
  visible number(1) default 0 not null,
  constraint pk_tlt_etattempnom primary key (id_etattemp)
)
/
 
--
--  dumping data for TABLE 'tlt_etattempnom'
--


INSERT into tlt_etattempnom (id_etattemp, nom, rpa, rpv, rpo, visible) values(seq_tlt_etattempnom.nextval,'etat normal', 0, 0, 0, 0)
/
 
--
--  TABLE structure for TABLE 'tlt_comp'
--

CREATE SEQUENCE seq_tlt_comp nocache
/

CREATE TABLE tlt_comp (
  id number not null,
  id_perso number not null,
  id_comp number not null,
  xp number default 0 not null,
  constraint pk_tlt_comp primary key (id)
)
/
 
ALTER TABLE tlt_comp ADD constraint tlt_comp_FK1 
  FOREIGN KEY (id_perso) 
    REFERENCES tlt_perso (id_perso)
    ON DELETE CASCADE
/

--
--  dumping data for TABLE 'tlt_comp'
--


--
--  TABLE structure for TABLE 'tlt_etattemp'
--
CREATE SEQUENCE seq_tlt_etattemp nocache
/
 
CREATE TABLE tlt_etattemp (
  id_clef number not null,
  id_etattemp number not null,
  id_comp number not null,
  bonus number(4) default 0 not null,
  constraint pk_tlt_etattemp primary key (id_clef)
)
/
 
--
--  dumping data for TABLE 'tlt_etattemp'
--

--
--  TABLE structure for TABLE 'tlt_persoetattemp'
--
CREATE SEQUENCE seq_tlt_persoetattemp nocache
/
 
CREATE TABLE tlt_persoetattemp (
  id_clef number not null,
  id_perso number not null,
  id_etattemp number not null,
  fin number default 0 not null,
  constraint pk_tlt_persoetattemp primary key (id_clef)
)
/ 

ALTER TABLE tlt_persoetattemp ADD constraint tlt_persoetattemp_FK1 
  FOREIGN KEY (id_perso) 
    REFERENCES tlt_perso (id_perso)
    ON DELETE CASCADE
/
 

--
--  dumping data for TABLE 'tlt_persoetattemp'
--

--
--  TABLE structure for TABLE 'tlt_persomagie'
--
CREATE SEQUENCE seq_tlt_persomagie nocache
/
 
CREATE TABLE tlt_persomagie (
  id_clef number not null,
  id_perso number not null,
  id_magie number not null,
  charges number(1) default 0 not null,
  constraint pk_tlt_persomagie primary key (id_clef)
)
/

ALTER TABLE tlt_persomagie ADD constraint tlt_persomagie_FK1 
  FOREIGN KEY (id_perso) 
    REFERENCES tlt_perso (id_perso)
    ON DELETE CASCADE
/
    
--
--  dumping data for TABLE 'tlt_persomagie'
--

--
--  TABLE structure for TABLE 'tlt_persoobjets'
--
CREATE SEQUENCE seq_tlt_persoobjets nocache
/
 
CREATE TABLE tlt_persoobjets (
  id_clef number not null,
  id_perso number not null,
  id_objet number not null,
  durabilite number default -1 not null,
  munitions number default -1 not null ,
  temporaire number(1) default 0 not null,
  equipe number(1) default 0 not null,
  constraint pk_tlt_persoobjets primary key (id_clef)
)
/
 
ALTER TABLE tlt_persoobjets ADD constraint tlt_persoobjets_FK1 
  FOREIGN KEY (id_perso) 
    REFERENCES tlt_perso (id_perso)
    ON DELETE CASCADE
/

--
--  dumping data for TABLE 'tlt_persoobjets'
--



--
--  TABLE structure for TABLE 'tlt_persospec'
--
CREATE SEQUENCE seq_tlt_persospec nocache
/
 
CREATE TABLE tlt_persospec (
  id_clef number not null,
  id_perso number not null,
  id_spec number default 0 not null,
  constraint pk_tlt_persospec primary key (id_clef)
)
/
 
ALTER TABLE tlt_persospec ADD constraint tlt_persospec_FK1 
  FOREIGN KEY (id_perso) 
    REFERENCES tlt_perso (id_perso)
    ON DELETE CASCADE
/
 
--
--  dumping data for TABLE 'tlt_persospec'
--

--
--  TABLE structure for TABLE 'tlt_sessions'
--
CREATE SEQUENCE seq_tlt_sessions nocache
/
 
CREATE TABLE tlt_sessions (
  idsession varchar2(100) not null,
  ip varchar2(25) not null ,
  datestart number default 0 not null,
  duree number default 3600 not null,
  permanent number(1) default 0 not null,
  id_joueur number default 0 not null ,
  lastaction number default 0 not null,
  pj number(1)  default 1 not null,
  constraint pk_tlt_sessions  primary key (idsession)  
)
/
 
--
--  dumping data for TABLE 'tlt_sessions'
--

--
--  TABLE structure for TABLE 'tlt_specnom'
--
CREATE SEQUENCE seq_tlt_specnom nocache
/
 

CREATE TABLE tlt_specnom (
  id_spec number  not null,
  nom varchar2(50) not null ,
  rpo number default 0 not null,
  rpa number default 0 not null,
  rpv number default 0 not null,
  visible number(1) default 0 not null,
  constraint pk_tlt_specnom  primary key (id_spec)
)
/

--
--  dumping data for TABLE 'tlt_specnom'
--

INSERT into tlt_specnom (id_spec, nom, rpo, rpa, rpv, visible)
values(seq_tlt_specnom.nextval,'pas de spécialisation', 0, 0, 0, 0)
/
 
--
--  TABLE structure for TABLE 'tlt_spec'
--
CREATE SEQUENCE seq_tlt_spec nocache
/
 
CREATE TABLE tlt_spec (
  id_clef number not null,
  id_spec number default 0 not null ,
  id_comp number default 0 not null ,
  bonus number(1) default 0 not null,
  constraint pk_tlt_spec  primary key (id_clef)
)
/

--
--  TABLE structure for TABLE 'tlt_zone'
--
CREATE SEQUENCE seq_tlt_zone nocache
/

CREATE TABLE tlt_zone (
  id_zone number  not null,
  id_lieu number default 0 not null,
  type number(1) default 0 not null,
  pointeur number default 0 not null,
  constraint pk_tlt_zone  primary key (id_zone)
)
/
 
--
--  dumping data for TABLE 'tlt_zone'
--




--  debut modif hixcks
alter TABLE tlt_perso add pnj number(1) default 0 not null
/


alter TABLE tlt_perso add relation number(1) default 2 not null
/
 
alter TABLE tlt_perso add reaction number(1) default 4 not null
/

alter TABLE tlt_perso add armepreferee number(1) null
/
 
alter TABLE tlt_perso add sortprefere number(1)  null
/

alter TABLE tlt_perso add phrasepreferee varchar2(2000)  null
/
 
alter TABLE tlt_perso add actionsurprise number(1) default 4 not null
/

CREATE SEQUENCE seq_tlt_entitecachee nocache
/
 
CREATE TABLE tlt_entitecachee (
  id number  not null,
  id_entite number  not null,
  id_lieu  number not null,
  type number(1) default 0 not null,
  constraint pk_tlt_entitecachee  primary key (id)
)
/

CREATE index tlt_entitecachee_1
   on tlt_entitecachee (id_lieu)
/
 
CREATE SEQUENCE seq_tlt_entitecacheeconnuede nocache
/

CREATE TABLE tlt_entitecacheeconnuede (
  id number  not null,
  id_entitecachee number  not null,
  id_perso  number not null,
  constraint pk_tlt_entitecacheeconnuede  primary key (id)
)
/
 
CREATE unique index tlt_entitecacheeconnuede_uk1
   on tlt_entitecacheeconnuede (id_entitecachee, id_perso)
/

CREATE unique index tlt_perso_uk1
   on tlt_perso (nom)
/
 
CREATE unique index tlt_inscriptions_uk1
   on tlt_inscriptions (nom)
/

CREATE unique index tlt_mj_uk1
   on tlt_mj (nom)
/
 
alter TABLE tlt_perso add archive number(1) default 0 not null
/

 
CREATE SEQUENCE seq_tlt_archive nocache
/

CREATE TABLE tlt_archive (
  id_archive number  not null,
  id_perso  number not null,
  datearchivage date not null,
  datedesarchivage date null,
  constraint pk_tlt_archive  primary key (id_archive)
)
/
 
ALTER TABLE tlt_archive ADD constraint tlt_archive_FK1 
FOREIGN KEY (id_perso) 
REFERENCES tlt_perso (id_perso)
ON DELETE CASCADE
/

--  ajout des groupes de pj
CREATE SEQUENCE seq_tlt_groupe nocache
/
 
CREATE TABLE tlt_groupe (
id_groupe number  not null,
nom varchar2(50) not null,
constraint pk_tlt_groupe  primary key (id_groupe)
)
/

CREATE unique index tlt_groupe_uk1
on tlt_groupe (nom)
/
 
alter TABLE tlt_perso add id_groupe number  null
/

CREATE index tlt_perso_2
on tlt_perso (id_groupe)
/
 
--  fin ajout des groupes de pj


CREATE index tlt_sessions_1
on tlt_sessions (id_joueur)
/

CREATE index tlt_sessions_2
on tlt_sessions (pj)
/

CREATE index tlt_sessions_3
on tlt_sessions (permanent)
/
 
CREATE index tlt_chemins_1
on tlt_chemins (id_lieu_1)
/

CREATE index tlt_chemins_2
on tlt_chemins (id_lieu_2)
/
 
CREATE index tlt_chemins_3
on tlt_chemins (type)
/
 
CREATE index tlt_comp_1
on tlt_comp (id_perso)
/

CREATE index tlt_persospec_1
on tlt_persospec (id_perso)
/

CREATE index tlt_persoetattemp_1
on tlt_persoetattemp (id_perso)
/

CREATE index tlt_persoobjets_1
on tlt_persoobjets (id_perso)
/

CREATE index tlt_persomagie_1
on tlt_persomagie (id_perso)
/

--  modif pour ajouter des points d'intellect
alter TABLE tlt_etattempnom add rpi number default 0 not null
/

alter TABLE tlt_specnom  add rpi number default 0 not null
/
 
alter TABLE tlt_perso add pi number default 0 not null
/

alter TABLE tlt_perso rename column interval_remise  to interval_remisepa
/

alter TABLE tlt_perso rename column derniere_remise  to derniere_remisepa
/
 
alter TABLE tlt_perso add interval_remisepi number  default 90 not null
/
 
alter TABLE tlt_perso add derniere_remisepi number default 0  not null
/

--  suppression de armepreferee qui ne sert plus
alter TABLE tlt_perso    DROP column armepreferee
/
 
--  fin modifs pour ajouter des points d'intellect

--  mise a 25 pour etre de meme taille que phpbb_users
alter TABLE tlt_perso modify nom  varchar2(25)
/
 
--  mise a 25 pour etre de meme taille que phpbb_users
alter TABLE tlt_mj modify nom varchar2(25)
/
 
--  mise a 25 pour etre de meme taille que phpbb_users
alter TABLE tlt_inscriptions modify nom varchar2(25)
/

CREATE unique index tlt_objets_uk1 on tlt_objets (nom)
/

CREATE unique index tlt_specnom_uk1 on tlt_specnom (nom)
/

CREATE unique index tlt_magie_uk1 on tlt_magie (nom)
/

CREATE unique index tlt_lieu_uk1 on tlt_lieu (nom, trigramme)
/

CREATE unique index tlt_etattempnom_uk1 on tlt_etattempnom (nom)
/

alter TABLE tlt_perso add ip_joueur varchar2(9) null
/

--  passage en null => null devient connu de tous les persos (utilisé pour placer un objet a tel endroit)
alter TABLE tlt_entitecacheeconnuede modify id_perso null
/

--  passage du mot de passe des joueurs en crypte
update tlt_inscriptions set pass = md5(pass)
/
 
alter TABLE tlt_inscriptions modify pass varchar2(32)
/

update tlt_perso set pass = md5(pass)
/
 
alter TABLE tlt_perso modify pass varchar2(32)
/


--  fin de passage du mot de passe des joueurs en crypte

-- sql du bug permanent temporaire. normalement ne fait rien
-- mais on ne sait jamais
update tlt_objets set permanent = 1 where sous_type <> 'clef'
/
 
update tlt_persoobjets set  temporaire = 0
where id_objet = (select id_objet
from  tlt_objets
where permanent = 1)
/
 
-- fin bug permanent temporaire

--  modifs pour gestion des armes a 2 mains
	delete from tlt_persoobjets where id_objet=1
/
 
update  tlt_persoobjets set equipe=0
/
 

INSERT into tlt_persoobjets (id_perso,id_objet,durabilite,munitions,temporaire,equipe)
select tlt_perso.id_perso,1,-1,-1,0,1 from tlt_perso
/
 
--  fin modifs pour gestion des armes a 2 mains

alter TABLE tlt_perso add id_categorieage number
/
 
alter TABLE tlt_inscriptions add id_categorieage number
/

--  modif race, sexe

alter TABLE tlt_perso add id_sexe number
/
 
alter TABLE tlt_inscriptions add id_sexe number
/

alter TABLE tlt_perso add id_race number
/

alter TABLE tlt_inscriptions add id_race number
/

CREATE SEQUENCE seq_tlt_typeetattemp nocache
/

CREATE TABLE tlt_typeetattemp (
	  id_typeetattemp number not null,
	  nomtype varchar2(50) not null,
	  constraint pk_tlt_typeetattemp  primary key (id_typeetattemp)
	)
/
 
INSERT into tlt_typeetattemp (id_typeetattemp,nomtype) values (seq_tlt_typeetattemp.nextval,'Age')
/
 
INSERT into tlt_typeetattemp (id_typeetattemp,nomtype) values (seq_tlt_typeetattemp.nextval,'Race')
/
 

INSERT into tlt_typeetattemp (id_typeetattemp,nomtype) values (seq_tlt_typeetattemp.nextval,'Sexe')
/
 

INSERT into tlt_typeetattemp (id_typeetattemp,nomtype) values (seq_tlt_typeetattemp.nextval,'Lieu')
/
 

INSERT into tlt_typeetattemp (id_typeetattemp,nomtype) values (seq_tlt_typeetattemp.nextval,'Divers')
/
 
alter TABLE tlt_etattempnom add id_typeetattemp  number  default 5 not null
/
 
update tlt_etattempnom set  id_typeetattemp  = 5
/
 
INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,1, 'enfant')
/
 
INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,1, 'jeune adulte')
/
 
INSERT into tlt_persoetattemp (id_perso, id_etattemp, fin)
select  id_perso,seq_tlt_etattempnom.currval ,-1
from tlt_perso
where id_categorieage is null
/
 
update tlt_perso set id_categorieage= seq_tlt_etattempnom.currval where id_categorieage is null
/

update tlt_inscriptions set id_categorieage= seq_tlt_etattempnom.currval where id_categorieage is null
/
 
alter TABLE tlt_perso modify id_categorieage  not null
/

alter TABLE tlt_inscriptions modify id_categorieage  not null
/
 
INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval, 1, 'adulte expérimenté')
/

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,1, 'viellard')
/
 

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,2, 'humain')
/
 


INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,2, 'elfe')
/
 

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,2, 'nain')
/
 

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,2, 'troll')
/
 

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,2, 'gobelin')
/

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,2, 'orc')
/
 
INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,2, 'demi-elfe')
/
 

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,3, 'male')
/
 

INSERT into tlt_etattempnom ( id_etattemp,id_typeetattemp, nom ) values (seq_tlt_etattempnom.nextval,3, 'femelle')
/
 
CREATE index tlt_etattempnom_0
on tlt_etattempnom (id_typeetattemp)
/

 
--  migration des races existantes
INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
select  distinct tlt_typeetattemp.id_typeetattemp ,race, 1
from (tlt_perso left join tlt_etattempnom on tlt_perso.race = tlt_etattempnom.nom) , tlt_typeetattemp
where nomtype='Race'
and tlt_perso.race is not null
and tlt_perso.race <>''
and tlt_etattempnom.nom is null
/
 

INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
select  distinct tlt_typeetattemp.id_typeetattemp ,race, 1
from (tlt_inscriptions  left join tlt_etattempnom on tlt_inscriptions.race = tlt_etattempnom.nom) , tlt_typeetattemp
where nomtype='Race'
and tlt_inscriptions.race is not null
and tlt_inscriptions.race <>''
and tlt_etattempnom.nom is null
/
 
--  migration des races des pjs
INSERT into tlt_persoetattemp (id_perso, id_etattemp, fin)
select  id_perso,id_etattemp ,-1
from tlt_perso,tlt_etattempnom  , tlt_typeetattemp
where nomtype='Race'
and tlt_perso.race = tlt_etattempnom.nom
and tlt_perso.race is not null
and tlt_perso.race <>''
and tlt_etattempnom.id_typeetattemp = tlt_typeetattemp.id_typeetattemp
/
 
update tlt_perso set id_race =  (
select tlt_etattempnom.id_etattemp from tlt_etattempnom, tlt_typeetattemp
where tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Race' and tlt_etattempnom.nom=tlt_perso.race)
/
 
--  migration des sexes existants
update tlt_perso set sexe =-1 where sexe=0
/
 
update tlt_inscriptions set sexe =-1 where sexe=0
/
 

INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
select  distinct tlt_typeetattemp.id_typeetattemp ,sexe, 1
from (tlt_perso left join tlt_etattempnom on tlt_perso.sexe = tlt_etattempnom.nom) , tlt_typeetattemp
where nomtype='Sexe'
and tlt_perso.sexe is not null
and tlt_etattempnom.nom is null
/
 
INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
select  distinct tlt_typeetattemp.id_typeetattemp ,sexe, 1
from (tlt_inscriptions left join tlt_etattempnom on tlt_inscriptions.sexe = tlt_etattempnom.nom) , tlt_typeetattemp
where nomtype='Sexe'
and tlt_inscriptions.sexe is not null
and tlt_etattempnom.nom is null
/
 

update tlt_etattempnom set nom = '0' where nom='-1'
/
 
update tlt_perso set sexe = '0' where sexe='-1'
/

update tlt_inscriptions set sexe = '0' where sexe='-1'
/

--  migration des sexes des pjs
INSERT into tlt_persoetattemp (id_perso, id_etattemp, fin)
select  id_perso,id_etattemp ,-1
from tlt_perso,tlt_etattempnom  , tlt_typeetattemp
where nomtype='Sexe'
and tlt_perso.sexe = tlt_etattempnom.nom
and tlt_etattempnom.id_typeetattemp = tlt_typeetattemp.id_typeetattemp
/

update tlt_perso  set id_sexe =
(
select tlt_etattempnom.id_etattemp from tlt_etattempnom, tlt_typeetattemp
where tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Sexe' and tlt_etattempnom.nom=tlt_perso.sexe)
/
 
-- migration des inscriptions a faire


update tlt_inscriptions  set id_race =
(
select tlt_etattempnom.id_etattemp from tlt_etattempnom, tlt_typeetattemp
where tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Race' and tlt_etattempnom.nom=tlt_inscriptions.race)
/

update tlt_inscriptions  set id_sexe =
(
select tlt_etattempnom.id_etattemp from tlt_etattempnom, tlt_typeetattemp
where tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Sexe' and tlt_etattempnom.nom=tlt_inscriptions.sexe)
/
 

alter TABLE tlt_perso DROP column race
/
 

alter TABLE tlt_perso DROP column sexe
/
 

alter TABLE tlt_inscriptions    DROP column sexe
/
 

alter TABLE tlt_inscriptions    DROP column race
/
 
INSERT into  tlt_persoetattemp (id_perso ,id_etattemp , fin)
select  tlt_perso.id_perso, id_sexe, '-1'
from tlt_perso left join tlt_persoetattemp on
 tlt_perso.id_sexe =  tlt_persoetattemp.id_etattemp and  tlt_perso.id_perso = tlt_persoetattemp.id_perso
where id_etattemp is null
/
 
INSERT into  tlt_persoetattemp (id_perso ,id_etattemp , fin)
select  tlt_perso.id_perso, id_race, '-1'
from tlt_perso left join tlt_persoetattemp on
 tlt_perso.id_race =  tlt_persoetattemp.id_etattemp and  tlt_perso.id_perso = tlt_persoetattemp.id_perso
where id_etattemp is null
/

INSERT into  tlt_persoetattemp (id_perso ,id_etattemp , fin)
select  tlt_perso.id_perso, id_categorieage, '-1'
from tlt_perso left join tlt_persoetattemp on
 tlt_perso.id_categorieage=  tlt_persoetattemp.id_etattemp and  tlt_perso.id_perso = tlt_persoetattemp.id_perso
where id_etattemp is null
/
 
-- fin modif race, sexe . il reste a admin a renommer les etats temporaires du sexe avec l'interface
--  dans la version de base, 0 doit etre male, 1 doit etre femelle mais certains admin ont pu le modifier ou en ajouter

-- ajout de se cacher
alter TABLE tlt_lieu add difficultedesecacher number(2) default 0 not null
/
 
alter TABLE tlt_perso add dissimule number(1) default 0 not null
/
 
--  mise a jour des droits pour se cacher
update tlt_lieu set flags =
 rpad(substr(flags,1,7)||'1',length(flags),'0') where trigramme <>'spe'
/

-- TABLEs de news

CREATE SEQUENCE seq_tlt_n_commentaires nocache
/

CREATE TABLE  tlt_n_commentaires (
  id number not null,
  news number not null,
  news_date number not null,
  auteur varchar2(25) null,
  texte  varchar2(2000)  not null,
  constraint pk_tlt_n_commentaires primary key (id)
)
/

CREATE SEQUENCE seq_tlt_n_config nocache
/

CREATE TABLE  tlt_n_config (
  id number not null,
  title varchar2(25) default 'bienvenue sur talesta-new' not null,
  nbre_news number(1) default '5' not null,
  nom_archive varchar2(25) default 'voir les archives'  not null,
  nom_proposer varchar2(25) default 'proposer une news' not null,
  nom_commentaires varchar2(25) default 'commentaires(-- )' not null,
  nom_index varchar2(25) default 'index' not null,
  constraint pk_tlt_n_config primary key (id)
)
/
 

CREATE SEQUENCE seq_tlt_n_news nocache
/
 

CREATE TABLE  tlt_n_news (
  id number not null,
  news_date number not null ,
  titre varchar2(25) not null ,
  auteur varchar2(25) not null ,
  texte  varchar2(2000)  not null,
  constraint pk_tlt_n_news primary key (id)
)
/
 
--
-- contenu de la TABLE tlt_n_news
--

INSERT into tlt_n_news (id, news_date, titre, auteur, texte) values (seq_tlt_n_news.nextval,1109590009, 'version de février 2005', 'hixcks', 'première version de la communauté:<ul type="square"><li> corrections de plusieurs bugs de la version novembre2004 trouvés sur le forum. </li><li> une doc d''install grace à kaeru. </li><li> les menus admin/joueur sont la fusion de ceux du duo chub/tidou et de luka. </li><li> le systeme de news (adapté de chub) </li><li> plusieurs évols/modifs fournies sur le forum:</li><ul type="square"> <li>soin via objet (uriel) </li><li> listechemin (lapin) </li><li> différents patches de kaeru (les connectés , l''intégration du forum au style talesta, plusieurs niveaux de maintenance).</li> <li> qcm optionnel avant inscription (chub, saikoh).</li></ul><ul type="square"> plusieurs évols/modifs proposées sur le forum.         <li> suppression des balises html dans les saisies (parler ...) </li>		 <li> objets maudits (ne pouvant etre enleves une fois équipés) </li>		 <li> se cacher</li><li> reveler des objets, persos, chemins caches</li>		 <li> abandonner objet</li>		 <li> integration de l''image de l''avatar du forum dans le jeu</li>		 <li> voler des po à un cadavre => réussite auto</li>		 <li> mail au format html</li>		 <li> mots de passe des pj cryptes en base</li></ul><li>autres.</li><ul type="square"> <li> gestion des états temporaires pour la race, l''age et le sexe (cf. doc d''exemple fournie)</li><li> limitations d''utilisation des objets et sorts aux pjs ayant un état temporaire, ce qui permet de créer des sorts et armes spécifiques aux elfes, ou aux mages...</li><li> possibilité de fermer les inscriptions.</li></ul><li> j''en oublie surement.... pour plus d''infos <a href=''http://vknab.free.fr/phpbb2/''>forum talesta4</a> </li></ul>')
/
 
--
-- contenu de la TABLE tlt_n_config
--

INSERT into tlt_n_config (id, title, nbre_news, nom_archive, nom_proposer, nom_commentaires, nom_index) values (seq_tlt_n_config.nextval, 'bienvenue sur talesta-new', 5, 'voir les archives', 'proposer une news', 'commentaires(-- )', 'index')
/

--
-- contenu de la TABLE tlt_n_config
--

INSERT into tlt_n_config (id, title, nbre_news, nom_archive, nom_proposer, nom_commentaires, nom_index) values (seq_tlt_n_config.nextval, 'bienvenue sur talesta-new', 5, 'voir les archives', 'proposer une news', 'commentaires(-- )', 'index')
/
 
--  fin des TABLEs de news

--  ajout de la colonne nom pour eviter de faire des jointures sans cesse pour l'affichage de ce que l'on a trouve

alter TABLE tlt_entitecachee add nom varchar2(64) null
/
 
update  tlt_entitecachee set
nom = (select 'chemin vers '|| tlt_lieu.nom
from tlt_chemins, tlt_lieu
where tlt_entitecachee.id_entite = tlt_chemins.id_clef and
tlt_lieu.id_lieu = tlt_chemins.id_lieu_1
and tlt_chemins.id_lieu_2 = tlt_entitecachee.id_lieu and
tlt_entitecachee.type = 0)
/
 
update  tlt_entitecachee set
nom = (select tlt_objets.nom from tlt_objets
where tlt_entitecachee.id_entite = tlt_objets.id_objet and
tlt_entitecachee.type = 1)
/
 
update  tlt_entitecachee set
nom = (select tlt_perso.nom
from tlt_perso
where tlt_entitecachee.id_entite = tlt_perso.id_perso and
tlt_entitecachee.type = 2)
/
 
--  fin ajout de la colonne nom pour eviter de faire des jointures sans cesse pour l'affichage de ce que l'on a trouve

--  ajout de la TABLE questionnaire

--
--  TABLE structure for TABLE 'tlt_qcm'
--
CREATE SEQUENCE seq_tlt_qcm nocache
/
 
CREATE TABLE tlt_qcm (
  id_question number not null,
  question varchar2(128) not null,
  reponse1 varchar2(128) not null,
  reponse2 varchar2(128) not null,
  reponse3 varchar2(128) not null,
  reponse4 varchar2(128) not null,
  bonne number(1) not null,
  constraint pk_tlt_qcm primary key (id_question)
)
/

--INSERT into tlt_qcm values (seq_tlt_qcm.nextval, 'n''en avez vous pas assez de la guéguerre kaeru/chub ?', 'oui', 'non', 'ah bon, ils ne s''aiment pas ?', 'on peut participer ?', 1)
--/
 
--INSERT into tlt_qcm values (seq_tlt_qcm.nextval, 'pourquoi la géguerre kaeru/chub est-elle dommage ?', 'parce qu''on peut pas y participer', 'parce qu''on voit pas les dégats faits', 'parce qu''il faut effacer leur messages', 'parce qu''ils sont parmi les plus actifs du forum', 4)
--/
 
--INSERT into tlt_qcm values (seq_tlt_qcm.nextval, 'comment régler cette guéguerre kaeru/chub ?', 'on les vire', 'on acheve le survivant', 'on les enferme ensemble jusqu''a ce qu''ils s''apprecient', 'on fait rien et on attend qu''ils se calment', 3)
--/
 
INSERT into tlt_qcm values (seq_tlt_qcm.nextval, 'puis avoir deux comptes de jeu ?', 'oui n''importe quand', 'oui si je demande', 'oui mais il faut pas se faire capter', 'non', 4)
/

INSERT into tlt_qcm values (seq_tlt_qcm.nextval, 'ai-je le droit d''insulter tout le monde ?', 'seulement les orcs', 'oui si je demande', 'oui mais il faut pas se faire capter', 'non', 4)
/

update tlt_mj set flags='11111111111111111111111111111111111111111111111111111111111111111111111111111111'
where id_mj=1
/
 
--  fin ajout de la TABLE questionnaire

alter TABLE tlt_perso add background varchar2(2000) null
/
 
alter TABLE tlt_inscriptions add background varchar2(2000) null
/
 
update tlt_inscriptions set background  = '.' where background is null
/

update tlt_perso set background  = '.' where background is null
/ 

alter TABLE tlt_perso modify background  not null
/

alter TABLE tlt_inscriptions modify background not null
/
 
alter TABLE tlt_objets add id_etattempspecifique number null
/

alter TABLE tlt_magie add id_etattempspecifique number null
/

alter TABLE tlt_magie
add constraint tlt_magie_FK1 foreign key (id_etattempspecifique)
references tlt_etattempnom (id_etattemp) 
on delete set null
/

CREATE index tlt_etattemp_FK1
   on tlt_etattemp (id_etattemp)
/

CREATE index tlt_archive_FK1
   on tlt_archive (id_perso)
/

CREATE index tlt_zone_FK1
   on tlt_zone (id_lieu)
/
 
CREATE unique index tlt_typeetattemp_UK1
   on tlt_typeetattemp (nomtype)
/

CREATE unique index tlt_etattemp_uk1
   on tlt_etattemp (id_etattemp,id_comp )
/ 
CREATE unique index tlt_comp_perso_uk1
   on tlt_comp (id_perso, id_comp)
/
	   

ALTER TABLE tlt_magie ADD typecible number(1) DEFAULT 1 NOT NULL
/

ALTER TABLE tlt_magie ADD sortdistant number(1) DEFAULT 0 NOT NULL
/


alter table tlt_perso add wantmusic number(1) default 0 not null
/
 
alter table tlt_lieu add cheminfichieraudio varchar2(50) null
/
 
--modif suite erreur remontée par Uriel sortprefere etant id_magie de tlt_magie, il doit etre du meme type
ALTER TABLE tlt_perso modify sortprefere number
/

-- engagement de Tidou 
ALTER TABLE tlt_perso ADD engagement number(1) DEFAULT 0 NOT NULL
/

 CREATE TABLE tlt_engagement (
 id_perso number NOT NULL,
   id_adversaire number NOT NULL,
   nom	VARCHAR2( 25 ) NOT NULL,
   propdes number(1) DEFAULT 0 NOT NULL,
   constraint pk_tlt_engagement PRIMARY KEY  (id_perso, id_adversaire)
 )
/


 ALTER TABLE tlt_engagement ADD constraint tlt_engagement_FK1 
   FOREIGN KEY (id_perso) 
     REFERENCES tlt_perso (id_perso)
     ON DELETE CASCADE
/


 ALTER TABLE tlt_engagement ADD constraint tlt_engagement_FK2
   FOREIGN KEY (id_adversaire) 
     REFERENCES tlt_perso (id_perso)
     ON DELETE CASCADE
/
                  
                  
ALTER TABLE tlt_spec add constraint tlt_spec_fk1                  
   FOREIGN KEY (id_spec) 
     references tlt_specnom (id_spec)
     ON DELETE CASCADE
/

alter table tlt_persoetattemp
   add constraint tlt_persoetattemp_fk2 foreign key (id_etattemp)
      references tlt_etattempnom (id_etattemp)
/

alter table tlt_n_commentaires
   add constraint tlt_n_commentaires_fk1 foreign key (news)
      references tlt_n_news (id)
      on delete cascade
/

alter table tlt_chemins
   add constraint tlt_chemins_fk1 foreign key (id_lieu_1) 
   references tlt_lieu (id_lieu) on delete cascade
/

alter table tlt_chemins
   add constraint tlt_chemins_fk2 foreign key (id_lieu_2) 
   references tlt_lieu (id_lieu) on delete cascade
/


alter table tlt_perso
   add constraint tlt_perso_fk1 foreign key (id_lieu) 
   references tlt_lieu (id_lieu)
/


alter table tlt_perso
   add constraint tlt_perso_fk2 foreign key (id_groupe) 
   references tlt_groupe (id_groupe)
   on delete set null
/
 

alter TABLE tlt_persomagie 
  add constraint tlt_persomagie_fk2 foreign key (id_magie)
  references tlt_magie (id_magie) on delete cascade
/


alter TABLE tlt_persoobjets 
  add constraint tlt_persoobjets_fk2 foreign key (id_objet)
  references tlt_objets (id_objet) on delete cascade
/

alter TABLE tlt_zone 
  add constraint tlt_zone_fk1 foreign key (id_lieu)
  references tlt_lieu (id_lieu) on delete cascade
/

alter TABLE tlt_entitecachee 
  add constraint tlt_entitecachee_fk1 foreign key (id_lieu)
  references tlt_lieu (id_lieu) on delete cascade
/

alter TABLE tlt_etattempnom 
	add constraint tlt_etattempnom_fk1  foreign key (id_typeetattemp)
	references tlt_typeetattemp (id_typeetattemp)
/


alter TABLE tlt_entitecacheeconnuede 
    add constraint tlt_entitecacheeconnuede_fk1 foreign key (id_entitecachee)
    references tlt_entitecachee (id)
/

alter TABLE tlt_objets 
	add constraint tlt_objets_fk1 foreign key(id_etattempspecifique)
	references tlt_etattempnom ( id_etattemp)  on delete set null
/


alter table TLT_ETATTEMP
   add constraint TLT_ETATTEMP_FK1 foreign key (ID_ETATTEMP)
      references TLT_ETATTEMPNOM (ID_ETATTEMP)
      on delete cascade
/

--  mise a jour des droits pour parler
update tlt_lieu set flags =
 rpad(substr(flags,1,11)||'1',length(flags),'0')
/


-- ajout de combiner objet
ALTER TABLE tlt_objets ADD composantes VARCHAR2( 100 )
/


ALTER TABLE tlt_zone ADD (stockmax number(4) DEFAULT -1 NOT NULL ,
 quantite number(4) DEFAULT -1 NOT NULL ,
 remisestock number(4) DEFAULT -1 NOT NULL,
 derniereremise  number DEFAULT 0 NOT NULL
) 
/

ALTER TABLE tlt_mj ADD wantmusic number(1) default 0 not null
/

ALTER TABLE tlt_objets MODIFY poids number
/

alter TABLE tlt_perso add commentaires_mj varchar2(2000)  null
/

ALTER TABLE tlt_mj ADD dispo_pour_ppa number(1) default 1 not null
/

ALTER TABLE tlt_etattempnom ADD utilisableinscription number(1) default 1 not null
/

ALTER TABLE tlt_typeetattemp ADD critereinscription number(1) default 0  NOT NULL
/

ALTER TABLE tlt_typeetattemp ADD modifiableparpj number(1) default 0 NOT NULL
/

update tlt_typeetattemp set critereinscription = 2 where nomtype = 'Age' or nomtype = 'Race' or nomtype = 'Sexe'
/
 
update tlt_etattempnom set visible = 1 
where id_typeetattemp  in (select tlt_typeetattemp.id_typeetattemp  
	from tlt_typeetattemp
	where nomtype in ('Age','Race','Sexe')
	)
/

commit
/

call adm_trigger_insert(null)
/
 
INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ( 'Taille', '1','0')
/

INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ('Corpulence', '1','1')
/

INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ('Humeur', '1','1')
/

 
call adm_patch('DROP TABLE tlt_inscriptetattemp',942)
/

call adm_patch('DROP SEQUENCE seq_tlt_inscriptetattemp',2289)
/
 

CREATE SEQUENCE seq_tlt_inscriptetattemp nocache
/

CREATE TABLE tlt_inscriptetattemp (
  id_clef number NOT NULL,
  id_inscript number NOT NULL,
  id_etattemp number NOT NULL,
  constraint pk_tlt_inscriptetattemp PRIMARY KEY  (id_clef)
)
/

alter table tlt_inscriptetattemp
   add constraint tlt_inscriptetattemp_FK1 foreign key (id_inscript)
      references tlt_inscriptions (ID)
      on delete cascade
/


alter table tlt_inscriptetattemp
   add constraint tlt_inscriptetattemp_FK2 foreign key (ID_ETATTEMP)
      references TLT_ETATTEMPNOM (ID_ETATTEMP)
      on delete cascade
/


call adm_trigger_insert ('tlt_inscriptetattemp')
/

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_categorieage from tlt_inscriptions
/

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_sexe from tlt_inscriptions
/

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_race from tlt_inscriptions
/

ALTER TABLE tlt_perso DROP column id_categorieage
/

ALTER TABLE tlt_perso DROP column id_sexe
/

ALTER TABLE tlt_perso DROP column id_race
/
  
ALTER TABLE tlt_inscriptions DROP column id_categorieage
/

ALTER TABLE tlt_inscriptions DROP column id_sexe
/

ALTER TABLE tlt_inscriptions DROP column id_race
/

--fonction ifnull pour nvl
create or replace function ifnull(str1 varchar2,str2 varchar2)
return varchar2 
is begin
   return nvl(str1,str2);
end;
/


alter table tlt_magie add composantes VARCHAR2(100) NULL
/

-- mise a jour des droits pour se recevoir des sorts exterieurs
UPDATE tlt_lieu SET flags = 
rpad(substr(flags,1,12)||'1',length(flags),'0') where trigramme <>'spe'
/


ALTER TABLE tlt_perso ADD role_mj number
/

--mise a jour des droits pour admin. Pourquoi avait-il encore des 0....
update tlt_mj set flags =
rpad('1',length(flags),'1') where id_mj = 1
/

commit
/


-- debut bug sur les objets caches 
alter table tlt_persoobjets modify id_perso null
/


begin
for i in (
	select id,id_entite as id_entiteOLD,durabilite , munitions from tlt_entitecachee, tlt_objets
	where id_entite = id_objet and tlt_entitecachee.type=1
	order by id
)
LOOP
	insert into tlt_persoobjets (id_perso,id_objet, durabilite, munitions,  temporaire ,  equipe   )
	values(null,i.id_entiteOLD,i.durabilite , i.munitions,0,0);
	update tlt_entitecachee set id_entite = (select max(id_clef) from tlt_persoobjets)
	where id = i.id;
END LOOP;
commit;
end;
/


-- fin bug sur les objets caches 

alter TABLE tlt_lieu add id_etattempspecifique number null
/

alter TABLE tlt_lieu
add constraint tlt_lieu_FK1 foreign key (id_etattempspecifique)
references tlt_etattempnom (id_etattemp) 
on delete set null
/


-- quetes

call adm_patch('DROP TABLE tlt_quetes cascade constraints',942)
/
 

call adm_patch('DROP SEQUENCE seq_tlt_quetes',2289)
/
 

CREATE SEQUENCE seq_tlt_quetes nocache
/

CREATE TABLE tlt_quetes (
  id_quete NUMBER NOT NULL ,
  nom_quete  VARCHAR2(50) NOT NULL,
  type_quete NUMBER NOT NULL,
  detail_type_quete NUMBER NOT NULL,
  duree_quete NUMBER NOT NULL DEFAULT -1,
  public NUMBER(1) NOT NULL DEFAULT 0,
  cyclique NUMBER(1) NOT NULL DEFAULT 0,
  proposepar NUMBER NOT NULL,
  proposepartype NUMBER(1) NOT NULL default 1,	
  texteproposition varchar2(2000) not null,
  textereussite varchar2(2000) not null,
  texteechec varchar2(2000) not null,
  refuspossible NUMBER(1) NOT NULL DEFAULT 0,
  abandonpossible NUMBER(1) NOT NULL DEFAULT 0,
  validationquete NUMBER(1) NOT NULL DEFAULT 0, 
  id_lieu NUMBER NULL REFERENCES tlt_lieu (id_lieu) ON DELETE CASCADE,
  proposant_anonyme NUMBER(1) NOT NULL DEFAULT 0,  
  constraint pk_tlt_quetes PRIMARY KEY (id_quete)
)
/


call adm_trigger_insert ('tlt_quetes')
/


call adm_patch('DROP SEQUENCE seq_tlt_recompensequete',2289)
/
 
call adm_patch('DROP TABLE tlt_recompensequete cascade constraints',942)
/
 
CREATE SEQUENCE seq_tlt_recompensequete  nocache
/

CREATE TABLE tlt_recompensequete (
  id_recompensequete NUMBER NOT NULL,
  id_quete NUMBER NOT NULL REFERENCES tlt_quetes (id_quete) ON DELETE CASCADE,
  type_recompense NUMBER NOT NULL,
  recompense NUMBER NOT NULL,
  constraint pk_tlt_recompensequete PRIMARY KEY  (id_recompensequete)
);



call adm_trigger_insert ('tlt_recompensequete')
/ 


call adm_patch('DROP SEQUENCE seq_tlt_persoquete ',2289)
/

call adm_patch('DROP TABLE tlt_persoquete  cascade constraints',942)
/


CREATE SEQUENCE seq_tlt_persoquete   nocache
/

CREATE TABLE tlt_persoquete (
  id_persoquete	NUMBER NOT NULL,
  id_quete NUMBER NOT NULL REFERENCES tlt_quetes (id_quete) ON DELETE CASCADE,	
  id_perso NUMBER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,		
  etat NUMBER NOT NULL,	
  debut number(11) not null DEFAULT 0,
  fin number(11) not null DEFAULT -1,
  constraint pk_tlt_persoquete  PRIMARY KEY  (id_persoquete)
)
/


call adm_trigger_insert ('tlt_persoquete ')
/ 


create or replace function curdate
return date
is 
begin
return sysdate;
end;
/

CREATE OR REPLACE FUNCTION adddate(date1 date, nbJours number)
RETURN date 
IS 
begin
return date1 + nbJours;
end;
/

update tlt_objets set type='Nourriture' where type='Divers' and sous_type='Nourriture'
/

--bestiaires n'ont pas de lieu
alter table tlt_perso modify id_lieu null
/

--modif du site du forum
update tlt_n_news set texte= replace (texte, 'http://vknab.free.fr/phpbb2/','http://www.talesta.free.fr/puntal')
/

call adm_patch('DROP SEQUENCE seq_tlt_apparitionmonstre ',2289)
/

call adm_patch('DROP TABLE tlt_apparitionmonstre  cascade constraints',942)
/

CREATE SEQUENCE seq_tlt_apparitionmonstre nocache
/

CREATE TABLE tlt_apparitionmonstre (
  id_apparitionmonstre	NUMBER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_apparitionmonstre'),
  id_typelieu NUMBER NOT NULL,
  id_perso NUMBER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  nb_max_apparition NUMBER(2) NOT NULL DEFAULT 1,	
  nb_max_lieu NUMBER(2) NOT NULL DEFAULT -1,
  chance_apparition NUMBER(3) NOT NULL
)
/


alter table tlt_lieu add apparition_monstre number(1) not null default 0
/

alter table tlt_lieu add type_lieu_apparition number(2) not null default 1
/

call adm_patch('DROP TABLE tlt_ppa',942)
/

call adm_patch('DROP SEQUENCE seq_tlt_ppa',2289)
/
 

CREATE SEQUENCE seq_tlt_ppa nocache
/
 
create table tlt_ppa (
  id_ppa NUMBER NOT NULL,
  id_perso NUMBER NOT NULL  REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,	
  id_mj NUMBER NOT NULL  REFERENCES tlt_mj (id_mj) ON DELETE CASCADE,	    
  date_ppa number NOT NULL,
  detail_ppa varchar2(2000) NOT NULL,
  qte_pa number(2) not null default 0,
  qte_pi number(2) not null default 0,
  constraint pk_tlt_ppa PRIMARY KEY  (id_ppa)
)
/ 

call adm_trigger_insert ('tlt_ppa')
/

--alter table tlt_perso add img_avatar varchar2(100) null
--/


ALTER TABLE tlt_perso DROP COLUMN engagement
/

create index tlt_apparitionmonstre_id_perso on tlt_apparitionmonstre  (id_perso)
/

create index tlt_apparitionmonstre_type_lie on tlt_apparitionmonstre  (id_typelieu )
/

create index tlt_inscriptetattemp_id_inscr on tlt_inscriptetattemp  (id_inscript) 
/

create index tlt_lieu_type_lieu_apparition on tlt_lieu  (type_lieu_apparition)
/

create index tlt_magie_type on tlt_magie  (type)
/

create index tlt_magie_soustype on tlt_magie  (sous_type)
/

create index tlt_mj_dispoppa on tlt_mj  (dispo_pour_ppa)
/

create index tlt_n_commentaires_news on tlt_n_commentaires  (news)
/

create index tlt_objets_type on tlt_objets  (type)
/

create index tlt_objets_sstype on tlt_objets  (sous_type)
/

create index tlt_perso_pnj on tlt_perso  (pnj)
/

create index tlt_perso_idlieu on tlt_perso  (id_lieu)
/

create index tlt_persoquete_etat on tlt_persoquete  (etat)
/

create index tlt_persoquete_is_perso on tlt_persoquete  (id_perso)
/

create index tlt_ppa_id_mj on tlt_ppa  (id_mj)
/

create index tlt_recompensequete_id_quete  on tlt_recompensequete  (id_quete) 
/

create index tlt_recompensequete_typeRecomp on tlt_recompensequete  (type_recompense)
/

create index tlt_quetes_nom_quete on tlt_quetes  (nom_quete)
/

create index tlt_quetes_type_quete on tlt_quetes  (type_quete)
/

create index tlt_quetes_public on tlt_quetes  (public)
/

create index tlt_quetes_proposepar on tlt_quetes  (proposepar)
/

ALTER TABLE tlt_perso add column pourcentage_reaction number(3) DEFAULT 100 not null
/

ALTER table tlt_etattempnom add column id_lieudepart number null REFERENCES tlt_lieu (id_lieu) ON DELETE SET NULL
/

ALTER table tlt_etattempnom add column objetsfournis varchar2(50) null
/

ALTER table tlt_etattempnom add column sortsfournis varchar2(50) null
/

ALTER TABLE tlt_perso ADD column nb_deces number DEFAULT 0 NOT NULL
/

ALTER TABLE tlt_quetes ADD column id_etattempspecifique number NULL
/

ALTER TABLE tlt_magie ADD coutpa number NULL
/

ALTER TABLE tlt_magie ADD coutpi number NULL
/

ALTER TABLE tlt_magie ADD coutpo number DEFAULT 0 NOT NULL
/

ALTER TABLE tlt_magie ADD coutpv number DEFAULT 0 NOT NULL
/

ALTER TABLE tlt_perso ADD moment_mort number
/


update tlt_perso set moment_mort = 0 where pv <0 and pnj <> 2
/

commit
/



call adm_patch('DROP TABLE tlt_traceactions',942)
/

call adm_patch('DROP SEQUENCE seq_tlt_traceactions',2289)
/
 

CREATE SEQUENCE seq_tlt_traceactions nocache
/

CREATE TABLE tlt_traceactions(
id_trace number NOT NULL,
action VARCHAR2( 30 ) NOT NULL ,
id_acteur number NOT NULL  REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,	
id_lieu  number NOT NULL  REFERENCES tlt_lieu (id_lieu) ON DELETE CASCADE,	
detail varchar2( 100 ) NOT NULL ,
heure_action number NOT NULL,
constraint pk_tlt_traceactions PRIMARY KEY  (id_trace)
 )
/


call adm_trigger_insert ('tlt_traceactions')
/

ALTER TABLE tlt_lieu RENAME COLUMN accessible TO accessible_telp
/
