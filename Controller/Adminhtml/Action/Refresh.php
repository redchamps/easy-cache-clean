<?php
namespace RedChamps\EasyCacheClean\Controller\Adminhtml\Action;

use Magento\Backend\Controller\Adminhtml\Cache;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Refresh extends Cache
{
    public function execute()
    {
        $ajaxResponse = ["error" => true];
        $message = "";
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
                $message = __("%1 cache type(s) refreshed.", implode(', ',$types));
                $ajaxResponse['error'] = false;
                $this->addMessage($message);
            } else {
                $message = __("Nothing to flush as no cache is at invalid state.");
                $this->addMessage($message, "notice");
            }
        } catch (LocalizedException $e) {
            $message = $e->getMessage();
            $this->addMessage($message, "error");
        } catch (\Exception $e) {
            $message = __('An error occurred while refreshing cache.');
            $this->messageManager->addExceptionMessage($e, $message);
        }

        if($this->getRequest()->isAjax()) {
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $ajaxResponse['message'] = $message;
            return $resultJson->setData($ajaxResponse);
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

    protected function addMessage($message, $type = "success")
    {
        if(!$this->getRequest()->isAjax()) {
            switch ($type) {
                case "success":
                    $this->messageManager->addSuccessMessage($message);
                    break;
                case "error":
                    $this->messageManager->addErrorMessage($message);
                    break;
                default:
                    $this->messageManager->addNoticeMessage($message);
            }
        }
    }
}
