<?php
namespace Kumbia;
use Pimple\Container;
use Kumbia\Component\Router;
use Kumbia\Component\Template;
/**
 * KumbiaPHP Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://wiki.kumbiaphp.com/Licencia
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@kumbiaphp.com so we can send you a copy immediately.
 *
 * @category   Kumbia
 * @package    Controller 
 * @copyright  Copyright (c) 2005-2014 Kumbia Team (http://www.kumbiaphp.com)
 * @license    http://wiki.kumbiaphp.com/Licencia     New BSD License
 */
class App{

    /**
     * Path of App
     * @var String
     */
    protected $path;
    /**
     * Service container
     * @var Container;
     */
    protected $container;

    function __construct($path){
        $this->path      =  $path;
        $this->container = new Container();
        $this->register();
    }

    protected function register(){
        $this->container['appdir']   = $this->path;
        $this->container['router'] = function($c){
            $url = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
            return new Router($url, $c);
        };
        $this->container['view']  = function($e){
            return new Template();
        };
    }

    function execute(){
        $controller = $this->container['router']->dispatch();
        $vars       = get_object_vars($controller);
        $controller->_view();
        echo $this->container['view']->render($vars);
    }
}