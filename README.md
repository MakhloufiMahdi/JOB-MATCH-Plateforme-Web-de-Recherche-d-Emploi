## JOB-MATCH – Plateforme Web de Recherche d’Emploi

 JOB-MATCH est une plateforme web complète permettant aux utilisateurs de rechercher des offres d'emploi, consulter les tendances du marché, recevoir des recommandations personnalisées et gérer leur profil. Le projet combine conception logicielle, réingénierie des processus, gestion de base de données et développement web.
 https://jobmatch.42web.io/

# 🔧 Technologies utilisées

  -Backend / Web : PHP 8+, MySQL, PDO

  -Frontend : HTML5, CSS3, Bootstrap 5, JavaScript (Chart.js pour graphiques)

  -Modélisation : UML (cas d’utilisation, diagramme de classes)

  -Gestion des données : SQL avancé, procédures stockées, vues, triggers, index, hashage des mots de passe

  -Méthodologie : Agile (User Stories, Product Backlog), Réingénierie des Processus d’Affaires (Hammer & Champy)
  
##  Structure du projet

Le projet est organisé en quatre grandes parties :

# 1. Atelier Génie Logiciel

Product Backlog : 10 user stories clés pour l’authentification, la gestion des offres, des utilisateurs et des compétences, la recommandation personnalisée et l’analyse des tendances.

Diagramme de cas d’utilisation UML : Interactions entre utilisateurs/administrateurs et le système.

Diagramme de classes UML : Entités principales (Compte, Utilisateur, Administrateur, Offre, Compétence) avec leurs relations.

# 2. Réingénierie des Processus d’Affaires (RPA)

Cartographie des processus : Étapes suivies par les jeunes pour trouver un emploi.

Analyse SWOT : Forces et faiblesses du système actuel et du système proposé.

Solution proposée : Plateforme intelligente pour automatiser la collecte et le traitement des offres et profils.

# 3. Système de Gestion de Bases de Données

Scripts SQL : Gestion des utilisateurs, compétences et offres.

Sécurité et optimisation : Hashage des mots de passe, triggers pour unicité des emails, index pour recherches rapides, procédures stockées pour l’ajout d’offres.

Analyse avancée : Vues et procédures pour générer dynamiquement des listes (compétences les plus demandées, utilisateurs et leurs compétences).

Utilisateur MySQL dédié : Gestion centralisée et sécurisée des données.

# 4. Programmation Web

Résumé : Plateforme web en PHP/MySQL avec Bootstrap 5 permettant aux utilisateurs de consulter, filtrer et rechercher des offres d’emploi, visualiser des recommandations personnalisées et suivre les tendances du marché.

Fonctionnalités principales :

Authentification sécurisée (connexion, inscription, mot de passe oublié)

Recherche et filtrage d’offres

Profil utilisateur avec score de matching

Recommandations basées sur la spécialité

Visualisation des tendances et compétences recherchées

Scraper pour alimenter la base de données
