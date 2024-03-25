<?php

namespace App\Console\Commands;

use GuzzleHttp\Promise;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class sendMassRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CouponTest sendMassRequests';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $totalRequests = 100;
        $client = new Client();
        $promises = [];

        // 创建大量并发请求
        for ($i = 0; $i < $totalRequests; $i++) {
            $promises[] = $client->postAsync('http://chuangte-new.test.muke.design/web/test', [
                'form_params' => [
                ],
            ]);
        }

        // 等待所有请求完成
        $results = Promise\Utils::unwrap($promises);

        // 计算成功和失败的请求
        $success = 0;
        $fail = 0;
        foreach ($results as $result) {
            if ($result->getStatusCode() == 200) {
                $success++;
            } else {
                $fail++;
            }
        }

        echo "成功请求：{$success}\n";
        echo "失败请求：{$fail}\n";

        return Command::SUCCESS;
    }
}
