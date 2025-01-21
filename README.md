
# ENGLISH

Allows you to build a list of offers, each of which can be displayed in several categories.

## How to install the extension?

* Backup your store database and web directory
* Open a terminal and move to Magento root directory
* Run these commands in your terminal

```shell
# You must be in Magento root directory
# Use your composer login and password available in your customer account on Owebia Store
composer require dnd/magento2-module-offering

php bin/magento cache:clean
php bin/magento module:enable Dnd_Offering
php bin/magento setup:upgrade
php bin/magento setup:di:compile

# Only if the store is in production mode
# Deploy static content for each used locale (here for en_US locale only)
php bin/magento setup:static-content:deploy en_US
```

* If you are logged to Magento backend, logout from Magento backend and login again

## Usage

In the backoffice, click on the menu Content > Offers  

Create yours offers for categories.

--------------------------

# FRANCAIS

Permet de construire une liste d'offres, chacune pouvant être affichée dans plusieurs catégories.

## Comment installer l'extension ?

* Faites une sauvegarde de votre boutique et de votre répertoire web
* Ouvrez une console et placez-vous dans le répertoire racine de Magento
* Exécutez les commandes suivantes dans votre console

```shell
# Vous devez être dans le répertoire racine de Magento
# Utilisez vos login et mot de passe composer disponibles dans votre espace client sur Owebia Store
composer require dnd/magento2-module-offering

php bin/magento cache:clean
php bin/magento module:enable Dnd_Offering
php bin/magento setup:upgrade
php bin/magento setup:di:compile

# Seulement si la boutique est en mode production
# Déploiement des contenus statiques pour chaque localisation utilisée (ici, uniquement en_US)
php bin/magento setup:static-content:deploy en_US
```

* Si vous êtes connecté au panneau d'administration de Magento, déconnectez-vous puis connectez-vous à nouveau

## Utilisation

Dans le panneau d'administration, cliquez sur le menu Contenu > Offres  

Créez vos offres pour les catégories.
