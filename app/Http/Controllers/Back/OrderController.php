<?php

namespace App\Http\Controllers\Back;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request){
        return Inertia::render('Back/Order/Index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request){

    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
