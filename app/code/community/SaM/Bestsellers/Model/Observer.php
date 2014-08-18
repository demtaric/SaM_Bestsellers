<?php

class SaM_Bestsellers_Model_Observer
{
    public function insertBlock(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig('sam_bestsellers/settings/enabled')) {
            $actionName = $observer->getEvent()->getAction()->getFullActionName();
            if ($actionName === 'cms_index_index') {
                $layoutUpdate = $observer->getEvent()->getData('layout')->getUpdate();
                if (Mage::getStoreConfig('sam_bestsellers/settings/position') === 'left') {
                    $layoutUpdate->addHandle('bestsellers_left');
                } else {
                    $layoutUpdate->addHandle('bestsellers_right');
                }
            }
        }
    }
}