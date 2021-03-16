<?php


namespace App;


use Illuminate\Http\Request;

class AlagamentoSilhuetaService
{
    /**
     * Matriz de valores que faz a representação da silhueta
     */
    public $silhueta = [];

    public $data;

    /**
     * Matriz de valores que faz a representação das áreas alagadas
     */
    public $alagamento = [];

    /**
     * Matriz de valores a serem somados para o resultado
     */
    public $somatorioAlagado = [];


    public function __construct()
    {
        $this->data = collect();
    }

    public function enviar(Request $request)
    {
        $silhueta = $request->input('silhouette');

        $proximaSilhueta = 1;

        $data = explode("\n", $silhueta);

        if (!$data) {
            throw new \Exception('Erro interno: arquivo com formato inválido!');
        }

        $quantidadeSilhueta = (int)$data[0];

        foreach ($data as $chave => $linha) {
            if ($this->continuar($chave, $proximaSilhueta)) {
                continue;
            }

            $this
                ->data
                ->push([
                    'key' => $chave,
                    'length' => $linha,
                    'data' => explode(' ', $data[$chave + 1])
                ]);

            $proximaSilhueta = $chave + 2;
        }

        if ($this->data->count() !== $quantidadeSilhueta) {
            throw new \Exception('Quantidade de silhuetas diferente da quantidade encontrada');
        }

        return $this
            ->data;
    }

    /**
     * Verificar se é para pular no loop
     *
     * @param $chave
     * @param $proximaSilhueta
     * @return bool
     */
    private function continuar($chave, $proximaSilhueta)
    {
        return $chave === ''
            || $chave === 0
            || $chave !== $proximaSilhueta;
    }

    /**
     * Alagar silhuetas
     *
     * @param array $silhueta
     */
    public function alagar(array $silhueta)
    {
        $valorAnterior = FALSE;
        $this->silhueta = $silhueta;

        foreach ($this->silhueta as $indiceCorrente => $valorCorrente) {
            if ($valorAnterior === FALSE) {
                $valorAnterior = $valorCorrente;
            }

            if ($valorCorrente > $valorAnterior) {
                $this->_setAlagamento($indiceCorrente);
            } else {
                if(count($this->alagamento)) {
                    $indiceBase = $this->_getIndiceBase($indiceCorrente);
                    $this->_soma($indiceBase);
                    $this->alagamento = [];
                }
            }

            $valorAnterior = $valorCorrente;
        }

        $this->_soma($valorCorrente);
    }

    /**
     * Método que totaliza a soma para obtenção do resultado final da área alagada
     */
    public function getResultado()
    {
        return array_sum($this->somatorioAlagado);
    }

    /**
     * Método para calcular o índice base para cálculo da área alagada e determinado pedaço
     */
    protected function _getIndiceBase($indiceCorrente)
    {
        $colunaReferencia = $this->silhueta[$this->_getIndiceColunaReferencia($indiceCorrente)];
        $valorCorrente = $this->silhueta[$indiceCorrente];

        return ($colunaReferencia < $valorCorrente) ? $colunaReferencia : $valorCorrente;
    }

    /**
     * Método que preenche uma determinada área alagada com base numa coluna dada
     *
     * @var $indiceCorrente Determina o índice base do array de silhueta para ser definido
     *                      um pedaço de área alagada
     */
    protected function _setAlagamento($indiceCorrente)
    {
        $this->alagamento = [];

        $valorCorrente = $this->silhueta[$indiceCorrente];
        $iColunaReferencia = $this->_getIndiceColunaReferencia($indiceCorrente);

        for($i = ($indiceCorrente - 1); $i > $iColunaReferencia; $i--) {
            $valorAnterior = $this->silhueta[$i];

            if ($valorCorrente <= $valorAnterior) {
                break;
            }

            $this->alagamento[$i] = $valorAnterior;
        }
    }

    /**
     * Método que preenche uma determinada área alagada com base numa coluna dada
     *
     */
    protected function _soma($nivelAlagamento)
    {
        foreach ($this->alagamento as $indiceCorrenteAlagamento => $nivelSilhueta) {
            $this->somatorioAlagado[$indiceCorrenteAlagamento] = $nivelAlagamento - $nivelSilhueta;
        }
    }

    /**
     * Método que procura o valor de cálculo da área alagada, dentro de um pedaço da silhueta
     *
     * @var $indiceCorrente Indica a partir de qual índice será iniciada a procura do indice
     */
    protected function _getIndiceColunaReferencia($indiceInicial)
    {
        $arrPedacoSilhueta = [];

        $valorCorrente = $this->silhueta[$indiceInicial];

        for ($i = ($indiceInicial-1); $i >= 0; $i--) {
            $item = $this->silhueta[$i];

            if ($item >= $valorCorrente) {
                return $i;
            }

            $arrPedacoSilhueta[$i][] = $this->silhueta[$i];
        }

        if (count($arrPedacoSilhueta) === 0) {
            return 0;
        }

        arsort($arrPedacoSilhueta);

        $chavesOrdenadas = array_keys($arrPedacoSilhueta);

        return $chavesOrdenadas[0];
    }
}
