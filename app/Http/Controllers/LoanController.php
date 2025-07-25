<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        return Loan::with(['user', 'book'])->get();
    }

    public function store(Request $request)
    {
        return Loan::create($request->all());
    }

    public function show($id)
    {
        return Loan::with(['user', 'book'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update($request->all());
        return $loan;
    }

    public function destroy($id)
    {
        return Loan::destroy($id);
    }
}
