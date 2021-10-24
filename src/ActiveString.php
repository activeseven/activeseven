<?php


namespace activeseven;

use activeseven\interfaces\ActiveStringInterface;
use activeseven\constants\ActiveStringConstants;

/**
 * Class ActiveString
 *
 * @TODO write the class documentation.
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
    /**
     * Array of 'string states' for this object.
     * Every time you make a change to the string the old
     * state is held in this array. This allows changes to
     * either be undone, or allows access to older versions
     * of this string.
     * @var array
     */
    protected $strings = [];

    /**
     * Serves as a pointer to the current string version.
     * @var int
     */
    protected $current_string_index = 0;

    /**
     * Stores the code for the current string operation being performed.
     * @var string
     */
    protected $current_operation = '';

    /**
     * The default start position for all operations.
     * @var int
     */
    protected $start_position = 0;

    /**
     * The end position for the current operation.
     * @var null
     */
    protected $end_position = null;

    /**
     * The length position for the current operation.
     * @var null
     */
    protected $length_position = null;

    /**
     * Holds the text we will search for in replace/with operations.
     * This var can be either a string OR an array.
     * @var string|array|null
     */
    protected $string_to_search_for = null;

    /**
     * Holds the text we will replace characters that we search for with.
     * @var string
     */
    protected $string_to_replace_with = '';

    /**
     * Toggles whether the search operations are case-sensitive.
     * @var bool
     */
    protected $case_sensitive = true;

    /**
     * ActiveString constructor.
     * @access  public
     * @param   string $string
     */
    public function __construct(string $string = '')
    {
        $this->importString($string);
    }

    /**
     * Calculates the proper start position for the current operation.
     * @access  protected
     * @return  int
     */
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

    /**
     * Calculates the correct end position;
     * @access  protected
     * @return  int
     */
    protected function calculateEndPosition(): int
    {
        // @TODO wtf is going on here? This method isn't used at all
        // and there is a condition for the return.
        $end_position   = $this->getEndPosition();
        $length         = $this->length();

        if( ($end_position <= $length) && ($end_position >= 0) ) {
            return $end_position;
        }
    }

    /**
     * Calculates the correct length position for the current operation
     * @access  protected
     * @return  int
     */
    protected function calculateLengthPosition(): int
    {
        $string_length                  = $this->length();
        $calculated_start_position      = $this->calculateStartPosition();
        $given_start_position           = $this->getStartPosition();
        $length_position                = 0;

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

    /**
     * Manages importing a NEW string to this object.
     * This method will set the string for this class to
     * the given value.
     *
     * @access  protected
     * @param   string $string
     */
    protected function importString(string $string = '')
    {
        $this->strings = [$string];
    }

    /**
     * Updates the strings state to the given string.
     * @TODO Should this increment the index value?
     * @param   string $string
     * @return  string
     */
    protected function updateStringState(string $string): string
    {
        array_push($this->strings, $string);
        return $this->__toString();
    }

    /**
     * Resets the state of this class for the next operation.
     * @access  protected
     * @uses    \activeseven\ActiveString::resetStartPosition()
     * @uses    \activeseven\ActiveString::resetEndPosition()
     * @uses    \activeseven\ActiveString::resetCurrentOperation()
     * @uses    \activeseven\ActiveString::resetStringToSearchFor()
     * @uses    \activeseven\ActiveString::resetStringToReplaceWith()
     * @uses    \activeseven\ActiveString::resetLengthPosition()
     * @used-by \activeseven\ActiveString::get()
     * @return  void
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
     * Undoes the last string operation.
     * @access  public
     * @return  ActiveStringInterface
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
     * Returns the length of the current string.
     * @access  public
     * @uses    \activeseven\ActiveString::__toString()
     * @return  int
     */
    public function length(): int
    {
        return strlen($this->__toString());
    }

    /**
     * Returns the size of the string history.
     * @access  public
     * @return  int
     */
    public function getSizeOfStringHistory(): int
    {
        return count($this->strings);
    }

    /**
     * Resets the current operation.
     * @access  public
     * @return  void
     */
    protected function resetCurrentOperation(): void
    {
        $this->current_operation = '';
    }

    /**
     * Returns boolean if there is a current operation.
     * @access  protected
     * @return  bool
     */
    protected function hasCurrentOperation(): bool
    {
        return (!empty($this->current_operation));
    }

    /**
     * Sets the current operation to the given operation value.
     * @access  protected
     * @param   string $operation
     * @return  void
     */
    protected function setCurrentOperation(string $operation): void
    {
        $this->current_operation = $operation;
    }

    /**
     * Returns the currently set operation.
     * NOTE: This method will return an empty string if no
     * operation is set. This is because an empty string is
     * the default state for the current_operation property.
     * @access  protected
     * @used-by ActiveString::get()
     * @return  string
     */
    protected function getCurrentOperation(): string
    {
        return $this->current_operation;
    }

    /**
     * Resets the start position.
     * @access protected
     * @return void
     */
    protected function resetStartPosition(): void
    {
        $this->start_position = 0;
    }

    /**
     * Returns boolean if the start position is set.
     * @access  protected
     * @return  bool
     */
    protected function hasStartPosition(): bool
    {
        return ($this->start_position !== 0);
    }

    /**
     * Returns the current start position.
     * @access  protected
     * @return  int
     */
    protected function getStartPosition(): int
    {
        return $this->start_position;
    }

    /**
     * Sets the start position to the given value.
     * @access  protected
     * @param   int $start_from
     * @return  void
     */
    protected function setStartPosition(int $start_from): void
    {
        $this->start_position = $start_from;
    }

    /**
     * Resets the current end position.
     * @access  protected
     * @return  void
     */
    protected function resetEndPosition(): void
    {
        $this->end_position = null;
    }

    /**
     * Returns boolean if an end position is set.
     * @access  protected
     * @return  bool
     */
    protected function hasEndPosition(): bool
    {
        return (!is_null($this->end_position));
    }

    /**
     * Returns the currently set end position.
     * @access  protected
     * @return  null
     */
    protected function getEndPosition()
    {
        return $this->end_position;
    }

    /**
     * Sets the end position to the given value.
     * @access  protected
     * @param   int $end
     * @return  void
     */
    protected function setEndPosition(int $end): void
    {
        $this->end_position = $end;
    }

    /**
     * Resets the length position.
     * @access  protected
     * @return  void
     */
    protected function resetLengthPosition(): void
    {
        $this->length_position = null;
    }

    /**
     * Returns boolean if the length position is set.
     * @access  protected
     * @return  bool
     */
    protected function hasLengthPosition(): bool
    {
        return (!is_null($this->length_position));
    }

    /**
     * Returns the currently set length position.
     * NOTE: This method may return null if length
     * was never set, as null is the default state.
     * @return int
     */
    protected function getLengthPosition(): ?int
    {
        return $this->length_position;
    }

    /**
     * Sets the length position to the given value.
     * @access  protected
     * @param   int $length
     */
    protected function setLengthPosition(int $length): void
    {
        $this->length_position = $length;
    }

    /**
     * Returns boolean if there is a length OR end position set.
     * @access  protected
     * @return  bool
     */
    protected function hasLengthOrEndPosition(): bool
    {
        return ($this->hasLengthPosition() || $this->hasEndPosition());
    }

    /**
     * Returns boolean if there is NO length or end position set.
     * @access  protected
     * @return  bool
     */
    protected function hasNoLengthOrEndPosition(): bool
    {
        return (!$this->hasLengthPosition() || !$this->hasEndPosition());
    }

    /**
     * Resets the string to search for.
     * @access  protected
     * @return  void
     */
    protected function resetStringToSearchFor(): void
    {
        $this->string_to_search_for = null;
    }

    /**
     * Returns boolean if there is a string set to search for.
     * @access  protected
     * @return  bool
     */
    protected function hasStringToSearchFor(): bool
    {
        return (!is_null($this->string_to_search_for));
    }

    /**
     * Returns the currently set search string.
     * @access  protected
     * @return  array|string|null
     */
    protected function getStringToSearchFor()
    {
        return $this->string_to_search_for;
    }

    /**
     * Sets the string to search for to the given string.
     * @access  protected
     * @param   $string_to_search_for
     * @return  bool
     */
    protected function setStringToSearchFor($string_to_search_for): bool
    {
        // @TODO consider putting all strings into an array instead of string|array.
        if (is_string($string_to_search_for) || is_array($string_to_search_for)) {
            $this->string_to_search_for = $string_to_search_for;
            return true;
        }
        return false;
    }

    /**
     * Resets the replace string to it's default value.
     * @access  protected
     * @return  void
     */
    protected function resetStringToReplaceWith(): void
    {
        $this->string_to_replace_with = '';
    }

    /**
     * Returns boolean if the replace string has been set.
     * @access  protected
     * @return  bool
     */
    protected function hasStringToReplaceWith(): bool
    {
        return (!empty($this->string_to_replace_with));
    }

    /**
     * Returns the currently set replace string.
     * NOTE: This method will return an empty string if no
     * replace string has been set.
     * @return string
     */
    protected function getStringToReplaceWith()
    {
        return $this->string_to_replace_with;
    }

    /**
     * Sets the replace string to the given value.
     * @access  protected
     * @param   $string_or_array_to_replace_with
     * @return  bool
     */
    protected function setStringToReplaceWith($string_or_array_to_replace_with): bool
    {
        if (is_string($string_or_array_to_replace_with) || is_array($string_or_array_to_replace_with)) {
            $this->string_to_replace_with = $string_or_array_to_replace_with;
            return true;
        }
        return false;
    }

    /**
     * Returns boolean if search operations are case-sensitive or not.
     * @access  public
     * @return  bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->case_sensitive;
    }

    /**
     * Sets case sensitivity to the given value.
     * Given value must be boolean.
     * @access  public
     * @param   bool $bool
     * @return  ActiveStringInterface
     */
    public function setCaseSensitive(bool $bool): ActiveStringInterface
    {
        $this->case_sensitive = $bool;
        return $this;
    }

    /**
     * Sets case sensitivity to true
     * @access  public
     * @return  ActiveStringInterface
     */
    public function caseSensitive(): ActiveStringInterface
    {
        return $this->setCaseSensitive(true);
    }

    /**
     * Sets case sensitivity to false
     * @access  public
     * @return  ActiveStringInterface
     */
    public function caseInsensitive(): ActiveStringInterface
    {
        return $this->setCaseSensitive(false);
    }

    /**
     * Sets the string index to the given value.
     * @access  protected
     * @param   int $index_value
     */
    protected function setStringIndexTo(int $index_value)
    {
        $this->current_string_index = $index_value;
    }

    /**
     * Will increment the string index by the given value.
     * @access  protected
     * @param   int $increment_by
     */
    protected function incrementStringIndex(int $increment_by = 1)
    {
        $this->current_string_index += $increment_by;
    }

    /**
     * Will decrement the string index by the given value.
     * @access  protected
     * @param   int $decrement_by
     * @return  void
     */
    protected function decrementStringIndex(int $decrement_by = 1): void
    {
        $this->current_string_index -= $decrement_by;
    }

    /**
     * Updates the string state with a decoded version of the string.
     * @access  public
     * @uses    \activeseven\ActiveString::decodeUrl()
     * @return  ActiveStringInterface
     */
    public function decodeUrl(): ActiveStringInterface
    {
        $this->updateStringState(urldecode($this->get()));
        return $this;
    }

    /**
     * Updates teh string state with an encoded version of the string.
     * @access  public
     * @uses    \activeseven\ActiveString::updateStringState()
     * @return  ActiveStringInterface
     */
    public function encodeUrl(): ActiveStringInterface
    {
        $this->updateStringState(urlencode($this->get()));
        return $this;
    }

    /**
     * Sets the current operation to uppercase.
     * @access  public
     * @uses    \activeseven\ActiveString::setCurrentOperation()
     * @return  ActiveStringInterface
     */
    public function toUppercase(): ActiveStringInterface
    {
        $this->setCurrentOperation(ActiveStringConstants::UPPERCASE);
        return $this;
    }

    /**
     * Sets the current operation to lowercase.
     * @access  public
     * @uses    \activeseven\ActiveString::setCurrentOperation()
     * @return  ActiveStringInterface
     */
    public function toLowercase(): ActiveStringInterface
    {
        $this->setCurrentOperation(ActiveStringConstants::LOWERCASE);
        return $this;
    }

    /**
     * Filters the string by the given parameters.
     * @access  protected
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

    /**
     * Returns boolean if the current string is considered to be a URL.
     * @access  public
     * @return  bool
     */
    public function isUrl(): bool
    {
        return $this->filterString(FILTER_VALIDATE_URL, null, false);
    }

    /**
     * Returns boolean if the current string is an email address.
     * @access  public
     * @return  bool
     */
    public function isEmail(): bool
    {
        return $this->filterString(FILTER_VALIDATE_EMAIL,null, false);
    }

    /**
     * Returns boolean if the current string is an IP address.
     * @access  public
     * @return  bool
     */
    public function isIp(): bool
    {
        return $this->filterString(FILTER_VALIDATE_IP, null, false);
    }

    /**
     * Returns boolean if the current string is a valid ipv6 address.
     * @access  public
     * @return  bool
     */
    public function isIpv6(): bool
    {
        return $this->filterString(FILTER_VALIDATE_IP, FILTER_FLAG_IPV6, false);
    }

    /**
     * Returns boolean if the current string is an valid ipv4 address.
     * @access  public
     * @return  bool
     */
    public function isIpv4(): bool
    {
        return $this->filterString(FILTER_VALIDATE_IP, FILTER_FLAG_IPV4, false);
    }

    /**
     * Returns boolean if the current string is a valid domain.
     * @access  public
     * @return  bool
     */
    public function isDomain(): bool
    {
        return $this->filterString(FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME, false);
    }

    /**
     * Returns boolean if the current string is a valid mac address.
     * @access  public
     * @return  bool
     */
    public function isMac(): bool
    {
        return $this->filterString(FILTER_VALIDATE_MAC, null, false);
    }

    /**
     * Will sanitize the current string as an email address.
     * @access  public
     * @return  ActiveStringInterface
     */
    public function sanitizeEmail(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_EMAIL);
        return $this;
    }

    /**
     * Strips characters with an ASCII value less than 32
     * @access  public
     * @return  ActiveStringInterface
     */
    public function stripLow(): ActiveStringInterface
    {
        $this->filterString(FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_LOW);
        return $this;
    }

    /**
     * Strips characters with an ASCII value greater than 127
     * @access  public
     * @return  ActiveStringInterface
     */
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

    /**
     * Returns boolean if the current string contains the given string.
     * @access  public
     * @param   string $string_to_find
     * @return  bool
     */
    public function contains(string $string_to_find ): bool
    {
        if( $this->isCaseSensitive() ) {
            $result = (strpos($this->get(),$string_to_find) !== false);
        } else {
            $result = (stripos($this->get(),$string_to_find) !== false);
        }
        return $result;
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

        // Reset everything and get ready for the next operation.
        $this->resetAll();

        // We're always going to return the result of the current opertation, but....
        $return_me = $this->__toString();
        // the user doesn't want the change to "stick" so just return the result but don't save
        // the result to the string state.
        if( !$sticky_change ) {
            $this->undo();
        }

        return $return_me;
    }

    /**
     * Returns the latest version of the string.
     * @access  public
     * @return  string
     */
    public function __toString(): string
    {
        return (string)$this->strings[array_key_last($this->strings)];
    }
}
