# 后台管理Laravel定时任务

[![](https://img.shields.io/packagist/v/zwping/scheduling.svg)](https://packagist.org/packages/zwping/scheduling)


## 截图

![main](https://raw.githubusercontent.com/zwping/scheduling/master/screenshot/main.png)
![run](https://raw.githubusercontent.com/zwping/scheduling/master/screenshot/run.png)

## 安装

1. `composer require zwping/scheduling`
2. 扩展中更新&启用
3. 配置用户菜单&权限
4. 打开 http://your-host/admin/scheduling


在`app/Console/Kernel.php`中添加一些定时任务

```php
class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->everyTenMinutes();
        
        $schedule->command('route:list')
            ->back
            ->dailyAt('02:00');
    }
}

```

打开`http://your-host/admin/scheduling`, 可以看见这些定时任务
