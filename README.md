# BookMaker-API

TP qui court sur toute la formation, avec plusieurs refactorisations (de la POO vanilla à la création d'une API) // Création d'une application web front + back pour une entreprise de vente de livre d'occasion

## Généralités

```bash
symfony server:start
```
**accès** API : url (local) + /api

## Dépendances

```bash
composer install
```

## Gestion API / BDD

```bash
 composer require symfony/maker-bundle --dev
```

```bash
composer require orm
```

**Création fichier** .env.local et mettre à jour connexion BDD (ligne DATABASE_URL)

**Création** dossier jwt dans config (config/jwt) + ouvrir un terminal **Gitbash** 

    * Configurer passphrase :

    ```bash
    openssl genrsa -out config/jwt/private.pem -aes256 4096
    ```

    * Générer la clef privée et la clef publique

    ```bash
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
    ```

    * Penser a modifier .env.local