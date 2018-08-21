<?php
namespace MIWeb\Neos\Blog\ViewHelpers\Widget\Controller;

use Neos\Utility\Arrays;
use Neos\FluidAdaptor\Core\Widget\AbstractWidgetController;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Exception\PageNotFoundException;
use Neos\ContentRepository\ViewHelpers\Widget\Controller\PaginateController as NeosPaginateController;


class PaginateController extends NeosPaginateController {
	/**
	 * @var array
	 */
	protected $configuration = [
		'itemsPerPage' => 10,
		'insertAbove' => false,
		'insertBelow' => true,
		'maximumNumberOfLinks' => 99,
		'maximumNumberOfItems' => 0,
		'maximumNumberOfNodes' => 0,
		'reverse' => false
	];

	/**
	 * @var boolean
	 */
	protected $reverse = false;

	/**
	 * @return void
	 */
	protected function initializeAction() {
		parent::intializeAction();

		if(
			isset($this->configuration['reverse']) && $this->configuration['reverse'] &&
			($this->configuration['reverse'] === true || $this->configuration['reverse'] === '1' || $this->configuration['reverse'] === 'true')
		) {
			$this->reverse = true;
		}
	}

	/**
	 * @param integer $currentPage
	 * @return void
	 * @throws PageNotFoundException
	 */
	public function indexAction($currentPage = 1) {
		$this->currentPage = (integer)$currentPage;
		if ($this->currentPage < 1) {
			$this->currentPage = 1;
		} elseif ($this->currentPage > $this->numberOfPages) {
			throw new PageNotFoundException();
		}

		$itemsPerPage = (integer)$this->configuration['itemsPerPage'];
		if ($this->maximumNumberOfNodes > 0 && $this->maximumNumberOfNodes < $itemsPerPage) {
			$itemsPerPage = $this->maximumNumberOfNodes;
		}

		$offset = ($this->currentPage > 1) ? (integer)($itemsPerPage * ($this->currentPage - 1)) : null;
		if ($this->parentNode === null) {
			$nodes = array_slice($this->nodes, $offset, $itemsPerPage);
		} else {
			$nodes = $this->parentNode->getChildNodes($this->nodeTypeFilter, $itemsPerPage, $offset);
		}
		/*if($this->reverse) {
			$nodes = array_reverse($nodes, true);
		}*/

		$this->view->assign('contentArguments', array(
			$this->widgetConfiguration['as'] => $nodes
		));
		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('pagination', $this->buildPagination());
	}
}