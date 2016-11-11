<?php

namespace Soda\Reports\Foundation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Soda\Reports\Models\Report;

abstract class AbstractReporter implements Reportable
{
    protected $report;
    protected $perPage = 10;

    abstract public function query(Request $request);
    abstract public function run(Request $request);

    public function export(Request $request)
    {
        $this->disableTimeLimit();

        $reportName = str_slug($this->getReport()->getAttribute('name'));

        return response()->stream(function () use ($request) {
            // Open output stream
            $handle = fopen('php://output', 'w');
            $headers = false;

            // Get all users
            $this->query($request)->chunk(500, function ($rows) use ($handle, &$headers) {
                foreach ($rows as $row) {
                    $row = $row->toArray();

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
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$reportName.'.csv"',
        ]);
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
