<?php

namespace BlogBundle\Tests\Controller;

use CoreBundle\Tests\CoreTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @class  BlogControllerTest
 * @brief Test the  Post and Comment entities
 *
 * To run the testcase:
 * @code
 * phpunit -v -c app vendor/sebardo/blog/BlogBundle/Tests/Controller/BlogControllerTest.php
 * @endcode
 */
class BlogControllerTest  extends CoreTest
{
    /**
     * @code
     * phpunit -v --filter testBlog -c app vendor/sebardo/blog/BlogBundle/Tests/Controller/BlogControllerTest.php
     * @endcode
     * 
     */
    public function testBlog()
    {
        //////////////////////////////////////////////////////////////////////////////
        // Category///////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////
        $uid = rand(999,9999);
        $crawler = $this->createPost($uid);
        
        $container = $this->client->getContainer();
        $manager = $container->get('doctrine')->getManager();
        $post = $manager->getRepository('BlogBundle:Post')->findOneByTitle('post '.$uid);
        
        $crawler = $this->client->request('GET', '/blog/'.$post->getSlug(), array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("'.$post->getTitle().'")')->count());
        
        ////////////////////////////////////////////////////////////////////////////////////
        //Comment  /////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////
        
        
        //fill form
        $uid = rand(999,9999);
        $form = $crawler->filter('form[name="comment_front"]')->form();
        $form['comment_front[name]'] = 'user_'.$uid;
        $form['comment_front[email]'] = '<p>email+'.$uid. '@email.com</p>';
        $form['comment_front[comment]'] = 'test comment '.$uid;
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Su comentario esta esperando validación.")')->count());

        //enable comment
        $comment = $manager->getRepository('BlogBundle:Comment')->findOneByComment('test comment '.$uid);
        $crawler = $this->client->request('GET', '/admin/post/comment/'.$comment->getId().'/edit', array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Editar Comentario")')->count());
        
        

        //fill form
        $uid = rand(999,9999);
        $form = $crawler->selectButton('Guardar')->form();
        $form['comment[isActive]']->tick();
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Se ha editado el comentario satisfactoriamente")')->count());
        
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click delete/////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $form = $crawler->filter('form[id="delete-entity"]')->form();
        $crawler = $this->client->submit($form);// submit the form
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Se ha eliminado el comentario satisfactoriamente")')->count());
    }
   
}
