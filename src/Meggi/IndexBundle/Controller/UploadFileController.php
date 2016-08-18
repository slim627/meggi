<?php

namespace Meggi\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\File\File;

class UploadFileController extends Controller
{
    /**
     * @Route("/upload/{slug}", name="upload_file")
     */
    public function uploadAction(Request $request, $slug)
    {
        $file = new File($this->container->getParameter('kernel.root_dir') . '/../web/' . urldecode($slug));

        return new BinaryFileResponse($file, Response::HTTP_OK, [
            'Content-Disposition' => 'attachment;filename="' . $file->getFilename() . '"',
        ]);
    }
}
