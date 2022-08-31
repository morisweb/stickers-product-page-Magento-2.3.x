<?php 
namespace Webdev\Stickers\Model\Config\Backend;
use Magento\Framework\Option\ArrayInterface;

class Categorylist implements ArrayInterface
{
    private $_categoryList;
    private $_categoryMatrix;
    protected $_categoryFactory;
    protected $_categoryCollectionFactory;

    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    )
    {
        $this ->_categoryFactory = $categoryFactory;
        $this ->_categoryCollectionFactory = $categoryCollectionFactory;
        $this ->_initializeCategoryMatrix();
    }
    public function toOptionArray ()
    {
        $arr = $this->_toArray();
        $ret = [];

        foreach ($arr as $key => $value)
        {
            $ret[]=[
                'value' => $key,
                'label' => $value
            ];
        }
        return $ret;
    }
    private function _toArray()
    {
        $this ->_categoryList = array();
        $this ->_categoryList[''] = __('-- NO CATEGORY --');

        $categories = $this->_getCategoryCollection(true,false,false,false);
        foreach ($categories as $category)
        {
        $this->_categoryList[$category->getEntityId()] = __($this->_getParentName($category->getPath()) . $category->getName());
        }
        return $this->_categoryList;
    }
protected function _getCategoryCollection ($isActive = true, $level = false, $sortBy=false, $pageSize=false)
        {
        $collection = $this->_categoryCollectionFactory ->create();
        $collection->addAttributeToSelect('*');
        
        //solo le categorie attive
        if ($isActive){
            $collection->addIsActiveFilter();
        }
        // le categorie di un certo livello dell'alberatura
        if($level){
            $collection->addLevelFilter($level);
        }
        //ordinamento delle categorie
        if($sortBy){
            $collection->addOrderField($sortBy);
        }
        // solo un numero specifico
        if($pageSize){
            $collection->setPageSize($pageSize);
        }
        return $collection;
        }     
private function _initializeCategoryMatrix()
        {
            $this->_categoryMatrix = array();
            $categories = $this-> _getCategoryCollection(false,false,false,false);
            foreach ($categories as $category)
            {
                $catID = $category->getEntityId();
                if (! array_key_exists($catID, $this->_categoryMatrix)){
                    $this->_categoryMatrix[$category->getEntityId()]["name"]=$category->getName();
                }
            }
        }
private function _getParentName($path='')
        {
            $parentName ='';
            $rootCats= array(1,2);
            $catTree = explode("/",$path);
            // cancellazione della categoria stessa
            array_pop($catTree);
            if($catTree && (count($catTree) > count($rootCats)))
            {
                foreach ($catTree as $catId)
                {
                    if(!in_array($catId, $rootCats))
                    {
                        $categoryName = $this->_categoryMatrix[$catId]["name"];
                        $parentName .= $categoryName . '->';
                    }
                }
            }
            return $parentName;
        }
}

