<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e8f5e9;
            /* Light green background */
            margin: 0;
            padding: 20px;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            color: #2e7d32;
            /* Darker green for headings */
        }

        .summary {
            margin: 20px auto;
            width: 90%;
            border: 1px solid #81c784;
            /* Light green border */
            padding: 20px;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .summary table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .summary th,
        .summary td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #c8e6c9;
            /* Light green border */
        }

        .summary th {
            font-weight: bold;
            color: #2e7d32;
            /* Darker green for table headers */
        }

        .payment-history {
            margin: 20px auto;
            width: 90%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #81c784;
            /* Light green border */
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .payment-history th,
        .payment-history td {
            border: none;
            padding: 12px;
            text-align: center;
        }

        .payment-history th {
            background-color: #a5d6a7;
            /* Light green header background */
            font-weight: bold;
            color: #1b5e20;
            /* Dark green text for header */
        }

        .payment-history tr:nth-child(even) {
            background-color: #f1f8e9;
            /* Very light green for even rows */
        }

        .payment-history tr:nth-child(odd) {
            background-color: #ffffff;
            /* White for odd rows */
        }

        .payment-history tr:hover {
            background-color: #c8e6c9;
            /* Lighter green on hover */
        }

        .net-profit-positive {
            color: #2e7d32;
            /* Dark green for positive net profit */
            font-weight: bold;
        }

        .net-profit-negative {
            color: #c62828;
            /* Dark red for negative net profit */
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h1>Profit Report</h1>

    <div class="summary">
        <h2>Financial Summary</h2>
        <table>
            <tr>
                <th>Revenue:</th>
                <td>{{ number_format($reportData['revenue'], 2) }}</td>
            </tr>
            <tr>
                <th>Expenses:</th>
                <td>{{ number_format($reportData['expenses'], 2) }}</td>
            </tr>
            <tr>
                <th>Trainer Payments:</th>
                <td>{{ number_format($reportData['trainer_payments'], 2) }}</td>
            </tr>
            <tr>
                <th>Inventory Payments:</th>
                <td>{{ number_format($reportData['inventory_payments'], 2) }}</td>
            </tr>
            <tr>
                <th>Other Expenses:</th>
                <td>{{ number_format($reportData['other_expenses'], 2) }}</td>
            </tr>
            <tr>
                <th>Net Profit:</th>
                <td class="{{ $reportData['net_profit'] >= 0 ? 'net-profit-positive' : 'net-profit-negative' }}">
                    {{ number_format($reportData['net_profit'], 2) }}
                </td>
            </tr>
        </table>
    </div>

    <h2>Payment History</h2>
    <table class="payment-history">
        <thead>
            <tr>
                <th>Type</th>
                <th>Payment ID</th>
                <th>Payment Amount</th>
                <th>Paid Amount</th>
                <th>Dues</th>
                <th>Balance</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['payment_history'] as $payment)
                <tr>
                    <td>{{ ucfirst($payment->TYPE) }}</td>
                    <td>{{ $payment->payment_id }}</td>
                    <td>{{ number_format($payment->payment_amount, 2) }}</td>
                    <td>{{ number_format($payment->paid_amount, 2) }}</td>
                    <td>{{ number_format($payment->dues, 2) }}</td>
                    <td>{{ number_format($payment->balance, 2) }}</td>
                    <td>{{ $payment->payment_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>