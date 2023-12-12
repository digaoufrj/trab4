<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Result</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\estiloCSS\styleResult.css">
</head>

<body>

    <main>

        <?php 
         
            function calculateMonthlyInstallment($loanAmount, $interestRate, $periods) {
                $exponent = -1 * ($periods);
                $numerator = ($loanAmount * $interestRate);
                $denominator = (1 - pow(1 + $interestRate, $exponent));
                $installment = $numerator / $denominator;
                return $installment;
            }

            function calculateLoanCoefficient($interestRate, $periods){
                $loanCoefficient = ($interestRate * pow(1 + $interestRate, $periods)) / (pow(1 + $interestRate, $periods) - 1);
                return $loanCoefficient;
            }

            function calculateTotalPayment($loanAmount, $interestRate, $periods){
                $installment = calculateMonthlyInstallment($loanAmount, $interestRate, $periods);
                $totalPayment = $installment * $periods;
                return $totalPayment;
            }

            function calculateAdjustedValue($totalPaid, $interestRate, $periods){
                $adjustedValue = $totalPaid / (pow(1+$interestRate, $periods));
                return $adjustedValue;
            }
        ?>

        <?php
            $periods = $_GET["np"]; // number of periods
            $interestRate = $_GET["tax"]/100; // monthly interest rate
            $loanAmount = $_GET["pv"];
            $finalValue = $_GET["pp"];
            $valueToReturn = $_GET["pb"];
            $monthsToReturn = $_GET["mm"];
            
            $installment = calculateMonthlyInstallment($loanAmount, $interestRate, $periods);
            $loanCoefficient = calculateLoanCoefficient($interestRate, $periods);
            $totalPayment = calculateTotalPayment($loanAmount, $interestRate, $periods);
            $adjustedValue = calculateAdjustedValue($totalPayment, $interestRate, $periods);

            $realInterestRate = 3.8956;

            $interestRateFormatted = number_format($interestRate * 100, 2);
            $annualInterestRate = (1 + (pow(1 + $interestRate, 12) - 1)) - 1;
            $annualInterestRateFormatted = number_format($annualInterestRate * 100, 2);
            $loanAmountFormatted = number_format($loanAmount, 2);
            $finalValueFormatted = number_format($finalValue, 2);
            $valueToReturnFormatted = number_format($valueToReturn, 2);

            $installmentBox2Formatted = number_format($installment, 2);
            $loanCoefficientBox2Formatted = number_format($loanCoefficient, 6);
            $totalPaidFormatted = number_format($totalPayment, 2);
            $realInterestRateBox2Formatted = number_format($realInterestRate, 4);
            $adjustedValueFormatted = number_format($adjustedValue, 2);

            echo <<< Result1
            <div class="boxes">
                <div id="box1">
                    <p id="output">Number of Periods: $periods </p>
                    <p id="output">Interest Rate: $interestRateFormatted% per month = $annualInterestRateFormatted% per year</p>
                    <p id="output">Loan Amount: $$loanAmountFormatted </p>
                    <p id="output">Final Value: $$finalValueFormatted </p>
                    <p id="output">Value to Return: $$valueToReturnFormatted </p>
                    <p id="output">Down Payment: False </p>
                    <p id="output">Months to Return: $monthsToReturn </p>
                </div>
                
                <div id="box2">
                    <p id="output">Monthly Installment: $$installmentBox2Formatted per month</p>
                    <p id="output">Loan Coefficient: $$loanCoefficientBox2Formatted </p>
                    <p id="output">Total Paid: $$totalPaidFormatted</p>
                    <p id="output">Real Interest Rate: $realInterestRateBox2Formatted% per month</p>
                    <p id="output">Adjusted Value: $$adjustedValueFormatted</p>
                </div>
            </div>
            Result1;
        ?>

        <?php
            function generateAmortizationTable($loanAmount, $monthlyInterestRate, $numPeriods) {
                $monthlyPayment = ($loanAmount * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -$numPeriods));

                $remainingBalance = $loanAmount;

                $totalMonthlyPayment = 0;
                $totalInterest = 0;
                $totalPrincipal = 0;

                echo "<p id='title'>Amortization Table</p>";
                echo "<table border='1'>";
                echo "<tr><th>Month</th><th>Monthly Payment</th><th>Interest</th><th>Principal</th><th>Remaining Balance</th></tr>";

                for ($month = 1; $month <= $numPeriods; $month++) {
                    $interest = $remainingBalance * $monthlyInterestRate;
                    $principal = $monthlyPayment - $interest;
                    $remainingBalance -= $principal;
                    $totalMonthlyPayment += $monthlyPayment;
                    $totalInterest += $interest;
                    $totalPrincipal += $principal;

                    echo "<tr>";
                    echo "<td>$month</td>";
                    echo "<td>" . number_format($monthlyPayment, 2) . "</td>";
                    echo "<td>" . number_format($interest, 2) . "</td>";
                    echo "<td>" . number_format($principal, 2) . "</td>";
                    echo "<td>" . number_format($remainingBalance, 2) . "</td>";
                    echo "</tr>";
                }   

                echo "<tr>";
                echo "<td>Total</td>";
                echo "<td>" . number_format($totalMonthlyPayment, 2) . "</td>";
                echo "<td>" . number_format($totalInterest, 2) . "</td>";
                echo "<td>" . number_format($totalPrincipal, 2) . "</td>";
                echo "<td>" . number_format($remainingBalance, 2) . "</td>";
                echo "</tr>";
                
                echo "</table>";
            }

            generateAmortizationTable($loanAmount, $interestRate, $periods);
        ?>
        
    </main>
    
</body>

</html>
