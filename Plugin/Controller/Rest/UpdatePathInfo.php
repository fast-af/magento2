<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @package   Fast_Checkout
 * @author    Fast <hi@fast.co>
 * @copyright 2022 Copyright Fast AF, Inc., https://www.fast.co/
 * @license   https://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link      https://www.fast.co/
 */

declare(strict_types=1);

namespace Fast\Checkout\Plugin\Controller\Rest;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Webapi\Controller\Rest;
use Magento\Webapi\Controller\Rest\Router;
use Fast\Checkout\Model\Config\FastIntegrationConfig;
use Fast\Checkout\Logger\Logger;

class UpdatePathInfo
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FastIntegrationConfig
     */
    private $fastIntegrationConfig;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Router $router
     * @param Request $request
     * @param FastIntegrationConfig $fastIntegrationConfig
     * @param Logger $logger
     */
    public function __construct(
        Router                $router,
        Request               $request,
        FastIntegrationConfig $fastIntegrationConfig,
        Logger                $logger
    )
    {
        $this->router = $router;
        $this->request = $request;
        $this->fastIntegrationConfig = $fastIntegrationConfig;
        $this->logger = $logger;
    }

    /**
     * @param Rest $subject
     * @param RequestInterface $request
     * @return null
     */
    public function beforeDispatch(Rest $subject, RequestInterface $request)
    {
        if ($this->request->getHeader('x-fast-updatepathinfo') === "true" && $this->fastIntegrationConfig->isEnabled()) {
            $pathInfo = $this->request->getPathInfo();
            $this->logger->info("Initiate redirect. {$pathInfo}");
            $redirectPath = null;

            //look for x-fast custom header and get new redirect endpoint.
            if (preg_match('/(?<=\/V1\/)[^\/]*(?=\/)/', $pathInfo, $matches) === 1 && sizeof($matches) > 0) {
                $redirectPath = preg_replace('/(?<=\/V1\/)[^\/]*(?=\/)/', 'fast-checkout/' . $matches[0], $pathInfo);
            }

            if ($redirectPath) {
                try {
                    $this->request->setPathInfo($redirectPath);
                    $this->router->match($this->request);
                    $this->logger->info("Redirecting path from: {$pathInfo} -> {$redirectPath}");
                    $redirectPath = '/rest' . $redirectPath;
                    $this->logger->info("Executing: {$redirectPath}");
                    $request->setPathInfo($redirectPath);
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                    $this->logger->error("Redirect path doesn't exist. Rolling back from: {$redirectPath} -> {$pathInfo}");
                    $this->request->setPathInfo($pathInfo);
                }
            }
        }

        return null;
    }
}
