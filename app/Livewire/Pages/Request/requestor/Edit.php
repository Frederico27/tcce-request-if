<?php

namespace App\Livewire\Pages\Request\requestor;

use Livewire\Component;

class Edit extends Component
{
    public $transactionId;
    public $transaction, $description, $amount;
    public function mount()
    {
        $this->transaction = \App\Models\Transactions::findOrFail($this->transactionId);
        $this->description = $this->transaction->description;
        $this->amount = $this->transaction->amount;
    }

    public function updateTransaction()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $this->transaction->update([
            'description' => $this->description,
            'amount' => $this->amount,
        ]);

        flash()->success('Transaksi berhasil diperbarui.');

        return $this->redirect(route('transactions.requestor.index'), true);
    }
    public function render()
    {
        return view('livewire.pages.request.requestor.edit');
    }
}
