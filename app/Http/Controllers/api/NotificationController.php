<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\AdminInfo;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getAdminInfo() {
        $adminInfo = AdminInfo::latest();
        return new  ApiResource(true, 'Data berhasil diambil', $adminInfo);
    }
}
