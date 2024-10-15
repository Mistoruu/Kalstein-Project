# Kalstein Project

## Description

Ce projet est une application PHP qui permet de gérer des produits à travers une API RESTful. Il permet d'effectuer des opérations CRUD (Créer, Lire, Mettre à jour, Supprimer) sur une base de données MySQL contenant des informations sur les produits.

## Table des matières

- [Technologies utilisées](#technologies-utilisées)
- [Installation](#installation)
- [Configuration](#configuration)
- [Base de données](#base-de-données)
- [Exécution de l'application](#exécution-de-lapplication)


## Technologies utilisées

- PHP
- MySQL
- Xampp
- HTML/CSS
- JavaScript
- PDO (PHP Data Objects)

## Installation

1. Clonez ce repository :

   ```bash
   git clone https://github.com/votre-utilisateur/kalsteinProject.git
  
2. Assurez-vous d'avoir installé XAMPP ou un autre serveur local pour exécuter PHP et MySQL.
  
3. Placez le dossier kalsteinProject dans le répertoire htdocs de votre installation XAMPP (généralement situé dans C:\xampp\htdocs sur Windows).

## Configuration

##Base de données :

  Ouvrez phpMyAdmin dans votre navigateur (généralement à l'adresse http://localhost/phpmyadmin).
  Créez une nouvelle base de données nommée kalsteinProject.
  Importez le fichier de base de données kalstein_project.sql que vous trouverez dans le dossier database.

Étapes pour importer la base de données :

  Sélectionnez la base de données kalsteinProject.
  Cliquez sur l'onglet "Importer".
  Choisissez le fichier database/kalstein_project.sql et cliquez sur "Exécuter".
    
## Exécution de l'application

  Démarrez le serveur Apache et MySQL à partir du Panneau de contrôle XAMPP.

  Ouvrez votre navigateur et accédez à l'application à l'adresse suivante : http://localhost/kalsteinProject/public/index.php
  
    
