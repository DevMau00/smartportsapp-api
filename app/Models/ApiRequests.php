<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequests extends Model
{
    use HasFactory;

    protected $table = 'api_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'api_key',
        'method',
        'url',
        'ip',
        'user_agent',
        'response_code',
        'response_time',
        'response_size',
        'response_type',
        'response_content',
        'response_headers',
        'request_headers',
        'request_body',
        'request_params',
        'request_cookies',
        'request_files',
        'request_server',
        'request_query',
        'request_route',
        'request_session',
        'request_input'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'api_key' => 'string',
        'method' => 'string',
        'url' => 'string',
        'ip' => 'string',
        'user_agent' => 'string',
        'response_code' => 'string',
        'response_time' => 'string',
        'response_size' => 'string',
        'response_type' => 'string',
        'response_content' => 'json',
        'response_headers' => 'string',
        'request_headers' => 'string',
        'request_body' => 'string',
        'request_params' => 'string',
        'request_cookies' => 'string',
        'request_files' => 'string',
        'request_server' => 'string',
        'request_query' => 'string',
        'request_route' => 'string',
        'request_session' => 'string',
        'request_input' => 'string'
    ];
}
