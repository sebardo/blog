<?php
namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\BaseActor;
use CoreBundle\Entity\Timestampable;

/**
 * @ORM\Entity(repositoryClass="BlogBundle\Entity\Repository\CommentRepository")
 * @ORM\Table(name="comment")
 */
class Comment extends Timestampable
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\BaseActor")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $actor;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $post;

    /**
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active=false;


    /**
     * Get id
     *
     * @return id
     */
    public function getId()
    {
        return $this->id;
    }

     /**
     * Set actor
     *
     * @param  Actor $actor
     * @return Post
     */
    public function setActor(BaseActor $actor)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return Actor
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set post
     *
     * @param  Post $post
     * @return Comment
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return text
     */
    public function getPost()
    {
        return $this->post;
    }

    
     /**
     * Set active
     *
     * @return Comment
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }
    
    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

}
