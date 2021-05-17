<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/15
 * Time: 15:03
 */

namespace Ihome\Weather;


use GuzzleHttp\Client;
use Ihome\Weather\Exceptions\HttpException;
use Ihome\Weather\Exceptions\InvalidArgumentException;

class Weather
{
	protected $key; //高德地图的key

	protected $guzzleOptions = [];

	public function __construct(string $key){
		$this->key = $key;
	}

	/**
	 * 获得请求实例
	 * @return Client
	 */
	public function getHttpClient()
	{
		return new Client($this->guzzleOptions);
	}

	/**
	 * 设置配置
	 * @param array $options
	 */
	public function setGuzzleOptions(array $options)
	{
		$this->guzzleOptions = $options;
	}

	/**
	 * 获取天气
	 * @param $city
	 * @param string $type
	 * @param string $format
	 * @return mixed|string
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getWeather($city,string $type = 'base',string $format = 'json')
	{
		$url = 'https://restapi.amap.com/v3/weather/weatherInfo';

		if(!in_array(strtolower($format),['json','xml'])){
			throw new InvalidArgumentException('返回数据类型，传入参数错误:'.$format);
		}

		if(!in_array(strtolower($type),['base','all'])){
			throw new InvalidArgumentException('返回数据内容，传入参数错误:'.$type);
		}

		$query = array_filter([
			'key'=>$this->key,
			'city'=>$city,
			'output'=>$format,
			'extensions'=>$type
		]);

		try{
			$response = $this->getHttpClient()->get($url,[
				'query'=>$query
			])->getBody()->getContents();

			return 'json' === $format ? json_decode($response,true) : $response;

		}catch (\Exception $exception){

			throw new HttpException($exception->getMessage(),$exception->getCode(),$exception);
		}
	}
}