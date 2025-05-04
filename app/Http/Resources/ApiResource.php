<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;
    public $extra;

    public function __construct($status, $message, $resource = null, $extra = null)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->extra = $extra;
    }

    public function toArray(Request $request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
            'extra' => $this->extra,
        ];
    }
}
