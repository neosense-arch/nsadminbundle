<?php

namespace NS\AdminBundle\Menu\Matcher\Voter;

use Knp\Menu\Matcher\Voter\VoterInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminVoter implements VoterInterface
{
	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Checks whether an item is current.
	 *
	 * If the voter is not able to determine a result,
	 * it should return null to let other voters do the job.
	 *
	 * @param ItemInterface $item
	 *
	 * @return boolean|null
	 */
	public function matchItem(ItemInterface $item)
	{
		$controller = $this->request->attributes->get('_controller');
		return $controller === $item->getExtra('controller');
	}
}
