<?php

namespace App\Livewire\Pages\Request\Finance;

use App\Models\Transactions;
use Livewire\Component;

class View extends Component
{
    public $transactionId;
    public $transaction;
    public function mount($transactionId)
    {
        $this->transaction = Transactions::where('id_transactions', $transactionId)->first();
        if (!$this->transaction) {
            abort(404, 'Transaction not found');
        }
    }

    public function render()
    {
        return view('livewire..pages.request.finance.view');
    }
}
