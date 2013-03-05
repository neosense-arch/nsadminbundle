<?php
namespace NS\AdminBundle\Dynatree;


class GenericMapper implements MapperInterface
{
	/**
	 * @var string
	 */
	private $idMethod = 'getId';

	/**
	 * @var string
	 */
	private $keyMethod = 'getId';

	/**
	 * @var string
	 */
	private $titleMethod = 'getTitle';

	/**
	 * @var string
	 */
	private $childrenMethod = 'getChildren';

	/**
	 * @param  mixed $rootObject
	 * @throws \Exception
	 * @return DynatreeNode
	 */
	public function getDynatreeNode($rootObject)
	{
		$methods = array($this->idMethod, $this->keyMethod, $this->titleMethod, $this->childrenMethod);

		foreach ($methods as $method) {
			if (!method_exists($rootObject, $method)) {
				throw new \Exception(sprintf("Method '%s::%s' wasn't found", get_class($rootObject), $method));
			}
		}

		$children = array();
		foreach (call_user_func(array($rootObject, $this->childrenMethod)) as $childObject) {
			$children[] = $this->getDynatreeNode($childObject);
		}

		return new DynatreeNode(
			call_user_func(array($rootObject, $this->idMethod)),
			call_user_func(array($rootObject, $this->titleMethod)),
			call_user_func(array($rootObject, $this->keyMethod)),
			$children
		);
	}

	/**
	 * @param string $childrenMethod
	 */
	public function setChildrenMethod($childrenMethod)
	{
		$this->childrenMethod = $childrenMethod;
	}

	/**
	 * @param string $idMethod
	 */
	public function setIdMethod($idMethod)
	{
		$this->idMethod = $idMethod;
	}

	/**
	 * @param string $keyMethod
	 */
	public function setKeyMethod($keyMethod)
	{
		$this->keyMethod = $keyMethod;
	}

	/**
	 * @param string $titleMethod
	 */
	public function setTitleMethod($titleMethod)
	{
		$this->titleMethod = $titleMethod;
	}
}