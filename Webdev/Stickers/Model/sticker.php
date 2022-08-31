<?php 
namespace Webdev\Stickers\Model;

class Sticker extends \Magento\Framework\Model\AbstractModel
{
    const   STICKER_ACTIVATION = "stickers/stickers_page/activation";
    const   STICKER_CALCULATION = "stickers/stickers_page/calculation";
    const   STICKER_CATEGORY = "stickers/stickers_page/category";
    const   STICKER_TYPE = "stickers/stickers_page/type";
    const   STICKER_IMAGE = "stickers/stickers_page/image";
    const   STYCKER_FIRST_LABEL = "stickers/stickers_page/first_label";
    const   STICKER_SECOND_LABEL = "stickers/stickers_page/second_label";
    const   STIKCER_BACKGROUND_COLOR = "stickers/stickers_page/background_color";
    const   STICKER_TEXT_COLOR = "stickers/stickers_page/text_color";
    const   STICKER_POSITION = "stickers/stickers_page/position";

    const AUTOMATIC_CALCULATION = 'automatic';
    const CUSTOM_STICKER = 'custom';

    const RESULT_IMAGE = 1;
    const RESULT_AREA = 2;
    const RESULT_CALCULATED = 3;
    
    protected $_productFactory;
    protected $_scopeConfig;
    protected $_urlInterface;

    private $_regularPrice;
    private $_finalPrice;
    protected $_product;

    public function __construct(
    \Magento\Framework\Model\Context $context,
    \Magento\Framework\Registry $registry,
    \Magento\Catalog\Model\ProductFactory $productFactory,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Framework\UrlInterface $urlInterface
        ){
        parent::__construct($context, $registry);

        $this->_productFactory = $productFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_urlInterface = $urlInterface;
    }
    public function setProduct($product)
    {
        $this->_product = $product;
    }
    public function isStickerActive()
    {
        $activation = $this->_getConfigValue(self::STICKER_ACTIVATION);

        if($activation && $activation==1){
            return true;
        }
        return false;
    }
    public function setStickerHTML ()
    {
        //manual
        if(!$this->_isAutomaticMode()){
            // Product in discounted categories
            if ($this->_isInCategory()){
                //custom sticker
                if($this->_isCustomSticker()){
                    return $this->_getHTML(self::RESULT_AREA, self::STICKER_FIRST_LABEL, self::STICKER_SECOND_LABEL);
                }
                //IMAGE STICKER
                else {
                    return $this->_getHTML(self::RESULT_IMAGE);
                }
            }
        }
        // AUTOMATIC
        else {
            //product discounted
            if ($this->_isDiscounted($this->_product->getData('entity_id'))){
                return $this->_getHTML(self::RESULT_CALCULATED);
            }
        }
    }
    private function _getHTML($stickerLayoutType, $firstLabel = '', $secondLabel =''){

        $html = "<div class='webdev-sticker-wrapper.".$this->_getStickerPosition()."'>";
        switch ($stickerLayoutType){
            case self::RESULT_IMAGE:
                $html.="<img class='productDiscountImage' src='".$this->getUrl('pub/media')."webdev/stickers/images/".$this->_getStickerImage()."'/>";
                break;
                case self::RESULT_AREA:
                    $html .="<div class='webdev-sticker discount-product' style='background-color:#".$this->_getStickerBackgroundColor().";
                    color:#".$this->_getStickeTextColor().";'>";
                    $html .= $this->_getStickerFirstLabel(). "<br/>".$this->_getStickerSecondoLabel();
                    $html .="</div>";
                    break;
                case self::RESULT_CALCULATED:
                    $html .="<div class='webdev-sticker discount-product automatic' style='background-color: #" .$this->_getStickerBackgroundColor().";
                    color: #".$this->_getStickerTextColor().";'>";
                    $html .=$this->_getDiscountAmount();
                    $html .="</div>";
                    break;
                default:
                    $html .="<img class='productDiscountImage' src=" .$this->getUrl('pub/media')."webdev/stickers/images/" .$this->_getStickerImage()."' />";
                    break;
        }
        $html .= "</div>";
        return $html;
        }
    private function _isInCategory()
    {
        $categories = $this->_getStickerCategory();
        if($categories && $categories != ''){
        $lastesCats = explode(",",$categories);
        $cats= $this->_product->getCategoryIds();
        foreach ($latestCats as $latestCats){
            if (in_array($latestCat, $cats))
            return true;
            }
        }
    return false;
    }
    private function _isDiscounted ($prodId)
    {
    $product = $this->_productFactory->create();
    $product->load($prodId);
    $this->_finalePrice = $product->getData('price');
    return $this->_regularPrice != $this->_finalPrice;
    }
    private function _getDiscountAmount()
    {
        return $this->_getDiscountPercentage ($this->_finalPrice,$this->_regularPrice);
    }
    private function _getDiscountPercentage($finalPrice=0,$regularPrice=1)
    {
        $percentage = number_format($finalPrice/$regularPrice * 100,2);
        $discountPercentage = round(100-$percentage);

        return __("Up to"). "<br/>".discountPercentage."%";
    }
    private function _getConfigValue($configKey)
    {
        return $this->_scopeConfig->getValue($configKey, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    private function _isAutomaticMode()
    {
        if($this->_getConfigValue(self::STICKER_CALCULATION) == self::AUTOMATIC_CALCULATION)
        return true;
        else
        return false;
    }
    private function _isCustomSticker()
    {
        if($this->_getConfigValue(self::STICKER_TYPE) == self::CUSTOM_STICKER)
        return true;
        else
        return false;
    }
    private function _getStickerCategory()
    {
        return $this->_getConfigValue(self::STICKER_CATEGORY);
    }
    private function _getStickerFirstLabel()
    {
        return $this->_getConfigValue(self::STICKER_FIRST_LABEL);
    }
    private function _getStickerSecondLabel()
    {
        return $this->_getConfigValue(self::STICKER_SECOND_LABEL);
    }
    private function _getStickerBackgroundColor()
    {
        return $this->_getConfigValue(self::STICKER_BACKGROUND_COLOR);
    }
    private function _getStickerTextColor ()
    {
        return $this->_getConfigValue(self::STICKER_TEXT_COLOR);
    }
    private function _getStickeImage()
    {
        return $this->_getConfigValue(self::STICKER_IMAGE);
    }
    private function _getStickerPosition()
    {
        return $this->_getConfigValue(self::STICKER_POSITION);
    }
}