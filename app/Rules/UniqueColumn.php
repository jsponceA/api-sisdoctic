<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueColumn implements ValidationRule
{
    private $tableName;
    private $columnName;
    private $id;

    private $useSoftDelete;

    public function __construct($tableName, $columnName, $id = null, $useSoftDelete = false)
    {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->id = $id;
        $this->useSoftDelete = $useSoftDelete;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table($this->tableName)
            ->where(DB::raw("LOWER({$this->columnName})"), "=", str($value)->lower())
            ->when(!empty($this->id), function ($query) {
                $query->where("id", "!=", $this->id);
            })
            ->when($this->useSoftDelete, function ($query) {
                $query->whereNull("deleted_at");
            })
            ->exists();

        if ($exists) {
            $fail('El :attribute ya está registrado.');
        }
    }
}
