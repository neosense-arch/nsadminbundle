<?php

namespace NS\AdminBundle\Service;

use Avalanche\Bundle\ImagineBundle\Templating\ImagineExtension;
use Imagine\Image\ImagineInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploaderService
{
	const UPLOAD_DIR = 'upload/j';

	/**
	 * @var ImagineExtension
	 */
	private $imagineExtension;

	/**
	 * @param ImagineExtension $imagineExtension
	 */
	public function __construct(ImagineExtension $imagineExtension)
	{
		$this->imagineExtension = $imagineExtension;
	}

	/**
	 * @param UploadedFile $file
	 * @return string
	 */
	public function saveUploadedFile(UploadedFile $file)
	{
		$dir = $this->getUploadDir();
		$name = $this->generateUniqueFilename($file);

		$file->move($dir, $name);

		return $name;
	}

	/**
	 * @param $name
	 * @return string
	 */
	public function getPreviewUrl($name)
	{
		return $this->imagineExtension->applyFilter($this->getUploadDir() . '/' . $name, 'ns_admin_preview');
	}

	/**
	 * @param UploadedFile $file
	 * @return string
	 */
	private function generateUniqueFilename(UploadedFile $file)
	{
		return uniqid('', true) . '.' . $file->getClientOriginalExtension();
	}

	/**
	 * @return string
	 */
	private function getUploadDir()
	{
		return self::UPLOAD_DIR;
	}
}
