<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Cache;

class CapturesController extends Controller
{
    /**
     * @return Renderable
     */
    public function index(): Renderable
    {
        $filter = [];
        $page = 1;
        $limit = 12;
        $filters = urldecode(http_build_query($filter));

        $estacoes = $this->getStations();
        $capturas = $this->doRequest('GET', "operator/captures?page={$page}&limit={$limit}&{$filters}");
        $radiantes = $this->getRadiants();

        return view('capturas.index', ['estacoes' => $estacoes, 'capturas' => $capturas, 'radiantes' => $radiantes]);
    }

    private function getStations()
    {
        if (Cache::has('stations')) {
            return Cache::get('stations');
        }

        $stations = $this->doRequest('GET', 'operator/stations?limit=1000');

        Cache::put('stations', $stations, Carbon::now()->addMinutes(10));

        return $stations;
    }

    private function getRadiants()
    {
        $radiants = file( __DIR__  . '/../../../resources/data/radiants.txt', FILE_IGNORE_NEW_LINES);
        $radiantsIndexed = [];

        foreach ($radiants as $radiant) {
            $tmp = explode(':', $radiant);

            $radiantsIndexed[ $tmp[0] ] = $tmp[1];
        }

        return $radiantsIndexed;
    }
}
