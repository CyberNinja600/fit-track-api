<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['name', 'created_by', 'admin', 'enrollment_code', 'description'])]
class Program extends Model
{
	protected function name(): Attribute
	{
		return Attribute::make(
			set: static fn (string $value): string => preg_replace('/\s*[\r\n]+\s*/', ' ', $value),
		);
	}
}
