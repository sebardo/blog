<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BlogBundle\Model\CommentFront;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Comment;
use CoreBundle\CoreBundle\Entity\Actor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use A2lix\I18nDoctrineBundle\Annotation\I18nDoctrine;
use DateTime;

/**
* @Route("/blog")
* @Template()
*/
class BlogController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($request->isXmlHttpRequest()) {
            
            $offset = $request->get('offset');
            $limit = $request->get('limit');
            $posts = $em->getRepository('BlogBundle:Post')->loadPostsCategory($offset, $limit, $categoryEntity);
            return $this->render('BlogBundle:Blog/Block:more.post.html.twig', array(
                'posts'    => $posts,
                'position' => $offset
            ));
        } else {
            
            $categories = $em->getRepository('BlogBundle:Category')->findBy(array('parentCategory' => null ), array('order' => 'ASC'));
            $tags = $em->getRepository('BlogBundle:Tag')->findBy(array(), array('name' => 'ASC'));


            return array(
                'categories' => $categories,
                'tags' => $tags
            );
        }
 
        

    }
   
    /**
    * @Route("/share-counter")
    */
    public function shareCounterAction()
    {
    //        $data = $this->get('request')->request->all();
        $url = $this->get('request')->query->get('url');
        //$this->getRequest()->isXmlHttpRequest() &&
        if (isset($url)) {
            $returnValues = $this->get('blog_manager')->shareCounter($url);

            return new JsonResponse($returnValues);
        }

        return JsonResponse();
   }
   
   
    /**
    * @Route("/feed")
    */
    public function feedAction()
    {
        $em = $this->getDoctrine()->getManager();
        $manager = $this->getDoctrine()->getManager();
        $core = $this->getParameter('core');
        

        $dql = "SELECT p, pTrans FROM BlogBundle:Post p JOIN p.translations pTrans ORDER BY p.created DESC";
        $entities = $manager
           ->createQuery($dql)
           ->getResult()
        ;

        $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
        $rssfeed .= '<rss version="2.0">';
        $rssfeed .= '<channel>';
        $rssfeed .= '<title>Kundalini Woman RSS feed</title>';
        $rssfeed .= '<link>http://www.undaliniwoman.com</link>';
        $rssfeed .= '<description>This is an example Kundalini Woman RSS feed</description>';
        $rssfeed .= '<language>en-us</language>';
        $rssfeed .= '<copyright>Copyright (C) 2009 mywebsite.com</copyright>';

        foreach ($entities as $value) {
            $rssfeed .= '<item>';
            $rssfeed .= '<title>' . utf8_decode($value->getTitle()) . '</title>';
            $rssfeed .= '<description>' . utf8_decode($value->getDescription()) . '</description>';
            $rssfeed .= '<link>' . $core['server_base_url'].$this->generateUrl('blog_blog_show', array('slug' => $value->getSlug())) . '</link>';
            $rssfeed .= '<pubDate>' . $value->getCreated()->format("D, d M Y H:i:s O")  . '</pubDate>';
            $rssfeed .= '</item>';
        }

        $rssfeed .= '</channel>';
        $rssfeed .= '</rss>';

        $response = new Response($rssfeed);
        $response->headers->set('Content-Type', 'application/rss+xml');

        $response->setPublic();
        $response->setMaxAge(3600);
        $now = new DateTime();
        $response->setLastModified($now);

        return $response;

   }
   
    /**
     * @Route("/{slug}")
     * @Method("GET")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        
//        $entity = $em->getRepository('BlogBundle:Post')->findOneBy(array('slug' => $slug));
        $qb = $em->getRepository('BlogBundle:Post')->createQueryBuilder('p')
                ->join('p.translations', 't')
                ->where('t.slug = :slug')
                ->setParameter('slug', $slug)
                ->setMaxResults(1);
        $entity = $qb->getQuery()->getSingleResult();
                
        $categories = $em->getRepository('BlogBundle:Category')->findBy(array('parentCategory' => null ), array('order' => 'ASC'));
        $tags = $em->getRepository('BlogBundle:Tag')->findBy(array(), array('name' => 'ASC'));
        $comments = $em->getRepository('BlogBundle:Comment')->findBy(array('post' => $entity->getId(), 'active' => true));
        
        $related = $this->getRelatedPost($entity);
        

        $form = $this->createCommentForm(new CommentFront(), $entity);
        
        return array(
            'post'       => $entity,
            'comments'   => $comments,
            'categories' => $categories,
            'form'       => $form->createView(),
            'tags'       => $tags,
            'related'   =>  $related
        );
        
    }
    
    private function getRelatedPost($entity)
    {
        $categories = $entity->getCategories();
        $em = $this->getDoctrine()->getManager();
        $returnValues = array();
        
        foreach ($categories as $category) {
            $posts = $em->getRepository('BlogBundle:Post')->loadPostsCategory(0, 5, $category);
            foreach ($posts as $post) {
                if($entity->getId() != $post->getId())
                $returnValues[] = $post;
            }
        }
        return $returnValues;
    }

    /**
     * @Route("/category/{category}")
     * @Template("BlogBundle:Blog:list.html.twig")
     */
    public function categoryAction(Request $request, $category)
    {
        
        $em = $this->getDoctrine()->getManager();
        $categoryEntity = $em->getRepository('BlogBundle:Category')->findOneBySlug($category);
        
        if ($request->isXmlHttpRequest()) {
            
            $offset = $request->get('offset');
            $limit = $request->get('limit');
            $posts = $em->getRepository('BlogBundle:Post')->loadPostsCategory($offset, $limit, $categoryEntity);
            return $this->render('BlogBundle:Blog/Block:more.post.html.twig', array(
                'posts'    => $posts,
                'position' => $offset
            ));
        } else {
            $categories = $em->getRepository('BlogBundle:Category')->findBy(array('parentCategory' => null ), array('order' => 'ASC'));
            $tags = $em->getRepository('BlogBundle:Tag')->findBy(array(), array('name' => 'ASC'));
            $posts = $em->getRepository('BlogBundle:Post')->loadPostsCategory(0, 2, $categoryEntity);
            $total_items = $em->getRepository('BlogBundle:Post')->countTotal();

            return array(
                'entity'   => $categoryEntity,
                'posts'      => $posts,
                'categories' => $categories,
                'total_items' => $total_items,
                'tags'       => $tags,
                'load_more_path' => $this->generateUrl('blog_blog_category', array('category'=>$categoryEntity->getSlug()))
            );
        }
        
    }
    
    /**
     * @Route("/tag/{tag}")
     * @Template("BlogBundle:Blog:list.html.twig")
     */
    public function tagAction(Request $request, $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $tagEntity = $em->getRepository('BlogBundle:Tag')->findOneBySlug($tag);
        
        if ($request->isXmlHttpRequest()) {
            
            $offset = $request->get('offset');
            $limit = $request->get('limit');
            $posts = $em->getRepository('BlogBundle:Post')->loadPostsTag($offset, $limit, $tagEntity);
            return $this->render('BlogBundle:Blog/Block:more.post.html.twig', array(
                'posts'    => $posts,
                'position' => $offset
            ));
        } else {
            $tags = $em->getRepository('BlogBundle:Tag')->findBy(array(),array('id' => 'DESC'));
            $categories = $em->getRepository('BlogBundle:Category')->findBy(array(), array('name' => 'ASC'));
            $posts = $em->getRepository('BlogBundle:Post')->loadPostsTag(0, 2, $tagEntity);
            $total_items = $em->getRepository('BlogBundle:Post')->countTotal();

            return array(
                'entity'   => $tagEntity,
                'posts'      => $posts,
                'categories' => $categories,
                'total_items' => $total_items,
                'tags'       => $tags,
                'load_more_path' => $this->generateUrl('blog_blog_tag', array('tag'=>$tagEntity->getSlug()))
            );
        }
    }
    
    /**
     * @Route("/comment/")
     * @Method("POST")
     * @Template()
     */
    public function commentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();

        try {
            $comment = $this->resolve($comment, $request);
            $this->get('session')->getFlashBag()->add('warning', 'comment.wait.for.validate');
        } catch (\Exception $exception) {
            // Write flash message
            print_r($exception->getMessage());die();
        }

        //save
        $em->persist($comment);
        $em->flush();
       
        return $this->redirect($this->get('core_manager')->getRefererPath($request));
    }
    /**
     * Creates a form to create a Post entity.
     *
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCommentForm(CommentFront $model, $entity)
    {
        $form = $this->createForm('BlogBundle\Form\CommentFrontType', $model, array(
            'action' => $this->generateUrl('blog_blog_comment', array('post' => $entity->getId())),
            'method' => 'POST',
            'attr' => array('id' => 'comment-form','class' => 'comment-form')
        ));

        return $form;
    }
    
    /**
     * @param Comment $comment
     * @param Request $request
     *
     * @throws ItemResolvingException
     * @return CartItemInterface|void
     */
    public function resolve(Comment $comment, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postId = $request->query->get('post');
        $itemForm = $request->get('comment_front');

        if( $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ){
            $comment->setActor($this->container->get('security.token_storage')->getToken()->getUser());
        }else{
            //register user
            $actor = $this->createActor($itemForm);
            $comment->setActor($actor);
        }
        
        $postRepository = $em->getRepository('BlogBundle:Post');
        if (!$postId || !$post = $postRepository->find($postId)) {
            // no product id given, or product not found
            throw new \Exception('Requested post was not found');
        }

        // assign the product and quantity to the item
        $comment->setPost($post);
        $comment->setComment($itemForm['comment']);
        
        return $comment;
    }
    
    public function createActor($itemForm) 
    {
        $em = $this->getDoctrine()->getManager();
        $uniqid = uniqid();
        $actor = new Actor();
        $actor->setNewsletter(false);
        $actor->setUsername($uniqid);
        $actor->setName($itemForm['name']);
        $actor->setEmail($itemForm['email']);
        
        //Encode pass
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($actor);
        $password = $encoder->encodePassword($uniqid, $actor->getSalt());
        $actor->setPassword($password);
        
        
        //Add ROLE
        $role = $em->getRepository('CoreBundle:Role')->findOneBy(array('role' => 'ROLE_USER'));
        $actor->addRole($role);

        $em->persist($actor);
        $em->flush();
        
        $this->get('core.mailer')->sendRegisteredEmailMessage($actor);
        
        return $actor;
    }
 
    /**
     * @Route("/search/", name="blog_search")
     * @Route("/search/{search}")
     * @Template("BlogBundle:Blog:list.html.twig")
     */
    public function searchAction(Request $request, $search = null) {

        if ($request->getMethod() == 'POST') {
            $search = $request->request->get('search');
        }
       
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->findPost($search);
        
        return array(
            'search'     => $search,
            'posts'      => $posts,
        );
    }
   
}
