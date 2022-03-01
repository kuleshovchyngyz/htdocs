<?php

namespace App\Http\Controllers;

use App\Charts\ReportLineCharts;
use App\Charts\ReportPieCharts;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index($id, ReportLineCharts $reportCharts, ReportPieCharts $reportPieCharts)
    {
        session(['selected_project_id' => $id]);
        return view('reports.index', ['reportCharts' => $reportCharts->build(), 'reportPieCharts' => $reportPieCharts->build()]);
    }
}
