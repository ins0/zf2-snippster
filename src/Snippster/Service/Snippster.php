<?php
namespace Snippster\Service;

use Snippster\Entity\ViewSnippet;
use Zend\Cache\Storage\StorageInterface;
use Zend\Http\Response;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\View;

class Snippster
{
    /** @var StorageInterface */
    protected $cacheAdapter;

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var View */
    protected $view;

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return StorageInterface
     */
    public function getCacheAdapter()
    {
        return $this->cacheAdapter;
    }

    /**
     * @param StorageInterface $cacheAdapter
     */
    public function setCacheAdapter(StorageInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * Get ViewSnippet
     *
     * @param $indexName
     * @param $indexIdentifier
     * @throws \Exception
     */
    public function getViewSnippet($indexName, $indexIdentifier)
    {
        $cacheKey = $this->getSnippetCacheName($indexName, $indexIdentifier);
        $cache = $this->getCacheAdapter();

        if($cache->hasItem($cacheKey))
        {
            return $cache->getItem($cacheKey);
        }
        throw new \Exception(sprintf('ViewSnippet [%s] with ID [%s] not found', $indexName, $indexIdentifier));
    }

    /**
     * Get Snippet Cache Name
     *
     * @param $name
     * @param $identifier
     * @return string
     */
    protected function getSnippetCacheName($name, $identifier)
    {
        return sprintf('snippster-%s-%s', $name, $identifier);
    }

    /**
     * Return the Zend View Service
     *
     * @return View
     */
    protected function getView()
    {
        if( $this->view == null ) {
            $this->view = clone $this->getServiceLocator()->get('View');
            $this->view->setResponse(new Response());
        }
        return $this->view;
    }

    /**
     * Render as ViewSnipped to HTML
     *
     * @param ViewSnippet $snippet
     * @return string
     */
    protected function renderSnippet(ViewSnippet $snippet)
    {
        $view = $this->getView();
        $view->render($snippet);

        return $view->getResponse()->getContent();
    }

    /**
     * Remove Snipped from Cache
     *
     * @param $name
     * @param $identifier
     * @return bool
     */
    protected function removeSnippet($name, $identifier)
    {
        $cacheKey = $this->getSnippetCacheName($name, $identifier);
        $cache = $this->getCacheAdapter();
        return $cache->removeItem($cacheKey);
    }

    /**
     * Save Snippet to Cache Adapter
     *
     * @param ViewSnippet $snippet
     * @return bool
     */
    public function saveViewSnippet(ViewSnippet $snippet)
    {
        $cacheKey = $this->getSnippetCacheName($snippet->getSnippetName(), $snippet->getSnippetIdentifier());
        $cache = $this->getCacheAdapter();

        $snippetHtml = $this->renderSnippet($snippet);

        if($cache->hasItem($cacheKey))
        {
            return $cache->replaceItem($cacheKey, $snippetHtml);
        }

        return $cache->setItem($cacheKey, $snippetHtml);
    }
}