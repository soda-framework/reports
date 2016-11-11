<?php

namespace Soda\Reports\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Soda\Reports\Foundation\ReportHandler;
use Soda\Reports\Models\Report;
use Zofe\Rapyd\Facades\DataFilter;
use Zofe\Rapyd\Facades\DataGrid;

class ReportController extends Controller
{
    public function index()
    {
        $filter = DataFilter::source(new Report);
        $filter->add('name', 'Name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('name', 'Name', true);
        $grid->add('description', 'Description');
        $grid->add('last_run_at', 'Last run', true)->style('width:250px')->cell(function($value){
            return $value === null ? 'Never' : Carbon::parse($value)->diffForHumans();
        });
        $grid->add('{{ $id }}', 'Action')->style('width:120px')->cell(function($value){
            return '<a href="' . route('soda.reports.setup', $value) . '" class="btn btn-success" style="margin-left: 5px">View</a>';
        });

        $grid->paginate(20)->getGrid(soda_cms_view_path('partials.grid'));

        return view('soda-reports::index', compact('filter', 'grid'));
    }

    public function setup($id)
    {
        $report = Report::with('fields')->findOrFail($id);

        if (!count($report->getRelation('fields'))) {
            return redirect()->route('soda.reports.view', $id);
        }

        return view('soda-reports::setup', compact('report'));
    }

    public function view(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        return ReportHandler::run($report, $request);
    }

    public function export(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        ReportHandler::export($report, $request);
    }
}
