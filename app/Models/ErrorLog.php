<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'error_type',
        'error_message',
        'stack_trace',
        'file_path',
        'line_number',
        'request_method',
        'request_url',
        'request_data',
        'user_agent',
        'ip_address',
        'user_id',
        'session_id',
        'http_status_code',
        'error_time',
        'email_sent'
    ];

    protected $casts = [
        'request_data' => 'array',
        'error_time' => 'datetime',
        'email_sent' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}