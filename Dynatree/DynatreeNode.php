<?php
namespace NS\AdminBundle\Dynatree;

class DynatreeNode
{
	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var DynatreeNode[]
	 */
	private $children;

	/**
	 * @param string         $id
	 * @param string         $title
	 * @param string|null    $key
	 * @param DynatreeNode[] $children
	 */
	public function __construct($id, $title, $key = null, $children = array())
	{
		$this->id       = $id;
		$this->title    = $title;
		$this->key      = $key;
		$this->children = $children;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$res = array(
			'id'       => $this->id,
			'title'    => $this->title,
			'key'      => $this->key ?: $this->id,
			'children' => array(),
		);

		foreach ($this->children as $childNode) {
			$res['children'][] = $childNode->toArray();
		}

		return $res;
	}
}