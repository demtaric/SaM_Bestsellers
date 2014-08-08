<?php

class SaM_Bestsellers_Block_Bestsellers extends Mage_Core_Block_Template
{
    public function getBestsellerProducts()
    {
        $storeId = (int) Mage::app()->getStore()->getId();
        $pageSize = Mage::getStoreConfig('sam_bestsellers/settings/limit');
        $daysToSubstract = Mage::getStoreConfig('sam_bestsellers/settings/period');
        $date = new Zend_Date();
        $toDate = $date->getDate()->get('Y-MM-dd');
        $fromDate = $date->subDay($daysToSubstract)->getDate()->get('Y-MM-dd');
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addStoreFilter()
            ->addPriceData()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->setPageSize($pageSize);
        $collection->getSelect()
            ->joinLeft(
                array('aggregation' => $collection->getResource()->getTable('sales/bestsellers_aggregated_monthly')),
                "e.entity_id = aggregation.product_id AND aggregation.store_id={$storeId} AND aggregation.period BETWEEN '{$fromDate}' AND '{$toDate}'",
                array('SUM(aggregation.qty_ordered) AS sold_quantity')
            )
            ->group('e.entity_id')
            ->order(array('sold_quantity DESC', 'e.created_at'));
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $collection;
    }
}