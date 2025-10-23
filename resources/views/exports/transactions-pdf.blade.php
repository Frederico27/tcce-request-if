<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transactions Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .transaction-card {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 15px;
            page-break-inside: auto;
            /* Changed from avoid to auto */
        }

        .transaction-header {
            background-color: #4B5563;
            color: white;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            font-weight: bold;
            page-break-after: avoid;
            /* Keep header with content */
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 35%;
            padding: 5px 0;
            color: #555;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-approved {
            background-color: #DEF7EC;
            color: #03543F;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-rejected {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .status-completed {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .divider {
            border-top: 1px solid #E5E7EB;
            margin: 10px 0;
        }

        .images-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #E5E7EB;
            page-break-before: auto;
            /* Allow breaking before images if needed */
        }

        .images-section h4 {
            margin: 0 0 10px 0;
            color: #374151;
            page-break-after: avoid;
            /* Keep heading with images */
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .image-item {
            text-align: center;
            page-break-inside: avoid;
            /* Prevent individual images from breaking */
        }

        .image-item img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<!-- Top-left logo + Produced by text -->
<div class="top-bar">
    <img src="{{ public_path('logo/image.png') }}" alt="IF Fund Logo"
        style="max-width: 90px; height: 30px; margin-bottom: 10px;">
    <span style="justify-content: center; display: flex;">Produced by Telkomcel</span>
</div>

<body>
    <div class="header">
        <img src="{{ public_path('logo/telkomcel-logo.png') }}" alt="Telkomcel Logo"
            style="max-width: 170px; height: auto; margin-bottom: 10px;">
        <h1>Transaction Report</h1>
        <p>Generated on {{ date('d F Y, H:i:s') }}</p>
        <p>Total Transactions: {{ count($transactions) }}</p>
    </div>

    @foreach ($transactions as $transaction)
        <div class="transaction-card">
            <div class="transaction-header">
                Transaction #{{ $transaction['id_transactions'] }} - {{ ucfirst($transaction['action']) }}
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Description:</div>
                    <div class="info-value">{{ $transaction['description'] }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Amount Requested:</div>
                    <div class="info-value">${{ number_format($transaction['amount'], 2) }}</div>
                </div>

                @if ($transaction['action'] === 'return')
                    <div class="info-row">
                        <div class="info-label">Amount Return:</div>
                        <div class="info-value">
                            ${{ number_format($transaction['details_amount'], 2) }}
                        </div>
                    </div>
                @endif
                @if ($transaction['additional_amount'] > 0)
                    <div class="info-row">
                        <div class="info-label">Additional Amount:</div>
                        <div class="info-value">${{ number_format($transaction['additional_amount'], 2) }}</div>
                    </div>
                @endif
                @if ($transaction['remaining_amount'] > 0)
                    <div class="info-row">
                        <div class="info-label">Remaining Amount:</div>
                        <div class="info-value">${{ number_format($transaction['remaining_amount'], 2) }}</div>
                    </div>
                @endif
            </div>

            <div class="divider"></div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Requested By:</div>
                    <div class="info-value">{{ $transaction['requested_by'] }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Approved By:</div>
                    <div class="info-value">{{ $transaction['approved_by'] }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        @php
                            $statusClass = 'status-badge ';
                            if (str_contains($transaction['status'], 'approved')) {
                                $statusClass .= 'status-approved';
                            } elseif ($transaction['status'] === 'pending') {
                                $statusClass .= 'status-pending';
                            } elseif ($transaction['status'] === 'rejected') {
                                $statusClass .= 'status-rejected';
                            } elseif ($transaction['status'] === 'completed') {
                                $statusClass .= 'status-completed';
                            }
                        @endphp
                        <span
                            class="{{ $statusClass }}">{{ str_replace('_', ' ', ucwords($transaction['status'])) }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $transaction['created_at'] }}</div>
                </div>
            </div>

            @if ($transaction['action'] === 'return' && !empty($transaction['return_images']))
                <div class="images-section">
                    <h4>Return Proof Images</h4>
                    <div class="image-grid">
                        @foreach ($transaction['return_images'] as $imagePath)
                            @if (file_exists($imagePath))
                                <div class="image-item">
                                    <img src="{{ $imagePath }}" alt="Return proof">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <p>This is an automatically generated document.</p>
        <p>© {{ date('Y') }} Telkomcel IF Fund Management Systems. All rights reserved.</p>
    </div>
</body>

</html>
