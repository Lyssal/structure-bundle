# LyssalStructureBundle


Ce dépôt est abandonné.

Vous pouvez le remplacer par plusieurs autres dépôts :

* [lyssal/doctrine-orm-bundle](https://packagist.org/packages/lyssal/doctrine-orm-bundle)
* [lyssal/entity-bundle](https://packagist.org/packages/lyssal/entity-bundle)
* [lyssal/twig-bundle](https://packagist.org/packages/lyssal/twig-bundle)


LyssalStructureBundle contient différents outils facilitant le développement d'applications Symfony.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6e0264ed-220c-4726-92b5-a174e0556cf0/small.png)](https://insight.sensiolabs.com/projects/6e0264ed-220c-4726-92b5-a174e0556cf0)

## Documentation

* [Manager de base](doc/Manager.md)
* [Repository de base](doc/Repository.md)
* [Decorator](doc/Decorator.md)
* [Appellation](doc/Appellation.md)
* [Traits pour entité](doc/Traits.md)
* [Réponses de contrôleur](doc/Response.md)
* [Messages Flash](doc/MessagesFlash.md)

### Autres bundles

* [Thèmes Pagefanta](doc/Pagerfanta.md)
* [Vues Simplepie](doc/Simplepie.md)
* [Vues DebrilRssAtomBundle](doc/DebrilRssAtomBundle.md)

## Installation

1. Mettez à jour votre `composer.json` :
```json
"require": {
    "lyssal/structure-bundle": "x.y.*"
}
```
2. Installez le bundle :
```sh
php composer.phar update
```
3. Mettez à jour `AppKernel.php` :
```php
new Lyssal\StructureBundle\LyssalStructureBundle(),
```
4. Mettez à jour `routing.yml` (si vous souhaitez utiliser le service `Response`) :
```php
lyssal_structure:
    resource: "@LyssalStructureBundle/Controller"
    type: "annotation"
    prefix: "/LyssalStructure"
```

Pour utiliser par défaut le `Repository` de `LyssalStructureBundle` ou si vous utilisez le `Manager`, il faut définir `doctrine.orm.default_repository_class` ainsi :

```yml
doctrine:
    orm:
        default_repository_class: "Lyssal\StructureBundle\Repository\EntityRepository"
```
