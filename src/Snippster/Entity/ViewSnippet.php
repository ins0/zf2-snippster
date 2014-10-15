<?php
namespace Snippster\Entity;

use Zend\View\Exception;
use Zend\View\Model;

class ViewSnippet extends Model\ViewModel
{
    /** @var  Snippet Name */
    protected $snippetName;

    /** @var  Snippet Identifier Id */
    protected $snippetIdentifier;

    /**
     * Constructor
     *
     * @param string $snippetName
     * @param string $snippetIdentifier
     * @param null $variables
     * @param null $options
     */
    public function __construct($snippetName, $snippetIdentifier, $variables = null, $options = null)
    {
        $this->setSnippetName($snippetName);
        $this->setSnippetIdentifier($snippetIdentifier);

        parent::__construct($variables, $options);
    }

    /**
     * @return mixed
     */
    public function getSnippetIdentifier()
    {
        return $this->snippetIdentifier;
    }

    /**
     * @param mixed $snippetIdentifier
     */
    public function setSnippetIdentifier($snippetIdentifier)
    {
        $this->snippetIdentifier = $snippetIdentifier;
    }

    /**
     * @return mixed
     */
    public function getSnippetName()
    {
        return $this->snippetName;
    }

    /**
     * @param mixed $snippetName
     */
    public function setSnippetName($snippetName)
    {
        $this->snippetName = $snippetName;
    }

}