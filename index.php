<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Calculator | Accurate Loan Calculations</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Loan Calculator helps you calculate loan payments, total repayment, and fees accurately. Find out your loan details easily.">
    <meta name="keywords" content="Loan Calculator, loan payments, fees, interest rates, amortization schedule, loan repayment">
    <meta name="author" content="Ogulcan Ozdogan">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Loan Calculator | Accurate Loan Calculations">
    <meta property="og:description" content="Use our Loan Calculator to easily calculate loan payments, total repayment, and fees. Perfect for accurate financial planning.">


    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Loan Calculator | Accurate Loan Calculations">
    <meta name="twitter:description" content="Calculate your loan payments and fees with our easy-to-use loan calculator. Start planning your finances today.">

    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon-standard.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/images/favicon-standard.png">

    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <h1>Loan Calculator</h1>
    <div class="container">
        <div class="form">
            <h2>Enter Loan Details</h2>
            <form method="POST">
                <div class="slider-container">
                    <label for="loanAmount">Loan Amount ($):</label>
                    <input 
                        type="number" 
                        id="loanAmountInput" 
                        name="loan_amount" 
                        min="50000" 
                        max="5000000" 
                        step="1000" 
                        value="<?php echo isset($_POST['loan_amount']) ? $_POST['loan_amount'] : '50000'; ?>" 
                        oninput="syncSliderAndInput('input')"
                    >
                    <input 
                        type="range" 
                        id="loanAmountSlider" 
                        min="50000" 
                        max="5000000" 
                        step="1000" 
                        value="<?php echo isset($_POST['loan_amount']) ? $_POST['loan_amount'] : '50000'; ?>" 
                        oninput="syncSliderAndInput('slider')"
                    >
                </div>

                <label for="downPayment">Down Payment (%):</label>
                <input 
                    type="number" 
                    id="downPayment" 
                    name="down_payment" 
                    min="15" 
                    max="100" 
                    value="<?php echo isset($_POST['down_payment']) ? $_POST['down_payment'] : '15'; ?>" 
                    required
                >

                <label for="termLength">Term Length (Years):</label>
                <select id="termLength" name="term_length" required>
                    <option value="7" <?php echo (isset($_POST['term_length']) && $_POST['term_length'] == '7') ? 'selected' : ''; ?>>7 Years</option>
                    <option value="10" <?php echo (isset($_POST['term_length']) && $_POST['term_length'] == '10') ? 'selected' : ''; ?>>10 Years</option>
                    <option value="15" <?php echo (isset($_POST['term_length']) && $_POST['term_length'] == '15') ? 'selected' : ''; ?>>15 Years</option>
                    <option value="25" <?php echo (isset($_POST['term_length']) && $_POST['term_length'] == '25') ? 'selected' : ''; ?>>25 Years</option>
                </select>

                <p><strong>Fees:</strong> 2.5% (Fixed)</p>
                <p><strong>Annual Interest Rate:</strong> 6.75% (Fixed)</p>

                <button type="submit">Calculate</button>
            </form>
        </div>
        <div class="results">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $loanAmount = floatval($_POST['loan_amount']);
                $downPaymentRate = floatval($_POST['down_payment']) / 100;
                $termLength = intval($_POST['term_length']);
                $annualRate = 6.75 / 100;
            
                $downPaymentAmount = $loanAmount * $downPaymentRate;
                $financedAmount = $loanAmount - $downPaymentAmount;
                $monthlyRate = $annualRate / 12;
                $numberOfPayments = $termLength * 12;
                $monthlyPayment = $financedAmount * $monthlyRate / (1 - pow(1 + $monthlyRate, -$numberOfPayments));
                $totalRepayment = $monthlyPayment * $numberOfPayments;
            
                $FeeRate = 0.025;
                $Fee = $financedAmount * $FeeRate;
            
                echo "<h2>Loan Results</h2>";
                echo "<p><strong>Monthly Payment:</strong> $" . number_format($monthlyPayment, 2) . "</p>";
                echo "<p><strong>Total Repayment:</strong> $" . number_format($totalRepayment, 2) . "</p>";
                echo "<p><strong>Estimated Down Payment:</strong> $" . number_format($downPaymentAmount, 2) . "</p>";
                echo "<p><strong>Fees (2.5%):</strong> $" . number_format($Fee, 2) . "</p>";
            
                echo "<p><strong>Amortization Schedule:</strong></p>";
                echo "<table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Date</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Remaining Balance</th>
                            </tr>
                        </thead>
                        <tbody>";
                
                // start date: this month
                $startDate = new DateTime();
                $remainingBalance = $financedAmount;
                
                for ($i = 1; $i <= $numberOfPayments; $i++) {
                    $interestPayment = $remainingBalance * $monthlyRate;
                    $principalPayment = $monthlyPayment - $interestPayment;
                    $remainingBalance -= $principalPayment;
            
                    $paymentDate = clone $startDate; // this is a original date clone
                    $paymentDate->modify("+{$i} month");
            
                    echo "<tr>
                            <td>$i</td>
                            <td>" . $paymentDate->format('F Y') . "</td> <!-- This is a month and day info -->
                            <td>$" . number_format($principalPayment, 2) . "</td>
                            <td>$" . number_format($interestPayment, 2) . "</td>
                            <td>$" . number_format(max($remainingBalance, 0), 2) . "</td>
                          </tr>";
                }
                
                echo "</tbody></table>";
            }
            ?>
        </div>
    </div>
    <script src="assets/js/scripts.js"></script>
</body>
</html>
