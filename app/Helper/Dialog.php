<?php

namespace Wordrobe\Helper;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class Dialog
 * @package Wordrobe\Helper
 */
class Dialog
{
    public static $questionHelper;
    public static $input;
    public static $output;

	/**
	 * Initializes Dialog
	 *
	 * @param QuestionHelper $questionHelper
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
    public static function init(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output)
	{
		self::$questionHelper = $questionHelper;
		self::$input = $input;
		self::$output = $output;
	}

	/**
	 * Provides an open-ended question
	 * @param $text
	 * @param string $default
	 * @param string $color
	 * @param array $autocomplete
	 * @return mixed
	 */
    public static function getAnswer($text, $default = '', $color = 'blue', $autocomplete = null)
    {
        $question = new Question('<fg=' . $color . '>' . $text . ' </>', $default);

		if ($autocomplete) {
			$question->setAutocompleterValues($autocomplete);
		}

        $answer = self::ask($question);
        return $answer;
    }

    /**
     * Provides a multiple choice question
     *
     * @param $text
     * @param array $choices
     * @param int $default
     * @param string $color
     * @return mixed
     */
    public static function getChoice($text, $choices, $default = 0, $color = 'blue')
    {
        $question = new ChoiceQuestion('<fg=' . $color . '>' . $text . ' </>', $choices, $default);
        $answer = self::ask($question);
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
    public static function getConfirmation($text, $default = false, $color = 'red')
    {
        $options = $default ? '[Y|n]' : '[y|N]';
        $question = new ConfirmationQuestion('<fg=' . $color . '>' . $text . ' ' . $options . ' </>', $default);
        $answer = self::ask($question);
        return $answer;
    }

    /**
     * Writes an output message
     *
     * @param $text
     * @param $color
     * @param bool $newLine
     */
    public static function write($text, $color = 'black', $newLine = true)
    {
        $message = '<fg=' . $color . '>' . $text . ' </>';

        if ($newLine) {
            self::$output->writeln($message);
        } else {
            self::$output->write($message);
        }
    }

    /**
     * Reads an input argument
     *
     * @param $argument
     * @return mixed
     */
    public static function read($argument)
    {
        return self::$input->getArgument($argument);
    }

	/**
	 * A QuestionHelper's "ask" method wrapper
	 *
	 * @param Question $question
	 * @return mixed
	 */
	private static function ask(Question $question)
	{
		$answer = self::$questionHelper->ask(self::$input, self::$output, $question);
		return $answer;
	}
}
