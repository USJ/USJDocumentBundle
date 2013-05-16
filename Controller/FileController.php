<?php
namespace MDB\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use MDB\DocumentBundle\Document\File;

class FileController extends Controller
{

    /**
     * This could retrieve file for specific id.
     * @Route("/files", name="mdb_document_document_file",options={"expose"= true} )
     */
    public function fileAction(Request $request)
    {
        $qId = $request->query->get('id');
        $qFilename = $request->query->get('filename');
        $download = $request->query->get('d');
        $qFormat = $request->query->get('format');

        $fileRepo = $this->container
            ->get('doctrine_mongodb')
            ->getManager()
            ->getRepository("MDBDocumentBundle:File");

        $file;
        if (isset($qId)) {
            $file = $fileRepo->findOneById($qId);
        } elseif (isset($qFilename)) {
            $file = $fileRepo->findOneByFilename($qFilename);
        }

        if (!isset($file)) {
            throw $this->createNotFoundException(sprintf('File with "%s" could not be found', $qId));
        }

        return $this->container->get('mdb_document.file_response_factory')->createResponse($file, $download, $qFormat);
    }
}
