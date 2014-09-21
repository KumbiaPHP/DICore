<?php
namespace Kumbia\Component;
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
abstract class Controller
{

    /**
     * Limita la cantidad correcta de 
     * parametros de una action
     *
     * @var bool
     */
    public $limit_params = TRUE;
    /**
     * Nombre del scaffold a usar
     *
     * @var string
     */
    public $scaffold;

    /**
     * Request object
     * @var Request
     */
    protected $request;

    /**
     * Container of Services
     * @var Container
     */
    protected $service;

    /**
     * Constructor
     *
     * @param Request $request request
     */
    public function __construct(Request $request, $container){
        $this->request = $request;
        $this->service = $container;
    }

    /**
     * BeforeFilter
     * 
     * @return bool
     */
    protected function before_filter()
    {
        
    }

    /**
     * AfterFilter
     * 
     * @return bool
     */
    protected function after_filter()
    {
        
    }

    /**
     * Initialize
     * 
     * @return bool
     */
    protected function initialize()
    {
        
    }

    /**
     * Finalize
     * 
     * @return bool
     */
    protected function finalize()
    {

    }

    /**
     * Ejecuta los callback filter
     *
     * @param boolean $init filtros de inicio
     * @return bool
     */
    final public function k_callback($init = FALSE)
    {
        if ($init) {
            if ($this->initialize() !== FALSE) {
                return $this->before_filter();
            }
            return FALSE;
        }

        $this->after_filter();
        $this->finalize();
    }

}
