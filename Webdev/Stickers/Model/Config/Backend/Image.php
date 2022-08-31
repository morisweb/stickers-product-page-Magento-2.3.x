<?php 
namespace Webdev\Stickers\Model\Config\Backend;

class Image extends \Magento\Config\Model\Config\Backend\Image
{
    const UPLOAD_DIR = 'webdev'.DIRECTORY_SEPARATOR.'stickers'.DIRECTORY_SEPARATOR.'images';

    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->appendScopeInfo(self::UPLOAD_DIR));
    }
    protected function _addWhetherScopeInfo()
    {
        return true;
    }
    protected function _getAllowedExtension()
    {
        return ['jpg','jpeg','gif','png','svg'];
    }

    public function beforeSave ()
    {
        $file = $this->getFileData();
        $value = $this->getValue();
        $deleteFlag = isset($value['delete']);
        $fileTmpName = isset($file['tmp_name']);
        if ($this->getOldValue() && ($fileTmpName || $deleteFlag)){
            $this->_mediaDirectory->delete(self::UPLOAD_DIR.'/'. $this->getOldValue());
        }
        return parent::beforeSave();
    }
}