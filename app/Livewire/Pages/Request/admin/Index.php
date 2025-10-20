<?php

namespace App\Livewire\Pages\Request\admin;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Saldo;
use App\Models\Transactions;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use Confirm;
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
    }

    public function submitAdditionalAmount()
    {

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
                'additionalAmount' => $this->additionalAmount,
                'additionalAmountReason' => $this->additionalAmountReason
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
        $this->transaction->status = 'admin_approved';
        $this->transaction->additional_amount = $data['additionalAmount'] ?? 0;
        $this->transaction->additional_amount_reason = $data['additionalAmountReason'] ?? null;
        // $this->transaction->approved_by = 'test'; // This should be replaced with the actual admin user ID
        $this->transaction->save();

        Saldo::where('id_user', 1)->increment('balance', $this->transaction->remaining_amount);

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

        $transaction = $query->paginate(10);
        $saldo = Saldo::findOrFail(1);

        return view('livewire.pages.request.admin.index', [
            'transactions' => $transaction,
            'saldo' => $saldo
        ]);
    }
}
