<?php namespace EscapeWork\Frete;

class CorreiosTest extends \PHPUnit_Framework_TestCase
{

    public function testSetCodigoEmpresa()
    {
        $correios = new Correios();
        $correios->setCodigoEmpresa('123');

        $this->assertEquals($correios->nCdEmpresa, '123');
    }

    public function testSetSenha()
    {
        $correios = new Correios();
        $correios->setSenha('123');

        $this->assertEquals($correios->sDsSenha, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetCodigoServicoShouldThrowInvalidArgumentException()
    {
        $correios = new Correios();
        $correios->setCodigoServico('123');

        $this->assertEquals($correios->nCdServico, '123');
    }

    public function testSetCodigoServico()
    {
        $correios = new Correios();
        $correios->setCodigoServico(CodigoServico::SEDEX);

        $this->assertEquals($correios->nCdServico, CodigoServico::SEDEX);
    }

    public function testSetCepOrigem()
    {
        $correios = new Correios();
        $correios->setCepOrigem('123');

        $this->assertEquals($correios->sCepOrigem, '123');
    }

    public function testSetCepDestino()
    {
        $correios = new Correios();
        $correios->setCepDestino('123');

        $this->assertEquals($correios->sCepDestino, '123');
    }

    public function testSetPeso()
    {
        $correios = new Correios();
        $correios->setPeso('123');

        $this->assertEquals($correios->nVlPeso, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetFormatoShouldThrowInvalidArgumentException()
    {
        $correios = new Correios();
        $correios->setFormato('123');

        $this->assertEquals($correios->nCdFormato, '123');
    }

    public function testSetFormato()
    {
        $correios = new Correios();
        $correios->setFormato(1);

        $this->assertEquals($correios->nCdFormato, 1);
    }

    public function testSetComprimento()
    {
        $correios = new Correios();
        $correios->setComprimento('123');

        $this->assertEquals($correios->nVlComprimento, '123');
    }

    public function testSetAltura()
    {
        $correios = new Correios();
        $correios->setAltura('123');

        $this->assertEquals($correios->nVlAltura, '123');
    }

    public function testSetLargura()
    {
        $correios = new Correios();
        $correios->setLargura('123');

        $this->assertEquals($correios->nVlLargura, '123');
    }

    public function testSetDiametro()
    {
        $correios = new Correios();
        $correios->setDiametro('123');

        $this->assertEquals($correios->nVlDiametro, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetMaoPropriaShouldThrowInvalidArgumentException()
    {
        $correios = new Correios();
        $correios->setMaoPropria('123');

        $this->assertEquals($correios->sCdMaoPropria, '123');
    }

    public function testSetMaoPropria()
    {
        $correios = new Correios();
        $correios->setMaoPropria('S');

        $this->assertEquals($correios->sCdMaoPropria, 'S');
    }

    public function testSetValorDeclarado()
    {
        $correios = new Correios();
        $correios->setValorDeclarado('123');

        $this->assertEquals($correios->nVlValorDeclarado, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetAvisoRecebimentoShouldThrowInvalidArgumentException()
    {
        $correios = new Correios();
        $correios->setAvisoRecebimento('123');

        $this->assertEquals($correios->sCdAvisoRecebimento, '123');
    }

    public function testSetAvisoRecebimento()
    {
        $correios = new Correios();
        $correios->setAvisoRecebimento('S');

        $this->assertEquals($correios->sCdAvisoRecebimento, 'S');
    }
}