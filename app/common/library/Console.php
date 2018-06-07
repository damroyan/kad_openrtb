<?php
namespace Tizer;
use Phalcon\Mvc\User\Component;

class Console extends Component {
    const BACKGROUND_BLACK      = '40';
    const BACKGROUND_RED        = '41';
    const BACKGROUND_GREEN      = '42';
    const BACKGROUND_YELLOW     = '43';
    const BACKGROUND_BLUE       = '44';
    const BACKGROUND_MAGENTA    = '45';
    const BACKGROUND_CYAN       = '46';
    const BACKGROUND_LIGHT_GRAY = '47';

    const COLOR_BLACK           = '0;30';
    const COLOR_DARK_GRAY       = '1;30';
    const COLOR_BLUE            = '0;34';
    const COLOR_LIGHT_BLUE      = '1;34';
    const COLOR_GREEN           = '0;32';
    const COLOR_LIGHT_GREEN     = '1;32';
    const COLOR_CYAN            = '0;36';
    const COLOR_LIGHT_CYAN      = '1;36';
    const COLOR_RED             = '0;31';
    const COLOR_LIGHT_RED       = '1;31';
    const COLOR_PURPLE          = '0;35';
    const COLOR_LIGHT_PURPLE    = '1;35';
    const COLOR_BROWN           = '0;33';
    const COLOR_YELLOW          = '1;33';
    const COLOR_LIGHT_GRAY      = '0;37';
    const COLOR_WHITE           = '1;37';

	public function __construct() {
	}

    /**
     * @param $e \Exception
     */
    static public function Exception($e) {
        Console::WriteLine(
            "[" . $e->getCode() . "] " . $e->getFile() . ":" . $e->getLine(),
            Console::COLOR_RED
        );
        Console::WriteLine($e->getMessage(), Console::COLOR_RED);
        Console::WriteLine($e->getTraceAsString(), Console::COLOR_LIGHT_RED);
    }

    /**
     * Выводит в консоль строки. Понимает минимальное форматирование \n - одна строка, br - одна строка, p - две строки
     * @param $string
     * @param null $color
     * @param null $background
     */
    static public function WriteLine($string, $color = null, $background = null) {
		$colored_string = "";

		// Check if given foreground color found
		if (!is_null($color)) {
			$colored_string .= "\033[" . $color . "m";
		}

		if (!is_null($background)) {
			$colored_string .= "\033[" . $background . "m";
		}

		// Add string and end coloring
		$colored_string .=  $string . "\033[0m";

        $colored_string = str_replace('\n',PHP_EOL,$colored_string);
        $colored_string = str_replace('<br>',PHP_EOL,$colored_string);
        $colored_string = str_replace('<p>',PHP_EOL.PHP_EOL,$colored_string);

		echo $colored_string . PHP_EOL;
	}

}