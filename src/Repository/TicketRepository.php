<?php

namespace App\Repository;

use App\Entity\Comment ;
use App\Entity\Tag;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

     /**
      * @return Ticket[] Returns an array of Ticket objects
      */
    
    public function findByExampleField($userid)
    {
	    return $this->createQueryBuilder('t')
	        ->select('t, COUNT(c.id)') 
		    ->where('t.creator = '.$userid)
		    ->leftJoin( 't.comments' , 'c' )
                    ->leftJoin(Tag::class, 'ta', 'with', 't.id = ta.id')
            //  ->leftJoin(Comment::class, 'c', 'with', 't.id = c.ticket')
	            ->groupBy('t.id')              
	   //   ->having('(t.id - COUNT(c.id)) <= 0')
                    ->orderBy('t.id', 'ASC')
                    ->getQuery()
	            ->getResult(); 
    }

    public function findTag($tagId)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.tags = :tagId')
            ->setParameter('tagId', $tagId)
            //->orderBy('t.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }    

    /*
    public function findOneBySomeField($value): ?Ticket
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
