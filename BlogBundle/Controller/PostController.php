<?php

namespace BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BlogBundle\Entity\Post;
use BlogBundle\Form\PostType;
use BlogBundle\CommonBundle\Exception\ExceptionBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use A2lix\I18nDoctrineBundle\Annotation\I18nDoctrine;

/**
 * Post controller.
 *
 * @Route("/admin/post")
 */
class PostController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    
    
    /**
     * Returns a list of Classes entities in JSON format.
     *
     * @return JsonResponse
     *
     * @Route("/postlist.{_format}", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")
     */
    public function listJsonAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('BlogBundle:Post'));
        $response = $jsonList->get();

        return new JsonResponse($response);
    }
    
    /**
     * Creates a new Post entity.
     *
     * @Route("/new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('BlogBundle\Form\PostType', $post, array('translator' => $this->get('translator') ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $post->setActor($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'post.created');
            
            return $this->redirectToRoute('blog_post_index');
        }

        return array(
            'category' => $post,
            'form' => $form->createView(),
        );
    }
    
    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{id}")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return array(
            'entity' => $post,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @I18nDoctrine
     */
    public function editAction(Request $request, Post $post)
    {
        if(is_object($post->getPublished()) && $post->getPublished()->format('dmY') == '3011-0001'){
            $post->setPublished(null);
        }
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('BlogBundle\Form\PostType', $post, array('translator' => $this->get('translator') ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'post.edited');
            
            return $this->redirectToRoute('blog_post_index');
        }

        return array(
            'entity' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'post.deleted');
        }

        return $this->redirectToRoute('blog_post_index');
    }
    
    /**
     * Creates a form to delete a Post entity.
     *
     * @param Post $post The Post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_category_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * @Route("/post-remove" , name="admin_post_remove")
     * @Method("POST")
     */
    public function removePostAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $data = $this->get('request')->request->all();

            $manager = $this->getDoctrine()->getManager();

            if (isset($data['id'])) {
                 $entity = $manager->getRepository('BlogBundle:Post')->findOneBy(array(
                        'id' => $data['id']
                        ));
                $manager->remove($entity);
                $manager->flush();
            } else {
                throw new ExceptionBase('Index "id" not defined.');
            }

            $returnResponse = new stdClass();
            $returnResponse->status = 'success';

            return new JsonResponse($returnResponse);
        }
    }
   
    
    /**
     * @Route("/post-remove-tag" , name="admin_post_remove_tag")
     * @Method("POST")
     */
    public function removeTagPostAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $data = $this->get('request')->request->all();

            $manager = $this->getDoctrine()->getManager();

            if (isset($data['post_id']) && isset($data['tag_id'])) {

                $post = $manager->getRepository('CommonBundle:Node')->findOneBy(array(
                        'id' => $data['post_id']
                        ));
                $tag = $manager->getRepository('MediaBundle:Tag')->findOneBy(array(
                        'id' => $data['tag_id']
                        ));
                $post->removeTag($tag);
                $manager->remove($tag);
                $manager->flush();
                
            } else {
                throw new ExceptionBase('Index "post_id" or "tag_id" not defined.');
            }

            $returnResponse = new stdClass();
            $returnResponse->status = 'success';

            return new JsonResponse($returnResponse);
        }
    }

    /**
     * Manages a product image
     *
     * @return array
     *
     * @Route("/{type}/{id}-{slug}/{route}/{entity}/{image_entity}/image")
     */
    public function manageImage()
    {
        $this->get('upload_handler');

        return new Response();
    }
    
}
