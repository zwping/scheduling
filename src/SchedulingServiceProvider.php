<?php

namespace Encore\Admin\Scheduling;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;

class SchedulingServiceProvider extends ServiceProvider
{
	protected $js = [
        'js/index.js',
    ];
	protected $css = [
		'css/index.css',
	];

	public function register() {
		//
	}

	public function init() {
		parent::init();

		//
		
	}

    protected $menu = [
        [
            'title' => 'Scheduling',
            'uri'   => 'scheduling',
            'icon'  => 'fa-clock-o',
        ],
    ];

	// public function settingForm() {
	// 	return new Setting($this);
	// }
}
