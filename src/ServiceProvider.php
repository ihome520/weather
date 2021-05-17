<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/17
 * Time: 10:38
 */

namespace Ihome\Weather;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	protected $defer = true;

	public function register()
	{
		$this->app->singleton(Weather::class,function (){
			return new Weather(config('services.weather.key'));
		});

		$this->app->alias(Weather::class,'weather');
	}

	public function providers()
	{
		return [Weather::class,'weather'];
	}
}