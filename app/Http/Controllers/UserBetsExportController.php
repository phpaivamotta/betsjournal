<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UserBetsExport;
use Maatwebsite\Excel\Facades\Excel;

class UserBetsExportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return Excel::download(new UserBetsExport, 'bets.xlsx');
    }
}
