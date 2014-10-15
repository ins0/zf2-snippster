zf2-snippster
=============

ZF2 Module to save prerendered heavy views as snippets to cache and lower the application response time on request

## todo

- [ ] tests
- [ ] view helper
- [ ] controller helper

## Quick start

### Install via Composer
In the `require` key of `composer.json` file add the following

    "ins0/zf2-snippster": "dev-master"

Run the Composer update command

    $ composer update

### cache adapter

edit the module.config.php to set your pref. cache adapter snippster works great with redis or memcache

    'snippster_configuration' => array(
        'cache' => array(
            'adapter' => 'Zend\Cache\Storage\Adapter\Redis',
            'options' => array(
                'server' => array(
                    'host' => '127.0.0.1',
                    'port' => 6379,
                )
            )
        ),
    ),

## example

create a zf2 console route and point a crontab to the route to pre generate all views that are used in your application

### Generate Snippets

*config.module.php*

    'view_manager' => array(
      'template_map' => array(
          'snippster-product-list-item' => __DIR__ . '/../view/snippster/example/product/list-item.phtml',
      )
    )
    
*ConsoleExampleController.php*    

    function snippsterCreateExampleProductViews()
    {
        /** @var \Snippster\Service\Snippster $snippster */
        $snippster = $this->getServiceLocator()->get('Snippster');

        $products = array(
            array('id' => 1, 'name' => 'Test Product1'),
            array('id' => 2, 'name' => 'Test Product2'),
            array('id' => 3, 'name' => 'Test Product3'),
            array('id' => 4, 'name' => 'Test Product4'),
            array('id' => 5, 'name' => 'Test Product5'),
            array('id' => 6, 'name' => 'Test Product6'),
        );

        foreach($products as $product)
        {
            $viewSnippet = new \Snippster\Entity\ViewSnippet('product-list-item', $product['id']);
            $viewSnippet->setTemplate('snippster-product-list-item');
            $viewSnippet->setVariables($product);

            $snippster->saveViewSnippet($viewSnippet);
        }
    }
    
*snippster/example/product/list-item.phtml*

    <div class="product">
        <h1><?php echo $this->escapeHtml($this->name); ?>
        <hr>
        More heavy stuff to build this view
    </div>

### Request Snippets
    
controller request view snippet example

    function productListAction()
    {
        /** @var \Snippster\Service\Snippster $snippster */
        $snippster = $this->getServiceLocator()->get('Snippster');

        $html = $snippster->getViewSnippet('product-list-item', 4);
        // get pre rendered product html - do stuff
    }
