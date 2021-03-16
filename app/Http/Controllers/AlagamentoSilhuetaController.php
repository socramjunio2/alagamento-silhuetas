<?php

namespace App\Http\Controllers;

use App\AlagamentoSilhuetaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlagamentoSilhuetaController extends Controller
{
    public function store(Request $request, AlagamentoSilhuetaService $floodingSilhouetteService)
    {
        $this->validate($request,[
            'silhouette' => ['min:1', 'required', 'string']
        ]);

        $data = $floodingSilhouetteService
            ->enviar($request);

        $results = [];
        foreach ($data as $datum) {
            $floodingSilhouetteService->alagar($datum['data']);
            $results[] = $floodingSilhouetteService->getResultado();
        }

        dd($results);
//
//        return Inertia::render('Dashboard', compact('data'));
    }
}
