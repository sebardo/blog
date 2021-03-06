<?php

namespace BlogBundle\Tests\Controller;

use CoreBundle\Tests\CoreTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @class  CategoryControllerTest
 * @brief Test the  Category entity
 *
 * To run the testcase:
 * @code
 * php vendor/bin/phpunit -v src/BlogBundle/Tests/Controller/CategoryControllerTest.php
 * @endcode
 */
class CategoryControllerTest  extends CoreTest
{
    /**
     * @code
     * php vendor/bin/phpunit -v --filter testCategoryAdmin vendor/sebardo/blog//BlogBundle/Tests/Controller/CategoryControllerTest.php
     * @endcode
     * 
     */
    public function testCategoryAdmin()
    {
        //////////////////////////////////////////////////////////////////////////////
        // Category///////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////
        $uid = rand(999,9999);
        $crawler = $this->createCategoryBlog($uid);
        $entity = $this->getEntity($uid, 'BlogBundle:Category', 'name');

        ///////////////////////////////////////////////////////////////////////////////////////////
        //Show/////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $crawler = $this->client->request('GET', '/admin/post/category/'.$entity->getId(), array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("category '.$uid.'")')->count());
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click edit///////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $link = $crawler
            ->filter('a:contains("Edit")') // find all links with the text "Greet"
            ->eq(0) // select the second link in the list
            ->link()
        ;
        $crawler = $this->client->click($link);// and click it
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit category '.$uid.'")')->count());
        
        //fill form
        $form = $crawler->selectButton('Save')->form();
        $uid = rand(999,9999);
        $form['category[name]'] = 'category '.$uid;
        $form['category[description]'] = 'category description'.$uid;
        $form['category[metaTitle]'] = 'Meta title_'.$uid;
        $form['category[metaDescription]'] = 'Meta description_'.$uid;
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Category has been edited successfully")')->count());

        ///////////////////////////////////////////////////////////////////////////////////////////
        //Show/////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $crawler = $this->client->request('GET', '/admin/post/category/'.$entity->getId(), array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("category '.$uid.'")')->count());
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click delete/////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $form = $crawler->filter('form[id="delete-entity"]')->form();
        $crawler = $this->client->submit($form);// submit the form
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Category has been deleted successfully")')->count());
    }
   
}
