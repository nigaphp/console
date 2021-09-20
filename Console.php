<?php declare(strict_types = 1);
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     /**
      * Console main class
      *
      * @param array[] $cliApp
      *
      * @return void
      */
    public function __construct($cliApp)
    {
        if (empty($cliApp)) {
            throw new InvalidArgumentException("Invalid argument:");
        }
         
        if (isset($cliApp[1])) {
            $command = $cliApp[1];
            if (preg_match("/(^m:c$)|(^make:c$)|(^make:controller$)|(^m:controller$)/", $command)) {
                (new Make(
                    ["controller" =>  Configuration::getAppConfig()["controller"]]
                ))->make($cliApp);
            }
        }
    }
}
