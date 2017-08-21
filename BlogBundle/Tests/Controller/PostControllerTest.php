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
 * php vendor/bin/phpunit -v src/BlogBundle/Tests/Controller/PostControllerTest.php
 * @endcode
 */
class PostControllerTest  extends CoreTest
{
    /**
     * @code
     * php vendor/bin/phpunit -v --filter testPostAdmin src/BlogBundle/Tests/Controller/PostControllerTest.php
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
        $entity = $this->getEntity($uid, 'BlogBundle:Post', 'title');
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Show/////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $crawler = $this->client->request('GET', '/admin/post/'.$entity->getId(), array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("post '.$uid.'")')->count());
        
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
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit post '.$uid.'")')->count());
        
        //fill form
        $uid = rand(999,9999);
        $form = $crawler->filter('form[name="post"]')->form();
        
        $locales = $this->client->getContainer()->get('core_manager')->getLocales();
        foreach ($locales as $locale) {
            $form['post[translations]['.$locale.'][title]'] = 'post '.$uid.' ('.$locale.')';
            $form['post[translations]['.$locale.'][shortDescription]'] = 'post shot description'.$uid. ' ('.$locale.')</p>';
            $form['post[translations]['.$locale.'][description]'] = '<p>post <b>description</b> '.$uid. ' ('.$locale.')</p>';
            $form['post[translations]['.$locale.'][metaTitle]'] = 'meta title  ('.$locale.')'.$uid;
            $form['post[translations]['.$locale.'][metaDescription]'] = 'meta description ('.$locale.')'.$uid;
        }
        
        $form['post[published]'] = date('d').'/'.date('m').'/'.date('Y');
        $form['post[highlighted]']->tick();
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Post has been edited successfully")')->count());
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Show/////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $crawler = $this->client->request('GET', '/admin/post/'.$entity->getId(), array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("post '.$uid.'")')->count());
        
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click delete/////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $form = $crawler->filter('form[id="delete-entity"]')->form();
        $crawler = $this->client->submit($form);// submit the form
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
       
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Post has been deleted successfully")')->count());
    }
   
}
