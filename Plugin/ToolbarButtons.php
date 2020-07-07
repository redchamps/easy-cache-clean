<?php
namespace RedChamps\EasyCacheClean\Plugin;

use Magento\Backend\Block\Cache;
use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Backend\Block\Widget\Button\Toolbar;
use Magento\Framework\View\Element\AbstractBlock;

class ToolbarButtons
{
    /**
     * @param Toolbar $subject
     * @param AbstractBlock $context
     * @param ButtonList $buttonList
     * @return array
     */
    public function beforePushButtons(
        Toolbar $subject,
        AbstractBlock $context,
        ButtonList $buttonList
    ) {
        if ($context instanceof Cache) {
            $url = $context->getUrl('cacheclean/action/refresh');

            $message = __('Are you sure that you want to refresh the cache?');
            $buttonList->add(
                'flush_invalidated_cache',
                [
                    'label' => __('Flush Invalidated Cache(s)'),
                    'onclick' => 'confirmSetLocation(\'' . $message . '\', \'' . $url . '\')',
                    'class' => 'flush-cache-storage'
                ],
                0,
                -1
            );
        }
        return [$context, $buttonList];
    }
}
