<?php

namespace Wordress\Helper;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class Dialog
 * @package Wordress\Helper
 */
class Dialog
{
    public $questionHelper;
    public $input;
    public $output;

    /**
     * Dialog constructor.
     *
     * @param QuestionHelper $questionHelper
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    function __construct(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output)
    {
        $this->questionHelper = $questionHelper;
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * A QuestionHelper's "ask" method wrapper
     *
     * @param Question $question
     * @return mixed
     */
    private function ask(Question $question)
    {
        $answer = $this->questionHelper->ask(
            $this->input,
            $this->output,
            $question
        );
        return $answer;
    }

    /**
     * Provides an open-ended question
     *
     * @param string $text - The question text
     * @param string $default - The default value for the answer
     * @param string $color - The text color
     * @return mixed
     */
    public function getAnswer($text, $default = '', $color = 'blue')
    {
        $question = new Question('<fg=' . $color . '>' . $text . ' </>', $default);
        $answer = $this->ask($question);
        return $answer;
    }

    /**
     * Provides a multiple choice question
     *
     * @param $text
     * @param $choices
     * @param int $default
     * @param string $color
     * @return mixed
     */
    public function getChoice($text, $choices, $default = 0, $color = 'blue')
    {
        $question = new ChoiceQuestion('<fg=' . $color . '>' . $text . ' </>', $choices, $default);
        $answer = $this->ask($question);
        return $answer;
    }

    /**
     * Provides a yes/no question
     *
     * @param $text
     * @param bool $default
     * @param string $color
     * @return mixed
     */
    public function getConfirmation($text, $default = false, $color = 'red')
    {
        $options = $default ? '[Y|n]' : '[y|N]';
        $question = new ConfirmationQuestion('<fg=' . $color . '>' . $text . ' ' . $options . ' </>', $default);
        $answer = $this->ask($question);
        return $answer;
    }

    /**
     * Writes an output message
     *
     * @param $text
     * @param $color
     * @param bool $newLine
     */
    public function write($text, $color = 'black', $newLine = true)
    {
        $message = '<fg=' . $color . '>' . $text . ' </>';
        if ($newLine) {
            $this->output->writeln($message);
        } else {
            $this->output->write($message);
        }
    }

    /**
     * Reads an input argument
     *
     * @param $argument
     * @return mixed
     */
    public function read($argument)
    {
        return $this->input->getArgument($argument);
    }
}
