<?php

namespace Encore\Admin\Scheduling\Http\Repositories;

use Dcat\Admin\Admin;
use Dcat\Admin\Extend\ServiceProvider as AbstractExtension;
use Illuminate\Contracts\Container\BindingResolutionException;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Support\Str;

class Scheduling extends Repository {


    /**
     * @var string out put file for command.
     */
    protected $sendOutputTo;

    /**
     * Get all events in console kernel.
     *
     * @return array
     */
    protected function getKernelEvents() {
        app()->make('Illuminate\Contracts\Console\Kernel');

        return app()->make('Illuminate\Console\Scheduling\Schedule')->events();
    }

    /**
     * Format a giving task.
     *
     * @param $event
     *
     * @return array
     */
    protected function formatTask($event)
    {
        if ($event instanceof CallbackEvent) {
            return [
                'type' => 'closure',
                'name' => 'Closure',
            ];
        }

        if (Str::contains($event->command, '\'artisan\'')) {
            $exploded = explode(' ', $event->command);

            return [
                'type' => 'artisan',
                'name' => 'artisan '.implode(' ', array_slice($exploded, 2)),
            ];
        }

        if (PHP_OS_FAMILY === 'Windows' && Str::contains($event->command, '"artisan"')) {
            $exploded = explode(' ', $event->command);

            return [
                'type' => 'artisan',
                'name' => 'artisan '.implode(' ', array_slice($exploded, 2)),
            ];
        }

        return [
            'type' => 'command',
            'name' => $event->command,
        ];
    }

    public function get(Grid\Model $model) {
        $data = [];
        foreach ($this->getKernelEvents() as $event) {
            $data[] = [
                'task'                  => $this->formatTask($event)['name'],
                'expression'            => $event->expression,
                'nextRunDate'           => $event->nextRunDate()->format('Y-m-d H:i:s'),
                'description'           => $event->description,
                'withoutOverlapping'    => $event->withoutOverlapping,
                'runInBackground'       => $event->runInBackground,
                // 'readable'      => CronSchedule::fromCronString($event->expression)->asNaturalLanguage(),
            ];
        }

        return $data;
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function runTask($id)
    {
        set_time_limit(0);
        $event = $this->getKernelEvents()[$id];
        if (PHP_OS_FAMILY === 'Windows') {
            $event->command = Str::of($event->command)->replace('php-cgi.exe', 'php.exe');
        }

        file_put_contents($this->getOutputTo(), '');
        $event->sendOutputTo($this->getOutputTo()); // 后台任务写入有延时
        $event->run(app());
        return $this->readOutput();
    }

    /**
     * @return string
     */
    protected function getOutputTo()
    {
        if (!$this->sendOutputTo) {
            $this->sendOutputTo = storage_path('app/task-schedule.output');
        }

        return $this->sendOutputTo;
    }

    /**
     * Read output info from output file.
     *
     * @return string
     */
    protected function readOutput()
    {
        return file_get_contents($this->getOutputTo());
    }

    public function edit(Form $form): array
    {
        return [];
    }

    public function update(Form $form)
    {
        return true;
    }

    /**
     * Get data before update.
     *
     * @param  Form  $form
     * @return array
     */
    public function updating(Form $form): array
    {
        return [];
    }

    public function detail(Show $show): array
    {
        return [];
    }

    public function delete(Form $form, array $deletingData)
    {
    }

    public function store(Form $form)
    {
    }

    public function deleting(Form $form): array
    {
        return [];
    }
}
