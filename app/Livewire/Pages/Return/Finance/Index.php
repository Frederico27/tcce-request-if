<?php

namespace App\Livewire\Pages\Return\Finance;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Saldo;
use App\Models\Transactions;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use Confirm;
    public $search;
    public $typeFilter;
    public $statusFilter;
    public $transaction;

    public function approveConfirmation($id)
    {
        $this->confirm(
            title: 'Apakah yakin ingin menyelesaikan transaksi ini?',
            html: 'Klik konfirmasi untuk menyetujui',
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
        if($transaction->remaining_amount >= 0) {
            //Top up the remaining to admin 
            Saldo::where('id_user', operator: User::role('admin')->first()->id)
                ->increment('balance', $transaction->remaining_amount);
        }else{
            flash()->error('Transaksi tidak dapat diselesaikan karena sisa jumlah kurang dari 0.');
        }
        $transaction->status = 'completed';
        $transaction->save();
        flash()->success('Transaksi berhasil diselesaikan.');

        return $this->redirect(url: route('transactions.finance.index'), navigate: true);
    }

   
    public function render()
    {
        $query = Transactions::query()->where('status', '!=', 'draft');
        // Filter by search
        if (!empty($this->searchs)) {
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

        return view('livewire.pages.return.finance.index', [
            'transactions' => $transaction
        ]);
    }
}
