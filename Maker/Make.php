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

namespace Nigatedev\Framework\Console\Maker;

use Nigatedev\Framework\Console\Maker\Controller\ControllerMaker;
use Nigatedev\Framework\Console\Helper\Help;
use Nigatedev\Framework\Console\Colors;

/**
 * Make class
 *
 * @author Abass Ben Cheik <abass@todaysdev.com>
 */
class Make
{
    public function __construct($commands, $config)
    {
        if (isset($commands["help"])) {
            return (new Help($commands["help"], []));
        }
        
        if (isset($commands["controller"])) {
            return (new ControllerMaker($commands["controller"], $config));
        }
    }
}
