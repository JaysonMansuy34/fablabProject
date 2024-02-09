# Fablab
Projet PHP Symfony pour un FabLab

Bienvenue sur le projet FabLab réalisé pour l'entreprise. Pour débuter, nous procéderons à l'installation de PHP et Symfony.

Etape 1: 
    - symfony new fablab --version="7.0.*" --webapp {

        L'option --webapp crée un projet Symfony avec toutes les fonctionnalités par défaut, y compris un ensemble complet de packages recommandés pour un projet web complet.
    }

Etape 2: 
    - composer install {

        Cette commande joue un rôle crucial dans le développement de projets PHP en automatisant l'installation et la mise à jour des bibliothèques et des paquets dont le projet dépend. Voici ce que fait composer install
    }

Information version visualisation : 
    - php bin/console about {

        Cette commande vous fournit un résumé détaillé des versions clés de votre environnement Symfony, y compris PHP, Symfony lui-même, et d'autres composants importants. Elle est utile pour vérifier rapidement la configuration de votre projet et s'assurer que toutes les dépendances sont à jour.
    }

Test Start Server :
    - symfony.exe server:ca:install {

        Cette commande installe un certificat d'autorité de certification (CA) local, ce qui permet à votre navigateur de faire confiance aux certificats auto-signés générés par le serveur web Symfony. C'est particulièrement utile si vous développez des applications qui nécessitent une connexion HTTPS, comme celles qui utilisent des fonctionnalités liées à la sécurité ou à la vie privée.
    }

Open Start Project : 
    - symfony server:start {

         lance le serveur web local de Symfony pour le développement, permettant l'accès à l'application via un navigateur web.
    }



