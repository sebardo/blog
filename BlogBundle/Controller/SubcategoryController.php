<?php

namespace BlogBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BlogBundle\Entity\Category;
use BlogBundle\Form\SubcategoryType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Subcategory controller.
 *
 * @Route("/admin/post/category/{categoryId}/subcategories")
 */
class SubcategoryController extends Controller
{
    /**
     * Lists all SubCategory entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template("BlogBundle:Subcategory:index.html.twig")
     */
    public function indexAction($categoryId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Category $entity */
        $entity = $em->getRepository('BlogBundle:Category')->find($categoryId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        return array(
            'category' => $entity,
        );
    }

    /**
     * Returns a list of subcategories from a Category entity in JSON format.
     *
     * @param int $categoryId The category id
     *
     * @return JsonResponse
     *
     * @Route("/list.{_format}", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")
     */
    public function listJsonAction($categoryId)
    {
 
        $em = $this->getDoctrine()->getManager();

        /** @var \Kitchenit\AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('BlogBundle:Category'));
        $jsonList->setCategory($categoryId);

        $response = $jsonList->get();

        return new JsonResponse($response);
    }

    /**
     * Creates a new SubCategory entity.
     *
     * @Route("/new")
     * @Method({"GET", "POST"})
     * @Template("BlogBundle:Subcategory:new.html.twig")
     */
    public function newAction(Request $request, Category $categoryId)
    {

        $entity = new Category();
        $form   = $this->createForm('BlogBundle\Form\SubcategoryType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->getParentCategory($categoryId);
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'subcategory.created');
            
            return $this->redirectToRoute('blog_category_show', array('id' => $entity->getId()));
        }
        
        return array(
            'entity' => $entity,
            'category' => $categoryId,
            'form'   => $form->createView(),
        );
    }
}
