<?php
/**
 * Created by PhpStorm.
 * User: mattisbeguin
 * Date: 03/01/2018
 * Time: 18:19
 */

namespace INSSET\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Blogger
 *
 * @ORM\Table(name="blogger")
 * @ORM\Entity(repositoryClass="INSSET\BlogBundle\Repository\BloggerRepository")
 */
class Blogger extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="INSSET\BlogBundle\Entity\Article", mappedBy="blogger")
     */
    private $articles;

    /**
     * Blogger constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->articles = new ArrayCollection();
    }

    /**
     * Add article
     *
     * @param \INSSET\BlogBundle\Entity\Article $article
     *
     * @return Blogger
     */
    public function addArticle(\INSSET\BlogBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \INSSET\BlogBundle\Entity\Article $article
     */
    public function removeArticle(\INSSET\BlogBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
