<?php

namespace BlogBundle\Tests\Controller;

use CoreBundle\Tests\CoreTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @class  PostControllerTest
 * @brief Test the  Post entity
 *
 * To run the testcase:
 * @code
 * phpunit -v -c app vendor/core/blog/BlogBundle/Tests/Controller/PostControllerTest.php
 * @endcode
 */
class PostControllerTest  extends CoreTest
{
    /**
     * @code
     * phpunit -v --filter testPostAdmin -c app vendor/core/blog/BlogBundle/Tests/Controller/PostControllerTest.php
     * @endcode
     * 
     */
    public function testPostAdmin()
    {
        //////////////////////////////////////////////////////////////////////////////
        // Category///////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////
        $uid = rand(999,9999);
        $crawler = $this->createPost($uid);
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Show/////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        
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
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Editar post '.$uid.'")')->count());
        
        //fill form
        $uid = rand(999,9999);
        $form = $crawler->filter('form[name="post"]')->form();
        $form['post[title]'] = 'post '.$uid;
        $form['post[description]'] = '<p>post <b>description</b> '.$uid. '</p>';
        $form['post[published]'] = date('d').'/'.date('m').'/'.date('Y');
        $form['post[highlighted]']->tick();
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("post '.$uid.'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Se ha editado la publicación satisfactoriamente")')->count());
        
        
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click delete/////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $form = $crawler->filter('form[id="delete-entity"]')->form();
        $crawler = $this->client->submit($form);// submit the form
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
       
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Se ha eliminado la publicación satisfactoriamente")')->count());
    }
   
}
