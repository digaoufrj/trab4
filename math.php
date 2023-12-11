<!DOCTYPE html>
<html lang="pt" xml:lang="pt" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Resultado</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\estiloCSS\styleResultado.css">
</head>

<body>

    <main>

        <?php 
         
            function Calcular_PrestacaoMensal($valorFinanciado, $t, $p) {
                $e = -1 * ($p);
                $numerador = ($valorFinanciado * $t);
                $denominador = (1 - pow(1 + $t, $e));
                $prestacao = $numerador / $denominador;
                return $prestacao;
            }

            function Calcular_CoeficienteFinanciamento($t, $p){
                $CF = ($t * pow(1 + $t, $p)) / (pow(1 + $t, $p) - 1);
                return $CF;
            }

            function Calcular_ValorPago($valorFinanciado, $t, $p){
                $prestacao = Calcular_PrestacaoMensal($valorFinanciado, $t, $p);
                $valor_pago = $prestacao*$p;
                return $valor_pago;
            }

            function Calcular_ValorCorrigido($valorPago, $t, $p){
                $valorCorrigido = $valorPago / (pow(1+$t, $p));
                return $valorCorrigido;
            }
        ?>

        <?php
            $p = $_GET["np"]; // parcelamento
            $t = $_GET["tax"]/100; // taxa mensal de juros
            $valorFinanciado = $_GET["pv"];
            $valorFinal = $_GET["pp"];
            $valorVoltar = $_GET["pb"];
            $mesVoltar = $_GET["mm"];
            
            $prestacao = Calcular_PrestacaoMensal($valorFinanciado, $t, $p);
            $CF = Calcular_CoeficienteFinanciamento($t, $p);
            $valorPago = Calcular_ValorPago($valorFinanciado, $t, $p);
            $valorCorrigido = Calcular_ValorCorrigido($valorPago, $t, $p);

            $treal = 3.8956;

            $taxa = number_format($t * 100, 2);
            $t_anual = (1 + (pow(1 + $t, 12) - 1)) - 1;
            $t_anual = number_format($t_anual * 100, 2);
            $vFinanciado = number_format($valorFinanciado, 2);
            $vFinal = number_format($valorFinal, 2);
            $vVoltar = number_format($valorVoltar, 2);

            $prestacao_box2 = number_format($prestacao, 2);
            $CF_box2 = number_format($CF, 6);
            $vPago = number_format($valorPago, 2);
            $treal_box2 = number_format($treal, 4);
            $vCorrigido = number_format($valorCorrigido, 2);

            echo <<< Resultado1
            <div class="boxes">
                <div id="box1">
                    <p id="output">Parcelamento: $p </p>
                    <p id="output">Taxa: $taxa% ao mês = $t_anual% ao ano</p>
                    <p id="output">Valor Financiado: $$vFinanciado </p>
                    <p id="output">Valor Final: $$vFinal </p>
                    <p id="output">Valor a Voltar: $$vVoltar </p>
                    <p id="output">Entrada: False </p>
                    <p id="output">Meses a voltar: $mesVoltar </p>
                </div>
                
                <div id="box2">
                    <p id="output">Prestação: $$prestacao_box2 ao mês</p>
                    <p id="output">Coeficiente de Financiamento: $$CF_box2 </p>
                    <p id="output">Valor Pago: $$vPago</p>
                    <p id="output">Taxa Real: $treal_box2% ao mês</p>
                    <p id="output">Valor Corrigido: $$vCorrigido</p>
                </div>
            </div>
            Resultado1;
        ?>

        <?php
            function gerarTabelaPrice($valorFinanciado, $taxaJurosMensal, $numParcelas) {
                $prestacaoMensal = ($valorFinanciado * $taxaJurosMensal) / (1 - pow(1 + $taxaJurosMensal, -$numParcelas));

                $saldoDevedor = $valorFinanciado;

                $prestacaoMensalTotal = 0;
                $JurosTotal = 0;
                $amortizacaoTotal = 0;

                echo "<p id='titulo'>Tabela Price</p>";
                echo "<table border='1'>";
                echo "<tr><th>Mês</th><th>Prestação</th><th>Juros</th><th>Amortização</th><th>Saldo Devedor</th></tr>";

                for ($mes = 1; $mes <= $numParcelas; $mes++) {
                    $juros = $saldoDevedor * $taxaJurosMensal;
                    $amortizacao = $prestacaoMensal - $juros;
                    $saldoDevedor -= $amortizacao;
                    $prestacaoMensalTotal += $prestacaoMensal;
                    $JurosTotal += $juros;
                    $amortizacaoTotal += $amortizacao;

                    echo "<tr>";
                    echo "<td>$mes</td>";
                    echo "<td>" . number_format($prestacaoMensal, 2) . "</td>";
                    echo "<td>" . number_format($juros, 2) . "</td>";
                    echo "<td>" . number_format($amortizacao, 2) . "</td>";
                    echo "<td>" . number_format($saldoDevedor, 2) . "</td>";
                    echo "</tr>";
                }   

                echo "<tr>";
                echo "<td>Total</td>";
                echo "<td>" . number_format($prestacaoMensalTotal, 2) . "</td>";
                echo "<td>" . number_format($JurosTotal, 2) . "</td>";
                echo "<td>" . number_format($amortizacaoTotal, 2) . "</td>";
                echo "<td>" . number_format($saldoDevedor, 2) . "</td>";
                echo "</tr>";
                
                echo "</table>";
            }

            gerarTabelaPrice($valorFinanciado, $t, $p);
        ?>
        
    </main>
    
</body>

</html>