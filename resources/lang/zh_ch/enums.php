<?php
/**
 * Date: 2025/1/27
 */
return [
    'settlement_method' => [
        'per_invoice' => '票结',
        'biweekly' => '半月结',
        'monthly' => '月结',
        'weekly' => '周结',
    ],
    'cargo_pickup_method' => [
        'self_pickup' => '自揽货',
        'designated' => '指定货',
    ],
    'customer_source' => [
        'market' => '市场',
        'direct' => '直客',
        'overseas' => '海外客户',
    ],
    'customer_category' => [
        'domestic' => '国内客户',
        'international' => '国外客户',
    ],
    'company_type' => [
        'dangerous_customer' => '普通客户-PC审',
        'competitor' => 'VIP客户-全放',
        'blacklist_customer' => '黑名单客户-全扣',
        'refuse_customer' => '危险客户-全审',
    ],
    'invoice_remark' => [
        'ETD/ETA' => 'ETD/ETA',
        'ref_no' => '参考号',
        'mbl_no' => '主单号',
        'voyage' => '船名/航次/航班号',
        'hbl_no' => '分单号',
        'customs_declaration_number' => '报关单编号',
        'pol_name' => 'POL NAME',
        'job_no' => 'JOB NO',
        'pod_name' => 'POD NAME',
    ],
];
