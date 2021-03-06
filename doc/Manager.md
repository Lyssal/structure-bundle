# Manager

Le manager de `LyssalStructureBundle` peut servir de base à tous vos manager. Il définit différentes méthodes.


## Utilisation

Vous pouvez simplement créer votre manager comme ceci :

```php
use Lyssal\StructureBundle\Manager\Manager;

/**
 * Manager de mon entité.
 */
class EntiteManager extends Manager
{
    
}
```

Mais si vous souhaitez utiliser le manager de `LyssalStructureBundle` (`%lyssal.manager.class%`) sans rien modifier, vous pouvez juste créer votre service ainsi :

```yaml
<service id="acme.mon_bundle.manager.mon_entite" class="%lyssal.manager.class%">
    <argument type="service" id="doctrine.orm.entity_manager" />
    <argument>%acme.mon_bundle.entity.mon_entite.class%</argument>
</service>
```

En utilisant `%lyssal.manager.class%`, vous n'avez donc pas besoin de créer de classe pour votre manager.


## Méthodes utilisables

Retourne un tableau d'entités :
```php
findBy(array $conditions, array $orderBy = null, $limit = null, $offset = null, $extras = array())
```

Retourne un tableau d'entités en utilisant des %LIKE% :
```php
findLikeBy(array $conditions, array $orderBy = null, $limit = null, $offset = null)
```

Retourne une entité :
```php
findOneBy(array $conditions)
```

Retourne une entité avec son identifiant :
```php
findOneById($id)
```

Retourne toutes les entités :
```php
findAll()
```

Retourne des entités indexées par leur identifiant :
```php
findByKeyedById(array $conditions, array $orderBy = null, $limit = null, $offset = null, $extras = array())
```

Retourne des entités en effectuant une recherche avec des "%LIKE%" indexées par leur identifiant :
```php
findLikeByKeyedById(array $conditions, array $orderBy = null, $limit = null, $offset = null)
```

Retourne toutes les entités indexées par leur identifiant :
```php
findAllKeyedById()
```

Retourne un tableau d'entités indexés par leur identifiant :
```php
getEntitiesKeyedById(array $entities)
```

Retourne le PagerFanta pour la méthode findBy() :
```php
getPagerFantaFindBy(array $conditions = array(), array $orderBy = null, $nombreResultatsParPage = 20, $currentPage = 1, array $extras = array())
```

Retourne le nombre de lignes en base :
```php
count()
```

Crée une nouvelle entité :
```php
create()
```

Enregistre une ou plusieurs entités :
```php
save($entites)
```

Persiste une ou plusieurs entités :
```php
persist($entites)
```

Flush :
```php
flush()
```

Détache tous les objets :
```php
clear()
```

Supprime une ou plusieurs entités :
```php
remove($entites)
```

Supprime toutes les entités :
```php
removeAll($initAutoIncrement)
```

Vérifie si une entité existe :
```php
exists($entity)
```

Effectue un TRUNCATE sur la table :
```php
truncate($initAutoIncrement)
```

Spécifie le nouveau AUTO_INCREMENT de l'identifiant de la table à 1 :
```php
initAutoIncrement()
```

Spécifie le nouveau AUTO_INCREMENT de l'identifiant de la table :
```php
setAutoIncrement($initAutoIncrement)
```

Retourne le nom de la table en base de données :
```php
getTableName()
```

Retourne le nom des identifiants de l'entité :
```php
getIdentifier()
```

Retourne si l'entité gérée possède un champ :
```php
hasField($fieldName)
```

Retourne si l'entité gérée possède une association :
```php
hasAssociation($fieldName)
```

### Paramètre $conditions


`LyssalStructureBundle` étend fortement ce paramètre pour multiplier les usages sans avoir à écrire manuellement une requête. Mais ce paramètre peut également être utilisé de manière classique tel qu'il est utilisé avec `Doctrine`.


Exemple d'utilisation de `$conditions` pour le manager (fictif) `VilleManager` :
```php
// (genre = $genre OR genreParent = $genre) AND genre.nom LIKE '%tratégi%'
$conditions = array
(
    EntityRepository::OR_WHERE => array
    (
        'genre' => $genre,
        'genreParent' => $genre
    ),
    EntityRepository::WHERE_LIKE => array
    (
        'genre.nom' => '%tratégi%'
    )
);
// (genre.nom LIKE '%tratégi%' OR genre.nom LIKE '%éflexio%')
$conditions = array
(
    EntityRepository::OR_WHERE => array
    (
        array(EntityRepository::WHERE_LIKE => array('genre.nom' => '%tratégi%')),
        array(EntityRepository::WHERE_LIKE => array('genre.nom' => '%éflexio%'))
    )
);
```

Les possibilités pour `$conditions` sont :
* `EntityRepository::OR_WHERE` : Pour des (x OR y OR ...)
* `EntityRepository::AND_WHERE` : Pour des (x AND y AND ...)
* `EntityRepository::WHERE_LIKE` : Pour des (x LIKE y)
* `EntityRepository::WHERE_IN` : Pour des (x IN (y1, y2...))
* `EntityRepository::WHERE_NULL` : Pour des (x IS NULL)
* `EntityRepository::WHERE_NOT_NULL` : Pour des (x IS NOT NULL)
* `EntityRepository::WHERE_EQUAL` : Pour des x = y
* `EntityRepository::WHERE_LESS` : Pour des x < y
* `EntityRepository::WHERE_LESS_OR_EQUAL` : Pour des x <= y
* `EntityRepository::WHERE_GREATER` : Pour des x > y
* `EntityRepository::WHERE_GREATER_OR_EQUAL` : Pour des x >= y
ou pour les HAVING :
* `EntityRepository::OR_HAVING`
* `EntityRepository::AND_HAVING`
* `EntityRepository::HAVING_EQUAL`
* `EntityRepository::HAVING_LESS`
* `EntityRepository::HAVING_LESS_OR_EQUAL`
* `EntityRepository::HAVING_GREATER`
* `EntityRepository::HAVING_GREATER_OR_EQUAL`



### Paramètre $extras

Exemple d'utilisation de `$extras` pour le manager (fictif) `VilleManager` :
```php
$extras = array
(
    EntityRepository::INNER_JOINS => array
    (
        'ville.maison' => 'maison'
    ),
    EntityRepository::SELECTS => array
    (
        'maison' => EntityRepository::SELECT_JOIN
    )
);
```
Les possibilités pour `$extras` sont :
* `EntityRepository::SELECTS` : Met à jour l'entité avec une jointure avec EntityRepository::SELECT_JOIN comme valeur (cf. Exemple ci-dessus) ou sinon ajoute une valeur à remonter.
* `EntityRepository::LEFT_JOINS`
* `EntityRepository::INNER_JOINS`
* `EntityRepository::GROUP_BYS`
