<?php
namespace MIWeb\Neos\Blog\Controller;

//use RobertLemke\Akismet\Service;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeTemplate;

/**
 * Comments controller for the Blog package
 */
class CommentController extends ActionController
{
//    /**
//     * @Flow\Inject
//     * @var Service
//     */
//    protected $akismetService;

    /**
     * Initialize the Akismet service
     *
     * @return void
     */
    protected function initializeAction()
    {
//        $this->akismetService->setCurrentRequest($this->request->getHttpRequest());
    }
    /**
     * Creates a new comment
     *
     * @param NodeInterface $postNode The post node which will contain the new comment
     * @param NodeTemplate<RobertLemke.Plugin.Blog:Comment> $newComment
     * @return string
     */
    public function createAction(NodeInterface $postNode, NodeTemplate $newComment)
    {
        # Workaround until we can validate node templates properly:
        if (strlen($newComment->getProperty('author')) < 2) {
            $this->throwStatus(400, 'Your comment was NOT created - please specify your name.');
        }
        if (filter_var($newComment->getProperty('emailAddress'), FILTER_VALIDATE_EMAIL) === false) {
            $this->throwStatus(400, 'Your comment was NOT created - you must specify a valid email address.');
        }
        if (strlen($newComment->getProperty('text')) < 5) {
            $this->throwStatus(400, 'Your comment was NOT created - it was too short.');
        }
        $newComment->setProperty('text', filter_var($newComment->getProperty('text'), FILTER_SANITIZE_STRIPPED));
        $newComment->setProperty('author', filter_var($newComment->getProperty('author'), FILTER_SANITIZE_STRIPPED));
        $newComment->setProperty('emailAddress', filter_var($newComment->getProperty('emailAddress'), FILTER_SANITIZE_STRIPPED));
        $commentNode = $postNode->getNode('comments')->createNodeFromTemplate($newComment, uniqid('comment-'));
        $commentNode->setProperty('spam', false);
        $commentNode->setProperty('datePublished', new \DateTime());
//        if ($this->akismetService->isCommentSpam('', $commentNode->getProperty('text'), 'comment', $commentNode->getProperty('author'), $commentNode->getProperty('emailAddress'))) {
//            $commentNode->setProperty('spam', true);
//        }
        $this->emitCommentCreated($commentNode, $postNode);
        $this->response->setStatus(201);
        return 'Thank you for your comment!';
    }
    
    /**
     * Signal which informs about a newly created comment
     *
     * @param NodeInterface $commentNode The comment node
     * @param NodeInterface $postNode The post node
     * @return void
     * @Flow\Signal
     */
    protected function emitCommentCreated(NodeInterface $commentNode, NodeInterface $postNode)
    {
    }
}
