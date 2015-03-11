<?php
namespace Lyssal\StructureBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Classe de base des managers.
 *
 * @author Rémi Leclerc
 */
abstract class Manager
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface EntityManager
     */
    private $entityManager;
    
    /**
     * @var \Doctrine\ORM\EntityRepository EntityRepository
     */
    private $repository;
    
    /**
     * @var string Classe de l'entité
     */
    private $class;

    /**
     * Constructeur du manager de base.
     * 
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager EntityManager
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
        
        $this->repository = $this->entityManager->getRepository($this->class);
    }
    
    /**
     * Retourne le EntityRepository.
     * 
     * @return \Doctrine\ORM\EntityRepository Le repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Retourne un tableau d'entités.
     *
     * @param array $conditions Conditions de la recherche
     * @param array|NULL $orderBy Tri des résultats
     * @param integer|NULL $limit Limite des résultats
     * @param integer|NULL $offset Offset
     * @return array Entités
     */
    public function findBy(array $conditions, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($conditions, $orderBy, $limit, $offset);
    }
    
    /**
     * Retourne une entité.
     *
     * @param array $conditions Conditions de la recherche
     * @param array|NULL $orderBy Tri des résultats
     * @return object|NULL L'entité ou NIL si rien trouvé
     */
    public function findOneBy(array $conditions)
    {
        return $this->getRepository()->findOneBy($conditions);
    }
    
    /**
     * Retourne une entité avec son identifiant.
     *
     * @param mixed $id L'identifiant
     * @return object|NULL L'entité ou NIL si rien trouvé
     */
    public function findOneById($id)
    {
        return $this->entityManager->find($this->class, $id);
    }
    
    /**
     * Retourne toutes les entités.
     *
     * @return array Les entités
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    
    /**
     * Retourne un entité vierge.
     * 
     * @return object Nouvelle entité
     */
    public function create()
    {
        return new $this->class;
    }

    /**
     * Enregistre une ou plusieurs entités.
     *
     * @param object|array<object> $donnees Une entité ou un tableau d'entités
     * @return void
     */
    public function save($entites)
    {
        $this->persist($entites);
        $this->flush();
    }
    
    /**
     * Persiste une ou plusieurs entités.
     * 
     * @param object|array<object> $donnees Une entité ou un tableau d'entités
     * @return void
     */
    public function persist($entites)
    {
        if (is_array($entites))
        {
            foreach ($entites as $entite)
                $this->entityManager->persist($entite);
        }
        else $this->entityManager->persist($entites);
    }
    
    /**
     * Flush.
     * 
     * @return void
     */
    public function flush()
    {
        $this->entityManager->flush();
    }

    /**
     * Détache tous les objets.
     *
     * @return void
     */
    public function clear()
    {
        $this->entityManager->clear($this->class);
    }

    /**
     * Supprime une ou plusieurs entités.
     *
     * @param object|array<object> $donnees Une entité ou un tableau d'entités
     * @return void
     */
    public function remove($entites)
    {
        if (is_array($entites))
        {
            foreach($entites as $entite)
                $this->entityManager->remove($entite);
        }
        else $this->entityManager->remove($entite);
    
        $this->flush();
    }

    /**
     * Supprime toutes plusieurs entités.
     *
     * @param boolean $initAutoIncrement Initialise ou pas l'AUTO_INCREMENT à 1
     * @return void
     */
    public function removeAll($initAutoIncrement = false)
    {
        $this->remove($this->findAll());
        if (true === $initAutoIncrement)
            $this->initAutoIncrement();
    }
    
    /**
     * Effectue un TRUNCATE sur la table (ne fonctionne pas si la table possède des contraintes).
     * 
     * @param boolean $initAutoIncrement Initialise ou pas l'AUTO_INCREMENT à 1
     * @return void
     */
    public function truncate($initAutoIncrement = false)
    {
        $this->entityManager->getConnection()->prepare('TRUNCATE TABLE '.$this->getTableName())->execute();
        if (true === $initAutoIncrement)
            $this->initAutoIncrement();
    }

    /**
     * Spécifie le nouveau AUTO_INCREMENT de l'identifiant de la table à 1.
     */
    public function initAutoIncrement()
    {
        $this->setAutoIncrement(1);
    }

    /**
     * Spécifie le nouveau AUTO_INCREMENT de l'identifiant de la table.
     * 
     * @param integer $autoIncrement Valeur de l'AUTO_INCREMENT
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->entityManager->getConnection()->prepare('ALTER TABLE '.$this->getTableName().' auto_increment = '.$autoIncrement)->execute();
    }
    
    /**
     * Retourne le nom de la table en base de données.
     * 
     * @return string Nom de la table
     */
    public function getTableName()
    {
        return $this->entityManager->getMetadataFactory()->getMetadataFor($this->repository->getClassName())->getTableName();
    }
}
