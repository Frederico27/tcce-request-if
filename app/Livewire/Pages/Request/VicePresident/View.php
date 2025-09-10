<?php

namespace App\Livewire\Pages\Request\VicePresident;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Transactions;
use Livewire\Attributes\On;
use Livewire\Component;

class View extends Component
{
    use Confirm;

    public $transactionId;
    public $transaction;
    public $showRejectModal = false;
    public $rejectReason = '';
    public function mount($transactionId)
    {
        $this->transaction = Transactions::where('id_transactions', $transactionId)->first();
        if (!$this->transaction) {
            abort(404, 'Transaction not found');
        }
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
            return redirect()->route('transactions.index');
        }
        if ($this->transaction->action !== 'request') {
            session()->flash('error', 'Transaction is not in requested status.');
            return redirect()->route('transactions.view', [$this->transactionId]);
        }
        $this->transaction->status = 'rejected';
        // $this->transaction->approved_by = 'test'; // This should be replaced with the actual admin user ID
        $this->transaction->rejection_reason = $this->rejectReason;
        $this->transaction->save();

        flash()->success('Transaction rejected successfully.');
        return $this->redirect(route('transactions.manager.view', [$this->transactionId]), navigate: true);
    }


    public function approveConfirmation($id)
    {
        $this->confirm(
            title: 'Apakah yakin ingin menyetujui transaksi ini?',
            html: 'Klik konfirmasi untuk menyetujui transaksi',
            event: 'approveTable',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    #[On('approveTable')]
    public function approvedInTable(array $data)
    {

        $this->transaction = Transactions::where('id_transactions', $data['id'])->first();
        if (!$this->transaction) {
            session()->flash('error', 'Transaction not found.');
            return redirect()->route('transactions.index');
        }
        if ($this->transaction->action !== 'request') {
            session()->flash('error', 'Transaction is not in requested status.');
            return redirect()->route('transactions.view', [$this->transactionId]);
        }
        $this->transaction->status = 'manager_approved';
        // $this->transaction->approved_by = 'test'; // This should be replaced with the actual admin user ID
        $this->transaction->save();

        flash()->success('Transaction approved successfully.');
        return $this->redirect(route('transactions.manager.view', [$this->transactionId]), navigate: true);
    }


    public function render()
    {
        return view('livewire.pages.request.vice-president.view');
    }
}
