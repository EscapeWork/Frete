<?php

namespace spec\EscapeWork\Frete\Correios;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RastreamentoResultSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('EscapeWork\Frete\Correios\RastreamentoResult');
    }

    function it_can_set_as_delivered_in_fill()
    {
        $this->fill((object) ['evento' => (object) ['tipo' => 'BDE', 'status' => '01']]);
        $this->delivered()->shouldBe(true);
    }

    function it_can_set_as_in_transit_in_fill()
    {
        $this->fill((object) ['evento' => (object) ['tipo' => 'DO', 'status' => '01']]);
        $this->inTransit()->shouldBe(true);
    }
}
