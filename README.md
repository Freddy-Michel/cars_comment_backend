Creation de systeme de commentaire sur une voiture en utilisant **MYSQL** - **SYMFONY** et **reactjs**

En debut, si vous etes sur Windows ou linux, installer **MySQL** sur votre ordinateur (Install wampserver ...),

**Requirement**
> PHP > 8
> Symfony > 5.3

Apres, pour lancer le projet, il faut suivre les etapes suivantes:
Ouvrire le terminal

1. Clone le repo **https://github.com/Freddy-Michel/cars_comment_backend.git**
2. Aller dans le dossier `cars_comment_backend` en tapant le commande **cd cars_comment_backend** sur le terminale.
3. Lance la commande

```bash
composer install
```
 pour installer les dependances dans `composer.json`

4. Pour creer la base de donnee

```bash
symfony console doctrine:database:create
```
 5. Pour lancer la migration de la base de donnee apres la creation,

 ```bash
 symfony console make:migration
 ```

 6. Pour appliquer la migration,

 ```bash
symfony console doctrine:migrations:migrate
 ```

Si la migration et l'installation des dependence sont terminee, entre le commande suivant dans le terminal pour lancer le server:
```bash
symfony server:start
```
Si le serveur demarre normalement
```
[OK] Web server listening                                                                                              
      The Web server is using PHP CLI 8.0.14                                                                            
      https://127.0.0.1:8000 
```

Ouvrire le navigateur et entrer **https://127.0.0.1:8000** sur l'url
Pour tester les endpoint de l'api, **https://127.0.0.1:8000/api**

## Fonctionnalite a venir
**upload des images** pour le `cars`
- **gestion des roles sur les utilisateurs**


# Auteur:

**NARISOA HARILALA Freddy Michel** : **https://github.com/Freddy-Michel**