<?php

namespace App\Livewire\Pages\Return\requestor;

use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\TransactionAttachment;
use App\Models\TransactionDetails;
use App\Models\TransactionImageActivity;
use App\Models\Transactions;
use Illuminate\Support\Facades\DB;
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
    public $invoice_date;
    public $description;
    public $filteredSubCategories = [];
    public $items = []; // dynamic items for multiple returns

    public function mount()
    {
        $this->id = request()->get('id');
        $this->loadSubCategories();
        // initialize with one empty item
        $this->items = [
            [
                'image' => null,
                'description' => '',
                'amount' => null,
                'invoice_date' => null,
                'category' => null,
                'id_sub_category' => null,
                'filteredSubCategories' => [],
                'activity_images' => [], // optional activity images
            ]
        ];
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

    // Handle dynamic item category change
    public function updated($propertyName, $value)
    {
        // Expecting property like items.0.category
        if (str_starts_with($propertyName, 'items.')) {
            $parts = explode('.', $propertyName);
            // parts: ['items', '{index}', '{field}'] or ['items','{index}']
            if (count($parts) >= 3) {
                $index = $parts[1];
                $field = $parts[2];
                if ($field === 'category') {
                    $cat = $value;
                    if ($cat) {
                        $subs = SubCategories::where('id_category', $cat)
                            ->select('id_sub_category', 'sub_category_name')
                            ->get()
                            ->toArray();
                    } else {
                        $subs = [];
                    }
                    // ensure index exists
                    if (!isset($this->items[$index])) {
                        return;
                    }
                    $this->items[$index]['filteredSubCategories'] = $subs;
                    $this->items[$index]['id_sub_category'] = null;
                }
            }
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'image' => null,
            'description' => '',
            'amount' => null,
            'invoice_date' => null,
            'category' => null,
            'id_sub_category' => null,
            'filteredSubCategories' => [],
            'activity_images' => [], // optional activity images
        ];
    }

    public function removeItem($index)
    {
        if (isset($this->items[$index])) {
            // cleanup temporary uploaded file if any
            // Livewire handles temp uploads; unset is enough
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function removeActivityImage($itemIndex, $imageIndex)
    {
        if (isset($this->items[$itemIndex]['activity_images'][$imageIndex])) {
            unset($this->items[$itemIndex]['activity_images'][$imageIndex]);
            $this->items[$itemIndex]['activity_images'] = array_values($this->items[$itemIndex]['activity_images']);
        }
    }


    public function save()
    {
        try {
            // Validate all items
            $this->validate([
                'items' => 'required|array|min:1',
                'items.*.description' => 'required|string|max:255',
                'items.*.amount' => 'required|numeric|min:0',
                'items.*.invoice_date' => 'required|date',
                'items.*.id_sub_category' => 'required|exists:sub_categories,id_sub_category',
                'items.*.image' => 'required|image|max:2048',
                'items.*.activity_images.*' => 'nullable|image|max:2048', // optional activity images
            ]);


            //Limit amount 
            // $amountTransaction = Transactions::where('id_transactions', $this->id)->first();
            // $totalAmountUploaded = TransactionDetails::where('id_transactions', $this->id)->sum('amount');

            // Calculate total amount from all items
            // $totalNewAmount = collect($this->items)->sum('amount');


            // if (($totalAmountUploaded + $totalNewAmount) > $amountTransaction->amount) {
            //     flash()->error('Total amount detail melebihi jumlah transaksi.');
            //     return;
            // }

            DB::beginTransaction();

            // Save each item
            foreach ($this->items as $item) {
                $detailTransaction = TransactionDetails::create([
                    'id_transactions' => $this->id,
                    'used_for' => $item['description'],
                    'amount' => $item['amount'],
                    'invoice_date' => $item['invoice_date'],
                    'id_sub_category' => $item['id_sub_category'],
                ]);

                if (isset($item['image']) && $item['image']) {
                    $imagePath = $item['image']->store('/buktiReturn', 'public');
                    TransactionAttachment::create([
                        'id_transaction_detail' => $detailTransaction->id_transaction_detail,
                        'file_path' => $imagePath,
                        'file_type' => 'image',
                        'uploaded_by' => 'Riko',
                    ]);
                }

                // Save activity images if provided
                if (isset($item['activity_images']) && is_array($item['activity_images'])) {
                    foreach ($item['activity_images'] as $activityImage) {
                        if ($activityImage) {
                            $activityImagePath = $activityImage->store('/activityImages', 'public');
                            TransactionImageActivity::create([
                                'id_transaction_detail' => $detailTransaction->id_transaction_detail,
                                'description' => $item['description'],
                                'image_path' => $activityImagePath,
                            ]);
                        }
                    }
                }
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
