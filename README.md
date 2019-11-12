 # projet Symfony 4 SymFood
Création d'un site de recette de cuisine 

installation du projet:
```
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load