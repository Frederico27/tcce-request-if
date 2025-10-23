# Export Functionality Implementation

## Overview
This implementation adds Excel and PDF export functionality to the admin transactions page with checkbox selection for multiple transactions.

## Features Implemented

### 1. **Checkbox Selection System**
- Added a "Select All" checkbox in the table header
- Individual checkboxes for each transaction row
- Visual counter showing how many transactions are selected
- Reactive selection state using Livewire

### 2. **Export Buttons**
- **Excel Export Button** (Green): Exports selected transactions to an Excel file
- **PDF Export Button** (Red): Exports selected transactions to a PDF file with return proof images
- Both buttons are disabled when no transactions are selected

### 3. **Excel Export** (`TransactionsExport.php`)
**Columns included:**
- ID Transaction
- Action (Request/Return)
- Description
- Amount
- Additional Amount
- Remaining Amount
- Amount Return (for return transactions)
- Requested By
- Approved By
- Status
- Date

**Features:**
- Formatted currency display ($X.XX)
- Styled header with gray background
- Auto-adjusted column widths
- Sorted by creation date (descending)

### 4. **PDF Export** (`transactions-pdf.blade.php`)
**Data included:**
- Transaction ID and Action type
- Description
- Amount, Additional Amount, Remaining Amount
- Amount Return (calculated for return transactions)
- Requested By and Approved By
- Status with color-coded badges
- Transaction date
- **Return Proof Images** (for return transactions only)

**Features:**
- Professional card-based layout
- Color-coded status badges (approved, pending, rejected, completed)
- Embedded images for return proof
- Page break prevention for cards
- Header with generation timestamp
- Footer with copyright notice

## Files Created/Modified

### Created Files:
1. `/app/Exports/TransactionsExport.php` - Excel export class
2. `/resources/views/exports/transactions-pdf.blade.php` - PDF template

### Modified Files:
1. `/app/Livewire/Pages/Request/admin/Index.php` - Added export methods and selection logic
2. `/resources/views/livewire/pages/request/admin/index.blade.php` - Added checkboxes and export buttons

## Packages Installed

```bash
composer require maatwebsite/excel barryvdh/laravel-dompdf
```

- **maatwebsite/excel** (v1.1.5) - For Excel file generation
- **barryvdh/laravel-dompdf** (v3.1.1) - For PDF generation
- **dompdf/dompdf** (v3.1.3) - PDF rendering engine

## Usage Instructions

### For Users:
1. Navigate to the Admin Transactions page
2. Use the checkbox next to each transaction to select items for export
3. Use the "Select All" checkbox to select/deselect all visible transactions
4. Click "Export to Excel" or "Export to PDF" button
5. The file will be downloaded automatically with timestamp in filename

### Export Behavior:
- **Excel**: Clean spreadsheet format suitable for data analysis
- **PDF**: Formatted document with images, suitable for printing/archiving
- **Return Transactions**: PDF includes embedded proof images
- **Filename Format**: `transactions_YYYY-MM-DD_HHMMSS.xlsx` or `.pdf`

## Technical Details

### Database Structure:
- **Transactions**: Main transaction table
- **TransactionDetails**: Details of each transaction (items/expenses)
- **TransactionAttachment**: File attachments (images/documents) linked to transaction details
- Return proof images are stored in `TransactionAttachment` with `file_type = 'image'`

### Livewire Properties Added:
- `$selectedTransactions` - Array of selected transaction IDs
- `$selectAll` - Boolean for select all state

### Livewire Methods Added:
- `updatedSelectAll($value)` - Handles select all functionality
- `exportExcel()` - Generates and downloads Excel file
- `exportPdf()` - Generates and downloads PDF file with embedded images

### Export Data Processing:
- Retrieves selected transactions from database
- Calculates additional fields (details_amount, return amounts)
- Loads transaction attachments (images) for return transactions via TransactionDetails relationship
- Filters only image type attachments
- Validates file existence before including in PDF
- Formats data for presentation

## Notes

- Return proof images are only included in PDF exports
- Images must exist in storage path to be displayed
- Export respects current filters (search, date, type, status)
- Large exports may take longer to process
- PDF generation includes inline styles for consistent rendering
- Empty selection triggers a warning message

## Future Enhancements (Optional)

1. Add date range filter for exports
2. Add custom column selection
3. Email export functionality
4. Scheduled/automated exports
5. Export progress indicator for large datasets
6. ZIP download for PDFs with separate image files
