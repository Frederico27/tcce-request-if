<?php

namespace App\Livewire\Pages\Return\requestor;

use Akhaled\LivewireSweetalert\Confirm;
use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\TransactionAttachment;
use App\Models\TransactionDetails;
use App\Models\TransactionImageActivity;
use DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use Confirm;
    use WithFileUploads;

    public $transactionId;
    public $transactionDetails = [];
    public $images = [];
    public $imageSrcs = [];
    public $descriptions = [];
    public $amounts = [];
    public $categories = [];
    public $subCategories = [];
    public $subCategoryOptions = [];
    public $categoryOptions = [];
    public $activityImages = [];
    public $existingActivityImages = [];

    public function mount()
    {
        $this->loadTransactionDetails();
        $this->loadCategories();
    }

    public function loadTransactionDetails()
    {
        $this->transactionDetails = TransactionDetails::with('transactionAttachments', 'subCategory.category')
            ->where('id_transactions', $this->transactionId)
            ->get();

        foreach ($this->transactionDetails as $index => $detail) {
            $this->descriptions[$index] = $detail->used_for;
            $this->amounts[$index] = $detail->amount;
            $this->categories[$index] = $detail->subCategory->category->id_category;
            $this->subCategories[$index] = $detail->id_sub_category;

            if ($detail->transactionAttachments->count() > 0) {
                $this->imageSrcs[$index] = asset('storage/' . $detail->transactionAttachments[0]->file_path);
            }

            // Load existing activity images
            $activityImages = TransactionImageActivity::where('id_transaction_detail', $detail->id_transaction_detail)->get();
            $this->existingActivityImages[$index] = $activityImages->map(function ($img) {
                return [
                    'id' => $img->id_image_activity,
                    'path' => asset('storage/' . $img->image_path),
                    'description' => $img->description,
                ];
            })->toArray();

            // Initialize activity images array
            $this->activityImages[$index] = [];

            // Load subcategory options for this category
            $this->loadSubCategoryOptions($index, $detail->subCategory->category->id_category);
        }
    }

    public function loadCategories()
    {
        $this->categoryOptions = Categories::select('id_category', 'category_name')->get();
    }

    public function loadSubCategoryOptions($index, $categoryId)
    {
        if (!$categoryId)
            return;

        $this->subCategoryOptions[$index] = SubCategories::where('id_category', $categoryId)
            ->select('id_sub_category', 'sub_category_name')
            ->get()
            ->toArray();
    }
    public function handleCategories($value, $index)
    {
        $this->subCategories[$index] = null;
        $this->loadSubCategoryOptions($index, $value);
        $this->dispatch('subcategory-options-updated', index: $index); // Emit event
    }

    public function removeActivityImage($itemIndex, $imageId)
    {
        try {
            $activityImage = TransactionImageActivity::find($imageId);
            if ($activityImage) {
                // Delete the file from storage
                if (\Storage::disk('public')->exists($activityImage->image_path)) {
                    \Storage::disk('public')->delete($activityImage->image_path);
                }
                $activityImage->delete();

                // Remove from existing images array
                $this->existingActivityImages[$itemIndex] = array_filter(
                    $this->existingActivityImages[$itemIndex] ?? [],
                    fn($img) => $img['id'] !== $imageId
                );
                $this->existingActivityImages[$itemIndex] = array_values($this->existingActivityImages[$itemIndex]);

                flash()->success('Gambar aktivitas berhasil dihapus.');
            }
        } catch (\Exception $e) {
            flash()->error('Gagal menghapus gambar aktivitas: ' . $e->getMessage());
        }
    }

    public function removeNewActivityImage($itemIndex, $imageIndex)
    {
        if (isset($this->activityImages[$itemIndex][$imageIndex])) {
            unset($this->activityImages[$itemIndex][$imageIndex]);
            $this->activityImages[$itemIndex] = array_values($this->activityImages[$itemIndex]);
        }
    }

    public function deleteConfirmation($index)
    {
        $this->confirm(
            title: 'Apakah yakin ingin menghapus item ini?',
            html: 'Klik konfirmasi untuk menghapus item',
            event: 'deleteDetail',
            options: [
                'confirmButtonText' => 'Konfirmasi',
                'cancelButtonText' => 'Batal',
            ],
            data: ['index' => $index]
        );
    }

    #[On('deleteDetail')]
    public function deleteDetail($data)
    {
        try {
            DB::beginTransaction();

            $detail = $this->transactionDetails[$data['index']];

            // Delete attachments first
            foreach ($detail->transactionAttachments as $attachment) {
                // Delete the file from storage
                if (\Storage::disk('public')->exists($attachment->file_path)) {
                    \Storage::disk('public')->delete($attachment->file_path);
                }
                $attachment->delete();
            }

            // Then delete the transaction detail
            $detail->delete();

            DB::commit();

            // Reload transaction details
            $this->loadTransactionDetails();

            flash()->success('Item berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Gagal menghapus item: ' . $e->getMessage());
        }
    }

    public function save()
    {
        try {
            DB::beginTransaction();



            foreach ($this->transactionDetails as $index => $detail) {

                //checktotal amount with amount transaction
                $amountTransaction = $detail->transaction->amount;
                $totalamountUploaded = TransactionDetails::where('id_transactions', $detail->id_transactions)
                    ->where('id_transaction_detail', '!=', $detail->id_transaction_detail)
                    ->sum('amount');

                //Limit amount

                // $totalamountUploaded += $this->amounts[$index];
                // if ($totalamountUploaded > $amountTransaction) {
                //     flash()->error('Total amount detail melebihi jumlah transaksi.');
                //     return;
                // }


                // Update transaction details
                $detail->used_for = $this->descriptions[$index];
                $detail->amount = $this->amounts[$index];
                $detail->id_sub_category = $this->subCategories[$index];
                $detail->save();

                // Update image if a new one is uploaded
                if (isset($this->images[$index]) && $this->images[$index]) {
                    // Delete old attachment if it exists
                    foreach ($detail->transactionAttachments as $attachment) {
                        if (\Storage::disk('public')->exists($attachment->file_path)) {
                            \Storage::disk('public')->delete($attachment->file_path);
                        }
                        $attachment->delete();
                    }

                    // Store new image
                    $imagePath = $this->images[$index]->store('/buktiReturn', 'public');

                    // Create new attachment
                    TransactionAttachment::create([
                        'id_transaction_detail' => $detail->id_transaction_detail,
                        'file_path' => $imagePath,
                        'file_type' => 'image',
                        'uploaded_by' => auth()->user()->full_name ?? 'System',
                    ]);
                }

                // Save new activity images if provided
                if (isset($this->activityImages[$index]) && is_array($this->activityImages[$index])) {
                    foreach ($this->activityImages[$index] as $activityImage) {
                        if ($activityImage) {
                            $activityImagePath = $activityImage->store('/activityImages', 'public');
                            TransactionImageActivity::create([
                                'id_transaction_detail' => $detail->id_transaction_detail,
                                'description' => $this->descriptions[$index],
                                'image_path' => $activityImagePath,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            flash()->success('Detail transaksi berhasil diupdate.');
            return $this->redirect(route('transactions.requestor.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Gagal mengupdate detail transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.return.requestor.edit', [
            'categoryOptions' => $this->categoryOptions,
        ]);
    }
}
