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
 * @package    Router
 * @copyright  Copyright (c) 2005-2014 Kumbia Team (http://www.kumbiaphp.com)
 * @license    http://wiki.kumbiaphp.com/Licencia     New BSD License
 */
use \Kumbia\Expection\SecurityException;
/**
 * Clase que Actua como router del Front-Controller
 *
 * Manejo de redirecciones de peticiones
 * Contiene información referente a la url de
 * la petición ( modudo, controlador, acción, parametros, etc )
 *
 * @category   Kumbia
 * @package    Router
 */
class Router
{   
    /**
     * Request object
     * @var Request
     */
    protected $request;

    /**
     * The path of app
     * @var string
     */
    protected $path;


    /**
	 * Create a Router
	 * @param string $url
     * @param Container $path
	 * @return Controller
	 */
	public  function __construct($url, $container)
	{
		$this->path = $container['path'];
		/*Check URL for security*/
        if (stripos($url, '/../') !== false)
			throw new SecurityException("URL malicious '$url'");
        $request = new Request();
        $request->url    = $url;
        $request->method = $_SERVER['REQUEST_METHOD'];
        $this->request = $request;
		$this->rewrite($url);
	}
    
	/**
     * Explode URL
     * @return void
     */
    protected function rewrite()
    {
        $request = $this->request;
        /*Nothing to do for default URL*/
        if ($request->url == '/')
            return;
        /*Explode URL*/
        $urls = explode('/', $request->url);
        /*Is it first a module?*/
        if (is_dir("{$this->path}/{$urls[0]}")) {
            $request->module = array_shift($urls);
        }else{
            $request->module = 'Default';
        }
        /*have it more element*/
        if (empty($url_items)) {
            $request->controller = 'Default';
            $request->action     = 'index';
            return;
        }
        /*Controller*/
        $request->controller = array_shift($urls);        
        /*Have it params?*/
        if (empty($urls)){
            $request->action = 'index';
            return;
        
        /*action*/
        $request->action = array_shift($urls);
        $request->parameters = $urls;
    }
    
	/**
     * to dispatch
     * @return Controller
     */
    protected function dispatch()
    {
        $controller = "{$this->module}/{$this->controller}Controller";
        $cont = new $controller($request, $this->container);
        /*execute filter initialize y before*/
        if ($cont->k_callback(true) === false) {
            return $cont;
        }
        /*Get method*/
		try {
			$reflectionMethod = new \ReflectionMethod($cont, $this->action);
		} catch (\ReflectionException $e) {
			throw new Exception(null, 'no_action');
		}
        /*No execute protected method*/
        if ($reflectionMethod->isProtected() || $reflectionMethod->isConstructor()) {
            throw new SecurityException('Forget Action');
        }
        /*Valid param number*/
        $num_params = count($request->parameters);
        if ($cont->limit_params && ($num_params < $reflectionMethod->getNumberOfRequiredParameters() ||
                $num_params > $reflectionMethod->getNumberOfParameters())) {
            throw new SecurityException(NULL,'num_params');
        }
		
		try {
			$reflectionMethod->invokeArgs($cont, $request->parameters);
		} catch (\ReflectionException $e) {
			throw new SecurityExceptionn(null, 'no_action'); //TODO: mejor no_public
		}

        /*After filter*/
        $cont->k_callback();
        return $cont;
    }
}
