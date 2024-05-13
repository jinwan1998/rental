<?php

class MortgageCalculator
{
    private $loanAmount;
    private $interestRate;
    private $loanTermMonths;
    private $monthlyRepayment;
    private $totalInterest;

    public function __construct($loanAmount = 0, $interestRate = 0, $loanTermMonths = 0)
    {
        $this->loanAmount = $loanAmount;
        $this->interestRate = $interestRate;
        $this->loanTermMonths = $loanTermMonths;
        $this->calculateMonthlyRepayment();
        $this->calculateTotalInterest();
    }

    public function setLoanAmount($loanAmount)
    {
        $this->loanAmount = $loanAmount;
        $this->calculateMonthlyRepayment();
        $this->calculateTotalInterest();
    }

    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;
        $this->calculateMonthlyRepayment();
        $this->calculateTotalInterest();
    }

    public function setLoanTermMonths($loanTermMonths)
    {
        $this->loanTermMonths = $loanTermMonths;
        $this->calculateMonthlyRepayment();
        $this->calculateTotalInterest();
    }

    public function getMonthlyRepayment()
    {
        return $this->monthlyRepayment;
    }

    public function getTotalInterest()
    {
        return $this->totalInterest;
    }

    private function calculateMonthlyRepayment()
    {
        $monthlyInterestRate = ($this->interestRate / 100) / 12;
        $numerator = $this->loanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $this->loanTermMonths);
        $denominator = pow(1 + $monthlyInterestRate, $this->loanTermMonths) - 1;
        $this->monthlyRepayment = $numerator / $denominator;
    }

    private function calculateTotalInterest()
    {
        $this->totalInterest = $this->monthlyRepayment * $this->loanTermMonths - $this->loanAmount;
    }
}

class MortgageCalculatorManager
{
    private $calculators = [];

    public function addCalculator($propertyName, MortgageCalculator $calculator)
    {
        $this->calculators[$propertyName] = $calculator;
    }

    public function getCalculator($propertyName)
    {
        return $this->calculators[$propertyName] ?? null;
    }

    public function removeCalculator($propertyName)
    {
        unset($this->calculators[$propertyName]);
    }

    public function compareCalculations()
{
    // This method can be used to compare calculations for different properties
    // For example, you can compare monthly repayments or total interest paid for each property
    // You can implement any comparison logic here based on your requirements

    $calculations = [];
    foreach ($this->calculators as $propertyName => $calculator) {
        $calculations[$propertyName] = [
            'monthly_repayment' => $calculator->getMonthlyRepayment(),
            'total_interest' => $calculator->getTotalInterest()
        ];
    }

    // Implement comparison logic here
    // For example, you can compare the calculations and display the results

    return $calculations;
}

public function saveCalculations()
{
    // This method can be used to save mortgage calculations for different properties
    // You can save the calculations to a database, file, or any other storage mechanism

    // Implement the saving logic here
    // For example, you can save the calculations to a database

    // Connect to database
    $pdo = new PDO("mysql:host=localhost;dbname=mydatabase", "username", "password");

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO mortgage_calculations (property_name, monthly_repayment, total_interest) VALUES (?, ?, ?)");

    // Iterate over calculators and save calculations to database
    foreach ($this->calculators as $propertyName => $calculator) {
        // Bind parameters
        $stmt->bindParam(1, $propertyName);
        $stmt->bindParam(2, $calculator->getMonthlyRepayment());
        $stmt->bindParam(3, $calculator->getTotalInterest());

        // Execute SQL statement
        $stmt->execute();
    }

    // Close database connection
    $pdo = null;
}

}

$calculatorManager = new MortgageCalculatorManager();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculate'])) {
    $loanAmount = isset($_POST['loan_amount']) ? floatval($_POST['loan_amount']) : 0;
    $interestRate = isset($_POST['interest_rate']) ? floatval($_POST['interest_rate']) : 0;
    $years = isset($_POST['years']) ? intval($_POST['years']) : 0;
    $months = isset($_POST['months']) ? intval($_POST['months']) : 0;

    $loanTermMonths = $years * 12 + $months;

    if ($loanAmount > 0 && $interestRate > 0 && $loanTermMonths > 0) {
        // Create a new mortgage calculator instance
        $calculator = new MortgageCalculator($loanAmount, $interestRate, $loanTermMonths);
        // Add calculator to the manager with a unique property name
        $calculatorManager->addCalculator('Property', $calculator);
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
        <input type="number" id="loan_amount" name="loan_amount" step="0.01" required><br><br>
        <label for="interest_rate">Interest Rate (% per annum):</label>
        <input type="number" id="interest_rate" name="interest_rate" step="0.01" required><br><br>
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

    <?php if (!empty($calculatorManager->getCalculator('Property'))): ?>
        <h3>Monthly Repayment Details</h3>
        <table>
            <tr>
                <th>Loan Amount</th>
                <th>Interest Rate</th>
                <th>Loan Term</th>
                <th>Monthly Repayment</th>
                <th>Total Interest</th>
            </tr>
            <tr>
                <td><?php echo formatCurrency($loanAmount); ?></td>
                <td><?php echo $interestRate; ?>%</td>
                <td><?php echo $years; ?> Year<?php echo $years !== 1 ? 's' : ''; ?>, <?php echo $months; ?> Month<?php echo $months !== 1 ? 's' : ''; ?></td>
                <td><?php echo formatCurrency($calculatorManager->getCalculator('Property')->getMonthlyRepayment()); ?></td>
                <td><?php echo formatCurrency($calculatorManager->getCalculator('Property')->getTotalInterest()); ?></td>
            </tr>
        </table>
    <?php endif; ?>

    <?php
    function formatCurrency($amount)
    {
        return '$' . number_format($amount, 2);
    }
    ?>
</body>

</html>
