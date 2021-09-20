<?php
/**
* This file is part of the Nigatedev PHP framework package
*
* (c) Abass Ben Cheik <abass@todaysdev.com>
*/
namespace Nigatedev\Framework\Console\Maker\Maker;

use Nigatedev\Framework\Console\Colors;
use Nigatedev\FrameworkBundle\Application\Configuration as AppConfig;
use Nigatedev\Framework\Console\Exception\BadConfigException;

/**
* Controller class maker
*
* @author Abass Ben Cheik <abass@todaysdev.com>
*/
class ControllerMaker
{

  /**
  * @var string
  */
    private string $className;

  /**
  * @var string[] $error
  */
    private $error = [];

  /**
  * @var string[] $success
  */
    private $success = [];

  /**
  * @var string
  */
    private $dirName;

  /**
  * @var string
  */
    private $rootDir;

    public function __construct($commands, $config)
    {
        
        $prefixDir = dirname(__DIR__, 5);
        $this->rootDir = str_replace("../", "/", $prefixDir.$config["controller"]['root_dir']);
        $this->dirName = str_replace("../", "/", $prefixDir.$config["controller"]["dir"]);
    
        $this->isController($commands);
    }

  /**
  * @param string $className
  *
  * @return void
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
  * Get model controller content
  *
  * @param string $model
  *
  * @return string
  */
    public function getModel($model)
    {
        $model = file_get_contents(dirname(__DIR__)."/Models/".$model);
        return $model;
    }

  /**
  * Final controller class generator
  *
  * @param string $cName   The controller to generate class name
  *
  * @return bool
  */
    public function make($cName)
    {
        $defaultTemplate = AppConfig::getDefaultTemplateConfig();
        
        if (array_key_exists("default_template", $defaultTemplate)) {
            $key = $defaultTemplate["default_template"];
            if ($key === "diyan") {
                $templateModel = "/DiyanModel.php";
                $templateExtension = ".php";
            } else {
                $templateModel = "/TwigModel.php";
                $templateExtension = ".twig";
            }
        } else {
            throw new BadConfigException("Unacceptable templating configuration");
        }
        
        $loaderFile = $this->rootDir."/config/loader.php";
        
        if (is_file("{$this->dirName}/{$cName}.php")) {
            $this->error["cname"] = "Can't create an existence controller class ".$cName;
            return false;
        }
        if (is_dir($this->dirName) && is_file(dirname(__DIR__)."/Models/ControllerModel.php")) {
            file_put_contents("{$this->dirName}/{$cName}.php", str_replace(["ControllerModel", "index"], [$cName, $this->lowerAndReplace("Controller", "", $cName)], $this->getModel("/ControllerModel.php")));
            fopen($this->rootDir."/views/".$this->lowerAndReplace("Controller", "", "{$cName}{$templateExtension}"), "w+");
            file_put_contents($this->rootDir."/views/".$this->lowerAndReplace("Controller", "", "{$cName}{$templateExtension}"), $this->getModel($templateModel));
            $loader = str_replace("];", "  '/". $this->lowerAndReplace("Controller", "", $cName)."' => [\\App\\Controller\\". $cName."::class, '".$this->lowerAndReplace("Controller", "", $cName)."'],\n];", file_get_contents($loaderFile));
            file_put_contents($loaderFile, $loader);
            $this->success["cname"] = "Your $cName was created successfully !";
            return true;
        } else {
            $this->error["cname"] = "Can't find ".$this->dirName." directory";
            return false;
        }
            $this->error["cname"] = "Unknown error: when try to create a controller";
        return false;
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
        return strtolower(str_replace($find, $replace, $content));
    }
    
    
    /**
     * @param array[] $controller
     *
     * @return void
     */
    public function isController($controller)
    {
        if (isset($controller[2]) && !isset($controller[3])) {
             $controllerName = $controller[2];
             $warning = strtoupper(readline(Colors::temp("INFO", "Generate", Colors::info("[". $controllerName ."]") . " Controller ? (".Colors::success("Y")."/".Colors::danger("N").", YES/NO) ")));
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
