<?php
/**
* This file is part of the Nigatedev PHP framework package
*
* (c) Abass Ben Cheik <abass@todaysdev.com>
*/
namespace Nigatedev\Framework\Console\Maker\Model;

use Nigatedev\Framework\App;
use Nigatedev\Framework\Console\Colors;

/**
* ControllerMaker
*
* @package Nigatedev\Framework\Maker\Controller
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

    public function __construct($controller)
    {
        $prefixDir = dirname(__DIR__, 5);
        
        $this->rootDir = str_replace("../", "", $prefixDir.$controller['root_dir']);
        $this->dirName = str_replace("../", "/", $this->rootDir.$controller["dir"]);
        
    }

  /**
  * @param string $className
  *
  * @return void
  */
    public function makeController($className)
    {
        if ($this->isSafeClassName($className)) {
            if ($this->make($className)) {
                echo Colors::successTemp($this->success["cname"]);
            } else {
                echo Colors::errorTemp($this->error["cname"]);
            }
        } else {
            echo Colors::errorTemp($this->error["cname"]);
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
        if (preg_match("/(Controller)$/", $className)) {
            return true;
        } else {
            $this->error["cname"] = "All controllers class name must ended with 'Controller' prefix. ";
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
        $model = file_get_contents(__DIR__ .$model);
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
        
        $loaderFile = $this->rootDir."/config/loader.php";
        
         if (is_file("{$this->dirName}/{$cName}.php")) {
            $this->error["cname"] = "Can't create an existence controller class ".$cName;
            return false;
        }
        if (is_dir($this->dirName) && is_file(__DIR__ ."/ControllerModel.php")) {
            file_put_contents("{$this->dirName}/{$cName}.php", str_replace(["ControllerModel", "index"], [$cName, $this->lowerAndReplace("Controller", "", $cName)], $this->getModel("/ControllerModel.php")));
            fopen($this->rootDir."/views/".$this->lowerAndReplace("Controller", "", $cName.".php"), "w+");
            file_put_contents($this->rootDir."/views/".$this->lowerAndReplace("Controller", "", $cName.".php"), $this->getModel("/TemplateModel.php"));
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
}
