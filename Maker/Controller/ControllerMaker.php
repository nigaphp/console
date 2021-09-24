<?php
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Nigatedev\Framework\Console\Maker\Controller;

use Nigatedev\Framework\Console\Colors;
use Nigatedev\FrameworkBundle\Application\Configuration as AppConfig;
use Nigatedev\Framework\Console\Exception\BadConfigException;
use Nigatedev\Framework\Console\Maker\AbstractMaker;

/**
* Controller class maker
*
* @author Abass Ben Cheik <abass@todaysdev.com>
*/
class ControllerMaker extends AbstractMaker
{

  /**
  * @var string[] $error
  */
    private $error = [];

  /**
  * @var string[] $success
  */
    private $success = [];

    /**
     * @param array $commands
     * @param array $config
     *
     * @return void
     */
    public function __construct($commands, $config)
    {
        parent::__construct($commands, $config);
        
        $this->isController($commands);
    }

  /**
  * @param string $className
  *
  * @return string
  */
    public function makeController($className)
    {
        if ($this->isSafeClassName($className) && $this->make($className)) {
               return Colors::successTemp($this->success["cname"]);
        } else {
            return Colors::errorTemp($this->error["cname"]);
        }
    }

  /**
  * Check to see if controller class name is safe
  *
  * @param string $className
  *
  * @return bool
  */
    public function isSafeClassName($className)
    {
        $className = trim($className);
        preg_match('/^(\d)|(\d)$/', $className, $match);
        if ($match) {
            $this->error["cname"] = "Controllers class name couldn't contains any special chars from the starting and must ended with the prefix 'Controller'!";
            return false;
        }
        if (preg_match("/^\w+(Controller)$/", $className)) {
            return true;
        } else {
            $this->error["cname"] = "Bad controllers class name, Note: all controller must ended with 'Controller' prefix. ";
            return false;
        }
    }

  /**
  * Final controller and view generator
  *
  * @param string $cName   The controller to generate class name
  *
  * @return bool
  */
    public function make($cName)
    {
        if (!is_dir($this->getDir())) {
            mkdir($this->getDir(), 0777, true);
        }
        
        $controller = $this->getDir().self::DSP.$cName."php";
        if (is_file($controller)) {
            $this->error["cname"] = "Can't create an existence controller class ".$cName;
            return false;
        }
        
        if ($this->createControllerClass($cName)
            && $this->createViewFile($cName)
            && $this->loaderUploader($cName)
        ) {
                $this->success["cname"] = "Your $cName was created successfully !";
                return true;
        } else {
            $this->error["cname"] = "Can't find ".$this->getDir()." directory";
            return false;
        }
    }
    
    /**
     * @param string $viewName
     *
     * @return int
     */
    public function createViewFile($viewName)
    {
        $defaultTemplate =  AppConfig::getAppConfig();
        
        if ($defaultTemplate["default_template"] === "diyan") {
            $viewModel = "Diyan";
            $viewExtension = ".php";
        } else {
            $viewModel = "Twig";
            $viewExtension = ".twig";
        }
        
        $root = $this->getRoot()."/views/";
        $view = $viewName.$viewExtension;
        return file_put_contents(
            $root.$this->lowerAndReplace("Controller", "", $view),
            $this->getModel($viewModel)
        );
    }
    
    /**
     * @param string $cName
     *
     * @return int
     */
    public function createControllerClass($cName)
    {
        $controllerName = $this->getDir().self::DSP."$cName.php";
        
        $find = ["ControllerModel", "index"];
        $replace = [$cName, $this->lowerAndReplace("Controller", "", $cName)];
        $content = $this->getModel("Controller");
        return file_put_contents($controllerName, \str_replace($find, $replace, $content));
    }
    
    /**
     * @param string $cName
     *
     * @return int
     */
    public function loaderUploader($cName)
    {
            $loaderFile = $this->getRoot()."/config/loader.php";
            $find = "];";
            $replaceBy = "    \\App\\Controller\\". $cName."::class,\n];";
            
            $loaderContent = str_replace($find, $replaceBy, \file_get_contents($loaderFile));
            return file_put_contents($loaderFile, $loaderContent);
    }
    
    /**
     * @param string|array $find
     * @param string|array $replace
     * @param string|array $content
     *
     * @return string
     */
    public function lowerAndReplace($find, $replace, $content)
    {
        return strtolower(\str_replace($find, $replace, $content));
    }

    /**
     * @param array[] $controller
     *
     * @return void
     */
    public function isController($controller)
    {
        if (isset($controller[2]) && !isset($controller[3])) {
             $controllerName = (string)$controller[2];
             $warning = strtoupper(readline(Colors::temp("INFO", "Generate[", Colors::info($controllerName) . "] Controller ? (".Colors::success("Y")."/".Colors::danger("N").", YES/NO) ")));
            if ($warning === "Y") {
                $this->makeController($controller[2]);
            } else {
                echo Colors::danger("N")." Canceled !";
            }
        } else {
            $controllerName = readline(Colors::temp("INFO", "Controller name E.g:", Colors::info('HomeController')));
            $this->makeController($controllerName);
        }
    }
}
