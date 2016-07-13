<?php

namespace BlogBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_SimpleFunction;
use BlogBundle\Entity\Category;
use BlogBundle\Entity\Tag;

/**
 * Class BlogExtension
 */
class BlogExtension extends \Twig_Extension
{

    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('blog_search', array($this, 'blogSearch')),
            new Twig_SimpleFunction('blog_tags', array($this, 'blogTags')),
            new Twig_SimpleFunction('blog_feed', array($this, 'blogFeed')),
            new Twig_SimpleFunction('blog_social', array($this, 'blogSocial')),
            new Twig_SimpleFunction('blog_history', array($this, 'blogHistory')),
            new Twig_SimpleFunction('countArrayValues', array($this, 'countArrayValues')),
            new Twig_SimpleFunction('shareUrlBlog', array($this, 'shareUrlBlog'), array('is_safe' => array('html'))),
            new Twig_SimpleFunction('countPost', array($this, 'countPost')),
            new Twig_SimpleFunction('get_carousel_items_blog', array($this, 'getCarouselItemsBlog')),
            new Twig_SimpleFunction('get_posts', array($this, 'getPosts')),
            new Twig_SimpleFunction('get_tags', array($this, 'getTags')),
            new Twig_SimpleFunction('get_blog_categories', array($this, 'getCategories')),
        );
    }
    
  
    public function blogSearch()
    {
        $twig = $this->container->get('twig');

        $content = $twig->render('BlogBundle:Blog/Block:_search.html.twig', array());

        return $content;
    }
    
    public function blogTags()
    {

        $twig = $this->container->get('twig');

        $content = $twig->render('BlogBundle:Blog/Block:_tags.html.twig', array());

        return $content;
    }
    
    public function blogFeed()
    {
        $twig = $this->container->get('twig');

        $content = $twig->render('BlogBundle:Blog/Block:_feed.html.twig', array());

        return $content;
    }
    
    public function blogSocial()
    {
        $twig = $this->container->get('twig');

        $content = $twig->render('BlogBundle:Blog/Block:_social.html.twig', array());

        return $content;
    }
    
    public function blogHistory()
    {
        $blogManager = $this->container->get('blog_manager');
        $history = $blogManager->blogHistory();

        $twig = $this->container->get('twig');

        $content = $twig->render('BlogBundle:Blog/Block:_blog.history.html.twig', array(
            'history' => $history
            ));

        return $content;
    }
    
     public function countArrayValues($array)
    {
        $count = 0;
        
        foreach($array as $item) {  // go thought the first level
            foreach($item as $value) {  // go through the second level
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
    * Returns the part of a feedID
    *
    * @param string $feedID  ID of the feed to load
    */
    public function shareUrlBlog($url, $big=false, $title)
    {
        if (!is_numeric(strpos($url, 'http'))) {
            $core = $this->container->getParameter('core');
            $url = $core['server_base_url'] . $url;
        }
        $text = $title;
        $tweetUrl =  'https://twitter.com/share?url='.$url.'&counturl='.$url.'&text='.$text;
        $faceUrl = 'http://www.facebook.com/sharer/sharer.php?u='.$url.'&text='.$text;
        $googleUrl = 'https://plus.google.com/share?url='.$url;
        $linkedUrl = 'https://www.linkedin.com/shareArticle?summary=&ro=false&title='.$text.'&mini=true&url='.$url.'&source=';

        $twig = $this->container->get('twig');
        $content = $twig->render('BlogBundle:Blog/Block:_share.html.twig', array(
            "tweetUrl" => $tweetUrl,
            "faceUrl" => $faceUrl,
            "googleUrl" => $googleUrl,
            "linkedUrl" => $linkedUrl,
            "id" => uniqid(),
            "big" => $big
        ));

        return $content;
    }
    
    /**
    * Returns integer counter
    *
    * @param object $actor  
    */
    public function countPost($actor)
    {
        $em = $this->container->get('doctrine')->getManager();
        $entities = $em->getRepository('BlogBundle:Post')->findBy(array('actor' => $actor));
        
        return count($entities);
    }
    
        
    /**
     * Returns all items from blog category.
     *
     * @return ArrayCollection
     */
    public function getCarouselItemsBlog($entity)
    {
        $em = $this->container->get('doctrine')->getManager();
        
        if($entity instanceof Category){
            $qb = $em->getRepository('BlogBundle:Post')
                ->createQueryBuilder('p')
                ->join('p.categories', 'c')
                ->where('c.id = :category')
                ->andWhere('p.highlighted = true')
                ->setParameter('category', $entity->getId())
                ->setMaxResults(3)
                ->orderBy('p.published', 'DESC');
        }elseif($entity instanceof Tag){
            $qb = $em->getRepository('BlogBundle:Post')
                ->createQueryBuilder('p')
                ->join('p.tags', 't')
                ->where('t.id = :tag')
                ->andWhere('p.highlighted = true')
                ->setParameter('tag', $entity->getId())
                ->setMaxResults(3)
                ->orderBy('p.published', 'DESC');
        }
        
        $entities = $qb->getQuery()->getResult();
        return $entities;
    }
    
    /**
    * Returns array posts
    *
    * @param object $post  
    */
    public function getPosts($limit=null, $offset=null)
    {
        $em = $this->container->get('doctrine')->getManager();
        if(is_null($limit) && is_null($offset)){
            $entities = $em->getRepository('BlogBundle:Post')->findBy(array(),array('published' =>  'DESC'));
        }elseif(is_null($limit) && !is_null($offset)){
            $entities = $em->getRepository('BlogBundle:Post')->findBy(array(),array('published' =>  'DESC'), $limit);
        }elseif(!is_null($limit) && !is_null($offset)){
            $entities = $em->getRepository('BlogBundle:Post')->findBy(array(),array('published' =>  'DESC'), $limit, $offset);
        }
        
        return $entities;
    }
    
    /**
    * Returns array tags
    *
    * @param object $tag  
    */
    public function getCategories()
    {
        $em = $this->container->get('doctrine')->getManager();
        $entities = $em->getRepository('BlogBundle:Category')->findBy(array('active' => true),array('name' =>  'ASC'));
        
        return $entities;
    }
    
    /**
    * Returns array tags
    *
    * @param object $tag  
    */
    public function getTags()
    {
        $em = $this->container->get('doctrine')->getManager();
        $entities = $em->getRepository('BlogBundle:Tag')->findBy(array(),array('name' =>  'ASC'));
        
        return $entities;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'blog_extension';
    }
}