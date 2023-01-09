<?php
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@abassdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nigatedev\Framework\Console\Maker\Controller;

use Nigatedev\Framework\Console\Colors;
use Nigatedev\FrameworkBundle\Application\Configuration as AppConfig;
use Nigatedev\Framework\Console\Exception\BadConfigException;
use Nigatedev\Framework\Console\Maker\AbstractMaker;

/**
 * Controller class maker
 *
 * @author Abass Ben Cheik <abass@abassdev.com>
 */
class ControllerMaker extends AbstractMaker
{
    /**
     * @param array $commands
     * @param array $config
     *
     * @return void
     */
    public function __construct(array $commands, array $config)
    {
        parent::__construct($commands, $config);

        $this->isController($commands);
    }

    /**
     * Check to see if controller class name is safe
     *
     * @param string $className
     *
     * @return mixed
     */
    public function isSafeClassName(string $className)
    {
        $className = trim(\ucfirst($className));
        if (preg_match('/^(\d)/', $className) || !preg_match("/Controller$/", $className)) {
            die(Colors::danger("Couldn't create controller, Bad controller class name"));
        } else {
            $this->make($className);
        }
    }

    /**
     * Final controller and view generator
     *
     * @param string $className   The controller to generate class name
     *
     * @return bool
     */
    public function make($className)
    {
        if (!is_dir($this->getDir())) {
            mkdir($this->getDir(), 0777, true);
        }

        $controller = $this->getDir() . self::DSP . $className . ".php";

        if (file_exists($controller)) {
            die(Colors::danger("Can't create an existence controller class $className"));
        }

        $find = ["ControllerModel", "index"];
        $replace = [$className, $this->lowerAndReplace("Controller", "", $className)];
        $content = $this->getModel("Controller");
        file_put_contents($controller, \str_replace($find, $replace, $content));

        if (
            $this->createViewFile($className)
            && $this->uploadLoader($className)
        ) {
            echo Colors::success("Your $className was created successfully !");
        } else {
            die(Colors::danger("Can't find " . $this->getDir() . " directory"));
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

        $root = $this->getRoot() . "/views/";
        $view = $viewName . $viewExtension;
        return file_put_contents(
            $root . $this->lowerAndReplace("Controller", "", $view),
            $this->getModel($viewModel)
        );
    }

    /**
     * @param string $className
     *
     * @return int
     */
    public function uploadLoader($className)
    {
        $loaderFile = $this->getRoot() . "/config/loader.php";
        $find = "];";
        $replaceBy = "    \\App\\Controller\\" . $className . "::class,\n];";

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
    public function isController(array $controller)
    {
        if (isset($controller[2]) && !isset($controller[3])) {
            $controllerName = $controller[2];
            $warning = strtoupper(readline(Colors::temp("INFO", "Generate[", Colors::info($controllerName) . "] Controller ? (" . Colors::success("Y") . "/" . Colors::danger("N") . ", YES/NO) ")));
            if ($warning === "Y") {
                $this->isSafeClassName($controllerName);
            } else {
                die(Colors::danger("N") . " Canceled !");
            }
        } else {
            $controllerName = readline(Colors::temp("INFO", "Controller name E.g:", Colors::info('HomeController ')));
            $this->isSafeClassName($controllerName);
        }
    }
}
