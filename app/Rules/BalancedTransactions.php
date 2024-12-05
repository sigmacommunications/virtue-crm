<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BalancedTransactions implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Validation logic to check if debit and credit records are balanced

        $debitTotal = collect($value)->filter(function ($item) {
            return $item['type'] === 'debit';
        })->sum('amount');

        $creditTotal = collect($value)->filter(function ($item) {
            return $item['type'] === 'credit';
        })->sum('amount');

        return $debitTotal === $creditTotal;
    }

    public function message()
    {
        return 'The total amount of debit records must equal the total amount of credit records.';
    }
}
