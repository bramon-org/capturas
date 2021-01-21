<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

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

        $estacoes = $this->getAllStations();
        $capturas = $this->doRequest('GET', "captures?page={$page}&limit={$limit}&{$filters}");
        $radiantes = $this->getRadiants();

        return view('capturas.index', ['estacoes' => $estacoes['data'], 'capturas' => $capturas, 'radiantes' => $radiantes]);
    }
}
