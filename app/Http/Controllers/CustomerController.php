<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Pest\ArchPresets\Custom;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {

        $dataUCustomer = Customer::all();
        return response()->json($dataUCustomer, 200);
    }
}
