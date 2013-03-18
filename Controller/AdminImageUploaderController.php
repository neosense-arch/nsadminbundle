<?php

namespace NS\AdminBundle\Controller;

use NS\AdminBundle\Service\UploaderService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminImageUploaderController extends Controller
{
	/**
	 * @throws \Exception
	 * @return Response
	 */
	public function uploadAction()
	{
		try {
			/** @var $uploaderService UploaderService */
			$uploaderService = $this->get('ns_admin.service.uploader');

			// only one file uploader at once =(
			$files = $this->getRequest()->files->getIterator()->current();

			// only one image per request =(((
			$file = $files[0];

			$name    = $uploaderService->saveUploadedFile($file);
			$preview = $uploaderService->getPreviewUrl($name);

			return new JsonResponse(array(
				'name'    => $name,
				'preview' => $preview,
			));
		}
		catch (\Exception $e) {
			return new JsonResponse(array('error' => $e->getMessage()));
		}
	}
}
