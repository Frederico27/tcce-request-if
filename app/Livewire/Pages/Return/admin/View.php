<?php

namespace App\Livewire\Pages\Return\admin;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\TransactionDetails;
use App\Models\Transactions;
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
    
    public function render()
    {

        $detailReturn = TransactionDetails::with('transactionAttachments', 'subCategory.category')
            ->where('id_transactions', $this->transaction->id_transactions)
            ->get();
            
        
        return view('livewire.pages.return.admin.view', [
            'detailReturn' => $detailReturn,
        ]);
    }
}
