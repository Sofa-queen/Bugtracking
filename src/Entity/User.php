<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Projects", mappedBy="author")
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="creator")
     */
    private $ticket;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="addressee")
     */
    private $ticket_addressee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="creator")
     */
    private $comments;

    public function __construct()
    {
        $this->project = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        //return $this;
    }
    
    /**
     * @see UserInterface
     */
    public function getRoles() : array
    {
       $roles = $this -> roles;
       $roles [] = 'ROLE_USER' ;
       $roles [] = 'ROLE_USER_' . $this->id;
       return array_unique ( $roles );
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

     public function eraseCredentials() { }

     /**
      * @return Collection|Projects[]
      */
     public function getProject(): Collection
     {
         return $this->project;
     }

     public function addProject(Projects $project): self
     {
         if (!$this->project->contains($project)) {
             $this->project[] = $project;
             $project->setAuthor($this);
         }

         return $this;
     }

     public function removeProject(Projects $project): self
     {
         if ($this->project->contains($project)) {
             $this->project->removeElement($project);
             // set the owning side to null (unless already changed)
             if ($project->getAuthor() === $this) {
                 $project->setAuthor(null);
             }
         }

         return $this;
     }

     /**
      * @return Collection|Ticket[]
      */
     public function getTicket(): Collection
     {
         return $this->ticket;
     }

     public function addTicket(Ticket $ticket): self
     {
         if (!$this->ticket->contains($ticket)) {
             $this->ticket[] = $ticket;
             $ticket->setCreator($this);
         }

         return $this;
     }

     public function removeTicket(Ticket $ticket): self
     {
         if ($this->ticket->contains($ticket)) {
             $this->ticket->removeElement($ticket);
             // set the owning side to null (unless already changed)
             if ($ticket->getCreator() === $this) {
                 $ticket->setCreator(null);
             }
         }

         return $this;
     }

     /**
      * @return Collection|Ticket[]
      */
     public function getTicketAddressee(): Collection
     {
         return $this->ticket_addressee;
     }

     public function addTicketAddressee(Ticket $ticketAddressee): self
     {
         if (!$this->ticket_addressee->contains($ticketAddressee)) {
             $this->ticket_addressee[] = $ticketAddressee;
             $ticketAddressee->setAddressee($this);
         }

         return $this;
     }

     public function removeTicketAddressee(Ticket $ticketAddressee): self
     {
         if ($this->ticket_addressee->contains($ticketAddressee)) {
             $this->ticket_addressee->removeElement($ticketAddressee);
             // set the owning side to null (unless already changed)
             if ($ticketAddressee->getAddressee() === $this) {
                 $ticketAddressee->setAddressee(null);
             }
         }

         return $this;
     }

     /**
      * @return Collection|Comment[]
      */
     public function getComments(): Collection
     {
         return $this->comments;
     }

     public function addComment(Comment $comment): self
     {
         if (!$this->comments->contains($comment)) {
             $this->comments[] = $comment;
             $comment->setCreator($this);
         }

         return $this;
     }

     public function removeComment(Comment $comment): self
     {
         if ($this->comments->contains($comment)) {
             $this->comments->removeElement($comment);
             // set the owning side to null (unless already changed)
             if ($comment->getCreator() === $this) {
                 $comment->setCreator(null);
             }
         }

         return $this;
     }
}
