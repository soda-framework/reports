<?php

namespace Soda\Reports\Foundation;

use Carbon\Carbon;
use RuntimeException;
use Illuminate\Http\Request;
use Soda\Reports\Models\Report;

class ReportHandler
{
    public static function run(Report $report, Request $request)
    {
        $reportable = static::resolve($report);

        $results = $reportable->run($request);
        static::updateReport($report);

        return $results;
    }

    public static function export(Report $report, Request $request)
    {
        $reportable = static::resolve($report);

        return $reportable->export($request);
    }

    protected static function updateReport(Report $report)
    {
        $report->update([
            'times_ran'   => intval($report->times_ran) + 1,
            'last_run_at' => Carbon::now(),
        ]);
    }

    protected static function resolve(Report $report)
    {
        $reportable = app($report->class);

        if (! $reportable instanceof Reportable) {
            throw new RuntimeException('Report "'.get_class($reportable).'" must implement "'.Reportable::class.'"');
        }

        $reportable->setReport($report);

        return $reportable;
    }
}
