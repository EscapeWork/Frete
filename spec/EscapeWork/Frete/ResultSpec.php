<?php

namespace spec\EscapeWork\Frete;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResultSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('EscapeWork\Frete\Result');
    }

    function it_fill_attributes()
    {
        $this->fill(['title' => 'Testing result']);
        $this->title->shouldBe('Testing result');
    }
}
