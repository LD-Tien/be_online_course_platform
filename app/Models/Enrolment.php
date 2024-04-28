<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolment extends Model
{
    use HasFactory;

    protected $table = 'enrolment';
    const CREATED_AT = 'enrolment_at';
    const UPDATED_AT = 'completed_at';
}