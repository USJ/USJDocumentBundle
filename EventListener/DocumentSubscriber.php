<?php 
namespace MDB\DocumentBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use MDB\DocumentBundle\Event\DocumentEvent;
use MDB\DocumentBundle\Events;
/**
* 
*/
class DocumentSubscriber implements EventSubscriberInterface
{
    public function preDocumentPersist(DocumentEvent $event)
    {
        $document = $event->getDocument();
        $maxVersionNumber = 0;

        foreach($document->getFiles() as $file) {
            if($file->getVersion() > $maxVersionNumber) {
                $maxVersionNumber = $file->getVersion();
            }
            if(is_null($file->getVersion())){
                $file->setVersion($maxVersionNumber+1);
            }
        }

    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::DOCUMENT_PRE_PERSIST => 'preDocumentPersist'
        );
    }
}
