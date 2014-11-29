<?php
namespace Kumbia\Component;
/**
 * KumbiaPHP web & app Framework
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
 * @package    Core
 * @copyright  Copyright (c) 2005-2014 Kumbia Team (http://www.kumbiaphp.com)
 * @license    http://wiki.kumbiaphp.com/Licencia     New BSD License
 */

/**
 * Render of views
 *
 * @category   Kumbia
 * @package    Core
 */
class Template
{
    
    /**
     * Contaienr
     * @var $container
     */
    protected $container = NULL;


    protected $block = array();

    protected $dir = '';

    protected $file = '';

    protected $parent = NULL;

    protected $content = NULL;

    protected $child   = FALSE;

    protected $stack   = array();

    function __construct($container, $child=FALSE){
        $this->container = $container;
        $this->child = $child;
    }

    function setPath($path){
        $this->dir = $path;
    }

    function select($file){
        $this->file = "{$file}.php";
    }

    function render(Array $var, Array $block = array()){
        $this->block = $block;
        $file = "$this->dir/$this->file";
        if(!is_file($file))
            throw new \RuntimeException("Not Found Template");
        $this->var = $var;
        /*set global var*/
        $var['_tpl']    = $this;

        extract($var);
        ob_start();
        include $file;
        if(is_object($this->parent)){
            ob_end_clean();
            return  $this->parent->render($this->var, $this->block);
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    function childOf($parent){
        if(!empty($this->parent)){
            throw new \RuntimeException('Only one parent');
        }
        $this->parent = new Template($this->container, TRUE);
        $this->parent->setPath($this->dir);
        $this->parent->select($parent);
    }

    function block($tpl){
        ob_start();
        $this->stack[]=$tpl;
    }


    function endblock(){
        /**
         * @TODO validation
         */
        $key = array_pop($this->stack);
        if($this->child){

            echo (isset($this->block[$key])) ?
                $this->block[$key]:
                ob_get_contents();
            ob_end_flush();
        }else{
            $this->block[$key] = ob_get_contents();
            ob_end_clean();
        }
    }

    /**
     * get the public path
     * @return String
     */
    function pp(){
        return $this->container['publicdir'];
    }
}

    