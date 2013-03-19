<?php namespace EscapeWork\Frete;

class Correios
{


    public 
        $nCdEmpresa,                # Seu código administrativo junto à ECT
        $sDsSenha,                  # Senha para acesso ao serviço
        $nCdServico,                # Código do serviço - Ver classe CodigoServico
        $sCepOrigem,                # CEP de Origem sem hífen.Exemplo: 05311900
        $sCepDestino,               # CEP de Destino sem hífen
        $nVlPeso,                   # Peso da encomenda, incluindo sua embalagem. O peso deve ser informado em quilogramas. Se o formato for Envelope, o valor máximo permitido será 1 kg;
        $nCdFormato = 1,            # Formato da encomenda (incluindo embalagem). Valores possíveis: 1, 2 ou 3 1 – Formato caixa/pacote | 2 – Formato rolo/prisma | 3 - Envelope
        $nVlComprimento,            # Comprimento da encomenda (incluindo embalagem), em centímetros.
        $nVlAltura,                 # Altura da encomenda (incluindo embalagem), em centímetros. Se o formato for envelope, informar zero (0).
        $nVlLargura,                # Largura da encomenda (incluindo embalagem), em centímetros.
        $nVlDiametro,               # Diâmetro da encomenda (incluindo embalagem), em centímetros.
        $sCdMaoPropria = 'N',       # S ou N; Indica se a encomenda será entregue com o serviço adicional mão própria;
        $nVlValorDeclarado = 0,     # Valor em Reais; Indica se a encomenda será entregue com o serviço adicional valor declarado;
        $sCdAvisoRecebimento = 'N'; # S ou N; Indica se a encomenda será entregue com o serviço adicional aviso de recebimento.

    public function setCodigoEmpresa($nCdEmpresa)
    {
        $this->nCdEmpresa = $nCdEmpresa;

        return $this;
    }

    public function setSenha($sDsSenha)
    {
        $this->sDsSenha = $sDsSenha;

        return $this;
    }

    public function setCodigoServico($nCdServico)
    {
        $this->nCdServico = $nCdServico;

        return $this;
    }

    public function setCepOrigem($sCepOrigem)
    {
        $this->sCepOrigem = $sCepOrigem;

        return $this;
    }
}