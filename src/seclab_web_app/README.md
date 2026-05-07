# SecLab Web App - Guide d'Installation

Ce dossier contient le code source de l'application e-commerce vulnérable ("SecLab") utilisée comme cible dans notre environnement SOC-in-a-Box.

> ⚠️ **AVERTISSEMENT :** Cette application est INTENTIONNELLEMENT VULNÉRABLE (Injections SQL, XSS, etc.). **Ne la déployez jamais sur un serveur de production ou exposé sur Internet.** Utilisez-la uniquement dans un environnement local isolé (Machine Virtuelle).

## 📋 Prérequis
* Un serveur Web (Apache2 ou Nginx)
* PHP (avec les extensions PDO et pgsql : `php-pgsql`)
* PostgreSQL

## 🚀 Étape 1 : Déploiement des fichiers
Déplacez le contenu de ce dossier vers le répertoire de votre serveur web (souvent `/var/www/html` sous Linux).

```bash
sudo cp -r src/seclab_web_app/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
```

## 🗄️ Étape 2 : Configuration de la Base de Données (PostgreSQL)

L'application s'attend par défaut à communiquer avec une base de données PostgreSQL. 

1. Connectez-vous à votre serveur PostgreSQL :
   ```bash
   sudo -u postgres psql
   ```

2. Créez la base de données et configurez le mot de passe (les identifiants par défaut attendus par l'application) :
   ```sql
   CREATE DATABASE "Test_Lab";
   ALTER USER postgres WITH PASSWORD '123456';
   \q
   ```

*(Note : Si vous avez un fichier d'export SQL `.sql` correspondant aux tables `users`, `products`, etc., n'oubliez pas de l'importer dans votre base `Test_Lab`).*
```bash
# Exemple d'importation si vous possédez un fichier dump.sql
sudo -u postgres psql -d Test_Lab -f votre_fichier.sql
```

## ⚙️ Étape 3 : Fichier de Configuration (`config.php`)

Si vous souhaitez utiliser des identifiants différents, ouvrez le fichier `config.php` situé à la racine du projet et modifiez ces variables :

```php
$servername = "127.0.0.1";
$username   = "postgres";    // Nom d'utilisateur de la BDD
$password   = "123456";      // Mot de passe de la BDD
$dbname     = "Test_Lab";    // Nom de la base de données
```

*(L'application utilise une classe proxy "postgres_mysqli" dans `config.php` pour traduire la syntaxe mysqli classique en PDO PostgreSQL, sans réparer les vulnérabilités de requêtes directes).*

## 🌐 Mettre en ligne
Une fois la base de données configurée, lancez votre serveur Apache/Nginx et votre base PostgreSQL :
```bash
sudo systemctl restart apache2
sudo systemctl restart postgresql
```

Accédez ensuite à l'application via votre navigateur : `http://localhost/` ou l'adresse IP de votre machine virtuelle Target.