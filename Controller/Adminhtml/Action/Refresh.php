<?php
namespace RedChamps\EasyCacheClean\Controller\Adminhtml\Action;

use Magento\Backend\Controller\Adminhtml\Cache;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Refresh extends Cache
{
    public function execute()
    {
        try {
            $types = $this->_getCacheTypesForRefresh();
            $typeIds = array_keys($types);
            $updatedTypes = 0;
            if (!is_array($types)) {
                $types = [];
            }
            $this->_validateTypes($typeIds);
            foreach ($typeIds as $type) {
                $this->_cacheTypeList->cleanType($type);
                $updatedTypes++;
            }
            if ($updatedTypes > 0) {
                $this->messageManager->addSuccessMessage(__("%1 cache type(s) refreshed.", implode(', ',$types)));
            } else {
                $this->messageManager->addNoticeMessage(__("Nothing to flush as no cache is at invalid state."));
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('An error occurred while refreshing cache.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;

    }

    /**
     * Get array of cache types which require data refresh
     *
     * @return array
     */
    protected function _getCacheTypesForRefresh()
    {
        $output = [];
        foreach ($this->_cacheTypeList->getInvalidated() as $type) {
            $output[$type->getId()] = $type->getCacheType();
        }
        return $output;
    }
}
