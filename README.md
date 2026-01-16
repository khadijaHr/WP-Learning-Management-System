# WP Learning Management System

## Description

Ce plugin WordPress est un système complet de gestion de formations et d'apprentissage. Il permet la gestion avancée des utilisateurs, profils, formations, inscriptions et statistiques pour toute plateforme de formation en ligne. Il propose une interface personnalisée pour les rôles "Participant", "Coach" et "Master Coach", avec des fonctionnalités d'administration, d'authentification, de gestion de profil et de suivi des formations.

---

## Fonctionnalités principales

- **Authentification personnalisée** : Formulaire de connexion, gestion de session, récupération de l'utilisateur connecté.
- **Gestion des profils** : Modification du profil, upload d'image, changement de mot de passe, description, informations personnelles.
- **Gestion des rôles** : Attribution et affichage des rôles (Participant, Coach, Master Coach).
- **Administration** : Ajout, modification, suppression et activation des utilisateurs via l'interface admin.
- **Formations** : Création, édition, affichage des blocs de formation, ajout de jours et de documents.
- **Inscription** : Liste des participants, coachs, gestion des inscriptions.
- **Statistiques** : Tableaux de bord, statistiques par région et par rôle.
- **Sécurité** : Utilisation de nonce WordPress pour sécuriser les actions et formulaires.
- **Interface responsive** : Feuilles de style CSS dédiées pour une expérience utilisateur optimale.

---

## Structure du plugin

```
custom-auth-profile.php
README.md
assets/
  css/
    admin.css
    wp_style_min.css
  images/
    bg_formation.png
    ...
  js/
includes/
  admin-formations.php
  admin.php
  auth.php
  dashboard_master_coach.php
  day_formation.php
  db.php
  detail_formation.php
  downloads.php
  formations.php
  inscription.php
  manage-formation.php
  mes_formations.php
  participant-registration.php
  profile_coach.php
  profile.php
  shortcodes.php
  statistiques.php
  templates/
```

---

## Installation

1. **Téléchargez** le dossier du plugin dans le répertoire `wp-content/plugins/`.
2. **Activez** le plugin via le menu Extensions de WordPress.
3. **Configurez** les pages nécessaires : créez des pages WordPress et ajoutez les shortcodes correspondants (voir ci-dessous).

---

## Shortcodes disponibles

- `[custom_login_form]` : Formulaire de connexion personnalisé.
- `[custom_profile]` : Page de profil utilisateur.
- `[profile_coach]` : Page de profil coach.
- `[custom_formations]` : Liste des formations.
- `[mes_formations]` : Mes formations (pour l'utilisateur connecté).
- `[day_formations]` : Formations du jour.
- `[detail_formations]` : Détail d'une formation.
- `[liste_des_participants]` : Liste des participants.
- `[participants]` : Liste des inscrits.
- `[coachs]` : Liste des coachs.
- `[statistiques_coach]` : Statistiques pour coach/master coach.
- `[dashboard_master_coach]` : Tableau de bord master coach.

---

## Utilisation

1. **Ajoutez les shortcodes** dans vos pages WordPress selon les besoins.
2. **Accédez à l'administration** pour gérer les utilisateurs, formations et documents.
3. **Personnalisez les styles** via les fichiers CSS dans `assets/css/`.
4. **Ajoutez des images** dans `assets/images/` pour les profils et formations.

---

## Sécurité

- Toutes les actions sensibles (modification, suppression, activation) sont protégées par des nonces WordPress.
- Les données utilisateurs sont validées et échappées avant insertion ou affichage.

---

## Développement

- **PHP** : Toutes les fonctionnalités sont dans le dossier `includes/`.
- **CSS** : Styles dans `assets/css/`.
- **JS** : Scripts pour l'export, l'inscription et l'administration dans `assets/js/`.
- **Templates** : Les vues HTML sont dans `includes/templates/`.

---

## Personnalisation

- Modifiez les fichiers dans `includes/templates/` pour adapter l'affichage.
- Ajoutez des styles dans `assets/css/wp_style_min.css` ou `admin.css`.
- Ajoutez des scripts JS dans `assets/js/`.

---

## Structure de la base de données

Le plugin crée automatiquement les tables suivantes :
- `lms_users` : Table principale des utilisateurs (Participants, Coachs, Master Coachs)
- `formations_lms` : Table des formations
- `formation_inscriptions` : Table des inscriptions aux formations
- `coach_participant` : Table de relation entre coachs et participants
- Et d'autres tables de support pour les jours de formation, fichiers, etc.

## Support

Pour toute question ou bug, contactez l'équipe de développement LMS.

---

## Auteur

**Khadija Har**  
GitHub: [@khadijahr](https://github.com/khadijahr)

## Licence

Ce plugin est distribué sous licence GPL v3 ou supérieure.
