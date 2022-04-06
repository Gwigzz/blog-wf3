# Blog

## Example de project "blog" avec Symfony 6

### Pour cloner le projet

```
git clone https://github.com/Gwigzz/blog-wf3.git
```

#### Installation
```
composer install
npm install (pour la partie Webpack Encore)
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load (pour nos fixtures)
symfony console checkedit:install (pour l'éditeur de formulaire pésonalisé)
symfony console assets:install public (pour l'éditeur de forumaire personalisé (style))
```

#### Webpack Encore (package.json)
```
npm install sass-loader@^12.0.0 sass --save-dev
npm install postcss-loader@^6.0.0 --save-dev
npm install autoprefixer --save-dev
npm install bootstrap --save-dev
npm install bootswatch
npm install jquery
npm install --save @fortawesome/fontawesome-free
```
#### Composants installés (voir composer.json
```
composer require symfony/webpack-encore-bundle
composer require stof/doctrine-extensions-bundle (Slug)
composer require symfony/rate-limiter (Limitation de tentatives de connexion)
composer require --dev orm-fixtures
composer require fakerphp/faker
composer require knplabs/knp-paginator-bundle
composer require friendsofsymfony/ckeditor-bundle


```