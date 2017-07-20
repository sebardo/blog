<?php

namespace BlogBundle\Tests\Controller;

use CoreBundle\Tests\CoreTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @class  TagControllerTest
 * @brief Test the  Tag entity
 *
 * To run the testcase:
 * @code
 * php vendor/bin/phpunit -v vendor/sebardo/blog/BlogBundle/Tests/Controller/TagControllerTest.php
 * @endcode
 */
class TagControllerTest  extends CoreTest
{
    /**
     * @code
     * php vendor/bin/phpunit -v --filter testTagAdmin vendor/sebardo/blog/BlogBundle/Tests/Controller/TagControllerTest.php
     * @endcode
     * 
     */
    public function testTagAdmin()
    {
        //////////////////////////////////////////////////////////////////////////////
        // Tag///////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////
        $uid = rand(999,9999);
        $crawler = $this->createTagBlog($uid);

        ///////////////////////////////////////////////////////////////////////////////////////////
        //Show/////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        
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
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit tag '.$uid.'")')->count());
        
        //fill form
        $form = $crawler->selectButton('Save')->form();
        $uid = rand(999,9999);
        $form['tag[name]'] = 'tag '.$uid;
        $form['tag[description]'] = 'tag description'.$uid;
        $form['tag[metaTitle]'] = 'Meta title_'.$uid;
        $form['tag[metaDescription]'] = 'Meta description_'.$uid;
        $crawler = $this->client->submit($form);// submit the form
        
        //Asserts
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("tag '.$uid.'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Tag has been edited successfully")')->count());

        ///////////////////////////////////////////////////////////////////////////////////////////
        //Click delete/////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////
        $form = $crawler->filter('form[id="delete-entity"]')->form();
        $crawler = $this->client->submit($form);// submit the form
        $this->assertTrue($this->client->getResponse() instanceof RedirectResponse);
        $crawler = $this->client->followRedirect();
        //Asserts
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Tag has been deleted successfully")')->count());
    }

}
