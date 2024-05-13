<?php
// Function to calculate monthly repayment
function calculateMonthlyRepayment($loan_amount, $interest_rate, $loan_term_months)
{
    // Convert interest rate to decimal and monthly interest rate
    $monthly_interest_rate = ($interest_rate / 100) / 12;

    // Calculate monthly repayment using the formula
    $numerator = $loan_amount * $monthly_interest_rate * pow(1 + $monthly_interest_rate, $loan_term_months);
    $denominator = pow(1 + $monthly_interest_rate, $loan_term_months) - 1;
    $monthly_repayment = $numerator / $denominator;

    // Return monthly repayment amount
    return $monthly_repayment;
}

// Function to format currency amount
function formatCurrency($amount)
{
    return '$' . number_format($amount, 2);
}

$loan_amount = $interest_rate = $years = $months = $loan_term_months = $monthly_repayment = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculate'])) {

    $loan_amount = isset($_POST['loan_amount']) ? floatval($_POST['loan_amount']) : 0;
    $interest_rate = isset($_POST['interest_rate']) ? floatval($_POST['interest_rate']) : 0;
    $years = isset($_POST['years']) ? intval($_POST['years']) : 0;
    $months = isset($_POST['months']) ? intval($_POST['months']) : 0;

    $loan_term_months = $years * 12 + $months;

    if ($loan_amount > 0 && $interest_rate > 0 && $loan_term_months > 0) {
        // Calculate monthly repayment
        $monthly_repayment = calculateMonthlyRepayment($loan_amount, $interest_rate, $loan_term_months);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mortgage Calculator</title>
    <link rel="stylesheet" href="buyer.css"> 

</head>
<body>
    <h2>Mortgage Calculator</h2>
    <a href="buyer.php">Home</a> 

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="loan_amount">Loan Amount:</label>
        <input type="number" id="loan_amount" name="loan_amount" step="0.01" value="<?php echo $loan_amount; ?>" required><br><br>
        <label for="interest_rate">Interest Rate (% per annum):</label>
        <input type="number" id="interest_rate" name="interest_rate" step="0.01" value="<?php echo $interest_rate; ?>" required><br><br>
        <label for="loan_term">Loan Term:</label>
        <select name="years" id="years" required>
            <option value="" selected disabled>Select Years</option>
            <?php for ($i = 1; $i <= 30; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?> Year<?php echo $i !== 1 ? 's' : ''; ?></option>
            <?php endfor; ?>
        </select>
        <select name="months" id="months" required>
            <option value="" selected disabled>Select Months</option>
            <?php for ($j = 0; $j < 12; $j++): ?>
                <option value="<?php echo $j; ?>"><?php echo $j; ?> Month<?php echo $j !== 1 ? 's' : ''; ?></option>
            <?php endfor; ?>
        </select><br><br>
        <button type="submit" name="calculate">Calculate</button>
    </form>

    <?php if ($monthly_repayment > 0): ?>
        <h3>Monthly Repayment Details</h3>
        <table>
            <tr>
                <th>Loan Amount</th>
                <th>Interest Rate</th>
                <th>Loan Term</th>
                <th>Monthly Repayment</th>
            </tr>
            <tr>
                <td><?php echo formatCurrency($loan_amount); ?></td>
                <td><?php echo $interest_rate; ?>%</td>
                <td><?php echo $years; ?> Year<?php echo $years !== 1 ? 's' : ''; ?>, <?php echo $months; ?> Month<?php echo $months !== 1 ? 's' : ''; ?></td>
                <td><?php echo formatCurrency($monthly_repayment); ?></td>
            </tr>
        </table>
    <?php endif; ?>
</body>
</html>
