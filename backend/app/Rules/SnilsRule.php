<?php

namespace App\Rules;

use App\Services\SnilsValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SnilsRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $validator = app(SnilsValidator::class);

        if (! $validator->isValid((string) $value)) {
            $fail('Поле :attribute содержит некорректный СНИЛС.');
        }
    }
}
