# Projet de Gestion de Projets et de Tâches

## 📌 Description
Ce projet est une application web développée avec **Laravel** permettant aux utilisateurs de créer, gérer et collaborer sur des projets. Chaque projet peut contenir plusieurs tâches et inclure plusieurs membres ayant des rôles différents (**admin** ou **membre**).

## 🛠 Technologies utilisées
- **Laravel** (Back-end)
- **Blade** (Templates)
- **SQLite** (Base de données)
- **Tailwind CSS** (Interface utilisateur)
- **FontAwesome** (Icônes)
- **AlpineJS** (boites de dialog et menu déroulant)

## 🎯 Fonctionnalités
✅ Authentification et gestion des utilisateurs (Laravel Breeze)  
✅ Création et gestion de projets  
✅ Attribution de rôles (Admin / Membre)  
✅ Ajout et gestion des tâches par projet
✅ Ajout et gestion des fichiers par projet  
✅ Suppression sécurisée des tâches  
✅ Vérification des autorisations via **Policies**  
✅ Messages de succès/erreur après chaque action  

## 📂 Installation et Configuration

### 1️⃣ Cloner le projet
```sh
 git clone https://github.com/AloproAgency/projetcollab.git
 cd projetcollab
```

### 2️⃣ Installer les dépendances
```sh
composer install
npm install
```

### 3️⃣ Configurer l'environnement
Copie le fichier `.env.example` en `.env` et modifie les valeurs MAIL_USERNAME, MAIL_PASSWORD et MAIL_FROM_ADDRESS par les infos privés (Je les ai mis dans le mail privé que je vous ai envoyé. Vous pouvez aussi utiliser votre propre server SMTP)
```sh
cp .env.example .env
php artisan key:generate
```

### 5️⃣ Lancer le serveur
```sh
php artisan serve
```
Accède à l’application sur **http://127.0.0.1:8000** 🚀

## 🔑 Gestion des Autorisations
Les actions sur les projets et tâches sont protégées par **Laravel Policies** :
- Seuls les **admins** d’un projet peuvent le modifier ou le supprimer.
- Tous les membres peuvent voir les projets auxquels ils participent.
- Seuls les **admins** peuvent ajouter des membres à un projet.

## 🔧 Routes Principales

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/` | Page d'accuiel |
| GET | `/dashboard` | Tableau de bord |
| GET | `/projects` | Liste des projets |
| GET | `/projects/{project}` | Détails d'un projet |
| POST | `/projects` | Créer un projet |
| PUT | `/projects/{project}` | Modifier un projet (Admin) |
| DELETE | `/projects/{project}` | Supprimer un projet (Admin) |
| POST | `/projects/{project}/tasks` | Ajouter une tâche |
| DELETE | `/projects/{project}/tasks/{task}` | Supprimer une tâche |

## 👤 Utilisateur de test
    -Alopro
        email: alopro512@gmail.com
        password: 12345678
    -Richo
        email: wixorweb@gmail.com
        password: 12345678

## 📜 Licence
Ce projet est sous licence **MIT**. Tu peux l'utiliser, le modifier et le distribuer librement.

## 💡 Contributions
Les contributions sont les bienvenues ! Pour proposer des modifications :
1. **Fork** le repo
2. Crée une **branche** (`git checkout -b feature-nouvelle-fonctionnalite`)
3. Fais un **commit** (`git commit -m 'Ajout d'une nouvelle fonctionnalité'`)
4. Pousse ta branche (`git push origin feature-nouvelle-fonctionnalite`)
5. Fais une **Pull Request**

🎯 Bon développement ! 🚀

