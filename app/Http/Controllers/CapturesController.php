<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CapturesController extends Controller
{
    /**
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $filters = [];

        if (!empty($request->get('date'))) {
            $filters['filter[captured_at]'] = $request->get('date');
        }

        if (!empty($request->get('station'))) {
            foreach ($request->get('station') as $station) {
                $filters['filter[station]'][] = $station;
            }
        }

        if (!empty($request->get('radiant'))) {
            $filters['filter[class]'] = $request->get('radiant');
        }

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 12);
        $filters = urldecode(http_build_query($filters));

        $estacoes = $this->getStations();
        $capturas = $this->doRequest('GET', "captures?page={$page}&limit={$limit}&{$filters}");
        $radiantes = $this->getRadiants();

        return view('capturas.index', ['estacoes' => $estacoes, 'capturas' => $capturas, 'radiantes' => $radiantes]);
    }

    private function getStations()
    {
        if (Cache::has('stations')) {
            return Cache::get('stations');
        }

        $stations = $this->doRequest('GET', 'stations?limit=1000');

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
