<?php
namespace BlogBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use BlogBundle\Lib\ShareCounter;
use BlogBundle\Entity\Image;
use stdClass;

class BlogManager 
{
    protected $container = null;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
     protected function getManager()
    {
        return $this->container->get('doctrine')->getManager();
    }
    
    public function blogHistory()
    {
        $locale = $this->container->get('request')->getLocale();
        
        $manager = $this->getManager();
//        if($this->container->getParameter('database_driver') == 'pdo_mysql'){
            $sql= " SELECT YEAR(post.published) as year, MONTH(post.published) as month, t.title, t.slug "
                    . " FROM post as post  "
                    . " LEFT JOIN PostTranslation as t ON t.translatable_id = post.id AND t.locale = '$locale' "
                    . " group by post.published, t.title,  t.slug "
                    . " order by year DESC, month ASC, title ASC "
                    ;
//        }elseif($this->container->getParameter('database_driver') == 'pdo_pgsql'){
//            $sql= " SELECT date_part('year', post.published)as year, date_part('month', post.published) as month, post.title, post.slug "
//                . " FROM post as post  "
//                . " group by post.published, post.title,  post.slug "
//                . " order by year DESC, month ASC, title ASC "
//                ;
//        }
    
        $stmt = $manager->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $returnValues = array();
        foreach ($result as $key => $value) {
            $year = $value['year'];
            if (isset($returnValues[$year][$this->getStringMonth($value['month'])])) {
                $returnValues[$year][$this->getStringMonth($value['month'])][] = $value;
            } else {
                $returnValues[$year][$this->getStringMonth($value['month'])] = array($value);
            }
        }

        return $returnValues;

    }
    
    public function getStringMonth($month)
    {
        return $this->container->get('translator')->trans(date("F", mktime(0, 0, 0, $month)));
    }
    
    public function getPostUrl($entity)
    {
        return $this->container->getParameter('server_base_url'). DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR . $entity->getSlug();
    }
   
    public function searchPosts($search)
    {
        $em = $this->getManager();
        
        $query = ' SELECT p'
                . ' FROM BlogBundle:Post p'
                . ' JOIN p.categories c '
                . " WHERE p.title LIKE '%".$search."%'  "
                . " OR p.description LIKE '%".$search."%' "
                . " OR c.name LIKE '%".$search."%' "
                ;
        $q = $em->createQuery($query);
        $entities = $q->getResult();
        
        return $entities;
    }
    
    public function shareCounter($url=null)
    {

        if(is_null($url))  throw new \Exception('Url should be defined.');
        $shareClass = new ShareCounter($url);

        $returnValue= new stdClass();
        $returnValue->tweet = $shareClass->get_tweets();
        $returnValue->facebook = $shareClass->get_fb();
        $returnValue->linkedin = $shareClass->get_linkedin();
        $returnValue->google = $shareClass->get_plusones();
//        $returnValue->delicious = $shareClass->get_delicious();
//        $returnValue->stumble = $shareClass->get_stumble();
//        $returnValue->pinterest = $shareClass->get_pinterest();
        return $returnValue;
    }
    
    public function addBlogImage($fileName, $post, $baseDir)
    {
        $absolutePath = $baseDir . '/../../../../web/uploads/images/post/'.$post->getId().'/'.$fileName;
        $image = new Image();
        $image->setPath('/uploads/images/post/'.$post->getId().'/'.$fileName);
        $filename =  $baseDir . '/../../Resources/public/images/'.$fileName ;
        $this->createPath($baseDir . '/../../../../web/uploads/images/post/'.$post->getId());
        $this->createPath($baseDir . '/../../../../web/uploads/images/post/'.$post->getId().'/thumbnail');
        copy($filename, $absolutePath );
        $post->addImage($image);
        $this->getManager()->persist($image);
       
        $arr = array();
        if(preg_match('/\.jpeg/', $fileName)) $arr = explode('.jpeg', $fileName);
        if(preg_match('/\.jpg/', $fileName)) $arr = explode('.jpg', $fileName);
        $img_name = $arr[0];
        $this->container->get('core_manager')->resizeImage($absolutePath, $img_name.'_380', 380, 180, __DIR__ . '/../../../../../web/uploads/images/post/'.$post->getId().'/');
        $this->container->get('core_manager')->resizeImage($absolutePath, $img_name.'_260', 260, 123, __DIR__ . '/../../../../../web/uploads/images/post/'.$post->getId().'/');
        $this->container->get('core_manager')->resizeImage($absolutePath, $img_name.'_142', 142, 88, __DIR__ . '/../../../../../web/uploads/images/post/'.$post->getId().'/');
        $this->container->get('core_manager')->resizeImage($absolutePath, $img_name.'_150', 150, 150, __DIR__ . '/../../../../../web/uploads/images/post/'.$post->getId().'/');
    }
     
    public static function createPath($path)
    {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
        $return = self::createPath($prev_path);

        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }
}
