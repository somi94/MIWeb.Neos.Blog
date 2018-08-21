<?php
namespace MIWeb\Neos\Blog\ViewHelpers\Widget;

use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\Core\Widget\AbstractWidgetViewHelper;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use MIWeb\Neos\Blog\ViewHelpers\Widget\Controller\PaginateController;

class PaginateViewHelper extends AbstractWidgetViewHelper
{
	/**
	 * @Flow\Inject
	 * @var PaginateController
	 */
	protected $controller;

	/**
	 * Render this view helper
	 *
	 * @param string $as Variable name for the result set
	 * @param \Neos\ContentRepository\Domain\Model\NodeInterface $parentNode The parent node of the child nodes to show (instead of specifying the specific node set)
	 * @param array $nodes The specific collection of nodes to use for this paginator (instead of specifying the parentNode)
	 * @param string $nodeTypeFilter A node type (or more complex filter) to filter for in the results
	 * @param array $configuration Additional configuration
	 * @return string
	 */
	public function render($as, NodeInterface $parentNode = null, array $nodes = array(), $nodeTypeFilter = null, array $configuration = array('itemsPerPage' => 10, 'insertAbove' => false, 'insertBelow' => true, 'maximumNumberOfLinks' => 99, 'maximumNumberOfNodes' => 0, 'reverse' => false))
	{
		$response = $this->initiateSubRequest();
		return $response->getContent();
	}
}