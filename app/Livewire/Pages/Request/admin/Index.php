<?php

namespace App\Livewire\Pages\Request\admin;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Saldo;
use App\Models\Transactions;
use App\Models\TransactionDetails;
use App\Models\TransactionAttachment;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Models\TransactionImageDishRebus;

class Index extends Component
{
    use WithPagination;
    use Confirm;
    use WithFileUploads;

    public $search;
    public $typeFilter;
    public $statusFilter;
    public $transaction;
    public $showRejectModal = false;
    public $rejectReason = '';
    public $dateFrom;
    public $dateTo;
    public $showAdditionalAmountModal = false;
    public $additionalAmount = null;
    public $additionalAmountReason = '';
    public $selectedTransactionId = null;
    public $selectedTransactions = [];
    public $selectAll = false;
    public $imageDishRebush = null;
    public $imageDishRebushDescription = '';
    public $storedImagePath = null;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedTransactions = Transactions::query()
                ->where('status', '!=', 'draft')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('description', 'like', '%' . $this->search . '%')
                            ->orWhere('action', 'like', '%' . $this->search . '%')
                            ->orWhere('requested_by', 'like', '%' . $this->search . '%')
                            ->orWhere('amount', 'like', '%' . $this->search . '%')
                            ->orWhere('id_transactions', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->typeFilter, fn($query) => $query->where('action', $this->typeFilter))
                ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
                ->when($this->dateFrom, fn($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
                ->when($this->dateTo, fn($query) => $query->whereDate('created_at', '<=', $this->dateTo))
                ->pluck('id_transactions')
                ->toArray();
        } else {
            $this->selectedTransactions = [];
        }
    }

    public function exportExcel()
    {
        if (empty($this->selectedTransactions)) {
            flash()->warning('Please select at least one transaction to export.');
            return;
        }

        return Excel::download(new TransactionsExport($this->selectedTransactions), 'transactions_' . date('Y-m-d_His') . '.xlsx');
    }

    public function exportPdf()
    {
        if (empty($this->selectedTransactions)) {
            flash()->warning('Please select at least one transaction to export.');
            return;
        }

        $transactions = Transactions::whereIn('id_transactions', $this->selectedTransactions)
            ->with(['fromUser'])
            ->get();

        $transactionsData = $transactions->map(function ($transaction) {
            // Get sum of all transaction details amounts
            $detailsAmount = TransactionDetails::where('id_transactions', $transaction->id_transactions)
                ->sum('amount');

            $returnImages = [];
            if ($transaction->action === 'return') {
                // Get all transaction details for this transaction with their attachments
                $attachments = TransactionDetails::where('id_transactions', $transaction->id_transactions)
                    ->with(['transactionAttachments' => function ($query) {
                        $query->where('file_type', 'image');
                    }])
                    ->get()
                    ->pluck('transactionAttachments')
                    ->flatten();

                // Map to get file paths
                $returnImages = $attachments->map(function ($attachment) {
                    $filePath = storage_path('app/public/' . $attachment->file_path);
                    // Only include if file exists
                    return file_exists($filePath) ? $filePath : null;
                })
                    ->filter()
                    ->values()
                    ->toArray();
            }

            return [
                'id_transactions' => $transaction->id_transactions,
                'action' => $transaction->action,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'additional_amount' => $transaction->additional_amount ?? 0,
                'remaining_amount' => $transaction->remaining_amount ?? 0,
                'requested_by' => $transaction->requested_by,
                'approved_by' => $transaction->approved_by ?? '-',
                'status' => $transaction->status,
                'created_at' => $transaction->created_at->format('d M Y H:i'),
                'details_amount' => $detailsAmount,
                'return_images' => $returnImages,
            ];
        });

        $pdf = Pdf::loadView('exports.transactions-pdf', [
            'transactions' => $transactionsData
        ]);

        $pdf->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'transactions_' . date('Y-m-d_His') . '.pdf');
    }


    public function clearDateFilter()
    {
        $this->dateFrom = null;
        $this->dateTo = null;
    }

    public function approveReturnConfirmation($id)
    {
        $this->confirm(
            title: 'Apakah yakin ingin menyetujui pengembalian dana ini?',
            html: 'Klik konfirmasi untuk menyetujui pengembalian dana',
            event: 'approveReturnTable',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }


    public function rejectConfirmation($id)
    {

        // Validate the rejected
        $this->validate([
            'rejectReason' => 'required|string|max:255',
        ]);

        $this->confirm(
            title: 'Apakah yakin ingin menolak transaksi ini?',
            html: 'Klik konfirmasi untuk menolak transaksi',
            event: 'rejectTable',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    public function openAdditionalAmountModal($id)
    {
        $this->selectedTransactionId = $id;
        $this->additionalAmount = null;
        $this->additionalAmountReason = '';
        $this->showAdditionalAmountModal = true;
    }

    public function closeAdditionalAmountModal()
    {
        $this->showAdditionalAmountModal = false;
        $this->additionalAmount = null;
        $this->additionalAmountReason = '';
        $this->selectedTransactionId = null;
        $this->imageDishRebush = null;
        $this->imageDishRebushDescription = '';
        $this->storedImagePath = null;
    }

    public function submitAdditionalAmount($id)
    {
        $this->selectedTransactionId = $id;


        // Get transaction to check if additional_amount exists
        $transaction = Transactions::where('id_transactions', $id)->first();

        // Validate if amount is provided
        if ($this->additionalAmount !== null && floatval($this->additionalAmount) > 0) {
            $this->validate([
                'additionalAmount' => 'required|numeric|min:0',
                'additionalAmountReason' => 'required|string|max:255',
            ]);
        } else {
            // Set to 0 if null or empty
            $this->additionalAmount = 0;
        }

        // Validate image dish rebush if additional amount > 0
        if (($transaction && $transaction->additional_amount > 0) || ($transaction && $transaction->remaining_amount > 0)) {
            $this->validate([
                'imageDishRebush' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'imageDishRebushDescription' => 'nullable|string|max:500',
            ], [
                'imageDishRebush.required' => 'Bukti Dana pengembalian atau Dana tambahan wajib diupload sebagai lampiran bukti.',
                'imageDishRebush.image' => 'File harus berupa gambar.',
                'imageDishRebush.mimes' => 'Gambar harus berformat: jpeg, png, jpg.',
                'imageDishRebush.max' => 'Ukuran gambar maksimal 2MB.',
            ]);

            // Store the image immediately to avoid losing it in confirmation
            $this->storedImagePath = $this->imageDishRebush->store('dish_rebush', 'public');
        }

        $this->confirm(
            title: 'Apakah yakin ingin memverifikasi transaksi ini?',
            html: floatval($this->additionalAmount) > 0
                ? 'Transaksi akan diverifikasi dengan tambahan dana $' . number_format(floatval($this->additionalAmount), 2)
                : 'Jika iya maka danamu sudah diterima oleh requestor',
            event: 'approveReturnTable',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: [
                'id' => $this->selectedTransactionId,
                'imagePath' => $this->storedImagePath,
                'imageDishRebushDescription' => $this->imageDishRebushDescription
            ]
        );

        $this->closeAdditionalAmountModal();
    }

    public function verifyConfirmation($id)
    {
        $this->confirm(
            title: 'Apakah yakin ingin memverifikasi transaksi ini?',
            html: 'Klik konfirmasi untuk memverifikasi transaksi',
            event: 'verifyTable',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    #[On('verifyTable')]
    public function verifyInTable(array $data)
    {

        $this->transaction = Transactions::where('id_transactions', $data['id'])->first();
        if (!$this->transaction) {
            flash()->error('Transaction not found.');
            return $this->redirect(route('transactions.index'), navigate: true);
        }
        if ($this->transaction->action !== 'request') {
            flash()->error('Transaction is not in requested status.');
            return $this->redirect(route('transactions.index'), navigate: true);
        }
        $this->transaction->status = 'verified';
        // $this->transaction->approved_by = 'test'; // This should be replaced with the actual admin user ID
        $this->transaction->save();

        Saldo::where('id_user', 1)->decrement('balance', $this->transaction->amount);

        flash()->success('Transaction verified successfully.');
        return $this->redirect(route('transactions.index'), navigate: true);
    }



    #[On('rejectTable')]
    public function rejectedInTable(array $data)
    {
        $this->transaction = Transactions::where('id_transactions', $data['id'])->first();
        if (!$this->transaction) {
            session()->flash('error', 'Transaction not found.');
            return redirect()->route('transactions.index');
        }
        if ($this->transaction->action !== 'request') {
            session()->flash('error', 'Transaction is not in requested status.');
            return redirect()->route('transactions.index');
        }
        $this->transaction->status = 'rejected';
        $this->transaction->rejection_reason = $this->rejectReason; // Store the rejection reason
        // $this->transaction->approved_by = 'test'; // This should be replaced with the actual admin user ID
        $this->transaction->save();

        flash()->success('Transaction rejected successfully.');
        return $this->redirect(route('transactions.index'), navigate: true);
    }


    #[On('approveReturnTable')]
    public function approvedReturn(array $data)
    {


        $this->transaction = Transactions::where('id_transactions', $data['id'])->first();
        if (!$this->transaction) {
            session()->flash('error', 'Transaction not found.');
            return redirect()->route('transactions.index');
        }
        if ($this->transaction->action !== 'return') {
            session()->flash('error', 'Transaction is not in return status.');
            return redirect()->route('transactions.index');
        }

        // Save image dish rebush if additional amount > 0 and image path is provided
        if (($this->transaction->additional_amount > 0 && !empty($data['imagePath'])) || ($this->transaction->remaining_amount > 0 && !empty($data['imagePath']))) {
            // Get the first transaction detail ID for this transaction
            $transactionDetail = TransactionDetails::where('id_transactions', $this->transaction->id_transactions)->first();

            if ($transactionDetail) {
                // Save to database using the already stored image path
                TransactionImageDishRebus::create([
                    'id_transaction_detail' => $transactionDetail->id_transaction_detail,
                    'description' => $data['imageDishRebushDescription'] ?? 'Bukti dish rebush',
                    'image_path' => $data['imagePath'],
                ]);
            }
        }

        $this->transaction->status = 'admin_approved';
        // $this->transaction->approved_by = 'test'; // This should be replaced with the actual admin user ID
        $this->transaction->save();

        if ($this->transaction->remaining_amount > 0) {
            Saldo::where('id_user', 1)->increment('balance', $this->transaction->remaining_amount);
        }

        if ($this->transaction->additional_amount > 0) {
            Saldo::where('id_user', 1)->decrement('balance', $this->transaction->additional_amount);
        }

        // Reset image fields after successful save
        $this->imageDishRebush = null;
        $this->imageDishRebushDescription = '';
        $this->storedImagePath = null;

        flash()->success('Return Transaction approved successfully.');
        return $this->redirect(route('transactions.index'), navigate: true);
    }

    public function render()
    {
        $query = Transactions::query()->where('status', '!=', 'draft');
        // Filter by search
        if (!empty($this->search)) {
            $query->where('description', 'like', '%' . $this->search . '%')
                ->orWhere('action', 'like', '%' . $this->search . '%')
                ->orWhere('requested_by', 'like', '%' . $this->search . '%')
                ->orWhere('amount', 'like', '%' . $this->search . '%')
                ->orWhere('id_transactions', 'like', '%' . $this->search . '%');
        }
        // Filter by type
        if (!empty($this->typeFilter)) {
            $query->where('action', $this->typeFilter);
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $transaction = $query->paginate(5);
        $saldo = Saldo::findOrFail(1);

        return view('livewire.pages.request.admin.index', [
            'transactions' => $transaction,
            'saldo' => $saldo
        ]);
    }
}
