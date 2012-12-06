<?php

namespace Goodby\CSV\Import\Standard;

use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException;
use Goodby\CSV\Import\Standard\Exception\StrictViolationException;

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
     * @var int
     */
    private $columnsConsistency = null;

    private $strict = true;

    /**
     * interpret line
     *
     * @param $line
     * @return void
     * @throws \Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException
     */
    public function interpret($line)
    {
        $this->checkColumnsConsistency($line);

        if (!is_array($line)) {
            throw new InvalidLexicalException('line is must be array');
        }

        $this->notify($line);
    }

    public function unStrict()
    {
        $this->strict = false;
    }

    private function checkColumnsConsistency($line)
    {
        if (!$this->strict) {
            return;
        }

        $current = count($line);

        if ($this->columnsConsistency === null) {
            $this->columnsConsistency = $current;
        }

        if ($current !== $this->columnsConsistency) {
            throw new StrictViolationException();
        }

        $this->columnsConsistency = $current;
    }

    /**
     * add observer
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
