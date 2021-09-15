<?php
/*
 * This file is part of Nigatedev PHP framework package.
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 */
namespace Nigatedev\Framework\Console\Maker;

use Nigatedev\Framework\Console\Maker\Maker\ControllerMaker;
use Nigatedev\Framework\Console\Colors;

/**
 * Make class
 *
 * @author Abass Ben Cheik <abass@todaysdev.com>
 */
class Make
{
  /**
   * @var ControllerMaker $controllerMaker
   */
    public ControllerMaker $controllerMaker;
   
  /**
   * @var array[] $arg
   */
    public $arg;
   
    public function __construct($models)
    {
        $this->controllerMaker = new ControllerMaker($models["controller"]);
    }
   
   /**
    * Execute command or throw command Unkwon exception
    *
    * @param array[] $arg
    *
    * @return void
    */
    public function make($arg)
    {
        $this->arg = $arg;
        if (is_array($this->arg) && isset($this->arg[1])) {
            switch ($this->arg[1]) {
                case 'make:controller':
                    $this->isController($this->arg);
                    break;
                case 'm:controller':
                    $this->isController($this->arg);
                    break;
                case 'make:c':
                    $this->isController($this->arg);
                    break;
                case 'm:c':
                    $this->isController($this->arg);
                    break;
                case "-h":
                    echo Colors::infoTemp("Sorry no help has been written yet");
                    break;
                case "--help":
                    echo Colors::infoTemp("Sorry no help has been written yet");
                    break;
                default:
                     echo Colors::warningTemp("Unkwon command");
                    break;
            }
        } else {
            echo "Type --help or -h for basic usage";
        }
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
                $this->controllerMaker->makeController($controller[2]);
            } else {
                echo Colors::danger("N")." Canceled !";
            }
        } else {
            $controllerName = readline(Colors::temp("INFO", "Controller name E.g:", Colors::info('HomeController')));
            $this->controllerMaker->makeController($controllerName);
        }
    }
}
