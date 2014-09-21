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
    protected $block = array();

    protected $dir = '';

    protected $file = '';


    function __construct(){

    }

    function setPath($path){
        $this->dir = $path;
    }

    function select($file){
        $this->file = "{$file}.php";
    }

    function render(Array $var){
        $file = "$this->dir/$this->file";
        if(!is_file($file))
            throw new \RuntimeException("Not Found Template");
        extract($var);
        include $file;

    }
}

    