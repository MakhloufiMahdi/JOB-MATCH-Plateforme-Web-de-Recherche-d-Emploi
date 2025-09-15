# Job Matching

Job Matching est une plateforme web permettant aux utilisateurs de rechercher des offres d'emploi, consulter les tendances du marché, recevoir des recommandations personnalisées et gérer leur profil. Le site est développé en **PHP**, avec une base de données **MySQL**, et utilise **Bootstrap 5** pour le design.

---

## Structure du projet

### 1. Fichiers principaux

- **index.php**  
  Page d'accueil du site. Présente les boutons pour se connecter ou s'inscrire et un message d'introduction.

- **connexion.php**  
  Page de connexion des utilisateurs. Vérifie les identifiants via la base de données et démarre une session.

- **inscription.php**  
  Page d'inscription pour les nouveaux utilisateurs. Les informations sont enregistrées dans la table `users`.

- **forgot_pass.php**  
  Permet aux utilisateurs de réinitialiser leur mot de passe en générant un token et un lien temporaire.

- **jobs.php**  
  Affiche la liste des offres d'emploi avec possibilité de filtrer par mot-clé ou type de contrat.

- **profil.php**  
  Affiche le profil de l'utilisateur connecté et un score de correspondance avec les offres disponibles.

- **recommandations.php**  
  Affiche les offres d'emploi recommandées en fonction de la spécialité de l'utilisateur.

- **tendances.php**  
  Montre les tendances du marché de l'emploi : top métiers, entreprises les plus actives et compétences recherchées.

- **scrap.php**  
  Script PHP pour scraper des offres depuis le site "emploitunisie.com" et les insérer dans la base de données.

- **logout.php**  
  Détruit la session de l'utilisateur et redirige vers l'accueil.

---

### 2. Dossiers

#### a) `views/`

Contient les fichiers de présentation (HTML + PHP) et les layouts communs.

- **layout/header.php**  
  Header global avec navbar, inclusion des CSS/JS et scripts Chart.js.  
  Gère la connexion/déconnexion dans la navbar.

- **layout/footer.php**  
  Footer global avec copyright et inclusion du JS de Bootstrap.

- **jobs/index.php**  
  Affiche la liste des jobs filtrés ou recherchés.

- **jobs/show.php**  
  Affiche les détails d'une offre spécifique.

---

#### b) `models/`

Contient la logique métier liée aux données.

- **job.php**  
  Classe `Job` pour interagir avec la table `jobs` :
  - `getAll()` : récupérer toutes les offres.
  - `getById($id)` : récupérer une offre spécifique.
  - `search($keyword)` : rechercher des offres par mot-clé.

---

#### c) `controllers/`

Contient les contrôleurs pour gérer les requêtes et charger les vues.

- **jobscontroller.php**  
  Contrôleur pour gérer les pages d'offres :
  - `index()` : affiche la liste des jobs.
  - `show()` : affiche le détail d'une offre.

---

#### d) `assets/`

Contient les ressources statiques :

- **style.css**  
  Fichier CSS global avec :
  - Styles pour navbar, boutons, formulaires, cartes d'offres.
  - Animations, transitions, scrollbar, gradient et responsive design.

- **images/**  
  Contient le favicon et autres images (logo, icônes…).

---

### 3. Base de données

- **Tables principales**
  - `users` : informations des utilisateurs (nom, prénom, email, spécialité, mot de passe, etc.)
  - `jobs` : offres d'emploi (titre, entreprise, lieu, type de contrat, description, lien, date de publication)

- **Connexion à la DB**  
  Fichier : `config/database.php`  
  Utilisé pour se connecter à MySQL avec PDO.

---

### 4. Fonctionnalités clés

1. Authentification sécurisée (connexion, inscription, mot de passe oublié)
2. Recherche et filtrage d'offres par mots-clés et type de contrat
3. Pages profils personnalisées avec score de matching
4. Recommandations d’offres basées sur la spécialité de l’utilisateur
5. Visualisation des tendances et compétences recherchées
6. Scraper pour alimenter la base de données avec de nouvelles offres

---

### 5. Technologies utilisées

- PHP 8+
- MySQL
- Bootstrap 5
- Chart.js
- HTML5 / CSS3
- JS (minimal, pour navbar et graphiques)

---




