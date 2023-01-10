<?php
/*
 * This file is part of the Nigatedev framework package.
 * (c) Abass Ben Cheik <abass@abassdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nigatedev\Framework\Console\Maker\Server;

use Nigatedev\Framework\Console\Colors;
use Nigatedev\Framework\Console\Console;
use Nigatedev\Framework\Console\Maker\AbstractMaker;

/**
 * Entity maker
 *
 * @author Abass Ben Cheik <abass@abassdev.com>
 */
class ServerMaker extends AbstractMaker
{
    /**
     * @var array[]
     */
    protected $commands = [];

    /**
     * @var array[]
     */
    protected $config = [];

    /**
     * Constructor
     *
     * @param array[] $commands
     * @param array[] $config
     *
     * @return void
     */
    public function __construct($commands, $config)
    {

        parent::__construct($commands, $config);
        $this->make($this->commands);
    }


    /**
     * @param array $commands
     *
     * @return mixed
     */
    public function make($commands)
    {
        if (array_key_exists("2", $commands)) {
            $this->handleOptions($commands);
        } else {
            $this->runBash([]);
        }
    }

    /**
     * @param array $commands
     *
     * @return mixed
     */
    public function runBash($portNewValue)
    {
        $host = strval($this->config['host']);

        if (is_numeric($portNewValue)) {
            $port = (int) $portNewValue;
        } else {

            $port = (int) $this->config['port'];
        }

        $defaultPort = $port;

        $sock = @fsockopen($host, $port, $err, $errMessage, 0);
        if (is_resource($sock)) {
            while (is_resource($sock)) {
                $port++;
                $sock = @fsockopen($host, $port, $err, $errMessage, 0);

                if (!$sock) {
                    $input = readline(Colors::warningTemp('The port (' . $defaultPort . ') you are trying to connect is already in usage, do you want to connect to the following port ' . $port . ' instead? (Y/N) '));
                    if (strtoupper($input) === 'Y') {
                        exec("php -S " . $this->config['host'] . ":" . $port . " -t " . $this->config['publicPath']);
                    } else {
                        print_r(Colors::danger('Starting of dev server is cancelled!'));
                    }
                }
            }
        } else {
            exec("php -S " . $this->config['host'] . ":" . $port . " -t " . $this->config['publicPath']);
        }
    }

    /**
     * @param array $commands
     *
     * @return mixed
     */
    function handleOptions($commands)
    {
        $portOption = strval($commands[2]);

        if ($portOption === '-p') {
            if (array_key_exists(3, $commands)) {
                $portValue =  $commands[3];
                if (is_numeric($portValue)) {
                    $this->runBash($portValue);
                } else {
                    Console::errorMessage('Invalid port value');
                }
            } else {
                $this->runBash([]);
            }
        } else {
            Console::unknownError();
        }
    }
}
