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
    
    public function findByExampleField($user)
    {
      /*  return $this->createQueryBuilder('tk')
            ->andWhere('tk.id LIKE :searchTerm
	         OR tk.name LIKE :searchTerm
	         OR tk.status LIKE :searchTerm')
            ->leftJoin( 'tk.tags' , 'tg' )
            ->setParameter('searchTerm', '%' .$term. '%')
         //   ->orderBy('t.id', 'ASC')
         //   ->setMaxResults(10)
            ->getQuery()
            ->getResult()
	    ;*/
	 $tick = $this-> createQueryBuilder('tk')
              ->select('tk')
             // ->from(Ticket::class, 'tk')
              ->innerJoin(Tag::class, 'tg', 'with', 'tg.tickets = tk.id')
              ->innerJoin(Comment::class, 'c', 'with', 'tk.id = c.ticket')
              ->where('tk.creator = '.$user->getId())
              ->orderBy('tk.id', 'ASC')
              ->getQuery()
	      ->getResult();

         /*  return $this->createQueryBuilder('t')
	      ->select('t')
              //->from(Ticket::class, 't')
              ->innerJoin(Tag::class, 'ta', 'with', 't.id = ta.tickets')
              ->innerJoin(Comment::class, 'c', 'with', 't.id = c.ticket')
              ->where('t.creator = '.$user->getId())
              ->orderBy('t.id', 'ASC')
              ->getQuery()
	      ->getResult(); */
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
