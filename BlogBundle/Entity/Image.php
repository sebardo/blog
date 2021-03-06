<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use CoreBundle\Entity\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Image Entity class
 *
 * @ORM\Table(name="post_image", indexes={@ORM\Index(columns={"path"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image extends Timestampable
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    private $file;


    /**
     * @var string
     */
    private $temp;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255,  nullable=true)
     */
    private $alt;
    
    /**
    * @ORM\ManyToMany(targetEntity="Post", mappedBy="images")
    */
    protected $posts;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get absolute path
     *
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    /**
     * Get web path
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    /**
     * Get upload root dir
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../../web/' . $this->getUploadDir();
    }

    /**
     * Get upload dir
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images/';
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     *
     * @return Image|null
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (null === $this->getFile()) {
            return null;
        }

        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Move file to its final location
     */
    protected function moveFile()
    {
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        $this->setFile(null);
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // do whatever you want to generate a unique name
        $filename = sha1(uniqid(mt_rand(), true));
        $this->path = $filename . '.' . $this->getFile()->guessExtension();
    }

    /**
     * @ORM\PostPersist()
     *
     * @return null|string
     */
    public function newUpload()
    {
        if (null === $this->getFile()) {
            return null;
        }

        $this->moveFile();

        return $this->getUploadRootDir() . $this->path;
    }

    /**
     * @ORM\PostUpdate()
     *
     * @return null|string
     */
    public function updateUpload()
    {
        if (null === $this->getFile()) {
            return null;
        }

        $this->moveFile();

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            @unlink($this->getUploadRootDir() . $this->temp);

            // clear the temp image path
            $this->temp = null;
        }

        return $this->getUploadRootDir() . $this->path;
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            @unlink($file);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->path;
    }
    
    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }
    
    
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Image
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
     * Add post
     *
     * @param Post $post
     *
     * @return Category
     */
    public function addPosts(Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove $post
     *
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

}