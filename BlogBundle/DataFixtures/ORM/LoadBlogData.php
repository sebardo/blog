<?php
namespace BlogBundle\DataFixtures\ORM;

use CoreBundle\DataFixtures\SqlScriptFixture;
use BlogBundle\Entity\Category;
use CoreBundle\Entity\Actor;
use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Image;
use BlogBundle\Entity\Tag;

class LoadBlogData extends SqlScriptFixture
{
    public function createFixtures()
    {
        
    }
    
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
