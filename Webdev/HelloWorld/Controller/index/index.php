<?php 
namespace Webdev\HelloWorld\Controller\index;
class Index extends \Magento\Framework\App\Action\Action
{
  //* @var \Magento\Framework\View\Result\PageFactory */
  protected $_resultPageFactory;
  /**
  * @param \Magento\Framework\App\Action\Context $context
  * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
  */
  public function __construct(
     \Magento\Framework\App\Action\Context $context,
     \Magento\Framework\View\Result\PageFactory $resultPageFactory
  )
  {
    $this->_resultPageFactory = $resultPageFactory;
    parent::__construct($context);
  }
    public function execute ()
    {
     echo "Hello World!";

     $resultPage = $this->_reslutPageFactory->create();
     return $resultPage;
    }
}