<?php

namespace App\Exports;

use App\Models\Transactions;
use App\Models\TransactionDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $selectedIds;

    public function __construct(array $selectedIds)
    {
        $this->selectedIds = $selectedIds;
    }

    public function collection()
    {
        return Transactions::whereIn('id_transactions', $this->selectedIds)
            ->with(['fromUser'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaction',
            'Action',
            'Description',
            'Amount',
            'Additional Amount',
            'Remaining Amount',
            'Amount Return',
            'Requested By',
            'Approved By',
            'Status',
            'Date',
        ];
    }

    public function map($transaction): array
    {
        $detailsAmount = TransactionDetails::where('id_transactions', $transaction->id_transactions)
            ->sum('amount');

        $amountReturn = 0;
        if ($transaction->action === 'return') {
            $amountReturn = ($transaction->remaining_amount ?? 0) + ($transaction->additional_amount ?? 0);
        }

        return [
            $transaction->id_transactions,
            ucfirst($transaction->action),
            $transaction->description,
            '$' . number_format($transaction->amount, 2),
            '$' . number_format($transaction->additional_amount ?? 0, 2),
            '$' . number_format($transaction->remaining_amount ?? 0, 2),
            '$' . number_format($amountReturn, 2),
            $transaction->requested_by,
            $transaction->approved_by ?? '-',
            str_replace('_', ' ', ucwords($transaction->status)),
            $transaction->created_at->format('d M Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4B5563']
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 12,
            'C' => 30,
            'D' => 15,
            'E' => 18,
            'F' => 18,
            'G' => 15,
            'H' => 20,
            'I' => 20,
            'J' => 15,
            'K' => 20,
        ];
    }
}
