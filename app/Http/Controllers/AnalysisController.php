<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    /**
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $filters = [];

        if (!empty($request->get('date_start')) && !empty($request->get('date_end'))) {
            $time_start = "{$request->get('date_start')} {$request->get('time_start')}:00";
            $time_end = "{$request->get('date_end')} {$request->get('time_end')}:59";

            $filters['filter[interval]'] = "{$time_start},{$time_end}";
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

        $estacoes = $this->getAllStations();
        $radiantes = $this->getRadiants();
        $capturas = [];

        if (!empty($filters)) {
            $capturas = $this->doRequest('GET', "captures?page={$page}&limit={$limit}&{$filters}");
        }

        return view('analysis.index', ['estacoes' => $estacoes, 'capturas' => $capturas, 'radiantes' => $radiantes]);
    }
}
