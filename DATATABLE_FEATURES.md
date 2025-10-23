# DataTable Features Implementation

This document describes the DataTable-like features that have been added to all index pages in the application.

## Features Implemented

### 1. **Rows Per Page Filter**
Users can now select how many rows to display per page:
- 10 rows (default)
- 25 rows
- 50 rows
- 100 rows
- All rows

The filter is located above each table and updates the display in real-time using Livewire.

### 2. **Column Sorting**
All table columns (except action columns) are now sortable:
- Click on any column header to sort by that column
- First click: Sort ascending (↑)
- Second click: Sort descending (↓)
- Active sort indicator is shown in blue
- Inactive sort arrows are shown in gray
- Hover effect on sortable columns for better UX

### 3. **Sortable Columns by Page**

#### Admin Transaction Index
- ID Transaksi
- Aksi Transaksi
- Deskripsi
- Nominal Request
- Dana Tambahan
- Sisa Dana
- Di Request oleh
- Tanggal
- Status

#### Manager Transaction Index
- ID Transaksi
- Aksi Transaksi
- Deskripsi
- Nominal Request
- Dana Tambahan
- Sisa Dana
- Di Request oleh
- Tanggal
- Status

#### Requestor Transaction Index
- ID Transaksi
- Aksi Transaksi
- Deskripsi
- Nominal Request
- Dana Tambahan
- Sisa Dana
- Di Request oleh
- Tanggal
- Status

#### Finance Transaction Index
- ID Transaksi
- Aksi Transaksi
- Deskripsi
- Nominal
- Di Request oleh
- Tanggal
- Status

#### Users Index
- Nama Pengguna
- NIK
- Posisi
- No. Telepon
- Status

## Technical Implementation

### Backend (Livewire Components)

Each index component now includes:

```php
// Properties
public $perPage = 10;
public $sortField = 'created_at'; // or 'full_name' for users
public $sortDirection = 'desc';

// Method for sorting
public function sortBy($field)
{
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
}

// In render method
$query->orderBy($this->sortField, $this->sortDirection);
$results = $query->paginate($this->perPage);
```

### Frontend (Blade Templates)

#### Rows Per Page Filter
```blade
<div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <label for="perPage" class="text-sm text-gray-700">Show</label>
            <select wire:model.live="perPage" id="perPage" class="...">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="{{ $items->total() }}">All</option>
            </select>
            <span class="text-sm text-gray-700">entries</span>
        </div>
    </div>
</div>
```

#### Sortable Header Component
A reusable Blade component was created at `resources/views/components/sortable-header.blade.php`:

```blade
<x-sortable-header field="column_name" :sortField="$sortField" :sortDirection="$sortDirection">
    Column Label
</x-sortable-header>
```

## Files Modified

### Livewire Components
1. `/app/Livewire/Pages/Request/admin/Index.php`
2. `/app/Livewire/Pages/Request/manager/Index.php`
3. `/app/Livewire/Pages/Return/requestor/Index.php`
4. `/app/Livewire/Pages/Return/Finance/Index.php`
5. `/app/Livewire/Pages/Users/Index.php`

### Blade Views
1. `/resources/views/livewire/pages/request/admin/index.blade.php`
2. `/resources/views/livewire/pages/request/manager/index.blade.php`
3. `/resources/views/livewire/pages/return/requestor/index.blade.php`
4. `/resources/views/livewire/pages/return/finance/index.blade.php`
5. `/resources/views/livewire/pages/users/index.blade.php`

### New Component
- `/resources/views/components/sortable-header.blade.php`

## Usage

### Changing Default Items Per Page
To change the default number of items per page, modify the `$perPage` property in the component:

```php
public $perPage = 25; // Change from 10 to 25
```

### Changing Default Sort
To change the default sort field or direction:

```php
public $sortField = 'id_transactions'; // Sort by transaction ID
public $sortDirection = 'asc'; // Ascending order
```

### Adding Sortable Columns
To make a new column sortable:

1. Replace the static `<th>` with the sortable component:
```blade
<x-sortable-header field="database_column_name" :sortField="$sortField" :sortDirection="$sortDirection">
    Column Label
</x-sortable-header>
```

## Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (with Babel transpilation)

## Performance Considerations
- Sorting is done at the database level for efficiency
- Pagination prevents large dataset loading
- Livewire uses AJAX to update only the table content without full page reload

## Future Enhancements
Potential improvements that could be added:
- Multi-column sorting
- Save user preferences (sort order, items per page) in session/database
- Export filtered/sorted data
- Advanced search with column-specific filters
- Drag and drop column reordering
