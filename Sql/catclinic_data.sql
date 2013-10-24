create database if not exists catclinic;

use catclinic;

insert into chat(nom, age, tatouage) values ('minouche', 7, 'ABCDEF');
-- insert into chat(nom, age, tatouage) values ('chatou', 8, 'ABCDEG');
-- insert into chat(nom, age, tatouage) values ('fritz', 10, 'ABCDEH');

insert into praticien(nom, prenom) values ('jean', 'pleur');
insert into praticien(nom, prenom) values ('simon', 'tanpi');
insert into praticien(nom, prenom) values ('sylvie', 'sekoul');

insert into utilisateur(login, motdepasse) values ('sebdu13', SHA1('sebdu13123cat5'));
insert into utilisateur(login, motdepasse, admin) values ('admincat', SHA1('admincat123cat5'), 1);

insert into proprietaire(nom, prenom, id_utilisateur, id_chat) values ('Ferrandez', 'Sébastien', 1, 1);

insert into visite(id_praticien, id_chat, date, prix, observations) values (1,1,current_timestamp(), 79.90, 'Opération bien déroulée');
