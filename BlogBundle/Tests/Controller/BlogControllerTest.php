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
 * php vendor/bin/phpunit -v src/BlogBundle/Tests/Controller/BlogControllerTest.php
 * @endcode
 */
class BlogControllerTest  extends CoreTest
{
    /**
     * @code
     * php vendor/bin/phpunit -v --filter testBlog src/BlogBundle/Tests/Controller/BlogControllerTest.php
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
        $post = $manager->getRepository('BlogBundle:PostTranslation')->findOneByTitle('post '.$uid.' (en)');
        //$post = $post->getTranslatable();

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
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Your comment is awaiting verification")')->count());

        //enable comment
        $comment = $manager->getRepository('BlogBundle:Comment')->findOneByComment('test comment '.$uid);
        $crawler = $this->client->request('GET', '/admin/post/comment/'.$comment->getId().'/edit', array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit")')->count());

        //fill form
        $uid = rand(999,9999);
        $form = $crawler->selectButton('Save')->form();
        $form['comment[active]']->tick();
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Comment has been edited successfully")')->count());
        
        //////////////////////////////
        // Check comment on front //
        //////////////////////////////
        $crawler = $this->client->request('GET', '/blog/'.$post->getSlug(), array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        $this->assertGreaterThan(0, $crawler->filter('html:contains("There 1 comments in this post")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("test comment")')->count());
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click delete/////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $crawler = $this->client->request('GET', '/admin/post/comment/'.$comment->getId().'/edit', array(), array(), array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ));
        $form = $crawler->filter('form[id="delete-entity"]')->form();
        $crawler = $this->client->submit($form);// submit the form
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Comment has been deleted successfully")')->count());
    }
   
}
