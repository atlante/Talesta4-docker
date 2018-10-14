

# MySQL-Front Dump 2.5
#
# Host: localhost   Database: Talesta4
# --------------------------------------------------------
# Server version 3.23.47-nt

# config generale du forum
update phorum_settings set data='0' where name='dns_lookup';

# ne pas envoyer un mail pour la validation des inscriptions au forum
update phorum_settings set data='0' where name='registration_control';

# Creation de differents Groupes d utilisateurs

# open =0 pour les rendre visibles mais ferm (pas d'adhesion puisque les affectations sont automatiques a la creation du PJ)
INSERT INTO phorum_groups (name, open) 
	values( 'MJ',  0);
select @groupMJ:=last_insert_id();


INSERT INTO phorum_groups (name, open) 
	values( 'PJ_PNJ', 0);
select @groupPJ:=last_insert_id();

# Creation de differentes Catgories et forums


SELECT @t1:=ifnull(max(forum_id)+1,1) from phorum_forums /*where folder_flag=0*/;
# contrairement a phpbb, les categories et les forums sont dans la meme table, il faut donc que t2 soit differente de t1 (d'ou le +2)
SELECT @t2:=ifnull(max(forum_id)+2,2) from phorum_forums /* where folder_flag=1*/;

INSERT INTO phorum_forums (forum_id, name, folder_flag) 
	values ( @t1,'Communications Hors Jeu', 0);



INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'Accueil des nouveaux', 'FAQ, Fonctionnement, Rgles ....',  @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);


SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;

INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'Demande des joueurs', 'Pour tout problme hors role play rencontr ', @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);

SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;

INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'Annonces ', 'Pour toute annonce hors role play (besoin de coopration ..) ',  @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);

SELECT @t1:=ifnull(max(forum_id)+1,1) from phorum_forums;

INSERT INTO phorum_forums (forum_id, name, display_order) 
	values ( @t1,'Communications En Jeu', @t1*10);

SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;
INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'Annonces des MJ', 'Pour toute annonce officielle en jeu ',  @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);
SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;


INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'Demande de joueurs', 'Pour toute demande en relation avec le jeu (sauf les problmes) ',   @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);

SELECT @t1:=ifnull(max(forum_id)+1,1) from phorum_forums;


INSERT INTO phorum_forums (forum_id, name, display_order) 
	values ( @t1,'Magasins', @t1*10);


SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;
INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'HVG -- Forge', 'Forum  propos de la Forge',   @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);

SELECT @t1:=ifnull(max(forum_id)+1,1) from phorum_forums;

/* active = 0 pour ne pas etre visible de tous*/
INSERT INTO phorum_forums (forum_id, name, display_order, active) 
	values ( @t1,'Guildes et Groupements de PJ', @t1*10,0);


SELECT @t1:=ifnull(max(forum_id)+1,1) from phorum_forums;


INSERT INTO phorum_forums (forum_id, name, display_order) 
	values ( @t1,'MJs', @t1*10);
	

SELECT @t1:=ifnull(max(forum_id)+1,1) from phorum_forums;


INSERT INTO phorum_forums (forum_id, name, display_order) 
	values ( @t1,'Modifs techniques  apporter', @t1*10);
	
SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;
	
INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'Ides', 'Vous voulez mettre une suggestion pour amliorer les fonctionnalits du jeu.... C\'est ici.',   @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);
SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;

INSERT INTO phorum_forums ( forum_id,parent_id, name, description,  display_order) 
	values(@t2,@t1, 'Bugs', 'encore une coquille des dveloppeurs. C\'est ici qu\'il faut se plaindre.',   @t2*10);

INSERT INTO phorum_forum_group_xref (group_id, forum_id, permission) values (@groupMJ, @t2,1);
SELECT @t2:=ifnull(max(forum_id)+1,1) from phorum_forums;



 ALTER TABLE phorum_users ADD UNIQUE (username) ;
   
 ALTER TABLE phorum_user_group_xref ADD UNIQUE (user_id , group_id) ;
 
 ALTER TABLE phorum_forums ADD UNIQUE (name) ;
 
 
 ALTER TABLE phorum_forums ADD UNIQUE(name, forum_id);
 
 ALTER TABLE phorum_groups ADD UNIQUE (name);
 
update phorum_settings set data='french' where name='default_language';

/*  
update phorum_settings set data='1' where name='allow_avatar_remote';
 
# disable_registration_FR_v1.1.0.txt

INSERT INTO phorum_settings (name, data) VALUES ('registration_status', '1');

INSERT INTO phorum_settings (name, data) VALUES ('registration_closed', 'Les inscriptions aux forum sont automatiques lors de l''inscription au jeu');

*/