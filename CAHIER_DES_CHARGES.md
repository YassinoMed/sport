# CAHIER DES CHARGES TECHNIQUE
## Plateforme de Gestion Intégrale de Club Sportif

**Version :** 2.5 (Complète)  
**Date :** 02 Janvier 2026  
**Technologies :** Laravel 10+ (PHP) - MySQL - Bootstrap 5 - Stripe API - Chart.js

---

## 1. Introduction et Périmètre

### Présentation du Projet
Le projet est une solution ERP (Enterprise Resource Planning) web dédiée à la gestion administrative, financière et sportive d'un réseau de clubs d'arts martiaux. Elle centralise les interactions entre l'administration, les coachs et les athlètes au sein d'une interface unifiée, sécurisée et bilingue.

### Objectifs Stratégiques
*   **Architecture Multi-Sites :** Gérer une hiérarchie Ville > Salle > Groupe > Élève.
*   **Zéro Papier :** Numérisation complète des dossiers (Inscriptions, Paiements, Grades).
*   **Contrôle Financier :** Traçabilité totale des flux (Entrées/Sorties) et prévention des fraudes.
*   **Expérience Premium :** Interface moderne, responsive et intuitive pour tous les acteurs.

---

## 2. Architecture Technique

### Stack Technologique
*   **Backend :** Laravel Framework (Architecture MVC, Eloquent ORM).
*   **Base de Données :** MySQL (Relations complexes avec contraintes d'intégrité).
*   **Frontend :** Blade Templating, Vanilla CSS (Design System "Glassmorphism"), JavaScript (ES6+).
*   **Infrastructure :** Serveur Linux (Ubuntu), Nginx (Optimisé SSL/HTTP2).
*   **Bibliothèques Clés :** Chart.js (Graphiques), DomPDF (Exports PDF), Stripe SDK (Paiements).

### Sécurité et Accès
*   **RBAC (Role-Based Access Control) :** Système de permissions granulaire :
    *   **Admin :** Accès total.
    *   **CityManager :** Gestion des salles d'une ville.
    *   **GymManager :** Gestion d'une salle.
    *   **Coach :** Gestion pédagogique (pas d'accès financier global).
    *   **User :** Accès espace personnel.
*   **Middleware :** Protection des routes par rôle, vérification du statut (Banni/Actif).
*   **Protection des Données :** Stockage privé des documents sensibles, hashage des mots de passe (Bcrypt), protection CSRF & XSS.

---

## 3. Modules Fonctionnels Détaillés

### A. Gestion Structurelle (Multi-Sites)
Ce module permet de modéliser l'organisation physique du club.
*   **Gestion des Villes :** Création, affectation de City Managers.
*   **Gestion des Salles (Gyms) :** Rattachement aux villes, configuration des capacités, affectation des Gym Managers.
*   **Gestion des Groupes :** Organisation des élèves par niveau/âge au sein des salles.

### B. Gestion des Ressources Humaines
1.  **Base de Données Athlètes :**
    *   Fiche signalétique complète (Photo, État civil, Poids, Catégorie).
    *   Dossier documentaire numérique (CIN, Certificats médicaux/scolaires, Diplômes).
    *   Historique complet (Changement de groupes, évolution).
2.  **Gestion des Coachs :**
    *   Profil professionnel, spécialités.
    *   Attribution aux groupes et salles.
    *   Suivi de la paie (Indicateurs de salaires versés/dus) avec rapports dédiés (sans accès aux dépenses globales).
3.  **Administration Utilisateurs :**
    *   Bannissement/Débannissement des utilisateurs.
    *   Réinitialisation sécurisée des accès.

### C. Module Financier Avancé
1.  **Gestion des Revenus (Cotisations) :**
    *   Suivi mensuel des paiements par élève (Grille de 12 mois glissants).
    *   Statuts visuels : `Payé` (Vert), `Impayé` (Rouge), `En attente` (Orange).
    *   Paiement en ligne via Stripe (Achat de packs, Cartes bancaires).
2.  **Gestion des Dépenses (Expenses) :**
    *   Saisie des frais de fonctionnement (Loyer, Matériel, Salaires Coachs).
    *   Catégorisation des dépenses.
    *   Justificatifs (Upload de factures).
    *   **Restriction :** Module invisible pour les coachs (Vue Admin/Manager uniquement).
3.  **Reporting Financier :**
    *   Tableaux de bord Chiffre d'Affaires.
    *   Comparatif Entrées vs Sorties.
    *   Liste des impayés avec relance visuelle.

### D. Vie Sportive et Pédagogie
1.  **Gestion des Présences :**
    *   Feuilles d'appel numériques par séance.
    *   Calcul automatique des taux de présence.
    *   Justification des absences.
2.  **Système de Grades (Ceintures) :**
    *   Historique de progression (Blanche à Noire).
    *   Dates d'obtention et examinateurs.
    *   Impression des diplômes (Génération PDF).
3.  **Événements et Compétitions :**
    *   Création d'événements (Stages, Tournois).
    *   Gestion des inscriptions (Liste des participants).
    *   Suivi des frais de participation spécifiques aux événements.
    *   Historique des participations sur le profil athlète.

---

## 4. Interface Utilisateur (UI/UX)

### Design System "Premium"
Une identité visuelle forte pour renforcer l'image de marque du club.
*   **Charte Graphique :** Palette de couleurs modernes (Indigo/Emerald/Rose), dégradés subtils.
*   **Composants UI :** Cartes "Modern-Card", Tableaux épurés avec actions au survol, Badges d'état clairs.
*   **Responsive Design :** Optimisation totale pour Mobile, Tablette et Desktop.

### Fonctionnalités Ergonomiques
*   **Internationalisation (i18n) :** Système bilingue natif (Français/Arabe) avec basculement LTR/RTL automatique.
*   **Dark Mode :** Thème sombre activable avec persistance des préférences utilisateur via `localStorage`.
*   **Recherche Globale (Spotlight) :** Barre de recherche rapide (`Ctrl+K`) pour accéder instantanément à n'importe quel athlète, page ou fonction, avec navigation clavier.
*   **Navigation Intelligente :** Barre latérale dynamique selon le rôle utilisateur (Items masqués pour les rangs inférieurs).

---

## 5. Administration Système et Maintenance

### Outils d'Administration
*   **Logs d'Activité :** Traçabilité des actions critiques.
*   **Gestion des Erreurs :** Pages 404/500 personnalisées et logging des exceptions.
*   **Configuration :** Paramètres globaux de l'application.

### Performance
*   **Optimisation des Requêtes :** Eager Loading pour éviter le problème N+1 sur les grandes listes.
*   **Cache :** Mise en cache des statistiques lourdes (Dashboard).
*   **Assets :** Minification et versioning des fichiers CSS/JS.
