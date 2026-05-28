<?php

namespace App\Livewire\Settings;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Component;

class ExpenseCategoryManager extends Component
{
    public string $newCategoryName = '';

    protected function rules(): array
    {
        return [
            'newCategoryName' => [
                'required',
                'string',
                'max:40',
                // Case-insensitive user-scoped uniqueness check
                function ($attribute, $value, $fail) {
                    $cleanValue = trim($value);
                    
                    // Prevent duplicates with default categories
                    $defaults = array_map('strtolower', ['Software', 'Travel', 'Marketing', 'Office', 'Salaries', 'Other']);
                    if (in_array(strtolower($cleanValue), $defaults)) {
                        $fail('This is a system default category and cannot be added as a custom one.');
                        return;
                    }

                    $exists = ExpenseCategory::where('user_id', auth()->id())
                        ->where('name', $cleanValue)
                        ->exists();

                    if ($exists) {
                        $fail('You have already added this category.');
                    }
                }
            ],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $cleanName = trim($this->newCategoryName);

        ExpenseCategory::create([
            'user_id' => auth()->id(),
            'name' => $cleanName,
        ]);

        $this->newCategoryName = '';
        session()->flash('success', "Category '{$cleanName}' created successfully!");
    }

    public function delete(int $id): void
    {
        $category = ExpenseCategory::where('user_id', auth()->id())->findOrFail($id);
        $categoryName = $category->name;

        // Re-categorize any existing expenses in this category to 'Other'
        Expense::where('user_id', auth()->id())
            ->where('category', $categoryName)
            ->update(['category' => 'Other']);

        $category->delete();

        session()->flash('success', "Category '{$categoryName}' deleted. Expenses in this category have been moved to 'Other'.");
    }

    public function render()
    {
        $defaultCategories = ['Software', 'Travel', 'Marketing', 'Office', 'Salaries', 'Other'];
        $customCategories = ExpenseCategory::where('user_id', auth()->id())->latest()->get();

        return view('livewire.settings.expense-category-manager', compact('defaultCategories', 'customCategories'))
            ->layout('layouts.app', ['title' => 'Expense Categories — InvoiceFlow']);
    }
}
