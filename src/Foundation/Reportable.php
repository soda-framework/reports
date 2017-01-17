<?php

namespace Soda\Reports\Foundation;

use Illuminate\Http\Request;
use Soda\Reports\Models\Report;

interface Reportable
{
    public function run(Request $request);

    public function export(Request $request);

    public function setReport(Report $report);

    public function getReport();
}
