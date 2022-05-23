<?php

namespace App\Services\Event;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class EventService{
    public function __construct(public AccountService $accountService) {
      
    }
    
    public function depostit(String $destination, int $amount){
        if(Cache::has('account_id_'.$destination)){
            $this->accountService->deposit($destination,$amount);
        }
        if(!Cache::has('account_id_'.$destination)){
           $this->accountService->create($destination,$amount);
        }

        return (new Response)->setContent(json_encode(['destination'=>  Cache::get('account_id_'.$destination)]))->setStatusCode(201)->header('Content-Type', 'application/json');
    }

    public function withdraw(String $origin, int $amount){
        if(Cache::has('account_id_'.$origin)){
            $this->accountService->withdraw($origin,$amount);
            return (new Response)->setContent(json_encode(['origin'=>  Cache::get('account_id_'.$origin)]))->setStatusCode(201)->header('Content-Type', 'application/json');
        }
        if(!Cache::has('account_id_'.$origin)){
            return (new Response(0,404))->header('Content-Type', 'application/json');
        }
    }

    public function transfer(String $origin,String $destination,int $amount){
        if(Cache::has('account_id_'.$origin)){
            $this->accountService->transfer($origin,$destination,$amount);
            return (new Response)->setStatusCode(201)->setContent(json_encode([
                'origin' => Cache::get('account_id_'.$origin),
                'destination' => Cache::get('account_id_'.$destination),
            ]))->header('Content-Type', 'application/json');
        }
        if(!Cache::has('account_id_'.$origin)){
            return (new Response)->setContent(0)->setStatusCode(404)->header('Content-Type', 'application/json');
        }
    }
    
}