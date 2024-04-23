<?php

namespace Encore\Admin\Scheduling\Http\Controllers;

use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Illuminate\Routing\Controller;
use Dcat\Admin\Grid;
use Dcat\Admin\Widgets\Modal;
use Encore\Admin\Scheduling\Http\Forms\RunScheduling;
use Encore\Admin\Scheduling\Http\Repositories\Scheduling;

class SchedulingController extends Controller {

    public function index(Content $content) {
        return $content
            ->title('任务调度')
            // ->description('Description')
            // ->body(Admin::view('zwping.scheduling::index'))
            ->body($this->grid())
            ;

    }

    private function grid() {
        return new Grid(new Scheduling(), function (Grid $grid) {
            $grid->setActionClass(\Dcat\Admin\Grid\Displayers\Actions::class);
            $grid->disableCreateButton();
            $grid->disablePagination();
            // $grid->disableActions();
            $grid->disableQuickEditButton();
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->disableDeleteButton();
            $grid->disableRowSelector();

            $grid->number();
            $grid->column('task', '任务')->label('danger', 1);
            $grid->column('expression', '表达式')->display(fn($it) => "<span class='label' style='background:#21b978'>$it</span>");
            $grid->column('nextRunDate', '下次执行时间');
            $grid->column('withoutOverlapping', '避免重复')->bool();
            $grid->column('runInBackground', '后台任务')->bool();
            $grid->column('description', '描述');
            // $grid->column('', '操作')->display('执行任务')->modal(function (Grid\Displayers\Modal $modal) {
            //     $modal->xl();
            //     $modal->icon('fa-play');
            //     $modal->title('任务日志 - '. ($modal->row->description ?? $this->task));
            //     return RunScheduling::make()->payload(['id' => $modal->row->_index]);
            // });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->append(Modal::make()
                    ->xl()
                    ->title('任务日志 - '. ($this->description ?? $this->_index))
                    ->icon('fa-play')
                    ->body(RunScheduling::make()->payload(['id' => $this->_index]))
                    ->button(sprintf('<a href="javascript:(0)"><i class="fa fa-play">&nbsp;执行任务</i></a>', Admin::color()->primary()))
                );
            });
        });
    }

}