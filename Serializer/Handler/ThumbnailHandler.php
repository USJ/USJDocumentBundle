<?php

namespace MDB\DocumentBundle\Serializer\Handler;

use JMS\Serializer\JsonSerializationVisitor;

/**
 * Type("Thumbnail<"my_thumb_filter">")
 */
class ThumbnailHandler {

	protected $requestStack;

	protected $imagineController;

    protected $cacheManager;

	public function __construct($requestStack, $imagineController, $cacheManager)
	{
		$this->requestStack = $requestStack;
		$this->imagineController = $imagineController;
        $this->cacheManager = $cacheManager;
	}

    public function serialize(JsonSerializationVisitor $visitor, $data, array $type)
    {
		if (!$this->getRequest()) {
			return null;
		}

        $filter = $type['params'][0]['name'];
        $regex = $type['params'][1]['name'];

        if (preg_match($regex, $data->getMimeType())) {
            $this->imagineController
                ->filterAction(
                    $this->getRequest(),
                    new \MongoId($data->getId()),      // original image you want to apply a filter to
                    $filter              // filter defined in config.yml
                );

            // string to put directly in the "src" of the tag <img>
            $path = $this->cacheManager->getBrowserPath($data, $filter);
        } else {
            $path = null;
        }

        return $path;
    }

	private function getRequest()
	{
		return $this->requestStack->getCurrentRequest();
	}
}
