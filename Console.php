<?php
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nigatedev\Framework\Console;

use Nigatedev\Framework\Console\Exception\InvalidConsoleArgumentException;
use Nigatedev\FrameworkBundle\Application\Configuration;
use Nigatedev\Framework\Console\Maker\Make;

/**
 * Console(CLI) application
 *
 * @author Abass Ben Cheik <abass@todaysdev.com>
 */
class Console
{
     
    public function __construct($cliApp)
    {
        if (empty($cliApp)) {
            throw new InvalidConsoleArgumentException("Invalid argument:");
        }
         
        if (isset($cliApp[1])) {
            if (preg_match("/(^m:c$)|(^make:c$)|(^make:controller$)|(^m:controller$)/", $cliApp[1])) {
                (new Make(
                    ["controller" =>  Configuration::getAppConfig()["controller"]]
                ))->make($cliApp);
            }
        }
        // exit;
    }
}
