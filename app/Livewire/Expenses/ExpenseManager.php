<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ExpenseManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCategory = '';
    public bool $isCreating = false;

    // Form fields
    public string $title = '';
    public $amount = 0;
    public string $currency = 'USD';
    public string $category = 'Other';
    public string $expense_date = '';
    public string $notes = '';
    public $receipt = null; // file upload

    // Custom Category Add fields
    public bool $isAddingCategory = false;
    public string $newCategoryName = '';

    public function mount(): void
    {
        $this->expense_date = now()->toDateString();
        $this->currency = auth()->user()->default_currency ?? 'USD';
    }

    public function getCategories(): array
    {
        $defaults = ['Software', 'Travel', 'Marketing', 'Office', 'Salaries', 'Other'];
        $customs = ExpenseCategory::where('user_id', auth()->id())->pluck('name')->all();
        
        return array_unique(array_merge($defaults, $customs));
    }

    protected function rules(): array
    {
        $allowed = implode(',', $this->getCategories());
        
        return [
            'title'        => 'required|string|max:100',
            'amount'       => 'required|numeric|min:0.01',
            'currency'     => 'required|string|max:3',
            'category'     => 'required|string|in:' . $allowed,
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string|max:500',
            'receipt'      => 'nullable|file|max:5120|mimes:jpeg,png,pdf,jpg',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function toggleCreate(): void
    {
        $this->isCreating = !$this->isCreating;
        if ($this->isCreating) {
            $this->resetForm();
        }
    }

    public function resetForm(): void
    {
        $this->title = '';
        $this->amount = 0;
        $this->currency = auth()->user()->default_currency ?? 'USD';
        $this->category = 'Other';
        $this->expense_date = now()->toDateString();
        $this->notes = '';
        $this->receipt = null;
        $this->isAddingCategory = false;
        $this->newCategoryName = '';
    }

    public function toggleAddCategory(): void
    {
        $this->isAddingCategory = !$this->isAddingCategory;
        $this->newCategoryName = '';
    }

    public function saveCategory(): void
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:40|unique:expense_categories,name,NULL,id,user_id,' . auth()->id(),
        ], [
            'newCategoryName.unique' => 'This category already exists.',
            'newCategoryName.required' => 'The category name is required.',
        ]);

        $cleanName = trim($this->newCategoryName);
        ExpenseCategory::create([
            'user_id' => auth()->id(),
            'name' => $cleanName,
        ]);

        $this->category = $cleanName;
        $this->isAddingCategory = false;
        $this->newCategoryName = '';
        
        session()->flash('category_success', "Category '{$cleanName}' added and selected!");
    }

    public function save(): void
    {
        $this->validate();

        $receiptPath = null;
        if ($this->receipt) {
            $receiptPath = $this->receipt->store('receipts', 'public');
        }

        Expense::create([
            'user_id'      => auth()->id(),
            'title'        => $this->title,
            'amount'       => $this->amount,
            'currency'     => $this->currency,
            'category'     => $this->category,
            'receipt_path' => $receiptPath,
            'expense_date' => $this->expense_date,
            'notes'        => $this->notes,
        ]);

        session()->flash('success', 'Expense recorded successfully!');
        $this->resetForm();
        $this->isCreating = false;
    }

    public function delete(int $id): void
    {
        $expense = Expense::where('user_id', auth()->id())->findOrFail($id);
        if ($expense->receipt_path) {
            \Storage::disk('public')->delete($expense->receipt_path);
        }
        $expense->delete();
        session()->flash('success', 'Expense deleted successfully.');
    }

    public function render()
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
            ->orderBy('expense_date', 'desc')
            ->paginate(10);

        // Aggregated totals
        $totalExpenses = Expense::where('user_id', auth()->id())->sum('amount');
        
        $categoriesData = Expense::where('user_id', auth()->id())
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->all();

        $availableCategories = $this->getCategories();

        return view('livewire.expenses.expense-manager', compact('expenses', 'totalExpenses', 'categoriesData', 'availableCategories'))
            ->layout('layouts.app', ['title' => 'Expense Tracker — InvoiceFlow']);
    }
}
