# Projet Symfony Personel

## Objectif : 
Découvrir le framework Symfony et faire un projet regroupant les grands concepts du framework.
Fait par Laly Teissier, étudiante aux Gobelins Annecy

## Projet :
Bienvenue sur "Book Library", votre bibliothèque de poche !
Grâce à cette webapp, vous saurez en un coup d'oeil si vous avez déjà ce fameux livre qui vous fait de l'oeil à la librairie ou bien si vous pouvez l'achter et enfin l'ajouter à votre collection (physique et numérique).


## Guide de démarage :

1 - Cloner le projet : `git clone `

2 - Ouvrir le projet ouvert sur VS Code ou PHP Storm

3 - Installer les dépendances du projet : 
   
    -- côté back : `composer install`
    
    -- côté front : `npm install`

4 - Lancer le projet :
    
    -- côté back : `symfony serve` avec symfony CLI
    
    -- côté front : `php bin/console tailwind:build --watch`

Le projet est maintenant disponible depuis le lien : http://127.0.0.1:8000/login


**Pour stocker des données en mode dev, vous devez avoir une base de donnée en PostgreSQL sur votre pc.**

Pour créer un utilisateur ou un livre depuis votre terminal, vous pouvez écrire les commandes :

    -- 'php bin/console app:init createUser'

    -- 'php bin/console app:init createBook'

Il faut juste changer l'email du user si vous voulez la relancer une deuxième fois, dans le fichier "InitCommand.php", l'email etant unique sur la plateforme.