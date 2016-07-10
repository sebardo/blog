<?php

namespace BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;
use BlogBundle\Entity\Category;


/**
 * Class TagRepository
 */
class TagRepository extends EntityRepository
{
    /**
     * Count the total of rows
     *
     * @return int
     */
    public function countTotal()
    {
        $qb = $this->getQueryBuilder()
            ->select('COUNT(t)');

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
            ->select('t.id, t.name');

        // search
        if (!empty($search)) {
            $qb->where('t.name LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('t.id', $sortDirection);
                break;
            case 1:
                $qb->orderBy('t.name', $sortDirection);
                break;
        }

        return $qb->getQuery();
    }

    /**
     * Find all rows with their related tags
     *
     * @return array
     */
    public function findAllWithCategories()
    {
        // select
        $qb = $this->getQueryBuilder()
            ->select('t');

        // sort by family and tag id
        $qb->orderBy('t.id', 'asc');

        return $qb->getQuery()
            ->getResult();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('BlogBundle:Tag')
            ->createQueryBuilder('t');

        return $qb;
    }
}