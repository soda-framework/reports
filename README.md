# reports
Reporting module for Soda CMS

composer require soda-framework/reports

app.php Providers:
    Soda\Reports\SodaReportsServiceProvider::class

php artisan soda:reports:install

php artisan soda:reports:seed

```
#!sql
INSERT INTO `reports` (`id`, `name`, `description`, `class`, `application_id`, `position`, `times_ran`, `last_run_at`, `created_at`, `updated_at`)
VALUES
	(1, 'Subscriptions', NULL, 'Themes\\Snackable\\Components\\SubscriptionReport', 1, NULL, 4, '2017-01-27 04:13:20', NULL, '2017-01-27 04:13:20');

INSERT INTO `report_role` (`report_id`, `role_id`)
VALUES
	(1, 1),
	(1, 2);
```
```
#!php
Themes\Snackable\Components\SubscriptionReport.php
<?php

    namespace Themes\Snackable\Components;

    use Soda;
    use Illuminate\Http\Request;
    use Soda\Reports\Foundation\AbstractReporter;
    use Themes\Snackable\Controllers\PageController;
    use Zofe\Rapyd\Facades\DataGrid;

    class SubscriptionReport extends AbstractReporter{

        public function query(Request $request)
        {
            $query = Soda::model(PageController::$subscription_block)->select('name','email','dob','created_at');

            return $query;
        }

        public function run(Request $request)
        {
            $grid = DataGrid::source($this->query($request));
            $grid->add('name', 'Name');
            $grid->add('email', 'Email');
            $grid->add('dob', 'Date of Birth');
            $grid->add('created_at', 'Date Subscribed');

            $grid->paginate(20)->getGrid($this->getGridView());

            return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
        }
    }
```
