<?php
function getrequest($urls)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urls,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $array=json_decode($response, TRUE);
    $data=$array['data']['markets'];

    $exchange = array(
        'binance',
        'coinbasepro',
        'huobipro',
        'okex',
        'zb',
    );

    foreach ($data as $value){
        if(in_array($value['exchange_code'],$exchange) && $value['pair2'] == 'USDT'){
            if(!$value['name_zh']){
                $value['name_zh'] = $value['exchange_code'];
            }
            $name_zh = $value['name_zh'];
            $result[$name_zh]['交易对'] = $value['symbol_pair'];
            $result[$name_zh]['价格'] = $value['price'];
            $result[$name_zh]['涨幅百分比'] = $value['changerate'];
            $result[$name_zh]['24h成交量'] = $value['vol'];
            $result[$name_zh]['24h交易额'] = $value['volume'];
            $result[$name_zh]['占比'] = $value['accounting'];
            $result[$name_zh]['更新时间'] = $value['update_time'];
        }else{
            continue;
        }
    }
    $finalresult=json_encode($result);
    file_put_contents('output1.json',$finalresult);


}


getrequest('https://dncapi.bqrank.net/api/v2/Coin/market_ticker?page=1&pagesize=100&code=dfinity&token=&tickertype=0&pair2=&webp=1');