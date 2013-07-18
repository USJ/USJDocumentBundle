<?php 

namespace MDB\DocumentBundle\Serializer\Handler;

use JMS\Serializer\JsonSerializationVisitor;

/**
 * Type("Thumbnail<"my_thumb_filter">")
 */
class ThumbnailHandler {

	protected $request;

	protected $imagineController;

    protected $cacheManager;

	public function __construct($request, $imagineController, $cacheManager)
	{
		$this->request = $request;
		$this->imagineController = $imagineController;
        $this->cacheManager = $cacheManager;
	}

	public function serialize(JsonSerializationVisitor $visitor, $data, array $type)
	{
		$filter = $type['params'][0]['name'];

		$imagemanagerResponse = $this->imagineController
                ->filterAction(
                    $this->request,
                    $data,      // original image you want to apply a filter to
                    $filter              // filter defined in config.yml
        );

        // string to put directly in the "src" of the tag <img>
        $srcPath = $this->cacheManager->getBrowserPath($data, $filter);
        return $srcPath;
	}
}