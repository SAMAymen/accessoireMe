# 📦 Projet CMS : Création d'un site e-commerce avec WordPress et WooCommerce

Ce projet consiste à créer un site e-commerce fonctionnel en utilisant WordPress et WooCommerce. Vous trouverez ici des instructions détaillées pour installer et configurer le site.

---

## 🚀 Fonctionnalités du site
- Page d'accueil attrayante
- Catalogue de produits avec système de filtrage
- Fiches produits détaillées
- Pages essentielles : "À propos", "Contact", "Panier", etc.
- Gestion des utilisateurs avec rôles
- Recherche et tri avancés

---

## 📂 Structure du projet
- **Source code** : Code du site WordPress
- **Base de données** : Fichier exporté au format `.sql`
- **README.md** : Ce document
- **Captures d'écran** : Images illustrant les étapes principales
- **Rapport** : Explications détaillées sur le processus de développement

---

## 🛠️ Prérequis
Avant de commencer, assurez-vous d'avoir installé les outils suivants :
- Un serveur local (XAMPP, MAMP, ou équivalent)
- MySQL pour la gestion de la base de données
- WordPress (dernière version stable)
- Un éditeur de texte comme Visual Studio Code

---

## 📖 Instructions d'installation
### 1️⃣ Installation de WordPress
1. Téléchargez la dernière version de WordPress depuis [WordPress.org](https://wordpress.org).
2. Décompressez les fichiers dans le dossier `htdocs` (ou équivalent selon votre serveur local).
3. Créez une base de données MySQL nommée `projet_cms`.
4. Configurez le fichier `wp-config.php` :
   ```php
   define('DB_NAME', 'wordpress');
   define('DB_USER', 'root');
   define('DB_PASSWORD', '');
   define('DB_HOST', 'localhost');
