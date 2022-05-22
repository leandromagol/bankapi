<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class BalanceController extends Controller
{
    public function index(Request $request)
    {
        $account = Cache::get('account_id_'.$request->account_id);
        if ($account == null){
            return (new Response)->setStatusCode(404)->header('Content-Type', 'application/json');
        }
        return (new Response($account['balance'],200))->header('Content-Type', 'application/json');
        
    }
}