# Dcat Admin Extension

后台管理Laravel定时任务

## 截图

![wx20170810-101048](https://user-images.githubusercontent.com/1479100/29151552-8affc0b2-7db4-11e7-932a-a10d8a42ec50.png)

## 安装

```
composer require zwping/scheduling

http://your-host/admin/auth/extensions 中更新&启用
```


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
