<?php namespace Dredd;

use UnexpectedValueException;

class Server
{
    const RECV_LENGTH = 10;

    const MESSAGE_END = "\n";

    private $host;
    private $port;

    /** @var Runner */
    private $runner;

    public function __construct($host, $port)
    {
        $this->runner = new Runner;
        $this->host = $host;
        $this->port = $port;
    }

    public function run($force = false)
    {
        if ($force) {
            $this->killProgramsOnDreddPort();
        }

        $socket = sprintf('tcp://%s:%s', $this->host, $this->port);
        $server = stream_socket_server($socket, $errno, $errorMessage);

        if ($server === false) {
            throw new UnexpectedValueException("Server could not bind to socket: $errorMessage");
        }

        $buffer = "";

        while ($connection = stream_socket_accept($server)) {

            while ($socketData = stream_socket_recvfrom($connection, self::RECV_LENGTH)) {

                $buffer .= $socketData;

                // determine if message terminating character is present.
                if (strpos($buffer, self::MESSAGE_END) === false) {

                    continue;
                }

                $messages = [];

                foreach (explode(self::MESSAGE_END, $buffer) as $data) {

                    $message = json_decode($data);

                    // if not valid json the partial message needs saved
                    if (! $message) {

                        $buffer = $message;
                        continue;
                    }

                    $messages[] = $message;
                }

                foreach ($messages as $message) {

                    $this->processMessage($message, $connection);
                }
            }
        }
    }

    public function processMessage($message, $client)
    {
        $event = $message->event;
        $data = $message->data;
        $uuid = $message->uuid;

        if ($event == "beforeEach") {

            $data = $this->runner->runBeforeEachHooksForTransaction($data);
            $data = $this->runner->runBeforeHooksForTransaction($data);
        }

        if ($event == "beforeEachValidation") {

            $data = $this->runner->runBeforeEachValidationHooksForTransaction($data);
            $data = $this->runner->runBeforeValidationHooksForTransaction($data);
        }

        if ($event == "afterEach") {

            $data = $this->runner->runAfterHooksForTransaction($data);
            $data = $this->runner->runAfterEachHooksForTransaction($data);
        }

        if ($event == "beforeAll") {

            $data = $this->runner->runBeforeAllHooksForTransaction($data);
        }

        if ($event == "afterAll") {

            $data = $this->runner->runAfterAllHooksForTransaction($data);
        }

        $messageToSend = [
            'uuid'  => $uuid,
            'data'  => $data,
            'event' => $event
        ];

        $messageToSend = json_encode($messageToSend);

        stream_socket_sendto($client, $messageToSend . self::MESSAGE_END);
    }

    private function killProgramsOnDreddPort()
    {
        foreach ([SIGTERM, SIGINT, SIGHUP, SIGKILL] as $signal) {
            // get any programs running on the dredd port
            if ($string = shell_exec(sprintf("lsof -i tcp:%s", $this->port))) {
                $regex = '/(?:php\s*)(\d*)/';

                // get matches if any programs are returned
                preg_match($regex, $string, $matches);

                // execute kill command so server can listen on port
                shell_exec("kill -$signal {$matches[1]}");

                sleep(3);
            }
        }
    }
}
