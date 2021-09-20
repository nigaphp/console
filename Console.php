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

namespace Nigatedev\Framework\Console;

use Nigatedev\Framework\Console\Exception\InvalidArgumentException;
use Nigatedev\FrameworkBundle\Application\Configuration;
use Nigatedev\Framework\Console\Maker\Make;

/**
 * Console(CLI) application
 *
 * @author Abass Ben Cheik <abass@todaysdev.com>
 */
class Console
{
    protected $config = [];
     /**
      * @param array[] $commands
      *
      * @return void
      */
    public function __construct($commands)
    {
        $this->config = Configuration::getAppConfig();
        
        // Handling empty command, redirect to Help::class
        if (isset($commands[0])
             && !isset($commands[1])) {
            return (new Make(["help" => "default"], []));
        }
         
        if (isset($commands[1])) {
            if (preg_match("/(^m:c$)|(^make:c$)|(^make:controller$)|(^m:controller$)/", $commands[1])) {
                (new Make(
                    ["controller" => $commands],
                    ["controller" =>  $this->config["controller"]]
                ));
            }
        }
    }
}
