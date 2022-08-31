<?php
namespace Webdev\Stickers\Model\Config\Backend;

use Magento\Framework\Option\ArrayInterface;

class CalculationType implements ArrayInterface
{
public function toOptionArray()
    { 
    $calculationType = array();

    $calculationType[] = [
    'value'=>'manual',
    'label'=> __('Manual')
    ];
    $calculationType[] = [
    'value'=>'automatic',
    'label'=> __('Automatic')
    ];
    return $calculationType;
    }
}