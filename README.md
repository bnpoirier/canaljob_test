# CanalJob - Test du carnet d'adresses

* [Installation](#installation)
  * [Utilisation du site](#utilisation-du-site)
    + [Créer un compte / Se connecter](#cr%C3%A9er-un-compte--se-connecter)
    + [Importer des utilisateurs](#importer-des-utilisateurs)
    + [Ajouter des contacts](#ajouter-des-contacts)
    + [Modifier les informations du compte](#modifier-les-informations-du-compte)

## Installation
Modifiez le fichier .env et indiquez les identifiants de votre base de données locale
`DATABASE_URL=mysql://user:password@127.0.0.1:3306/canaljob_test`

Créez la base de données
`php bin/console doctrine:database:create`

Créez les tables
`php bin/console doctrine:schema:update --force`


## Utilisation du site
### Créer un compte / Se connecter
Pour s'inscrire : 
http://yourdomain/register

Pour se connecter : 
http://yourdomain/login

### Importer des utilisateurs
Le fichier **import.csv** donné dans l'exercice est inclus à la racine du projet Symfony.

* Se connecter et cliquer sur "Importer des utilisateurs".
* Choisir le fichier **import.csv**
* Cliquer sur **Importer**

### Ajouter des contacts
Nécessite d'***importer des utilisateurs***
* Utiliser la barre de recherche en haut à gauche et taper le **nom, prenom ou email d'un contact existant**.
* Si des résultats s'affichent. Cliquez sur le bouton **"+"** à gauche du contact

Le contact se retrouve désormais en page d'accueil du profil

### Modifier les informations du compte
Cette section pour informer qu'il n'est pas obligatoire de spécifier le mot de passe si l'utilisateur ne veut pas le changer. Le champ vide sera ignoré.
