<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Portfolio;
use App\Models\Association;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'productsCount' => Product::count(),
            'servicesCount' => Service::count(),
            'customersCount' => Customer::count(),
            'portfolioCount' => Portfolio::count(),
            'associationsCount' => Association::count(),
        ];

        return view('admin.dashboard', $data);
    }
}

