<?php

namespace App\Livewire\Pages\Return\requestor;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{

    use Confirm;

    public function submitConfirmation($id)
    {
        $this->confirm(
            title: 'Apakah yakin ingin mengajukan transaksi ini?',
            html: 'Klik konfirmasi untuk mengajukan transaksi',
            event: 'submitTransactions',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    #[On('submitTransactions')]
    public function submitTransactions(array $data)
    {

        $transaction = Transactions::findOrFail($data['id']);
        $transaction->status = 'pending';
        $transaction->save();
        //send telegram notification

        $message = "🔔 *New Transaction Submitted*\n\n"
        . "📋 *Transaction ID:* {$transaction->id_transactions}\n"
        . "📦 *Action:* " . ucfirst($transaction->action) . "\n"
        . "💰 *Amount:* $" . number_format($transaction->amount, 2) . "\n"
        . "📝 *Description:* " . $transaction->description . "\n"
        . "📅 *Date:* " . $transaction->created_at->format('d/m/Y H:i') . "\n"
        . "🔄 *Status:* Pending Review\n\n"
        . "Please review this transaction in the admin panel.";

        app('App\Services\TelegramService')->sendMessage($message);

        flash()->success('Transaksi berhasil diajukan.');

        return $this->redirect(url: route('transactions.requestor.index'), navigate: true);
    }

   


    public function returnConfirmation($id)
    {

        $this->confirm(
            title: 'Apakah yakin ingin mengajukan return transaksi ini?',
            html: 'Klik konfirmasi untuk mengajukan transaksi',
            event: 'returnTransactions',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['id' => $id]
        );
    }

    #[On('returnTransactions')]
    public function returnTransactions(array $data)
    {

        $amounDetails = TransactionDetails::where('id_transactions', $data['id'])->sum('amount');
        if ($amounDetails <= 0) {
            flash()->error('Transaksi tidak dapat diajukan return karena tidak memiliki detail transaksi atau jumlah detail transaksi nol.');
            return $this->redirect(url: route('transactions.requestor.index'), navigate: true);
        }
        $transaction = Transactions::findOrFail($data['id']);
        $remaining_amount = $transaction->amount - $amounDetails;
        $transaction->action = 'return';
        $transaction->status = 'pending';
        $transaction->remaining_amount = $remaining_amount;
        $transaction->save();
        flash()->success('Transaksi berhasil diajukan return.');

        return $this->redirect(url: route('transactions.requestor.index'), navigate: true);
    }

    public function render()
    {
        $query = Transactions::query();
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

        return view('livewire.pages.return.requestor.index', [
            'transactions' => $transaction
        ]);
    }
}
