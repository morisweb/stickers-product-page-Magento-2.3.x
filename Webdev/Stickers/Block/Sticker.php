<?php
namespace Webdev\Stickers\Block;

class Sticker extends \Magento\Framework\View\Element\Template
{

    protected $_coreRegistry;
    protected $_sticker;
    
    private $_product = null;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Webdev\Stickers\Model\StickerFactory $stickerFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Webdev\Stickers\Model\StickerFactory $stickerFactory,
        array $data = []
    ){
        $this->_coreRegistry = $registry;
        $this->_sticker = $stickerFactory->create();
        parent::__construct($context, $data);       
    }

    private function _getProduct()
    {
        if (!$this->_product){
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }
    public function isStickerActive()
    {
        return $this->_sticker->isStickerActive();
    }
    public function setStickerHTML()
    {
        $this->_sticker->setProduct($this->_getProduct());
        $html = $this->_sticker->setStickerHTML();

        return $html;
    }
}