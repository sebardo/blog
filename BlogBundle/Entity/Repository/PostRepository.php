<?php

namespace BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CustomerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    
    /**
     * Count the total of rows
     *
     * @return int
     */
    public function countTotal($categoryId=null)
    {
        $qb = $this->getQueryBuilder()
            ->select('COUNT(p)');

        if(!is_null($categoryId)){
            $qb->join('p.categories', 'c')
               ->where('c.id = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }
        
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Find all rows filtered for DataTables
     *
     * @param string $search        The search string
     * @param int    $sortColumn    The column to sort by
     * @param string $sortDirection The direction to sort the column
     *
     * @return \Doctrine\ORM\Query
     */
    public function findPost($search)
    {
        // select
        $qb = $this->getQueryBuilder()
            ->select('p, pTrans')
            ->join('p.translations', 'pTrans')
            ;    
        
        
        // search
        if (!empty($search)) {
            $qb->where('pTrans.title LIKE :search')
                ->orWhere('pTrans.description LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        $qb->orderBy('p.published', 'DESC');

        return $qb->getQuery()->getResult();
    }
    
    /**
     * Find all rows filtered for DataTables
     *
     * @param string $search        The search string
     * @param int    $sortColumn    The column to sort by
     * @param string $sortDirection The direction to sort the column
     *
     * @return \Doctrine\ORM\Query
     */
    public function findAllForDataTables($search, $sortColumn, $sortDirection, $locale='en')
    {
        
        // select
        $qb = $this->getQueryBuilder()
            ->select('p.id, pTrans.title');    

        // join
        $qb->leftJoin('p.translations', 'pTrans');
        
        // search
        if (!empty($search)) {
            $qb->andWhere('p.title LIKE :search')
                ->andWhere('pTrans.locale = :locale ')
                ->setParameter('search', '%'.$search.'%')
                ->setParameter('locale', $locale);
        }else{
//            $qb->andWhere('pTrans.locale = :locale ')
//               ->setParameter('locale', $locale)
//                    ;
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('p.id', $sortDirection);
                break;
            case 1:
                $qb->orderBy('pTrans.title', $sortDirection);
                break;
 
        }

        return $qb->getQuery();
    }


    /**
     * Find last Post with image for a specyfic site
     * 
     * @param type $site
     * @return array
     */
    public function findOneLastPost($site)
    {
        // select
        $qb = $this->getQueryBuilder()
                ->select('p.id, p.title, p.description, p.slug, p.published, i.path as image_path')
                ->leftJoin('p.images', 'i')

                ->andWhere('p.highlighted=1')
                ->setMaxResults(1)
                ->orderBy('p.published', 'DESC');
        return $qb->getQuery()->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function loadPosts($offset=0, $limit=2)
    {
        // select
        $qb = $this->getQueryBuilder()
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->orderBy('p.published', 'DESC');

        return $qb->getQuery()->getResult();
    }
    
    public function loadPostsCategory($offset=0, $limit=2, $category)
    {
        // select
        $qb = $this->getQueryBuilder()
                ->join('p.categories', 'c')
                ->where('c.id = :category')
                ->setParameter('category', $category->getId())
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->orderBy('p.published', 'DESC');

        return $qb->getQuery()->getResult();
    }
    
    public function loadPostsTag($offset=0, $limit=2, $tag)
    {
        // select
        $qb = $this->getQueryBuilder()
                ->join('p.tags', 'c')
                ->where('c.id = :tag')
                ->setParameter('tag', $tag->getId())
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->orderBy('p.published', 'DESC');

        return $qb->getQuery()->getResult();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('BlogBundle:Post')
            ->createQueryBuilder('p');

        return $qb;
    }
}
