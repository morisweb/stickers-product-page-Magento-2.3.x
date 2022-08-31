<?php 
namespace Webdev\Stickers\Model\Config\Backend;

class Position implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $position = array();
        $position[]=[
            'value'=>'right',
            'label'=> __('Right')
        ];
        $position[] =[
            'value' => 'left',
            'label' => __('Left')
        ];
    return $position;
    }
}