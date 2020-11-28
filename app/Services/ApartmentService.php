<?php

namespace App\Services;

use App\Models\Apartment;
use Illuminate\Support\Facades\Cache;
use PHPHtmlParser\Dom;

class ApartmentService
{
    public function newApartments()
    {
        $apartments = $this->apartments();
        $lastApartmentId = Cache::get('last_apartment_id');

        if (!$lastApartmentId) {
            Cache::forever('last_apartment_id', $apartments->first()->getId());
            return $apartments;
        }

        $newApartments = $apartments->takeUntil(function ($item) use ($lastApartmentId) {
            return $item->getId() == $lastApartmentId;
        });

        if ($newApartments->isNotEmpty()) {
            Cache::forever('last_apartment_id', $newApartments->first()->getId());
        }

        return $newApartments;
    }

    public function apartments()
    {
        $html = $this->getHtml();
        $dom = new Dom();
        $dom->loadStr($html);
        /** @var Dom\Node\HtmlNode $tableHead */
        $tableHead = $dom->getElementById('head_line');
        $table = $tableHead->getParent();

        $apartments = collect();

        /** @var Dom\Node\HtmlNode $child */
        foreach ($table->getChildren() as $child) {
            $attributes = $child->getAttributes();

            if (empty($attributes) || $attributes['id'] == 'head_line') {
                continue;
            }

            $href = $child->getChildren()[1]->firstChild()->getAttribute('href');
            $id = pathinfo($href)['filename'];
            $price = strip_tags($child->getChildren()[9]->innerhtml);
            $street = strip_tags($child->getChildren()[3]->innerhtml);

            $apartment = new Apartment($id, $street, $price);
            $apartments[] = $apartment;
        }

        return $apartments;
    }

    private function getHtml()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.ss.lv/lv/real-estate/flats/riga/centre/sell/filter/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'topt[8][min]' => '',
                'topt[8][max]' => '140000',
                'topt[1][min]' => '2',
                'topt[1][max]' => '',
                'topt[3][min]' => '45',
                'topt[3][max]' => '',
                'topt[4][min]' => '2',
                'topt[4][max]' => '',
                'opt[6]' => '',
                'sid' => '/lv/real-estate/flats/riga/centre/sell/filter/',
                'opt[11]' => ''
            ],
            CURLOPT_HTTPHEADER => [
                'Cookie: PHPSESSID=e1a942652f659fbfbe732544076eda44; LG=lv; sid=d1e9ea7779dfe5169cc4af8416d7ba75751ca0e8e22b9db3902b0122b78323de42f9348f736ae59b3991eb85cce5a1dc'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
