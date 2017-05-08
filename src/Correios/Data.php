<?php

namespace EscapeWork\Frete\Correios;

class Data
{
    /**
     * Urls
     */
    const URL_PRECO_PRAZO  = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
    const URL_RASTREAMENTO = 'https://webservice.correios.com.br/service/rastro/Rastro.wsdl';
    const URL_CONSULTA_CEP = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';

    /**
     * Sedex varejo
     */
    const SEDEX = '04014';

    /**
     * Sedex a cobrar varejo
     */
    const SEDEX_A_COBRAR = '40045';

    /**
     * Sedex 10 varejo
     */
    const SEDEX_10 = '04510';

    /**
     * Sedex hoje varejo
     */
    const SEDEX_HOJE = '40290';

    /**
     * PAC Varejo
     */
    const PAC = '04510';

    /**
     * Array com todos os códigos
     */
    public static $codigos = [
        '04014', '40045', '04510', '40290', '04510'
    ];
}
