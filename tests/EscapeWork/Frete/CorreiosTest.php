<?php namespace EscapeWork\Frete;

use EscapeWork\Frete\Correios;
use EscapeWork\Frete\CodigoServico;

class CorreiosTest extends \PHPUnit_Framework_TestCase
{

    public function test_set_codigo_empresa()
    {
        $correios = new Correios();
        $correios->setCodigoEmpresa('123');

        $this->assertEquals($correios->nCdEmpresa, '123');
    }

    public function test_set_senha()
    {
        $correios = new Correios();
        $correios->setSenha('123');

        $this->assertEquals($correios->sDsSenha, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_set_codigo_servico_should_throw_invalid_argument_exception()
    {
        $correios = new Correios();
        $correios->setCodigoServico('123');

        $this->assertEquals($correios->nCdServico, '123');
    }

    public function test_set_codigo_servico()
    {
        $correios = new Correios();
        $correios->setCodigoServico(CodigoServico::SEDEX);

        $this->assertEquals($correios->nCdServico, CodigoServico::SEDEX);
    }

    public function test_set_cep_origem()
    {
        $correios = new Correios();
        $correios->setCepOrigem('123');

        $this->assertEquals($correios->sCepOrigem, '123');
    }

    public function test_set_cep_destino()
    {
        $correios = new Correios();
        $correios->setCepDestino('123');

        $this->assertEquals($correios->sCepDestino, '123');
    }

    public function test_set_peso()
    {
        $correios = new Correios();
        $correios->setPeso('123');

        $this->assertEquals($correios->nVlPeso, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_set_formato_should_throw_invalid_argument_exception()
    {
        $correios = new Correios();
        $correios->setFormato('123');

        $this->assertEquals($correios->nCdFormato, '123');
    }

    public function test_set_formato()
    {
        $correios = new Correios();
        $correios->setFormato(1);

        $this->assertEquals($correios->nCdFormato, 1);
    }

    public function test_set_comprimento()
    {
        $correios = new Correios();
        $correios->setComprimento('123');

        $this->assertEquals($correios->nVlComprimento, '123');
    }

    public function test_set_altura()
    {
        $correios = new Correios();
        $correios->setAltura('123');

        $this->assertEquals($correios->nVlAltura, '123');
    }

    public function test_set_largura()
    {
        $correios = new Correios();
        $correios->setLargura('123');

        $this->assertEquals($correios->nVlLargura, '123');
    }

    public function test_set_diametro()
    {
        $correios = new Correios();
        $correios->setDiametro('123');

        $this->assertEquals($correios->nVlDiametro, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_set_mao_propria_should_throw_invalid_argument_exception()
    {
        $correios = new Correios();
        $correios->setMaoPropria('123');

        $this->assertEquals($correios->sCdMaoPropria, '123');
    }

    public function test_set_mao_propria()
    {
        $correios = new Correios();
        $correios->setMaoPropria('S');

        $this->assertEquals($correios->sCdMaoPropria, 'S');
    }

    public function test_set_valor_declarado()
    {
        $correios = new Correios();
        $correios->setValorDeclarado('123');

        $this->assertEquals($correios->nVlValorDeclarado, '123');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_set_aviso_recebimento_should_throw_invalid_argument_exception()
    {
        $correios = new Correios();
        $correios->setAvisoRecebimento('123');

        $this->assertEquals($correios->sCdAvisoRecebimento, '123');
    }

    public function test_set_aviso_recebimento()
    {
        $correios = new Correios();
        $correios->setAvisoRecebimento('S');

        $this->assertEquals($correios->sCdAvisoRecebimento, 'S');
    }

    public function test_get_data()
    {
        $correios  = new Correios();
        $queryData = $correios->getData();

        $this->assertTrue(is_string($queryData));
    }
}