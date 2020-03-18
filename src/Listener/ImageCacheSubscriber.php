<?php

namespace App\Listener;

use App\Entity\Property;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber{

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager   = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }

    /**
     * Permet d'éviter de garder les images en cache quand on supprime le bien
     */
    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if(!$entity instanceof UploadedFile){
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
    }


    /**
     * Permet d'éviter de garder les anciennes images en cache quand on update le bien
     */
    public function preUpdate(PreUpdateEventArgs $args){
        $entity = $args->getEntity();

        if(!$entity->getImageFile() instanceof UploadedFile){
            return;
        }
        if($entity->getImageFile() instanceof UploadedFile){
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        }
    }
}