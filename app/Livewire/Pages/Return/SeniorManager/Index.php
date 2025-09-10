<?php

namespace App\Livewire\Pages\Return\SeniorManager;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Transactions;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use Confirm;
    public $search;
    public $typeFilter;
    public $statusFilter;
    public $transaction;
    public $showRejectModal = false;
    public $rejectReason = '';


    public function approveConfirmation($id)
    {
        $this->confirm(
            title: 'Apakah yakin ingin menyetujui transaksi ini?',
            html: 'Klik konfirmasi untuk menyetujui transaksi',
            event: 'approveTransactions',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    #[On('approveTransactions')]
    public function approveTransactions(array $data)
    {
        $transaction = Transactions::findOrFail($data['id']);
        $transaction->status = 'senior_approved';
        $transaction->save();
        flash()->success('Transaksi berhasil disetujui.');

        return $this->redirect(url: route('transactions.senior.manager.index'), navigate: true);
    }


    public function rejectConfirmation($id){
         
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

    #[On('rejectTable')]
    public function rejectedInTable(array $data)
    {
    
        $this->transaction = Transactions::where('id_transactions', $data['id'])->first();
        if (!$this->transaction) {
            session()->flash('error', 'Transaction not found.');
            return redirect()->route('transactions.senior.manager.index');
        }
       
        $this->transaction->status = 'rejected';
        $this->transaction->rejection_reason = $this->rejectReason;
        $this->transaction->save();

        flash()->success('Transaction rejected successfully.');
        return $this->redirect(route('transactions.senior.manager.index'), navigate: true);
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

        $transaction = $query->paginate(10);

        return view('livewire..pages.return.senior-manager.index', [
            'transactions' => $transaction,
        ]);
    }
}
