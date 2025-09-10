<?php

namespace App\Livewire\Pages\Request\requestor;

use Livewire\Component;

class Create extends Component
{

    public $description;
    public $amount;
    public $action = 'request';
    public $requested_by;
    public $status = 'draft';

    // public function mount()
    // {
    //     $this->requested_by = auth()->user()->name; // Assuming the user is logged in
    // }

    public function save(){

        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        // Create a new transaction
        $transaction = new \App\Models\Transactions();
        $transaction->id_transactions = \Illuminate\Support\Str::uuid();
        $transaction->description = $this->description;
        $transaction->amount = $this->amount;
        $transaction->action = $this->action;
        $transaction->from_user_id = 1;
        $transaction->to_user_id = 2; // Assuming the transaction is to the same user for now
        $transaction->requested_by = 'Joao';
        $transaction->status = $this->status;
        $transaction->save();

        flash()->success('Transaksi request berhasil dibuat.');
        return $this->redirect(route('transactions.requestor.index'), true);
    }


    public function render()
    {
        return view('livewire.pages.request.requestor.create');
    }
}
