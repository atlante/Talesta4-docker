

# MySQL-Front Dump 2.5
#
# Host: localhost   Database: Talesta4
# --------------------------------------------------------
# Server version 3.23.47-nt

# config generale du forum
update phpbb_config set config_value='d/m/Y H:i:s' where config_name='default_dateformat';

update phpbb_config set config_value='2.00' where config_name='board_timezone';

update phpbb_config set config_value='0' where config_name='board_disable';

update phpbb_config set config_value='1' where config_name='allow_smilies';

update phpbb_config set config_value='0' where config_name='allow_namechange';

update phpbb_config set config_value='1' where config_name='allow_avatar_local';

update phpbb_config set config_value='1' where config_name='allow_avatar_remote';

update phpbb_config set config_value='1' where config_name='allow_avatar_upload';

update phpbb_config, tlt_mj set config_value = tlt_mj.email
where id_mj=1 and config_name='board_email';



# Creation de differents Groupes d utilisateurs

# group_type =1 pour les rendre visibles mais ferm (pas d'adhesion puisque les affectations sont automatiques a la creation du PJ)
INSERT INTO phpbb_groups (group_type, group_name, group_description, group_moderator, group_single_user) 
	values( 1, 'MJ', 'Groupe des MJs', 2, 0);
select @groupMJ:=last_insert_id();


INSERT INTO phpbb_groups (group_type, group_name, group_description, group_moderator, group_single_user) 
	values( 1, 'PJ_PNJ', 'Groupe des PJs/PNJs', 2, 0);
select @groupPJ:=last_insert_id();

# Creation de differentes Catgories et forums


SELECT @t1:=ifnull(max(cat_id)+1,1) from phpbb_categories;

SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;

INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) 
	values ( @t1,'Communications Hors Jeu', @t1*10);



INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'Accueil des nouveaux', 'FAQ, Fonctionnement, Rgles ....',  @t2*10,  0, 0, 0, 0, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);


SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;

INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'Demande des joueurs', 'Pour tout problme hors role play rencontr ', @t2*10,  1, 1, 1, 1, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);

SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;

INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'Annonces ', 'Pour toute annonce hors role play (besoin de coopration ..) ',  @t2*10,  1, 1, 1, 1, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);

SELECT @t1:=ifnull(max(cat_id)+1,1) from phpbb_categories;

INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) 
	values ( @t1,'Communications En Jeu', @t1*10);

SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;
INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'Annonces des MJ', 'Pour toute annonce officielle en jeu ',  @t2*10,  1, 1, 1, 1, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);
SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;


INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'Demande de joueurs', 'Pour toute demande en relation avec le jeu (sauf les problmes) ',   @t2*10,  1, 1, 1, 1, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);

SELECT @t1:=ifnull(max(cat_id)+1,1) from phpbb_categories;


INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) 
	values ( @t1,'Magasins', @t1*10);


SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;
INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'HVG -- Forge', 'Forum  propos de la Forge',   @t2*10,  1, 1, 1, 1, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);

SELECT @t1:=ifnull(max(cat_id)+1,1) from phpbb_categories;


INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) 
	values ( @t1,'Guildes et Groupements de PJ', @t1*10);


SELECT @t1:=ifnull(max(cat_id)+1,1) from phpbb_categories;


INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) 
	values ( @t1,'MJs', @t1*10);
	

SELECT @t1:=ifnull(max(cat_id)+1,1) from phpbb_categories;


INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) 
	values ( @t1,'Modifs techniques  apporter', @t1*10);
	
SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;
	
INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'Ides', 'Vous voulez mettre une suggestion pour amliorer les fonctionnalits du jeu.... C\'est ici.',   @t2*10,  1, 1, 1, 1, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);
SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;

INSERT INTO phpbb_forums ( forum_id,cat_id, forum_name, forum_desc,  forum_order,  auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate) 
	values(@t2,@t1, 'Bugs', 'encore une coquille des dveloppeurs. C\'est ici qu\'il faut se plaindre.',   @t2*10,  1, 1, 1, 1, 1, 1, 3, 3, 1, 1);

INSERT INTO phpbb_auth_access (group_id, forum_id, auth_mod) values (@groupMJ, @t2,1);
SELECT @t2:=ifnull(max(forum_id)+1,1) from phpbb_forums;



 ALTER TABLE phpbb_users ADD UNIQUE (username) ;
   
 ALTER TABLE phpbb_user_group ADD UNIQUE (user_id , group_id) ;
 
 ALTER TABLE phpbb_categories ADD UNIQUE (cat_title) ;
 
 ALTER TABLE phpbb_ranks ADD UNIQUE (rank_title);
 
 ALTER TABLE phpbb_forums ADD UNIQUE(forum_name, cat_id);
 
 
update phpbb_config set config_value='french' where config_name='default_lang';

update phpbb_config set config_value='1' where config_name='allow_avatar_remote';
 
# disable_registration_FR_v1.1.0.txt

INSERT INTO phpbb_config (config_name, config_value) VALUES ('registration_status', '1');

INSERT INTO phpbb_config (config_name, config_value) VALUES ('registration_closed', 'Les inscriptions aux forum sont automatiques lors de l''inscription au jeu');

