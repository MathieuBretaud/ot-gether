<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Ot'gether 🎎 - api back avec Laravel

Laravel + Adminer + PGSQL with Docker 🐳

Notre application tourne sur Docker avec Laravel Sail

## Initialiser le projet

### 1. Faire un git clone du projet

```
git clone https://github.com/O-clock-Pegase/projet-08-Ot-gether-back.git
```

Changer de branche pour 'develop'

### 2. Dupliquer le .env.exemple

### 3. Faire un composer install

```
composer install
```

### 4. Lancer les conteneurs Docker avec sail up

```
sail up
```

le raccourci pour sail est disponible dans le fichier `Makefile`

### 5. Lancer la commande Make prepare pour faire les migrations et le seeding

```
Make prepare
```

### 6. L'application devrait normalement tourner sur http://localhost/

Enjoy it ! 🙏❤️

## Liste des routes disponibles - http://localhost/api/

### Routes d'Authentification

-   POST `/register` : Enregistrer un nouvel utilisateur (AuthController@register)
-   GET `/check-email` : Vérifier si un e-mail existe (AuthController@checkEmail)
-   GET `/user` : Obtenir les données de l'utilisateur authentifié (avec le middleware `auth:sanctum`)

### Routes de l'API Version 1 (Préfixées par `/v1`) - http://localhost/api/v1/

-   GET `/v1/me` : Obtenir les données de l'utilisateur authentifié (avec le middleware `auth`)
-   POST `/v1/events/{event}/participate` : Enregistrer la participation à un événement (ParticipateController@participate)

### Ressources API (avec le middleware `auth:sanctum`) - http://localhost/api/v1/

-   `events` : Gestion des événements (EventController)
-   `event-tags` : Gestion des tags liés aux événements (TagController)
-   `event-categories` : Gestion des catégories pour les événements (CategoryController)
-   `users` : Gestion des utilisateurs (UserController)

## Merci de nous avoir lu - l'équipe Ot'gether with ❤️
