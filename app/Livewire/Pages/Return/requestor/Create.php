<?php

namespace App\Livewire\Pages\Return\requestor;

use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\TransactionAttachment;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $id;
    public $category;
    public $id_sub_category;
    public $image;
    public $amount;
    public $description;
    public $filteredSubCategories = [];

    public function mount()
    {
        $this->id = request()->get('id');
        $this->loadSubCategories();
    }

    // Update this method to properly handle the category update
    public function updatedCategory($value)
    {
        // Only dispatch if there's an actual change to prevent loops
        if ($this->category !== $value) {
            $this->category = $value;
            $this->id_sub_category = null; // Reset subcategory when category changes
            $this->loadSubCategories();
            $this->dispatch('categoryUpdated', $value);
            $this->dispatch('subcategoriesUpdated', $this->filteredSubCategories);
        }
    }

    // Add a method to set category from JS without triggering re-renders
    public function setCategory($value)
    {
        $this->category = $value;
        $this->id_sub_category = null; // Reset subcategory when category changes
        $this->loadSubCategories();
        $this->dispatch('subcategoriesUpdated', $this->filteredSubCategories);
    }

    // Add a method to set subcategory from JS
    public function setSubCategory($value)
    {
        $this->id_sub_category = $value;
    }

    // Load subcategories based on selected category
    private function loadSubCategories()
    {
        if ($this->category) {
            $this->filteredSubCategories = SubCategories::where('id_category', $this->category)
                ->select('id_sub_category', 'sub_category_name')
                ->get()
                ->toArray();
        } else {
            $this->filteredSubCategories = [];
        }
    }


    public function save()
    {
        try {
            
            $this->validate([
                'description' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'id_sub_category' => 'required|exists:sub_categories,id_sub_category',
                'image' => 'required|image|max:2048', // Optional image upload
            ]);

            $amountTransaction = Transactions::find($this->id)->select('amount')->first();
            $totalamountUploaded = TransactionDetails::where('id_transactions', $this->id)->sum('amount');

            $totalamountUploaded += $this->amount;
            
            if($totalamountUploaded > $amountTransaction->amount){
                flash()->error('Total amount detail melebihi jumlah transaksi.');
                return;
            }

            DB::beginTransaction();

            $detailTransaction = TransactionDetails::create([
                'id_transactions' => $this->id,
                'used_for' => $this->description,
                'amount' => $this->amount,
                'id_sub_category' => $this->id_sub_category,
            ]);

            if ($this->image) {
                $imagePath = $this->image->store('/buktiReturn', 'public');
                TransactionAttachment::create([
                    'id_transaction_detail' => $detailTransaction->id_transaction_detail,
                    'file_path' => $imagePath,
                    'file_type' => 'image',
                    'uploaded_by' => 'Riko',
                ]);

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return;
        }
        flash()->success('Detail transaksi berhasil diupload.');
        return $this->redirect(route('transactions.requestor.index'), navigate: true);
    }
    public function render()
    {
        $categories = Categories::select('id_category', 'category_name')->get();
        return view('livewire.pages.return.requestor.create', [
            'subCategories' => $this->filteredSubCategories,
            'categories' => $categories
        ]);
    }
}
