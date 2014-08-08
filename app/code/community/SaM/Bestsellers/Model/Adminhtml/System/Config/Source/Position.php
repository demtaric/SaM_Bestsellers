<?php

class SaM_Bestsellers_Model_Adminhtml_System_Config_Source_Position
{
    public function toOptionArray()
    {
        $position = array(
            array('value' => 'right', 'label' => 'Right'),
            array('value' => 'left', 'label' => 'Left'),
        );

        return $position;
    }
}