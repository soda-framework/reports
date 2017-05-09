<?php

namespace Soda\Reports\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Soda\Reports\Models\Report;
use Zofe\Rapyd\Facades\DataGrid;
use Illuminate\Routing\Controller;
use Zofe\Rapyd\Facades\DataFilter;
use Soda\Reports\Foundation\ReportHandler;

class ReportController extends Controller
{
    public function __construct()
    {
        app('soda.interface')->setHeading('Reports')->setHeadingIcon('fa fa-bar-chart');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), 'Home');
    }

    public function index(Request $request, $reportId = null)
    {
        if ($reportId !== null) {
            return $this->setup($reportId);
        }

        $report = new Report;

        if (! $request->has('ord')) {
            $report = $report->ordered();
        }

        $filter = DataFilter::source($report->permitted());
        $filter->add('name', 'Name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('name', 'Name', true)->cell(function ($value, $model) {
            return $value.'<br /><span class="small text-muted">'.$model->description.'</span>';
        });
        $grid->add('last_run_at', 'Last run', true)->style('width:250px')->cell(function ($value) {
            return $value === null ? 'Never' : Carbon::parse($value)->diffForHumans();
        });
        $grid->add('{{ $id }}', 'Action')->style('width:120px')->cell(function ($value) {
            return '<a href="'.route('soda.reports.index', $value).'" class="btn btn-success" style="margin-left: 5px">View</a>';
        });

        $grid->paginate(10)->getGrid(soda_cms_view_path('partials.grid'));

        return view('soda-reports::index', compact('filter', 'grid'));
    }

    public function setup($id)
    {
        $report = Report::permitted()->with('fields')->findOrFail($id);

        if (! count($report->getRelation('fields'))) {
            return redirect()->route('soda.reports.view', $id);
        }

        app('soda.interface')->breadcrumbs()->addLink(route('soda.reports.index'), 'Reports');
        app('soda.interface')->setHeading('Setup: '.$report->name);

        return view('soda-reports::setup', compact('report'));
    }

    public function view(Request $request, $id)
    {
        $report = Report::permitted()->findOrFail($id);

        app('soda.interface')->breadcrumbs()->addLink(route('soda.reports.index'), 'Reports');
        app('soda.interface')->setHeading($report->name);

        return ReportHandler::run($report, $request);
    }

    public function export(Request $request, $id)
    {
        $report = Report::permitted()->findOrFail($id);

        return ReportHandler::export($report, $request);
    }
}
