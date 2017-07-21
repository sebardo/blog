<?php
namespace BlogBundle\EventListener;

use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\EventSubscriber;

class DynamicRelationSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    protected $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getReflectionClass();
                        
        if ($metadata->getName() == 'BlogBundle\Entity\Post') {
            $metadata->mapManyToOne(array(
                'targetEntity' => $this->mapping['baseactor']['entity'],
                'fieldName' => 'actor',
                'joinColumns' => array(array('name' => 'actor_id')),
                'inversedBy' => 'posts',
            ));
        }elseif ($metadata->getName() == $this->mapping['baseactor']['entity']) {
            $metadata->mapOneToMany(array(
                'targetEntity' => $class->getName(),
                'fieldName' => 'posts',
                'mappedBy' => 'actor',
            ));
        }
    }
}
                    