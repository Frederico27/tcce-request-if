<?php

namespace App\Livewire\Pages\Request\manager;

use Akhaled\LivewireSweetalert\Confirm;
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

    public function approveConfirmation($id){
        $this->confirm(
            title: 'Apakah yakin ingin menyetujui request ini?',
            html: 'Klik konfirmasi untuk menyetujui request',
            event: 'approveTable',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    #[On('approveTable')]
    public function approveTable(array $data){

        $this->transaction = Transactions::where('id_transactions', $data['id'])->first();

        if(!$this->transaction){
            session()->flash('error', 'Transaction not found.');
            return $this->redirect(route('transactions.manager.index')) ;
        }
        if($this->transaction->action !== 'request'){
            session()->flash('error', 'Transaction is not in requested status.');
            return $this->redirect(route('transactions.manager.index'));
        }
        $this->transaction->status = 'manager_approved';
        $this->transaction->approved_by =['Riko']; // This should be replaced with the actual admin user ID
        $this->transaction->save();
        session()->flash('success', 'Transaction approved successfully.');
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
    #[On('approveReturnTable')]
    public function approveReturnTable(array $data)
    {

        $this->transaction = Transactions::where('id_transactions', $data['id'])->first();

        if (!$this->transaction) {
            session()->flash('error', 'Transaction not found.');
            return $this->redirect(route('transactions.manager.index'));
        }
        if ($this->transaction->action !== 'return') {
            session()->flash('error', 'Transaction is not in return status.');
            return $this->redirect(route('transactions.manager.index'));
        }
        $this->transaction->status = 'manager_approved';
        $this->transaction->approved_by = ['Riko']; // This should be replaced with the actual admin user ID
        $this->transaction->save();
        flash()->success('Return transaction approved successfully.');
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
       
        $this->transaction->status = 'rejected';
        $this->transaction->rejection_reason = $this->rejectReason;
        $this->transaction->save();

        flash()->success('Transaction rejected successfully.');
        return $this->redirect(route('transactions.manager.index'), navigate: true);
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


        return view('livewire.pages.request.manager.index', [
            'transactions' => $transaction,
        ]);
    }
}
