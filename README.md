# Creation-de-sorties

Un projet de test de création de sorties/voyages sous PHP Symfony avec possibilité de création de compte, d'inscription/désinscription à une sortie, etc.

Lancement en local
  * Lancer WAMP Server (MySQL 8.0.31 | PHP 8.0.26)
  * composer update (première exec)
  * symfony server:start -d

L'environnement de développement ainsi que l'URL Doctrine/BDD sont modifiables dans le fichier .env
Les mots de passes utilisateurs sont encodés, l'algorithme de génération des hashs est définit dans "config/packages/security.yaml".

Script de création de BDD : [127.0.0.1](https://github.com/Foxxyyy/Creation-de-sorties/blob/main/127_0_0_1.sql)

Projet ENI réalisé par :
  * Antoine B.
  * Julien C.
  * Marc C.
  * Stéphane B.
