<?php

namespace BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;
use BlogBundle\Entity\Category;


/**
 * Class CategoryRepository
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Count the total of rows
     *
     * @return int
     */
    public function countTotal()
    {
        $qb = $this->getQueryBuilder()
            ->select('COUNT(c)');

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
    public function findAllForDataTables($search, $sortColumn, $sortDirection)
    {
        // select
        $qb = $this->getQueryBuilder()
            ->select('c.id, c.name, c.order');

        // where
        $qb->where('c.parentCategory IS NULL ');
            
        // search
        if (!empty($search)) {
            $qb->where('c.name LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('c.id', $sortDirection);
                break;
            case 1:
                $qb->orderBy('c.name', $sortDirection);
                break;
            case 2:
                $qb->orderBy('c.order', $sortDirection);
                break;
        }

        if($sortColumn=='') $qb->orderBy('c.order', 'ASC');
        return $qb->getQuery();
    }

    /**
     * Find all rows with their related categories
     *
     * @return array
     */
    public function findAllWithCategories()
    {
        // select
        $qb = $this->getQueryBuilder()
            ->select('c');

        // sort by family and category id
        $qb->orderBy('c.id', 'asc');

        return $qb->getQuery()
            ->getResult();
    }
    
    /**
     * Find all rows filtered for DataTables
     *
     * @param string   $search        The search string
     * @param int      $sortColumn    The column to sort by
     * @param string   $sortDirection The direction to sort the column
     * @param int|null $categoryId    The category ID
     *
     * @return \Doctrine\ORM\Query
     */
    public function findAllForDataTablesByCategory($search, $sortColumn, $sortDirection, $categoryId)
    {
        $qb = $this->getQueryBuilder();

//        var_dump($categoryId);die();
        // this is a category
        if (is_null($categoryId)) {
            // select
            $qb->select('c.id, c.name, f.id familyId, f.name familyName, c.order, c.active')
                ->leftJoin('c.family', 'f');
        }
        // this is a subcategory
        else {
            // select
            $qb->select('c.id, c.name');

            // where
            $qb->where('c.parentCategory = :category_id')
                ->setParameter('category_id', $categoryId);
        }

        // search
        if (!empty($search)) {
            $qb->andWhere('c.name LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('c.id', $sortDirection);
                break;
            case 1:
                $qb->orderBy('c.name', $sortDirection);
                break;
            case 2:
                $qb->orderBy('c.order', $sortDirection);
                break;
            case 3:
                $qb->orderBy('c.active', $sortDirection);
                break;
        }

        if($sortColumn=='') $qb->orderBy('c.order', 'ASC');
        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('BlogBundle:Category')
            ->createQueryBuilder('c');

        return $qb;
    }
}