<?php


namespace activeseven;

use activeseven\interfaces\ActiveStringInterface;
use activeseven\constants\ActiveStringConstants;

/**
 * Class ActiveString
 *
 * Basic Usage
 * $string = new ActiveString('A really cool string!');
 * echo $string;                                                                // 'A really cool string!'
 * echo $string->length()                                                       // 21
 * echo $string->contains('cool')                                               // 1 ( boolean true )
 * echo $string->from(9)->to(13)->get()                                         // 'cool'
 * echo $string->toUppercase()->get()                                           // 'A REALLY COOL STRING!'
 * echo $string->toUppercase()->from(9)->to(13)->get()                          // 'A really COOL string!'
 * echo $string->toUppercase()->from(-12)->forLength(4)->get()                  // 'A really COOL string!'
 * echo $string->toLowercase()->get()                                           // 'a really cool string!'
 *
 * Validate a string
 *
 * $string = new ActiveString('https://www.somedomain.com/index.php?param=true')
 * $string->isUrl()                     // true
 *
 * $string = new ActiveString('
 * @package activeseven
 */
class ActiveString implements ActiveStringInterface, ActiveStringConstants
{

    protected $strings = [];

    protected $current_operation = '';

    protected $start_position = 0;

    protected $end_position = null;

    protected $length_position = null;


    /**
     * Holds the text we will search for in replace/with operations.
     * This var can be either a string OR an array.
     * @var string|array|null
     */
    protected $string_to_search_for = null;

    protected $string_to_replace_with = '';

    protected $case_sensitive = true;

    protected $current_string_index = 0;

    /**
     * ActiveString constructor.
     * @param string $string
     */
    public function __construct(string $string = '')
    {
        $this->importString($string);
    }

    /**
     * Manages importing a NEW string to this object.
     * This method will set the string for this class to
     * the given value.
     *
     * NOTE: This method will trim all incoming strings
     * @access  protected
     * @param   string $string
     */
    protected function importString(string $string = '')
    {
        $this->strings = [$string];
    }

    /**
     * Updates the strings state
     * @param string $string
     * @return string
     */
    protected function updateStringState(string $string): string
    {
        array_push($this->strings, $string);
        return $this->__toString();
    }

    /**
     * Resets all this shit.
     */
    protected function resetAll(): void
    {
        $this->resetStartPosition();
        $this->resetEndPosition();
        $this->resetCurrentOperation();
        $this->resetStringToSearchFor();
        $this->resetStringToReplaceWith();
        $this->resetLengthPosition();
    }

    /**
     * Undoes the last operation
     * @return $this|ActiveStringInterface
     */
    public function undo(): ActiveStringInterface
    {
        if( !$this->hasCurrentOperation() ) {
            $this->setCurrentOperation(ActiveStringConstants::UNDO);
        }
        if ($this->getSizeOfStringHistory() > 1) {
            array_pop($this->strings);
        }
        return $this;
    }

    /**
     * Returns the length of the current string
     * @access  public
     * @return  int
     */
    public function length(): int
    {
        return strlen($this->__toString());
    }

    public function getSizeOfStringHistory(): int
    {
        return count($this->strings);
    }

    protected function resetCurrentOperation(): void
    {
        $this->current_operation = '';
    }

    protected function hasCurrentOperation(): bool
    {
        return (!empty($this->current_operation));
    }

    protected function setCurrentOperation(string $operation): void
    {
        $this->current_operation = $operation;
    }

    protected function getCurrentOperation(): string
    {
        return $this->current_operation;
    }

    protected function resetStartPosition()
    {
        $this->start_position = 0;
    }

    protected function hasStartPosition()
    {
        return ($this->start_position !== 0);
    }

    protected function getStartPosition(): int
    {
        return $this->start_position;
    }

    protected function setStartPosition(int $start_from)
    {
        $this->start_position = $start_from;
    }

    protected function calculateStartPosition():  int
    {
        $start_position = $this->getStartPosition();
        $string_length  = $this->length();

        if( abs($start_position) > $string_length ) {
            $mod            = $start_position % $string_length;
            $start_position = ($start_position < 0 ) ? $mod + $string_length : $mod;
        }

        if( $start_position < 0 ) {
            $start_position = $string_length - abs($start_position);
        }

        return $start_position;
    }

    protected function resetEndPosition()
    {
        $this->end_position = null;
    }

    protected function hasEndPosition()
    {
        return (!is_null($this->end_position));
    }

    protected function getEndPosition()
    {
        return $this->end_position;
    }

    protected function setEndPosition(int $end)
    {
        // The end_position can be
        $this->end_position = $end;
    }

    protected function resetLengthPosition(): void
    {
        $this->length_position = null;
    }

    protected function hasLengthPosition(): bool
    {
        return (!is_null($this->length_position));
    }

    protected function getLengthPosition(): int
    {
        return $this->length_position;
    }

    protected function setLengthPosition(int $length): void
    {
        $this->length_position = $length;
    }

    protected function hasLengthOrEndPosition(): bool
    {
        return ($this->hasLengthPosition() || $this->hasEndPosition());
    }

    protected function hasNoLengthOrEndPosition(): bool
    {
        return (!$this->hasLengthPosition() || !$this->hasEndPosition());
    }

    protected function calculateLengthPosition(): int
    {
        $string_length                  = $this->length();
        $calculated_start_position      = $this->calculateStartPosition();
        $given_start_position           = $this->getStartPosition();

        // First we change to see if the user sent us a LENGTH or if the users specified and END position.
        // In the event that we have both LENGTH and END then LENGTH takes priority.
        if( ($this->hasLengthPosition() && !$this->hasEndPosition()) ||
            ($this->hasLengthPosition() && $this->hasEndPosition())) {
            // HAS LENGTH

            $length_position = $this->getLengthPosition();
            switch( true ) {
                case( $length_position > 0):
                    // POSITIVE LENGTH
                    if( $length_position > $string_length ) {
                        $length_position = $string_length;
                    }
                    break;
                case ($length_position < 0):
                    // NEGATIVE LENGTH
                    if( $calculated_start_position == 0 ) {
                        // NEGATIVE LENGTH / ZERO START POSITION
                        if( $string_length < abs($length_position) ) {
                            // NEGATIVE LENGTH / ZERO START POSITION / OVERLOADED
                            $mod = abs($length_position) % $string_length;
                            $length_position = $string_length - $mod;
                        } else {
                            // NEGATIVE LENGTH / ZERO START POSITION / NOT OVERLOADED
                            $length_position = $string_length - abs($length_position);
                        }
                    } else {
                        // NEGATIVE LENGTH / POSITIVE START POSITION
                        if( $calculated_start_position < abs($length_position) ) {
                            // NEGATIVE LENGTH / POSITIVE START POSITION / OVERLOADED
                            $this->setStartPosition(0);
                            $length_position = $calculated_start_position;
                        } else {
                            // NEGATIVE LENGTH / POSITIVE START POSITION / NOT OVERLOADED
                            $new_start_position = $calculated_start_position - abs($length_position);
                            $this->setStartPosition($new_start_position);
                            $length_position = $calculated_start_position - $new_start_position;
                        }
                    }
                    break;
                default:
                    // ZERO LENGTH
                    $length_position = 0;
                    break;
            }
        }

        // If we only have an END position but no LENGTH
        if( !$this->hasLengthPosition() && $this->hasEndPosition() ) {
            // HAS CHARACTER POSITION

            $given_end_position = $this->getEndPosition();
            switch( true ) {
                case ($given_end_position > 0 ):
                    // POSITIVE CHARACTER POSITION
                    $overload_meter = ( abs($given_end_position) / $string_length );
                    switch( true ) {
                        case ($overload_meter > 1):
                            // POSITIVE CHARACTER POSITION / OVERLOADED
                            if( $calculated_start_position == 0 ) {
                                $length_position = ( abs($given_end_position) % $string_length);
                            } else {
                                if( $given_start_position >= 0 ) {
                                    $length_position = $string_length - $calculated_start_position;
                                } else {
                                    $mod = $given_end_position % $string_length;
                                    $length_position = $mod - $calculated_start_position;
                                }
                            }
                            break;
                        case ($overload_meter < 1):
                            // POSITIVE CHARACTER POSITION / NOT OVERLOADED
                            if( $calculated_start_position == 0 ) {
                                $length_position = $given_end_position;
                            } else {
                                if( $calculated_start_position > $given_end_position ) {
                                    // POSITIVE CHARACTER POSITION / NOT OVERLOADED / NO OVERLAP
                                    $this->setStartPosition($given_end_position);
                                    $length_position = $calculated_start_position - $given_end_position;
                                } elseif($calculated_start_position < $given_end_position) {
                                    // POSITIVE CHARACTER POSITION / NOT OVERLOADED / OVERLAPPED
                                    $length_position = $given_end_position - $calculated_start_position;
                                } else {
                                    // POSITIVE CHARACTER POSITION / NOT OVERLOADED / EVEN
                                    $length_position = 0;
                                }
                            }
                            break;
                        default:
                            /// POSITIVE CHARACTER POSITION / OVERLOADED ONE
                            $length_position = $string_length;
                            break;
                    }
                    break;
                case ($given_end_position < 0 ):
                    // NEGATIVE CHARACTER POSITION
                    $this->setStartPosition(0);
                    $overload_meter = ( abs($given_end_position) / $string_length );
                    switch( true ) {
                        case ($overload_meter > 1):
                            // NEGATIVE CHARACTER POSITION / OVERLOADED
                            if( $calculated_start_position == 0 ) {
                                $length_position = $string_length - ( abs($given_end_position) % $string_length);
                            } else {
                                $length_position = $calculated_start_position;
                            }
                            break;
                        case ($overload_meter < 1):
                            // NEGATIVE CHARACTER POSITION / NOT OVERLOADED
                            if ($calculated_start_position > ($string_length - abs($given_end_position))) {
                                // NEGATIVE CHARACTER POSITION / NOT OVERLOADED / OVERLAP
                                $new_start_position = $string_length - abs($given_end_position);
                                $this->setStartPosition($new_start_position);

                                $length_position = $calculated_start_position - $new_start_position;
                            } else {
                                // NEGATIVE CHARACTER POSITION / NOT OVERLOADED / NO OVERLAP
                                if( $calculated_start_position == 0 ) {
                                    $length_position = $string_length - abs($given_end_position);
                                } else {
                                    $this->setStartPosition($calculated_start_position);
                                    $subtract_from_string_length = abs($given_end_position) + $calculated_start_position;
                                    $length_position = $string_length - $subtract_from_string_length;
                                }
                            }
                            break;
                        default:
                            // / NEGATIVE CHARACTER POSITION / OVERLOADED ONE
                            $length_position = $given_start_position;
                            break;
                    }
                    break;
                default:
                    // ZERO CHARACTER POSITION
                    $this->setStartPosition(0);
                    $length_position = $given_start_position;
                    break;
            }
        }

        return $length_position;
    }

    protected function calculateEndPosition(): int
    {
        // The user gave us an 'end' position.
        // Problem is that I need 'length'
        // I really just need to deal with negative numbers here.
        $end_position   = $this->getEndPosition();
        $length         = $this->length();

        if( ($end_position <= $length) && ($end_position >= 0) ) {
            return $end_position;
        }

    }


    // Search for

    protected function resetStringToSearchFor()
    {
        $this->string_to_search_for = null;
    }

    protected function hasStringToSearchFor(): bool
    {
        return (!is_null($this->string_to_search_for));
    }

    protected function getStringToSearchFor()
    {
        return $this->string_to_search_for;
    }

    protected function setStringToSearchFor($string_to_search_for): bool
    {
        if (is_string($string_to_search_for) || is_array($string_to_search_for)) {
            $this->string_to_search_for = $string_to_search_for;
            return true;
        }
        return false;
    }


    // REPLACE WITH
    protected function resetStringToReplaceWith(): void
    {
        $this->string_to_replace_with = '';
    }

    protected function hasStringToReplaceWith(): bool
    {
        return (!empty($this->string_to_replace_with));
    }

    protected function getStringToReplaceWith()
    {
        return $this->string_to_replace_with;
    }

    protected function setStringToReplaceWith($string_or_array_to_replace_with): bool
    {
        if (is_string($string_or_array_to_replace_with) || is_array($string_or_array_to_replace_with)) {
            $this->string_to_replace_with = $string_or_array_to_replace_with;
            return true;
        }
        return false;
    }



    // CASE SENSITIVE

    public function isCaseSensitive(): bool
    {
        return $this->case_sensitive;
    }

    public function setCaseSensitive(bool $bool): ActiveStringInterface
    {
        $this->case_sensitive = $bool;
        return $this;
    }

    public function caseSensitive(): ActiveStringInterface
    {
        return $this->setCaseSensitive(true);
    }

    public function caseInsensitive(): ActiveStringInterface
    {
        return $this->setCaseSensitive(false);
    }





    protected function setStringIndexTo(int $index_value)
    {
        $this->current_string_index = $index_value;
    }

    protected function incrementStringIndex(int $increment_by = 1)
    {
        $this->current_string_index += $increment_by;
    }

    protected function decrementStringIndex(int $decrement_by = 1)
    {
        $this->current_string_index -= $decrement_by;
    }

    public function decodeUrl(): ActiveStringInterface
    {
        $this->updateStringState(urldecode($this->get()));
        return $this;
    }

    public function encodeUrl(): ActiveStringInterface
    {
        $this->updateStringState(urlencode($this->get()));
        return $this;
    }

    public function toUppercase(): ActiveStringInterface
    {
        $this->setCurrentOperation(ActiveStringConstants::UPPERCASE);
        return $this;
    }

    public function toLowercase(): ActiveStringInterface
    {
        $this->setCurrentOperation(ActiveStringConstants::LOWERCASE);
        return $this;
    }

    /**
     * @param   int|null $flag
     * @param   null $options
     * @param   bool $chain         Defaults to TRUE, will return an instance of ActiveString.
     * @return  ActiveString|mixed
     */
    protected function filterString(int $flag = null, $options = null, bool $chain = true)
    {
        $result = filter_var($this->get(),$flag,$options);
        if( !$chain ) {
            return $result;
        }

        $this->updateStringState($result);
        return $this;
    }

    public function isUrl(): bool
    {
        return $this->filterString(FILTER_VALIDATE_URL, null, false);
    }

    public function isEmail(): bool
    {
        return $this->filterString(FILTER_VALIDATE_EMAIL,null, false);
    }

    public function isIp(): bool
    {
        return $this->filterString(FILTER_VALIDATE_IP, null, false);
    }

    public function isIpv6(): bool
    {
        return $this->filterString(FILTER_VALIDATE_IP, FILTER_FLAG_IPV6, false);
    }

    public function isIpv4(): bool
    {
        return $this->filterString(FILTER_VALIDATE_IP, FILTER_FLAG_IPV4, false);
    }

    public function isDomain(): bool
    {
        return $this->filterString(FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME, false);
    }

    public function isMac(): bool
    {
        return $this->filterString(FILTER_VALIDATE_MAC, null, false);
    }

    public function sanitizeEmail(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_EMAIL);
        return $this;
    }

    public function stripLow(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_LOW);
        return $this;
    }

    public function stripHigh(): ActiveStringInterface
    {
        $this->filterString( FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_HIGH);
        return $this;
    }

    public function stripBacktick(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_BACKTICK);
        return $this;
    }

    public function encodeLow(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_ENCODED, FILTER_FLAG_ENCODE_LOW);
        return $this;
    }

    public function encodeHigh(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_ENCODED, FILTER_FLAG_ENCODE_HIGH);
        return $this;
    }

    /**
     *
     * NOTE: FILTER_SANITIZE_MAGIC_QUOTES was deprecated at php7.3 and is removed from php8.0
     * @deprecated
     * @return $this|ActiveStringInterface
     */
    public function magicQuotes(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_MAGIC_QUOTES);
        return $this;
    }

    /**
     * Executes addslashes() against the current string.
     * This will add a slash before a single quote ('), double quote ("),
     * backslash (/) and the NULL byte char.
     * @access  public
     * @return  ActiveStringInterface
     */
    public function addSlashes(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_ADD_SLASHES);
        return $this;
    }

    public function htmlEncode(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        return $this;
    }

    public function htmlEncodeNoQuotes(): ActiveStringInterface
    {
        $this->filterString(
            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            FILTER_FLAG_NO_ENCODE_QUOTES
        );
        return $this;
    }

    public function contains(string $string_to_find )
    {
        return (strpos($this->get(),$string_to_find) !== false);
    }


    // SUB_STRING
    public function fromThisCharacterPosition(int $start_from): ActiveStringConstants
    {
        if(!$this->hasCurrentOperation()) {
            $this->setCurrentOperation(ActiveStringConstants::SUB_STRING);
        }
        $this->setStartPosition($start_from);
        return $this;
    }

    public function from(int $start_from): ActiveStringInterface
    {
        return $this->fromThisCharacterPosition($start_from);
    }



    public function forThisManyCharacters(int $length): ActiveStringInterface
    {
        $this->setLengthPosition($length);
        return $this;
    }

    public function forLength(int $length): ActiveStringInterface
    {
        return $this->forThisManyCharacters($length);
    }
    public function for(int $length): ActiveStringInterface
    {
        return $this->forThisManyCharacters($length);

    }

    public function toThisCharacterPosition(int $end): ActiveStringInterface
    {
        $this->setEndPosition($end);
        return $this;
    }

    public function to(int $end): ActiveStringInterface
    {
        return $this->toThisCharacterPosition($end);
    }

    public function replace($search_for)
    {
        $this->setStringToSearchFor($search_for);
        return $this;
    }

    public function with($replace_with): ActiveStringInterface
    {
        $function = 'str_replace';
        if( !$this->isCaseSensitive() ) {
            $function = 'str_ireplace';
        }

        $this->updateStringState(
            $function(
                $this->getStringToSearchFor(),
                $replace_with,
                $this->get()
            )
        );
        return $this;
    }

    public function positionOf(string  $string, int $start_from = 0)
    {
        $function = 'strpos';
        if(!$this->isCaseSensitive()) {
            $function = 'stripos';
        }

        return $function($this->get(),$string,$start_from);
    }

    public function getNumberOfWords(string $extra_chars = null): int
    {
        return str_word_count($this->get(),0, $extra_chars);
    }

    public function getWords(string $extra_chars = null): array
    {
        return str_word_count($this->get(),1,$extra_chars);
    }

    public function getWordsWithPositions(string $extra_chars = null): array
    {
        return str_word_count($this->get(),2, $extra_chars);
    }

    /**
     * @param bool $sticky_change
     * @return string
     */
    public function get(bool $sticky_change = true): string
    {
        // @TODO Refactor ActiveString:get()

        $string             = $this->__toString();
        $current_operation  = $this->getCurrentOperation();
        $string_length      = $this->length();
        // Calculating the length can -in some cases- alter the start position
        // So make sure we calculate the length first.
        $length_position    = ($this->hasLengthOrEndPosition())
                            ? $this->calculateLengthPosition()
                            : null;
        $start_position     = $this->calculateStartPosition();


        switch ( $current_operation ) {
            case ActiveStringConstants::UPPERCASE:
            case ActiveStringConstants::LOWERCASE:

                $string_function    = ($current_operation == ActiveStringConstants::UPPERCASE)
                                    ? 'strtoupper'
                                    : 'strtolower';

                if (!$this->hasStartPosition() &&
                    !$this->hasEndPosition() &&
                    !$this->hasLengthPosition()) {

                    // Only executes If there is no start,end or length set.
                    // Basically just to do the whole damn string
                    $new_string = $string_function($string);
                } else {
                    // @TODO if user only sends an end position that needs to work too

                    // IF LENGTH/END IS SET
                    // Ok the user has specified start/length/end parameters.
                    // So this is how it's going to break down. I need to capture
                    // exactly which characters we need to work on. First, I wanna see
                    // if maybe the user only specified a starting position. If that's
                    // the case then I don't have to worry about sub_str's third param.
                    if (!$this->hasLengthOrEndPosition()) {
                        // NO LENGTH/END POSITION
                        // Don't have a length/end position. So just capture from the
                        // start position to the end of the string.
                        $target_characters = substr($string, $start_position);
                    } else {
                        // YES LENGTH/END POSITION
                        // We had to calculate a length position, let's use it!
                        $target_characters = substr( $string, $start_position, $length_position);

                        // IF WE DIDN'T EDIT STRING FROM START TO END.
                        if (($start_position + $length_position) < $string_length) {
                            $prepend_position = $start_position + $length_position;
                        }
                    }

                    // I need to rebuild the string now. Starting from the left I need to capture from
                    // the start of the string to whatever the starting point is.
                    $new_string  = substr($string, 0, $start_position);
                    // Now that I have the 'left' part of this string I will perform the requested
                    // operation on the target characters and append it.
                    $new_string .= $string_function($target_characters);
                    // Odds are pretty good that we didn't perform the above operation to the end
                    // of the string. So let's see if prepend_position is set. If it is, then
                    // I know how to get the 'right' part of this string.
                    if (isset($prepend_position)) {
                        $new_string .= substr($string, $prepend_position);
                    }
                }
                // Ok, I think I have an answer so let's update the string.
                $this->updateStringState($new_string);

                break;
            case ActiveStringConstants::SUB_STRING:
                $string = substr(
                    $this->__toString(),
                    $this->calculateStartPosition(),
                    $this->calculateLengthPosition()
                );
                $this->updateStringState($string);
                break;
            default:
                break;

        }

        $this->resetAll();

        $return_me = $this->__toString();
        if( !$sticky_change ) {
            $this->undo();
        }

        return $return_me;
    }

    public function __toString(): string
    {
        return (string)$this->strings[array_key_last($this->strings)];
    }
}