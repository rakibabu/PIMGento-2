<?php

namespace Pimgento\Variant\Observer;

use Magento\Framework\Event\ObserverInterface;
use Pimgento\Import\Observer\AbstractAddImportObserver;

class AddPimgentoImportObserver extends AbstractAddImportObserver implements ObserverInterface
{
    /**
     * Get the import code
     *
     * @return string
     */
    protected function getImportCode()
    {
        return 'variant';
    }

    /**
     * Get the import name
     *
     * @return string
     */
    protected function getImportName()
    {
        if ($this->moduleManager->isEnabled('Pimgento_VariantFamily')) {
            return __('Product Model');
        }
        return __('Variant');
    }

    /**
     * Get the default import classname
     *
     * @return string
     */
    protected function getImportDefaultClassname()
    {
        return '\Pimgento\Variant\Model\Factory\Import';
    }

    /**
     * Get the sort order
     *
     * @return int
     */
    protected function getImportSortOrder()
    {
        return 50;
    }

    /**
     * get the steps definition
     *
     * @return array
     */
    protected function getStepsDefinition()
    {
        $import = __('Variant');

        if ($this->moduleManager->isEnabled('Pimgento_VariantFamily')) {
            $import = __('Product Model');
        }

        $stepsBefore = array(
            array(
                'comment' => __('Create temporary table'),
                'method'  => 'createTable',
            ),
            array(
                'comment' => __('Fill temporary table'),
                'method'  => 'insertData',
            ),
            array(
                'comment' => __('Clean up %1', $import),
                'method'  => 'removeColumns',
            ),
            array(
                'comment' => __('%1 data enrichment', $import),
                'method'  => 'addColumns',
            ),
            array(
                'comment' => __('Fill %1 data', $import),
                'method'  => 'updateData',
            )
        );

        $stepsAfter = array(
            array(
                'comment' => __('Drop temporary table'),
                'method'  => 'dropTable',
            ),
        );

        return array_merge(
            $stepsBefore,
            $this->getAdditionnalSteps(),
            $stepsAfter
        );
    }
}
