<?php

namespace Goodby\CSV\Import\Standard;

use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException;

/**
 * standard interpreter
 *
 */
class Interpreter implements InterpreterInterface
{
    /**
     * @var array
     */
    private $observers = array();

    /**
     * interpret line
     *
     * @param $line
     * @return void
     * @throws \Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException
     */
    public function interpret($line)
    {
        if (!is_array($line)) {
            throw new InvalidLexicalException('line is must be array');
        }

        $this->notify($line);
    }

    /**
     * add obvserver
     *
     * @param callable $observer
     */
    public function addObserver($observer)
    {
        $this->checkCallable($observer);

        $this->observers[] = $observer;
    }

    /**
     * notify to observers
     *
     * @param $line
     */
    private function notify($line)
    {
        $observers = $this->observers;

        foreach ($observers as $observer) {
            $this->delegate($observer, $line);
        }
    }

    /**
     * delegate to observer
     *
     * @param $observer
     * @param $line
     */
    private function delegate($observer, $line)
    {
        $this->checkCallable($observer);

        call_user_func($observer, $line);
    }

    /**
     * check observer is callable
     *
     * @param $observer
     * @throws \InvalidArgumentException
     */
    private function checkCallable($observer)
    {
        if (!is_callable($observer)) {
            throw new \InvalidArgumentException('observer must be callable');
        }
    }
}
