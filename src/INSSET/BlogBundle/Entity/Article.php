<?php

namespace INSSET\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="INSSET\BlogBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank(message="Le titre est obligatoire !!!")
     * @Assert\Length(max="255", maxMessage="Le titre ne doit pas comprendre plus de {{ limit }} caractères !!!")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     *
     * @Assert\NotBlank(message="Le corps est obligatoire !!!")
     */
    private $body;

    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="INSSET\BlogBundle\Entity\Comment", mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="INSSET\BlogBundle\Entity\Blogger", inversedBy="articles")
     * @ORM\JoinColumn(name="blogger_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotNull(message="Le blogger est obligatoire !!!")
     * @Assert\Type(type="INSSET\BlogBundle\Entity\Blogger")
     * @Assert\Valid()
     */
    private $blogger;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->published = false;
        $this->date = null;
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Article
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add comment
     *
     * @param \INSSET\BlogBundle\Entity\Comment $comment
     *
     * @return Article
     */
    public function addComment(\INSSET\BlogBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \INSSET\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\INSSET\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set blogger
     *
     * @param \INSSET\BlogBundle\Entity\Blogger $blogger
     *
     * @return Article
     */
    public function setBlogger(\INSSET\BlogBundle\Entity\Blogger $blogger)
    {
        $this->blogger = $blogger;

        return $this;
    }

    /**
     * Get blogger
     *
     * @return \INSSET\BlogBundle\Entity\Blogger
     */
    public function getBlogger()
    {
        return $this->blogger;
    }
}
