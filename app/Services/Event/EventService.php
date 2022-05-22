<?php

namespace App\Services\Event;

use Illuminate\Support\Facades\Cache;

class EventService {
    public function __construct(public EventValidatorService $eventValidatorService) {
       
    }
    public function create(int $accountId, float $amount){
        $account = ['id'=>$accountId,'balance'=>$amount];
        Cache::put('account_id_'.$accountId,$account);
    }
    public function deposit(int $accountId, float $amount) {
        $account = Cache::get('account_id_'.$accountId);
        $account['balance'] += $amount;
        $account['id'] =$accountId;
        Cache::put('account_id_'.$accountId,$account);
    }

    public function withdraw(int $accountId, float $amount)
    {
        $account = Cache::get('account_id_'.$accountId);
        $account['balance'] -= $amount;
        $account['id'] =$accountId;
        Cache::put('account_id_'.$accountId,$account);
    }

    public function transfer(int $origin, int $destination, float $amount)
    {
        $this->withdraw($origin, $amount);
        if(Cache::has('account_id_'.$destination)){
            $this->deposit($destination, $amount);
        }
        if(!Cache::has('account_id_'.$destination)){
            $this->create($destination, $amount);
        }
    }
    
}