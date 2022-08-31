<?php 
namespace Webdev\HelloWorld\Block;

class Landingpage extends \Magento\Framework\View\Element\Template
{
    private $_productCollectionFactory;

    public function __construct
    (
     \Magento\Framework\View\Element\Template\Context $context,
     \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
     array $data    
    )
    {
     parent::__construct($context, $data);
     $this->_productCollectionFactory = $productCollectionFactory;   
    }

    public function getProducts()
    {
        $collection = $this -> _productCollectionFactory -> create();

        $collection
        ->addAttributeToSelect ('*')
        ->setOrder ('created_at')
        ->setPageSize(5);

    return $collection;
    }
    
}