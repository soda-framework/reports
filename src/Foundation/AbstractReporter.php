<?php

namespace Soda\Reports\Foundation;

use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Soda\Reports\Models\Report;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class AbstractReporter implements Reportable
{
    protected $report;
    protected $perPage = 10;
    protected $dontReorder = false;

    abstract public function query(Request $request);

    abstract public function run(Request $request);

    public function export(Request $request)
    {
        $query = $this->query($request);

        if (!$this->dontReorder && $order = $request->input('ord')) {
            $dir = ($order[0] === "-") ? "desc" : "asc";
            $query->orderBy(ltrim($order, '-'), $dir);
        }

        $this->disableTimeLimit();

        $reportName = $this->getReportName($request);

        return new StreamedResponse(function () use ($query) {

            // Open output stream
            $handle = fopen('php://output', 'w');

            $headers = false;

            // Get all users
            $query->chunk(500, function ($rows) use ($handle, &$headers) {
                foreach ($rows as $row) {
                    $row = method_exists($row, 'toArray') ? $row->toArray() : (array)$row;

                    // Add headers if not already present
                    if ($headers === false) {
                        $headers = true;
                        fputcsv($handle, array_keys($row));
                    }

                    // Add a new row with data
                    fputcsv($handle, $row);
                }
            });

            // Close the output stream
            fclose($handle);
        }, 200, [
            'Pragma'              => 'public',
            'Expires'             => '0',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$reportName.'.csv"',
        ]);
    }

    public function getReportName(Request $request)
    {
        return str_slug($this->getReport()->getAttribute('name'));
    }

    public function setReport(Report $report)
    {
        $this->report = $report;

        return $this;
    }

    public function getReport()
    {
        return $this->report;
    }

    protected function getPerPage()
    {
        return isset($this->perPage) ? $this->perPage : 10;
    }

    protected function getGridView()
    {
        return isset($this->gridView) ? $this->gridView : soda_cms_view_path('partials.grid');
    }

    protected function getView()
    {
        return isset($this->view) ? $this->view : 'soda-reports::default-view';
    }

    protected function disableStrictMode()
    {
        DB::statement("set session sql_mode='NO_ENGINE_SUBSTITUTION'");

        return $this;
    }

    protected function disableTimeLimit()
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        return $this;
    }
}
