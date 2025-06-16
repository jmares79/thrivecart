<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\ProductLogic;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected readonly ProductLogic $productLogic) {}

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
