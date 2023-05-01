<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use const CNAME;
use const CNAMECATEGORIENAME;
use const FCATEGORIES;
use const PFORMATIONS;
use const PIDID;
use const PNAMENAME;

define("PIDID", "p.id id");
define("PNAMENAME", "p.name name");
define("CNAMECATEGORIENAME", "c.name categoriename");
define("PFORMATIONS", "p.formations");
define("FCATEGORIES", "f.categories");
define("PID", "p.id");
define("CNAME", "c.name");
/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Retourne toutes les playlists triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderBy($champ, $ordre): array{
        return $this->createQueryBuilder('p')
                ->select(PIDID)
                ->addSelect(PNAMENAME)
                ->addSelect(CNAMECATEGORIENAME)
                ->leftjoin(PFORMATIONS, 'f')
                ->leftjoin(FCATEGORIES, 'c')
                ->groupBy(PID)
                ->addGroupBy(CNAME)
                ->orderBy('p.'.$champ, $ordre)
                ->addOrderBy(CNAME)
                ->getQuery()
                ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur): array{
        if($valeur==""){
            return $this->findAllOrderBy('name', 'ASC');
        }
       
            return $this->createQueryBuilder('p')
                    ->select(PIDID)
                    ->addSelect(PNAMENAME)
                    ->addSelect(CNAMECATEGORIENAME)
                    ->leftjoin(PFORMATIONS, 'f')
                    ->leftjoin(FCATEGORIES, 'c')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy(PID)
                    ->addGroupBy(CNAME)
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy(CNAME)
                    ->getQuery()
                    ->getResult();
        
    }

    /**
     * Enregistrements dont un champ contient une valeur et table
     * @param type $champ
     * @param type $valeur
     * @param type $table
     * @return array
     */
    public function findByContainValueBis($champ, $valeur, $table): array{
        if($valeur==""){
            return $this->findAllOrderBy('name', 'ASC');
        }
           
        return $this->createQueryBuilder('p')
                    ->select(PIDID)
                    ->addSelect(PNAMENAME)
                    ->addSelect(CNAMECATEGORIENAME)
                    ->leftjoin(PFORMATIONS, 'f')
                    ->leftjoin(FCATEGORIES, 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy(PID)
                    ->addGroupBy(CNAME)
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy(CNAME)
                    ->getQuery()
                    ->getResult();
    }
}