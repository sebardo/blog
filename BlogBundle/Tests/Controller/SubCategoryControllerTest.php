<?php

namespace BlogBundle\Tests\Controller;

use CoreBundle\Tests\CoreTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @class  SubCategoryControllerTest
 * @brief Test the  Category entity
 *
 * To run the testcase:
 * @code
 * phpunit -v -c app vendor/core/blog/BlogBundle/Tests/Controller/SubCategoryControllerTest.php
 * @endcode
 */
class SubCategoryControllerTest  extends CoreTest
{
    /**
     * @code
     * phpunit -v --filter testCategoryAdmin -c app vendor/core/blog/Bundle/BlogBundle/Tests/Controller/SubCategoryControllerTest.php
     * @endcode
     * 
     */
    public function testCategoryAdmin()
    {
        $container = $this->client->getContainer();
        $manager = $container->get('doctrine')->getManager();
        
        //////////////////////////////////////////////////////////////////////////////
        // Category///////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////
        $uid = rand(999,9999);
        $crawler = $this->createCategoryBlog($uid);
        $category = $manager->getRepository('BlogBundle:Category')->findOneByName('category '.$uid);
        $crawler = $this->client->request('GET', '/admin/post/category/'.$category->getId().'/subcategories/', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        ///////////////////////////////////////////////////////////////////////////////////////////
        // New ////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $link = $crawler
            ->filter('a:contains("Añadir nueva")') // find all links with the text "Greet"
            ->eq(0) // select the second link in the list
            ->link()
        ;
        $crawler = $this->client->click($link);// and click it
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Nueva subcategoría")')->count());
        
        //fill form
        $form = $crawler->selectButton('Guardar')->form();
        $uid = rand(999,9999);
        $form['subcategory[name]'] = 'subcategory '.$uid;
        $form['subcategory[description]'] = 'subcategory description'.$uid;
        $form['subcategory[metaTitle]'] = 'Meta title_'.$uid;
        $form['subcategory[metaDescription]'] = 'Meta description_'.$uid;
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("subcategory '.$uid.'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Se ha creado la subcategoría satisfactoriamente")')->count());

        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click edit///////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $link = $crawler
            ->filter('a:contains("Editar")') // find all links with the text "Greet"
            ->eq(0) // select the second link in the list
            ->link()
        ;
        $crawler = $this->client->click($link);// and click it
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Editar subcategory '.$uid.'")')->count());
        
        //fill form
        $form = $crawler->selectButton('Guardar')->form();
        $uid = rand(999,9999);
        $form['category[name]'] = 'subcategory '.$uid;
        $form['category[description]'] = 'subcategory description'.$uid;
        $form['category[metaTitle]'] = 'Meta title_'.$uid;
        $form['category[metaDescription]'] = 'Meta description_'.$uid;
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("subcategory '.$uid.'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Se ha editado la categoría satisfactoriamente")')->count());

        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click delete/////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $form = $crawler->filter('form[id="delete-entity"]')->form();
        $crawler = $this->client->submit($form);// submit the form
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Se ha eliminado la categoría satisfactoriamente")')->count());
        
    }
  
}
