<?php

use PHPUnit\Framework\TestCase;

class ActiveStringTest extends TestCase
{
    public function test_play()
    {
        $this->assertTrue(true);
    }

    public function test_class_returns_the_string_it_was_set_to()
    {
        $test_string    = 'Just s0m3 s!lly $tring.';
        $active_string  = new \activeseven\ActiveString( $test_string );

        $this->assertEquals($test_string,$active_string);
    }

    public function test_class_returns_empty_string_when_not_set()
    {
        $active_string = new \activeseven\ActiveString();
        $this->assertEquals('',$active_string);
    }

    public function test_class_count_returns_one_when_no_string_set()
    {
        $active_string = new \activeseven\ActiveString();
        $this->assertEquals(1,$active_string->getSizeOfStringHistory());
    }

    public function test_class_returns_all_uppercase()
    {
        $test_string    = 'this is an all lowercase string.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = strtoupper($test_string);
        $received       = $active_string->toUppercase()->get();

        $this->assertEquals($expected,$received);
    }

    public function test_class_returns_sub_string()
    {
        $test_string    = "I am the very model of a modern major general.";
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = substr($test_string,0,45);
        $received       = $active_string->from(0)->to(45)->get();
        $this->assertEquals($expected,$received);

        $expected       = substr($test_string,14,5);
        $received       = $active_string->from(14)->forLength(5)->get();
        $this->assertEquals($expected,$received);

    }

    // UPPERCASE Tests
    public function test_class_returns_uppercase_with_from()
    {
        $test_string    = 'This is a string to capitalize for this test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to CAPITALIZE FOR THIS TEST.';
        $received       = $active_string->toUppercase()->from(20)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_min_value()
    {
        $test_string    = 'this is a string to capitalize for this silly test';;
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE FOR THIS SILLY TEST';;
        $received       = $active_string->toUppercase()->from(0)->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_max_value()
    {
        $test_string    = 'This is a string to capitalize for this test';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to capitalize for this test';
        $received       = $active_string->toUppercase()->from(44)->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to CAPITALIZE FOR THIS TEST.';
        $received       = $active_string->toUppercase()->from(65)->get();
        $this->assertEquals($expected,$received);

        $received       = $active_string->toUppercase()->from(110)->get();
        $this->assertEquals($expected,$received);
    }

    public function test_class_returns_uppercase_with_negative_from()
    {
        $test_string    = 'This is a string to capitalize for this test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to CAPITALIZE FOR THIS TEST.';
        $received       = $active_string->toUppercase()->from(-25)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_from_min_value()
    {
        $test_string    = 'This is a string to capitalize for this silly test';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to capitalize for this silly tesT';
        $received       = $active_string->toUppercase()->from(-1)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_from_max_value()
    {
        $test_string    = 'this is a string to capitalize for this silly test';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE FOR THIS SILLY TEST';
        $received       = $active_string->toUppercase()->from(-50)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_from_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to CAPITALIZE FOR THIS TEST.';
        $received       = $active_string->toUppercase()->from(-70)->get();
        $this->assertEquals($expected,$received);

        $received       = $active_string->toUppercase()->from(-115)->get();
        $this->assertEquals($expected,$received);
    }

    public function test_class_returns_uppercase_with_to()
    {
        $test_string    = 'This is a string to capitalize for this test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE for this test.';
        $received       = $active_string->toUppercase()->to(30)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_to_min_value()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to capitalize for this cool test.';
        $received       = $active_string->toUppercase()->to(0)->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_to_max_value()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to capitalize for this cool test.';
        $received       = $active_string->toUppercase()->to(50)->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_to_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO capitalize for this test.';

        $received       = $active_string->toUppercase()->to(65)->get();
        $this->assertEquals($expected,$received);
    }

    public function test_class_returns_uppercase_with_negative_to()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO capitalize for this cool test.';
        $received       = $active_string->toUppercase()->to(-30)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_to_min_value()
    {
        $test_string    = 'This is a string to capitalize for this silly test';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE FOR THIS SILLY TESt';
        $received       = $active_string->toUppercase()->to(-1)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_to_max_value()
    {
        $test_string    = 'this is a string to capitalize for this silly test';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'this is a string to capitalize for this silly test';
        $received       = $active_string->toUppercase()->to(-50)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_to_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO capitalize for this cool test.';
        $received       = $active_string->toUppercase()->to(-80)->get();
        $this->assertEquals($expected,$received);

        $received       = $active_string->toUppercase()->to(-130)->get();
        $this->assertEquals($expected,$received);
    }

    public function test_class_returns_uppercase_with_length() {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE for this cool test.';
        $received       = $active_string->toUppercase()->forThisManyCharacters(30)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_length_min_value()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to capitalize for this cool test.';
        $received       = $active_string->toUppercase()->forThisManyCharacters(0)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_length_max_value()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE FOR THIS COOL TEST.';
        $received       = $active_string->toUppercase()->forThisManyCharacters(50)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_length_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE for this cool test.';
        $received       = $active_string->toUppercase()->forThisManyCharacters(80)->get();
        $this->assertEquals($expected,$received);
    }

    public function test_class_returns_uppercase_with_negative_length() {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALize for this cool test.';
        $received       = $active_string->toUppercase()->forThisManyCharacters(-23)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_length_min_value()
    {
        $test_string    = 'this is a string to capitalize for this silly test';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE FOR THIS SILLY TESt';
        $received       = $active_string->toUppercase()->forThisManyCharacters(-1)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_length_max_value()
    {
        $test_string    = 'this is a string to capitalize for this silly test';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'this is a string to capitalize for this silly test';
        $received       = $active_string->toUppercase()->forThisManyCharacters(-50)->get();
        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_negative_length_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE for this cool test.';
        $received       = $active_string->toUppercase()->forThisManyCharacters(-70)->get();
        $this->assertEquals($expected,$received);
    }

    public function test_class_returns_uppercase_with_from_and_length()
    {
        $test_string    = 'This is a string to capitalize for this cool testy';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to CAPITALIZE for this cool testy';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(20)
                            ->forThisManyCharacters(10)
                            ->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_and_length_min_value()
    {
        $test_string    = 'This is a string to capitalize for this cool testy';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to capitalize for this cool testy';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(20)
                            ->forThisManyCharacters(0)
                            ->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_and_length_max_value()
    {
        $test_string    = 'This is a string to capitalize for this cool testy';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to CAPITALIZE FOR THIS COOL TESTY';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(20)
                            ->forThisManyCharacters(30)
                            ->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_and_length_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this cool testy';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to CAPITALIZE FOR THIS COOL TESTY';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(20)
                            ->forThisManyCharacters(99)
                            ->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_overloaded_and_length_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this cool testy';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a string to cAPITALIZE FOR THIS COOL TESTY';
        $received       = $active_string->toUppercase()
            ->fromThisCharacterPosition(71)
            ->forThisManyCharacters(99)
            ->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_from_and_negative_length()
    {
        $test_string    = 'This is a string toe capitalize for this cool test';
        $active_string  = new \activeseven\ActiveString($test_string);

        // Start should be 11 for a length of 9
        $expected       = 'This is a sTRING TOE capitalize for this cool test';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(20)
                            ->forThisManyCharacters(-9)
                            ->get();

        $this->assertEquals($expected,$received);
    }
/*    public function test_class_returns_uppercase_with_offset_and_negative_length_min_value()
    {
        $test_string    = 'This is a string to capitalize for this cool testy';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'This is a strinG to capitalize for this cool testy';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(16)
                            ->forThisManyCharacters(-1)
                            ->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_offset_and_negative_length_max_value()
    {
        $test_string    = 'This is a string to capitalize for this cool testy';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING TO CAPITALIZE for this cool testy';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(30)
                            ->forThisManyCharacters(-30)
                            ->get();

        $this->assertEquals($expected,$received);
    }
    public function test_class_returns_uppercase_with_offset_and_negative_length_overloaded()
    {
        $test_string    = 'This is a string to capitalize for this cool test.';
        $active_string  = new \activeseven\ActiveString($test_string);

        $expected       = 'THIS IS A STRING to capitalize for this cool test.';
        $received       = $active_string->toUppercase()
                            ->fromThisCharacterPosition(16)
                            ->forThisManyCharacters(-99)
                            ->get();

        $this->assertEquals($expected,$received);
    }*/
//
//
//    public function test_class_returns_uppercase_with_negative_offset_length()
//    {
//        $test_string    = 'This is a string to capitalize for this test.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'This is a string to CAPITALIZE for this test.';
//        $received       = $active_string->toUppercase()
//                            ->fromThisCharacterPosition(-25)
//                            ->forThisManyCharacters(10)
//                            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_uppercase_with_negative_offset_and_negative_length()
//    {
//        $test_string    = 'This is a string to capitalize for this test.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'This is a string to CAPITALIZE for this test.';
//        $received       = $active_string->toUppercase()
//                            ->fromThisCharacterPosition(-25)
//                            ->forThisManyCharacters(-15)
//                            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_uppercase_with_offset_and_to()
//    {
//        $test_string    = 'This is a string to capitalize for this test.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'This is a string to CAPITALIZE for this test.';
//        $received       = $active_string->toUppercase()
//            ->fromThisCharacterPosition(20)
//            ->toThisCharacterPosition(30)
//            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_uppercase_with_negative_offset_to()
//    {
//        $test_string    = 'This is a string to capitalize for this test.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'This is a string to CAPITALIZE for this test.';
//        $received       = $active_string->toUppercase()
//            ->fromThisCharacterPosition(-25)
//            ->toThisCharacterPosition(30)
//            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//
//    // LOWERCASE Tests
//    public function test_class_returns_lowercase_with_offset_only()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS COOL TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO capitalize for this cool test.';
//        $received       = $active_string->toLowercase()->from(20)->get();
//        $this->assertEquals($expected,$received);
//
//        $received       = $active_string->toLowercase()->from(70)->get();
//        $this->assertEquals($expected,$received);
//
//        $received       = $active_string->toLowercase()->from(120)->get();
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_offset_min_value()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'this is a string to capitalize for this test.';
//        $received       = $active_string->toLowercase()->from(0)->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_offset_in_middle()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO capitalize for this test.';
//        $received       = $active_string->toLowercase()->from(20)->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_offset_max_value()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST';
//        $received       = $active_string->toUppercase()->from(44)->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_offset_negative()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO CAPITALIZE FOR THIS test.';
//        $received       = $active_string->toLowercase()->from(-5)->get();
//        $this->assertEquals($expected,$received);
//
//        $received       = $active_string->toLowercase()->from(-50)->get();
//        $this->assertEquals($expected,$received);
//
//        $received       = $active_string->toLowercase()->from(-95)->get();
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_offset_and_length()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO capitalize FOR THIS TEST.';
//        $received       = $active_string->toLowercase()
//            ->fromThisCharacterPosition(20)
//            ->forThisManyCharacters(10)
//            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_offset_and_to()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO capitalize FOR THIS TEST.';
//        $received       = $active_string->toLowercase()
//            ->fromThisCharacterPosition(20)
//            ->toThisCharacterPosition(30)
//            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_negative_offset_length()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO capitalize FOR THIS TEST.';
//        $received       = $active_string->toLowercase()
//            ->fromThisCharacterPosition(-25)
//            ->forThisManyCharacters(10)
//            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_lowercase_with_negative_offset_to()
//    {
//        $test_string    = 'THIS IS A STRING TO CAPITALIZE FOR THIS TEST.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = 'THIS IS A STRING TO capitalize FOR THIS TEST.';
//        $received       = $active_string->toLowercase()
//            ->fromThisCharacterPosition(-25)
//            ->toThisCharacterPosition(30)
//            ->get();
//
//        $this->assertEquals($expected,$received);
//    }
//
//
//
//    public function test_class_returns_all_lowercase()
//    {
//        $test_string    = 'THIS IS AN ALL UPPERCASE STRING';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = strtolower($test_string);
//        $received       = $active_string->toLowercase()->get();
//        $this->assertEquals($expected,$received);
//    }
//
//    public function test_class_returns_correct_string_when_undone()
//    {
//        $original_string    = 'This is a test string';
//        $active_string      = new \activeseven\ActiveString($original_string);
//
//        $modified_string    = strtoupper($original_string);
//        $received           = $active_string->toUppercase()->get();
//        $this->assertEquals($modified_string,$received);
//
//        $undone_string      = $active_string->undo()->get();
//        $this->assertEquals($original_string,$undone_string);
//    }
//
//    public function test_class_cannot_undo_original_string()
//    {
//        $test_string    = 'This is a test string';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $active_string->undo();
//        $this->assertEquals($test_string,$active_string);
//    }
//
//    // Testing Validators
//
//    public function test_class_can_validate_url()
//    {
//        $is_url_string      = 'https://www.somedomain.com';
//
//        $active_string      = new \activeseven\ActiveString($is_url_string);
//        $this->assertTrue(
//            $active_string->isUrl(),
//            "ActiveString::isUrl thinks {$is_url_string} is NOT a valid URL."
//        );
//    }
//
//    public function test_class_can_validate_domain()
//    {
//        $is_domain_string   = 'www.activeseven.com'; // Gonna put a site here one day LOL
//
//        $active_string = new \activeseven\ActiveString($is_domain_string);
//        $this->assertTrue(
//            $active_string->isDomain(),
//            "ActiveString::isDomain() thinks {$is_domain_string} us not a valid domain."
//        );
//    }
//
//    public function test_class_can_validate_email()
//    {
//        $is_email_string    = 'someone@somehwere.com';
//
//        $active_string      = new \activeseven\ActiveString($is_email_string);
//        $this->assertTrue(
//            $active_string->isEmail(),
//            "ActiveString::isEmail() thinks {$is_email_string} is NOT a valid email."
//        );
//    }
//
//    public function test_class_can_validate_ipv6()
//    {
//        $ipv6_string    = '2001:db8:3333:4444:5555:6666:7777:8888';
//
//        $active_string  = new \activeseven\ActiveString($ipv6_string);
//        $this->assertTrue(
//            $active_string->isIpv6(),
//            "ActiveString::isIpv6() thinks {$ipv6_string} is NOT a valid IPv6 address."
//        );
//    }
//
//    public function test_class_can_validate_ipv4()
//    {
//        $ipv4_string    = '192.0.2.146';
//
//        $active_string  = new \activeseven\ActiveString($ipv4_string);
//        $this->assertTrue(
//            $active_string->isIpv4(),
//            "ActiveString::isIpv4() thinks {$ipv4_string} is NOT a valid IPv4 address."
//        );
//    }
//
//    public function test_class_can_validate_ip()
//    {
//        $ipv4_string    = '192.0.2.199';
//        $ipv6_string    = '2001:db8:8888:4444:5555:6666:7777:3333';
//
//        $ipv4   = new \activeseven\ActiveString($ipv4_string);
//        $ipv6   = new \activeseven\ActiveString($ipv6_string);
//
//        $this->assertTrue(
//            $ipv4->isIp(),
//            "ActiveString::isIp() thinks {$ipv4} is NOT a valid IP address."
//        );
//        $this->assertTrue(
//            $ipv6->isIp(),
//            "ActiveString::isIp() thinks {$ipv6} is NOT a valid IP address."
//        );
//    }
//
//    public function test_class_can_validate_mac()
//    {
//        $mac_string     = '00:1B:44:11:3A:B7';
//
//        $active_string  = new \activeseven\ActiveString($mac_string);
//        $this->assertTrue(
//            $active_string->isMac(),
//            "ActiveString::isMac() thinks {$mac_string} is NOT a valid mac address."
//        );
//    }
//
//    public function test_class_can_urlencode_string()
//    {
//        $url_to_encode  = 'https://www.somewhere.com/index.php?option=1&argument=false';
//        $active_string  = new \activeseven\ActiveString($url_to_encode);
//
//        $this->assertEquals(
//            urlencode($url_to_encode),
//            $active_string->encodeUrl()->get()
//        );
//    }
//
//    public function test_class_can_urldecode_string()
//    {
//        $url_encoded    = 'https%3A%2F%2Fwww.somewhere.com%2Findex.php%3Foption%3D1%26argument%3Dfalse';
//        $active_string  = new \activeseven\ActiveString($url_encoded);
//
//        $this->assertEquals(
//            urldecode($url_encoded),
//            $active_string->decodeUrl()->get()
//        );
//    }

    // Testing Sanitizers

//    public function test_class_can_sanitize_email()
//    {
//        $email_to_sanitize  = 'someone\@somehwere<>./com';
//        $active_string  = new \activeseven\ActiveString($email_to_sanitize);
//
//        $this->assertEquals(
//            filter_var($email_to_sanitize,FILTER_SANITIZE_EMAIL),
//            $active_string->sanitizeEmail()->get()
//        );
//    }
//
//    public function test_class_can_strip_low()
//    {
//        $test_string    = 'https://www.somedomain.com/index.html?message=blah' . chr(24);
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $url_sanitized  = $active_string->stripLow()->get();
//        $url_should_be  = filter_var($test_string,FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_LOW);
//
//        $this->assertEquals(
//            $url_should_be,$url_sanitized,
//            "ActiveString::stripLow() failed to strip ASCII characters below 32."
//        );
//    }
//
//    public function test_class_can_strip_high()
//    {
//        $test_string    = 'https://www.somedomain.com/index.html?message=blah' . chr(255);
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $url_sanitized  = $active_string->stripHigh()->get();
//        $url_should_be  = filter_var($test_string,FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_HIGH);
//
//        $this->assertEquals(
//            $url_should_be,$url_sanitized,
//            "ActiveString::stripHigh() failed to strip ASCII chars over 127."
//        );
//
//    }
//
//    public function test_class_can_strip_backtick()
//    {
//        $test_string    = 'some string with a ` backtick';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $string_should_be   = filter_var($test_string,FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_BACKTICK);
//        $string_sanitized   = $active_string->stripBacktick()->get();
//
//        $this->assertEquals(
//            $string_should_be, $string_sanitized,
//            "ActiveString:stripBacktick() failed to strip backticks."
//        );
//    }
//
//    public function test_class_can_encode_low()
//    {
//        // Sooooo...ascii 32 is the space character and we got some trimming going on here.
//        $test_string    = 'some string with Ascii values lower than 32' . chr(1) . chr(31);
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $string_should_be   = filter_var($test_string, FILTER_SANITIZE_ENCODED, FILTER_FLAG_ENCODE_LOW);
//        $string_encoded     = $active_string->encodeLow()->get();
//
//        $this->assertEquals(
//            $string_should_be, $string_encoded,
//            'ActiveString:encodeLow() failed to encode ascii values below 32.'
//        );
//    }
//
//    public function test_class_can_encode_high()
//    {
//        $test_string    = 'some string with Ascii values higher than 127' . chr(127) . chr(255);
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $string_should_be   = filter_var($test_string, FILTER_SANITIZE_ENCODED, FILTER_FLAG_ENCODE_HIGH);
//        $string_encoded     = $active_string->encodeHigh()->get();
//
//        $this->assertEquals(
//            $string_should_be, $string_encoded,
//            'ActiveString:encodeHigh() failed to encode ascii values above 127'
//        );
//    }
//
//    public function test_class_can_magic_quotes()
//    {
//        // @TODO Deprecated as of 7.3 and totally not a thing in 8.0.
//        // We're not running 8.0 yet soooooo.....
//        $test_string    = 'some string with "quotes" in it.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $string_should_be   = filter_var( $test_string, FILTER_SANITIZE_MAGIC_QUOTES);
//        $string_is          = $active_string->magicQuotes()->get();
//
//        $this->assertEquals(
//            $string_should_be, $string_is,
//            'ActiveString:magicQuotes() failed to make the quotes magical!'
//        );
//    }
//
//    public function test_class_can_add_slashes()
//    {
//        // Only available as of 7.3
//        $test_string    = 'some string with "quotes" in it.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $string_should_be   = filter_var($test_string, FILTER_SANITIZE_ADD_SLASHES);
//        $string_is          = $active_string->addSlashes()->get();
//
//        $this->assertEquals(
//            $string_should_be, $string_is,
//            'ActiveString:addSlashes() failed to add slashes.'
//        );
//    }
//
//    public function test_class_can_html_encode()
//    {
//        $test_string    = 'some <string> & to "HTML" encode.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $string_should_be   = filter_var($test_string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $string_is          = $active_string->htmlEncode()->get();
//
//        $this->assertEquals(
//            $string_should_be,$string_is,
//            'ActiveString:htmlEncode() failed to HTML encode the string.'
//        );
//    }
//
//    public function test_class_can_html_encode_without_quotes()
//    {
//        $test_string    = 'some <string> $ to "HTML" encode without the quotes though.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $string_should_be   = filter_var(
//            $test_string,
//            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
//            FILTER_FLAG_NO_ENCODE_QUOTES
//        );
//        $string_is  = $active_string->htmlEncodeNoQuotes()->get();
//
//        $this->assertEquals(
//            $string_should_be, $string_is,
//            'ActiveString:htmlEncodeNowQuotes() failed to encode without quotes :('
//        );
//    }
//
//    public function test_class_can_check_if_string_has_another_string()
//    {
//        $test_string    = "Some string to use for this string test.";
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        //echo $active_string->contains('string');
//        $this->assertTrue(
//            $active_string->contains("Some")
//        );
//
//        $this->assertFalse(
//            $active_string->contains('who')
//        );
//    }
//
//    public function test_class_can_return_sub_string()
//    {
//        $test_string    = "Some string to use for this crazy-ass test.";
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $sub_string     = $active_string->from(28)->to(37)->get();
//        $this->assertEquals(
//            'crazy-ass',
//            $sub_string
//        );
//
//        $this->assertEquals(
//            $test_string,
//            $active_string->undo()->get(),
//            "ActiveString:undo() was not able to under to/from operation."
//        );
//    }
//
//    public function test_class_can_return_sub_string_with_sticky_change()
//    {
//        $test_string    = "Some string to use for this dumb-shit test.";
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $sub_string     = $active_string->from(28)->to(37)->get(false);
//        $this->assertEquals(
//            'dumb-shit',
//            $sub_string
//        );
//
//        $this->assertEquals(
//            $test_string,
//            $active_string,
//            "ActiveString:get() Did not render the string mutable when false was passed."
//        );
//    }
//
//    public function test_class_returns_position_of_string_case_sensitive()
//    {
//        $test_string    = "Some string to use for this ass-munchin test.";
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $position_of    = $active_string->caseSensitive()->positionOf('ass-munchin');
//        $this->assertEquals(
//            28,
//            $position_of
//        );
//
//        $position_of    = $active_string->caseSensitive()->positionOf('Ass-Munchin');
//        $this->assertFalse($position_of);
//    }
//
//    public function test_class_returns_position_of_string_case_insensitive()
//    {
//        $test_string    = "Some string to use for this ass-munchin test.";
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $position_of    = $active_string->caseInsensitive()->positionOf('Ass-Munchin');
//        $this->assertEquals(
//            28,
//            $position_of
//        );
//    }
//
//    public function test_class_returns_false_when_string_position_not_found()
//    {
//        $test_string    = "Some string to use for this titty-twisting test.";
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $this->assertFalse($active_string->positionOf("purple-nurple"));
//    }
//
//    public function test_class_can_replace_strings_with_string()
//    {
//        $test_string    = 'Some string to use for this titty-twisting test.';
//        $search_for     = 'titty-twisting';
//        $replace_with   = 'poop-scoopin';
//        $active_seven   = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_replace($search_for,$replace_with,$test_string);
//        $result         = $active_seven->replace($search_for)->with($replace_with)->get();
//        $this->assertEquals(
//            $expected,
//            $result
//        );
//    }
//
//    public function test_class_can_replace_strings_with_array_of_same_size()
//    {
//        $test_string    = 'Some silly-philly string to use for this titty-twisting test.';
//        $search_for     = ['silly-philly','titty-twisting'];
//        $replace_with   = ['chilly-whilly','popp-scoopin'];
//        $active_seven   = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_replace($search_for,$replace_with,$test_string);
//        $result         = $active_seven->replace($search_for)->with($replace_with)->get();
//
//        $this->assertEquals(
//            $expected,
//            $result,
//            "ActiveString:replace/with() failed to replace with 2 arrays of identical length."
//        );
//    }
//
//    public function test_class_can_replace_strings_with_search_array_larger_than_replace_array()
//    {
//        $test_string    = 'Some silly-philly string to use for this titty-twisting test.';
//        $search_for     = ['silly-philly','titty-twisting'];
//        $replace_with   = ['chilly-whilly'];
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_replace($search_for,$replace_with,$test_string);
//        $result         = $active_string->replace($search_for)->with($replace_with)->get();
//
//        $this->assertEquals(
//            $expected,
//            $result,
//            "ActiveString:replace/with() failed to replace with search array larger than replace array."
//        );
//    }
//
//    public function test_class_can_replace_strings_with_search_array_and_replace_as_string()
//    {
//        $test_string    = 'Some silly-philly string to use for this titty-twisting test.';
//        $search_for     = ['silly-philly','titty-twisting'];
//        $replace_with   = 'same-thing';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_replace($search_for,$replace_with,$test_string);
//        $result         = $active_string->replace($search_for)->with($replace_with)->get();
//
//        $this->assertEquals(
//            $expected,
//            $result,
//            "ActiveString:replace/with() failed to replace with search array and replace string."
//        );
//
//    }
//
//    public function test_class_can_toggle_case_sensitivity()
//    {
//        $active_string  = new \activeseven\ActiveString();
//        $active_string->caseSensitive();
//        $this->assertTrue($active_string->isCaseSensitive());
//
//        $active_string->caseInsensitive();
//        $this->assertFalse($active_string->isCaseSensitive());
//    }
//
//    public function test_class_can_replace_strings_with_string_case_insensitive()
//    {
//        $test_string    = 'Some string to use for this Titty-Twisting test.';
//        $search_for     = 'titty-twisting';
//        $replace_with   = 'poop-scoopin';
//        $active_string   = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_ireplace($search_for,$replace_with,$test_string);
//        $result         = $active_string->caseInsensitive()->replace($search_for)->with($replace_with)->get();
//        $this->assertEquals(
//            $expected,
//            $result
//        );
//    }
//
//    public function test_class_can_replace_strings_with_array_of_same_size_case_insensitive()
//    {
//        $test_string    = 'Some Silly-Philly string to use for this Titty-Twisting test.';
//        $search_for     = ['silly-philly','titty-twisting'];
//        $replace_with   = ['chilly-whilly','poop-scoopin'];
//        $active_seven   = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_ireplace($search_for,$replace_with,$test_string);
//        $result         = $active_seven->caseInsensitive()->replace($search_for)->with($replace_with)->get();
//
//        $this->assertEquals(
//            $expected,
//            $result,
//            "ActiveString:replace/with() failed to replace with 2 arrays of identical length, case insensitive."
//        );
//    }
//
//    public function test_class_can_replace_strings_with_search_array_larger_than_replace_array_case_insensitive()
//    {
//        $test_string    = 'Some Silly-Philly string to use for this Titty-Twisting test.';
//        $search_for     = ['silly-philly','titty-twisting'];
//        $replace_with   = ['chilly-whilly'];
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_ireplace($search_for,$replace_with,$test_string);
//        $result         = $active_string->caseInsensitive()->replace($search_for)->with($replace_with)->get();
//
//        $this->assertEquals(
//            $expected,
//            $result,
//            "ActiveString:replace/with() failed to replace with search array larger than replace array, case insensitive."
//        );
//    }
//
//    public function test_class_can_replace_strings_with_search_array_and_replace_as_string_case_insensitive()
//    {
//        $test_string    = 'Some Silly-Philly string to use for this Titty-Twisting test.';
//        $search_for     = ['silly-philly','titty-twisting'];
//        $replace_with   = 'same-thing';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_ireplace($search_for,$replace_with,$test_string);
//        $result         = $active_string->caseInsensitive()->replace($search_for)->with($replace_with)->get();
//
//        $this->assertEquals(
//            $expected,
//            $result,
//            "ActiveString:replace/with() failed to replace with search array and replace string, case insensitive."
//        );
//
//    }
//
//    public function test_class_can_count_words_in_string()
//    {
//        $test_string    = 'Some Silly-Philly string to use for this Titty-Twisting test.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_word_count($test_string,0);
//        $result         = $active_string->getNumberOfWords();
//
//        $this->assertEquals($expected,$result);
//    }
//
//    public function test_class_can_count_words_in_string_with_user_specified_words()
//    {
//        $test_string    = 'Some s1lly string to use for this fri3ndly test.';
//        $extra_chars    = '13';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_word_count($test_string,0,$extra_chars);
//        $result         = $active_string->getNumberOfWords($extra_chars);
//
//        $this->assertEquals($expected,$result);
//
//    }
//
//    public function test_class_can_return_words_in_string()
//    {
//        $test_string    = 'Some Silly-Philly string to use for this Titty-Twisting test.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_word_count($test_string,1);
//        $result         = $active_string->getWords();
//
//        $this->assertEquals($expected,$result);
//    }
//
//    public function test_class_can_return_words_in_string_with_user_specified_words()
//    {
//        $test_string    = 'Some s1lly string to use for this fri3ndly test.';
//        $extra_chars    = '13';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_word_count($test_string,1,$extra_chars);
//        $result         = $active_string->getWords($extra_chars);
//
//        $this->assertEquals($expected,$result);
//    }
//
//    public function test_class_can_return_words_their_positions_from_string()
//    {
//        $test_string    = 'Some Silly-Philly string to use for this Titty-Twisting test.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_word_count($test_string,2);
//        $result         = $active_string->getWordsWithPositions();
//
//        $this->assertEquals($expected,$result);
//
//    }
//
//    public function test_class_can_return_words_their_positions_from_string_with_user_specified_words()
//    {
//        $test_string    = 'Some s1lly string to use for this fri3ndly test.';
//        $extra_chars    = '13';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = str_word_count($test_string,2,$extra_chars);
//        $result         = $active_string->getWordsWithPositions($extra_chars);
//
//        $this->assertEquals($expected,$result);
//    }
//
//    public function test_class_can_return_length_of_string()
//    {
//        $test_string    = 'I am the very model of a modern major general.';
//        $active_string  = new \activeseven\ActiveString($test_string);
//
//        $expected       = strlen($test_string);
//        $result         = $active_string->length();
//
//        $this->assertEquals($expected,$result);
//    }
}