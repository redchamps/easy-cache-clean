<?php
namespace RedChamps\EasyCacheClean\Plugin;

use Magento\AdminNotification\Model\System\Message\CacheOutdated;
use Magento\Framework\UrlInterface;

class CacheOutdatedMessage
{
    /**
     * @var UrlInterface
     */
    private $_urlBuilder;

    /**
     * CacheOutdated constructor.
     * @param UrlInterface $_urlBuilder
     */
    public function __construct(
        UrlInterface $_urlBuilder
    )
    {
        $this->_urlBuilder = $_urlBuilder;
    }

    public function afterGetText(CacheOutdated $subject, $result)
    {
        $parts = explode(".", $result);
        $url = $this->_urlBuilder->getUrl('easy-cache-clean/action/refresh');
        $result = $parts[0]. __('. Please <a id="easy-cache-clean" href="%1">Click Here</a> to refresh them instantly.', $url);
        return $result;
    }
}
