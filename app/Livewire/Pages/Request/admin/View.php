<?php

namespace App\Livewire\Pages\Request\admin;
use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Saldo;
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
        $this->transaction->status = 'admin_approved';
        // $this->transaction->approved_by = 'test'; // This should be replaced with the actual admin user ID
        $this->transaction->save();

        flash()->success('Transaction approved successfully.');
        return $this->redirect(route('transactions.view', [$this->transactionId]), navigate: true);
    }


    public function verifyConfirmation($id)
    {
        $this->confirm(
            title: 'Apakah yakin ingin memverifikasi transaksi ini?',
            html: 'Jika iya maka danamu sudah diterima oleh requestor',
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





    public function render()
    {
        return view('livewire.pages.request.admin.view');
    }
}
