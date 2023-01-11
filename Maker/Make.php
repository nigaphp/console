<?php
/*
 * This file is part of the Niga framework package.
 *
 * (c) Abass Dev <abass@abassdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Niga\Framework\Console\Maker;

use Niga\Framework\Console\Maker\Controller\ControllerMaker;
use Niga\Framework\Console\Maker\Entity\EntityMaker;
use Niga\Framework\Console\Helper\Help;
use Niga\Framework\Console\Colors;
use Niga\Framework\Console\Maker\Server\ServerMaker;

/**
 * Make class
 *
 * @author Abass Dev <abass@abassdev.com>
 */
class Make
{
    /**
     * Constructor
     *
     * @param array $commands
     * @param array $config
     *
     * @return mixed
     */
    public function __construct($commands, $config)
    {
        if (isset($commands["help"])) {
            return (new Help($commands["help"], []));
        }

        if (isset($commands["controller"])) {
            return (new ControllerMaker($commands["controller"], $config));
        }

        if (isset($commands["entity"])) {
            return (new EntityMaker($commands["entity"], $config));
        }
        if (isset($commands["server"])) {
            return (new ServerMaker($commands["server"], $config));
        }
    }
}
