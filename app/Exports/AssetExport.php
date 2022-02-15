<?php

namespace App\Exports;

use App\Models\Asset;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AssetExport implements FromView
{
   
    public function view(): View
    {
        return view('hardware.index', [
            'Assets' => Assets::all()
        ]);
    }
}
