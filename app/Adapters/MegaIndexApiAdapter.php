<?php

namespace App\Adapters;

use App\Competitor;
use App\Position;
use App\CompetitorPosition;
use App\Interfaces\ApiInterface;
use App\ProjectRegion;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class MegaIndexApiAdapter implements ApiInterface
{
	private $configurations = [];
	private $client;
	public function __construct()
	{
		$this->configurations = config('app.megaindex');

		$handler = new CurlHandler();
		$stack = HandlerStack::create($handler); // Wrap w/ middleware


		$this->client = new Client([
			'debug' => fopen('php://stderr', 'w'),
			'handler' => $stack,
			'base_uri' => $this->configurations['url'],
			'headers' => ['content-type' => 'application/json; charset=utf-8']
		]);
	}

	public  function message()
	{
		echo 'mega index';
	}

	public function getPosition($params)
	{
		if (isset($params['method']) && $params['method'] == 'yandex') {
			return $this->getYandexPosition($params);
		} else if (isset($params['method']) && $params['method'] == 'google') {
			return $this->getGooglePosition($params);
		}
		return -1;
	}

	public function getPositions($params)
	{
		$promises = [];
		foreach ($params as $key => $singleRequest) {
			$promises[] = $this->client->getAsync('/scanning/yandex_position', [
				'query' => [
					'key' 	=> $this->configurations['key'],
					'word' 	=> $singleRequest['word'],
					'lr' 	=> $singleRequest['region'],
					'show_page' => 0,
					'show_direct' => 0,
				]
			]);
		}
		$responses = Promise\settle($promises)->wait();
		foreach ($responses as $key => $singleResponse) {
			$tempResponse = json_decode($singleResponse['value']->getBody()->getContents());
			if (isset($tempResponse->status) && $tempResponse->status == '1') {
			}
		}
	}

	public function getTaskIDs($params)
	{

		if (config('app.fake_http') == 1) {
			$returnData = [];
			foreach ($params as $key => $singleRequest) {

				if (isset($singleRequest['method']) && $singleRequest['method'] == 'yandex') {
					$searchEngine = '/scanning/yandex_position';
				} else if (isset($singleRequest['method']) && $singleRequest['method'] == 'google') {
					$searchEngine = '/scanning/google_position';
				}

				if ($searchEngine != '') {
					$returnData[] = (object)[
						"method" => 	$singleRequest['method'],
						"task_id" => 'fake_' . Str::random(28),
						"method" => 	$singleRequest['method'],
						'params' => (object)[
							'request' => $singleRequest['word'],
							'lr' => $singleRequest['region'],
						],

					];
				}
			}
			return $returnData;
		}
		//real call
		$promises = [];

		foreach ($params as $key => $singleRequest) {
			$searchEngine = '';
			if (isset($singleRequest['method']) && $singleRequest['method'] == 'yandex') {
				$searchEngine = '/scanning/yandex_position';
			} else if (isset($singleRequest['method']) && $singleRequest['method'] == 'google') {
				$searchEngine = '/scanning/google_position';
			}

			if ($searchEngine != '') {
				$promises[$key] = $this->client->getAsync($searchEngine, [
					'query' => [
						'key' 	=> $this->configurations['key'],
						'word' 	=> $singleRequest['word'],
						'lr' 	=> $singleRequest['region'],
						'show_page' => 0,
						'show_direct' => 0,
					]
				]);
			}
		}
		$responses = Promise\settle($promises)->wait();
		$returnData = [];
		$FullData = ["1" => $responses];
		foreach ($responses as $key => $singleResponse) {

			$tempResponse = json_decode($singleResponse['value']->getBody()->getContents());
			if (isset($tempResponse->status) && $tempResponse->status == '1') {
				$tempResponse->data->method = $params[$key]['method'];
				$returnData[$key] = $tempResponse->data;
			}
		}
		$FullData = ["1" => $responses, "2" => $returnData, "3" => $params];
		return $returnData;
	}

	public function getPositionsByTaskIDs($pendingPositions)
	{
		$data = ['path' => [], 'position' => []];
		//faking of getting position
		if (config('app.fake_http') == 1) {
			$pages = ['http://eka.dorogo.online/avto/prodaji', 'http://salam.kg/avto-bez-dokumentov', 'https://smokelab.org/arabasatis'];
			foreach ($pendingPositions as $key => $pendingPosition) {

				$value = rand(1, 5) == '3' ? '-3' : rand(1, 50);

				$returnValue[$pendingPosition->id] = $value;
				if ($value > 0) {
					$path[$pendingPosition->id] = $pages[rand(1, 3) - 1];
				} else {
					$path[$pendingPosition->id] = '';
				}
			}
			$data["path"] = $path;
			$data["position"] = $returnValue;
			return $data;
		}
		$p_id = session('selected_project_id');
		if (null === session('selected_project_id')) {
			$p_id = $pendingPositions->first()->project->id;
		}

		//dd($responses);
		$competitors = DB::table('competitors')
			->leftJoin('competitor_regions', 'competitors.id', '=', 'competitor_regions.competitor_id')
			->where('competitors.project_id', $p_id)
			->get();
		$projectUrls = ProjectRegion::where('project_id', $p_id)->pluck('url', 'region_id');

		$promises = [];
		$returnData = [];
		$returnDataUrl = [];
		foreach ($pendingPositions as $key => $pendingPosition) {
			//dd($pendingPosition->region_id);
			$promises[0] = $this->client->getAsync('/scanning/check', [
				'query' => [
					'key' 	=> $this->configurations['key'],
					'method' => $pendingPosition->method . '_position',
					'task_id' => $pendingPosition->task_id
				]
			]);

			$responses = Promise\settle($promises)->wait();

			$tempResponse = json_decode($responses[0]['value']->getBody()->getContents());
			//dd($tempResponse);

			\Storage::disk('local')->put('example.txt', json_encode($pendingPosition->task_id, JSON_UNESCAPED_UNICODE));
			if (isset($tempResponse->status) && $tempResponse->status == '-105') {

				$p = Position::where('task_id', $pendingPosition->task_id)->first();
				$p->status = 'error';
				$p->save();
			}
			if (isset($tempResponse->status) && $tempResponse->status == '1') {

				//dd($pendingPositions[$key]['id']);
				if ($competitors->count() > 0) {
					foreach ($competitors as $competitor) {
						if ($competitor->region_id == $pendingPositions[$key]->region_id) {
							$position = $this->getUrlPositionFromResponse($competitor->url, $tempResponse->data);
							$tempPosition = $pendingPositions[$key];
							if ($position < 0) {
								$fullurl = '';
								$pos = -3;
							} else {
								$fullurl = $tempResponse->data[$position]->domain . $tempResponse->data[$position]->path;
								$pos = $tempResponse->data[$position]->position;
							}

							CompetitorPosition::create([
								'competitor_id' => $competitor->competitor_id,
								'position_id' => $tempPosition->id,
								'query_id' => $tempPosition->query_id,
								'region_id' => $competitor->region_id,
								$tempPosition['method'] . '_position' => $pos,
								$tempPosition['method'] . '_date' => Carbon::now(),
								'full_url' => $fullurl,
								'task_id' => $tempPosition->task_id,
								'method' => $tempPosition->method,
								'project_id' => $p_id
							]);
						}
					}
				}
				//dump($pendingPositions[$key]->region_id);
				$projectUrl = $projectUrls[$pendingPositions[$key]->region_id];

				$id = $this->getUrlPositionFromResponse($projectUrl, $tempResponse->data);
				$pos = 0;
				if ($id < 0) {
					$returnData[$pendingPositions[$key]->id] = -3;
					$pos = -3;
					$returnDataUrl[$pendingPositions[$key]->id] = "";
				} else {
					$returnData[$pendingPositions[$key]->id] = $tempResponse->data[$id]->position;
					$returnDataUrl[$pendingPositions[$key]->id] = $tempResponse->data[$id]->domain . $tempResponse->data[$id]->path;
					$pos = $tempResponse->data[$id]->position;
				}
			}
		}
		$data["path"] = $returnDataUrl;
		$data["position"] = $returnData;
		//die("I am dead");
		return $data;
	}
	private function getUrlPositionFromResponse($projectUrl, $response)
	{
		if (config('app.fake_http') == 1) {
			return rand(1, 5) == '3' ? '-3' : rand(1, 50);
		}

		foreach ($response as $key => $value) {
			$projectUrl = str_replace("https://", "", $projectUrl, $count);
			$projectUrl = str_replace("http://", "", $projectUrl, $count);
			$projectUrl = str_replace("www.", "", $projectUrl, $count);
			$target = $projectUrl;
			$pattern_start = "/^\/+/";
			$pattern_end = "/\/+$/";
			if (preg_match($pattern_start, $target)) {
				$target = preg_split($pattern_start, $target);
				$target = $target[1];
			}
			if (preg_match($pattern_end, $target)) {
				$target = preg_split($pattern_end, $target);
				$target = $target[0];
			}

			if (strpos(strtolower($value->domain), strtolower($target)) !== false) {
				return $key;
			}
		}
		return '-3';
	}

	public function test()
	{
		echo 'wwwww';
		$ddd = json_decode('{"status":1,"data":[{"www":false,"position":1,"domain":"vauto96.ru","path":"\/"},{"www":true,"position":2,"domain":"Avito.ru","path":"\/ekaterinburg?q=%D0%B2%D1%8B%D0%BA%D1%83%D0%BF+%D0%B0%D0%B2%D1%82%D0%BE"},{"www":false,"position":3,"domain":"vikupauto96.ru","path":"\/"},{"www":false,"position":4,"domain":"xn----8sbecaoa6anyahjdncd7bw5mh.xn--p1ai","path":"\/ekaterinburg"},{"www":true,"position":5,"domain":"gorpom.ru","path":"\/list\/vykup-avtomobilej\/jekaterinburg\/"},{"www":true,"position":6,"domain":"yandex.ru","path":"\/search\/direct?lr=54&mw=1&source=direct_wizard&text=%D0%B2%D1%8B%D0%BA%D1%83%D0%BF+%D0%B0%D0%B2%D1%82%D0%BE%D0%BC%D0%BE%D0%B1%D0%B8%D0%BB%D0%B5%D0%B9+%D0%B2+%D0%B5%D0%BA%D0%B0%D1%82%D0%B5%D1%80%D0%B8%D0%BD%D0%B1%D1%83%D1%80%D0%B3%D0%B5"},{"www":false,"position":7,"domain":"avtovycup96.ru","path":"\/"},{"www":false,"position":8,"domain":"2gis.ru","path":"\/ekaterinburg\/search\/%D0%92%D1%8B%D0%BA%D1%83%D0%BF%20%D0%B0%D0%B2%D1%82%D0%BE%D0%BC%D0%BE%D0%B1%D0%B8%D0%BB%D0%B5%D0%B9"},{"www":false,"position":9,"domain":"kupilauto.ru","path":"\/"},{"www":false,"position":10,"domain":"ekburg.dorogo.online","path":"\/"},{"www":false,"position":11,"domain":"skupauto24.ru","path":"\/"},{"www":false,"position":12,"domain":"lubimiy-vykup.ru","path":"\/"},{"www":false,"position":13,"domain":"gedeon-auto.ru","path":"\/"},{"www":false,"position":14,"domain":"xn--80aadbbilb1aod3bhlwdyeko5m.xn--p1ai","path":"\/"},{"www":false,"position":15,"domain":"rava-auto.ru","path":"\/"},{"www":false,"position":16,"domain":"xn--80aadcailb1aqb3bfqtdvhok9m.xn--p1ai","path":"\/"},{"www":false,"position":17,"domain":"vk.com","path":"\/autosale66"},{"www":false,"position":18,"domain":"tradeinauto.su","path":"\/"},{"www":false,"position":19,"domain":"okami-market.ru","path":"\/vykup\/"},{"www":false,"position":20,"domain":"AutoMax96.ru","path":"\/"},{"www":false,"position":21,"domain":"xn---196-53dkc5dxbg0bh6h.xn--p1ai","path":"\/"},{"www":true,"position":22,"domain":"sprosavto66.ru","path":"\/"},{"www":false,"position":23,"domain":"creditors24.com","path":"\/ekaterinburg\/srochnyj-vykup-avto\/"},{"www":false,"position":24,"domain":"ekb.zoon.ru","path":"\/autoservice\/type\/vykup_avtomobilej\/"},{"www":false,"position":25,"domain":"youla.ru","path":"\/ekaterinburg?q=%D0%B0%D1%80%D0%B5%D0%BD%D0%B4%D0%B0%20%D0%B0%D0%B2%D1%82%D0%BE%20%D1%81%20%D0%B2%D1%8B%D0%BA%D1%83%D0%BF%D0%BE%D0%BC"},{"www":false,"position":26,"domain":"autokupim96.ru","path":"\/"},{"www":false,"position":27,"domain":"uslugio.com","path":"\/ekaterinburg\/6\/31\/arenda-s-vykupom"},{"www":false,"position":28,"domain":"vikupavto96.ru","path":"\/"},{"www":false,"position":29,"domain":"ekaterinburg.spravker.ru","path":"\/vykup-avtomobilei\/"},{"www":false,"position":30,"domain":"ekaterinburg.cataloxy.ru","path":"\/firms\/kw\/%E2%FB%EA%F3%EF%20%E0%E2%F2%EE%EC%EE%E1%E8%EB%E5%E9.htm"},{"www":true,"position":31,"domain":"blizko.ru","path":"\/predl\/transport\/freightservices\/arenda\/vykup_avto"},{"www":false,"position":32,"domain":"goldcar66.ru","path":"\/"},{"www":false,"position":33,"domain":"xn----8sbgvpv1a0fq.xn--p1ai","path":"\/sell\/"},{"www":false,"position":34,"domain":"xn--80acbbp1ad4ag6ah8g.xn--p1ai","path":"\/"},{"www":true,"position":35,"domain":"ekb.vikup-service.ru","path":"\/"},{"www":false,"position":36,"domain":"ekb.auto2.info","path":"\/ekaterinburg\/vykup-avtomobiley\/"},{"www":false,"position":37,"domain":"yekaterinburg.big-book-avto.ru","path":"\/vykup_avto\/"},{"www":false,"position":38,"domain":"auto-purchase.ru","path":"\/"},{"www":false,"position":39,"domain":"auto.ru","path":"\/ekaterinburg\/dilery\/cars\/used\/"},{"www":false,"position":40,"domain":"ekaterinburg.bizly.ru","path":"\/vikup-avtomobiley\/"},{"www":false,"position":41,"domain":"ekaterinburg.spravka.ru","path":"\/avto\/vykup-avtomobilej"},{"www":false,"position":42,"domain":"autovikup66.ru","path":"\/"},{"www":false,"position":43,"domain":"ekb.auto-lombard.com","path":"\/vykup-avto\/"},{"www":false,"position":44,"domain":"mapage.ru","path":"\/used_car_dealership-yekaterinburg"},{"www":false,"position":45,"domain":"do.e1.ru","path":"\/search\/?string=%D0%B0%D1%80%D0%B5%D0%BD%D0%B4%D0%B0+%D0%B0%D0%B2%D1%82%D0%BE+%D1%81+%D0%B2%D1%8B%D0%BA%D1%83%D0%BF%D0%BE%D0%BC"},{"www":true,"position":46,"domain":"xn--66-6kchb0c4af3ag0g.xn--p1ai","path":"\/"},{"www":true,"position":47,"domain":"partner96.ru","path":"\/"},{"www":false,"position":48,"domain":"ekaterinburg.jsprav.ru","path":"\/vyikup-avtomobilej\/"},{"www":false,"position":49,"domain":"internet-cabinet.ru","path":"\/ekaterinburg\/arenda-avtomobilej-s-vykupom\/"},{"www":false,"position":50,"domain":"xtimeauto.ru","path":"\/"},{"www":false,"position":51,"domain":"ekb.c-ar.ru","path":"\/prodazha-avtomobilej\/vykup-avtomobilej\/"},{"www":false,"position":52,"domain":"ProSkupka.ru","path":"\/srochnyj-vykup-avto"},{"www":false,"position":53,"domain":"internet-cabinet.ru","path":"\/ekaterinburg\/arenda-avtomobilej-s-vykupom\/"},{"www":false,"position":54,"domain":"do.e1.ru","path":"\/search\/?string=%D0%B0%D1%80%D0%B5%D0%BD%D0%B4%D0%B0+%D0%B0%D0%B2%D1%82%D0%BE+%D1%81+%D0%B2%D1%8B%D0%BA%D1%83%D0%BF%D0%BE%D0%BC"},{"www":false,"position":55,"domain":"fortcar.ru","path":"\/"},{"www":false,"position":56,"domain":"ekaterinburg.riaavto.ru","path":"\/vikup-avto"},{"www":false,"position":57,"domain":"AutoVikup.online","path":"\/ekaterinburg"},{"www":false,"position":58,"domain":"ekb.redeemauto.ru","path":"\/"},{"www":false,"position":59,"domain":"avtoskup66.ru","path":"\/"},{"www":false,"position":60,"domain":"topauto66.ru","path":"\/"},{"www":false,"position":61,"domain":"stranauslug.ru","path":"\/ekaterinburg\/arenda-avtomobilej-s-vykupom\/"},{"www":false,"position":62,"domain":"torpeda66.ru","path":"\/"},{"www":false,"position":63,"domain":"TimeAvto66.ru","path":"\/"},{"www":false,"position":64,"domain":"avtovikupural.ru","path":"\/"},{"www":false,"position":65,"domain":"ekaterinburg.YP.ru","path":"\/rajon\/ekaterinburg\/avtomobili_obmen_vykup_registratsiya\/"},{"www":false,"position":66,"domain":"xn---96-5cdjb8c8ag6ah8g.xn--p1ai","path":"\/"},{"www":false,"position":67,"domain":"spravka7.ru","path":"\/ekaterinburg\/vykup-avtomobiley\/"},{"www":false,"position":68,"domain":"kupimavto196.ru","path":"\/"},{"www":false,"position":69,"domain":"ruscatalog.org","path":"\/ekaterinburg\/category-vykup-avtomobilej\/"},{"www":false,"position":70,"domain":"autopin.ru","path":"\/"},{"www":false,"position":71,"domain":"kupimcar96.ru","path":"\/"},{"www":false,"position":72,"domain":"yekaterinburg.hipdir.com","path":"\/vykup-avto\/"},{"www":true,"position":73,"domain":"spravkaforme.ru","path":"\/city\/ekaterinburg\/category\/vykup-avtomobiley"},{"www":false,"position":74,"domain":"vykupbox.ru","path":"\/ekaterinburg\/"},{"www":false,"position":75,"domain":"ekaterinburg.kitabi.ru","path":"\/avtobiznes\/vykup-avtomobiley"},{"www":false,"position":76,"domain":"kupimavto96.ru","path":"\/"},{"www":false,"position":77,"domain":"vikupauto66.ru","path":"\/"},{"www":false,"position":78,"domain":"skupkaauto66.ru","path":"\/"},{"www":false,"position":79,"domain":"ekaterinburg.prodayavto.ru","path":"\/"},{"www":false,"position":80,"domain":"autosale66.ru","path":"\/"},{"www":false,"position":81,"domain":"xn--80accap3ab2al0ao8g.xn--p1ai","path":"\/"},{"www":false,"position":82,"domain":"toyota-ekaterinburg.ru","path":"\/vikup"},{"www":false,"position":83,"domain":"altdir.ru","path":"\/ekaterinburg\/%D0%92%D1%8B%D0%BA%D1%83%D0%BF+%D0%B0%D0%B2%D1%82%D0%BE%D0%BC%D0%BE%D0%B1%D0%B8%D0%BB%D0%B5%D0%B9\/page-10\/"},{"www":false,"position":84,"domain":"vikup-avto96.ru","path":"\/"},{"www":false,"position":85,"domain":"avtomoll96.ru","path":"\/"},{"www":false,"position":86,"domain":"pc01.ru","path":"\/ekaterinburg\/uslugi\/arenda\/avto\/s-vykupom\/"},{"www":false,"position":87,"domain":"ruborg.ru","path":"\/yekaterinburg\/avtovykup\/"},{"www":false,"position":88,"domain":"ekaterinburg.masmotors.ru","path":"\/vikup"},{"www":true,"position":89,"domain":"orgpage.ru","path":"\/ekaterinburg\/vykup-avtomobiley\/"},{"www":false,"position":90,"domain":"kinf.ru","path":"\/ekaterinburg\/vykup-avto\/"},{"www":false,"position":91,"domain":"all-companies.ru","path":"\/catalog\/ekaterinburg\/vykup-avtomobiley"},{"www":false,"position":92,"domain":"avtobitoe.ru","path":"\/"},{"www":false,"position":93,"domain":"ekaterinburg.moyauto.ru","path":"\/"},{"www":false,"position":94,"domain":"avtoexpert.pro","path":"\/expertise\/vykup-avto-dorogo-bystro-v-ekaterinburge"},{"www":false,"position":95,"domain":"spravbiz.ru","path":"\/ekaterinburg\/avto\/subcategory\/vyikup-avtomobilej"},{"www":false,"position":96,"domain":"razbor66.ru","path":"\/vykup_auto"},{"www":false,"position":97,"domain":"avtospace.net","path":"\/index.htm"},{"www":false,"position":98,"domain":"ekaterinburg.regtorg.ru","path":"\/goods\/vykup_avtomobilej.html"},{"www":true,"position":99,"domain":"avtovikup96.com","path":"\/"},{"www":true,"position":100,"domain":"yandex.ru","path":"\/search\/direct?lr=54&mw=1&source=direct_wizard&text=%D0%B2%D1%8B%D0%BA%D1%83%D0%BF+%D0%B0%D0%B2%D1%82%D0%BE%D0%BC%D0%BE%D0%B1%D0%B8%D0%BB%D0%B5%D0%B9+%D0%B2+%D0%B5%D0%BA%D0%B0%D1%82%D0%B5%D1%80%D0%B8%D0%BD%D0%B1%D1%83%D1%80%D0%B3%D0%B5"},{"www":false,"position":101,"domain":"avtovikup66.ru","path":"\/"},{"www":false,"position":102,"domain":"amk-ekt.ru","path":"\/cashout\/"}],"total":4000000,"request_id":"0","geo_dependent":null,"count_show":0,"request_time":0.014}');
		print_r($ddd);
		echo strpos('ekb', 'ekb.zoon.ru') . "::::<br/>";
		$returnData = [];
		if (isset($ddd->status) && $ddd->status == '1') {
			echo $this->getUrlPositionFromResponse('ekb', $ddd->data);
		}
		die;
	}

	private function getYandexPosition($params)
	{
		//echo 'yandex';
		if ($params['region']['yandex_index'] == null) {
			return -2;
		}
		$requestData['url'] = $this->configurations['url'] . '/scanning/yandex_position';
		$requestData['data'] = [
			'key' => $this->configurations['key'],
			'word' => $params['word'],
			'lr' => $params['region']['yandex_index'],
			'show_page' => 0,
			'show_direct' => 0,
		];
		$taskID = $this->getTaskID($requestData);
		if ($taskID < 0) {
			return $taskID;
		}

		$requestData['url'] = $this->configurations['url'] . '/scanning/check';
		$requestData['data'] = [
			'key' => $this->configurations['key'],
			'method' => 'yandex_position',
			'task_id' => $taskID
		];
		$response = $this->getTask($requestData, 0);
		session(['available_units' => $this->getUnits()]);
		return $this->getUrlPositionFromResponse($params['project_url'], $response);
	}

	private function getGooglePosition($params)
	{
		//echo 'google';
		if ($params['region']['google_index'] == null) {
			return -2;
		}
		$requestData['url'] = $this->configurations['url'] . '/scanning/google_position';
		$requestData['data'] = [
			'key' => $this->configurations['key'],
			'word' => $params['word'],
			'lr' => $params['region']['google_index'],
			'show_page' => 0,
			'show_direct' => 0,
		];
		$taskID = $this->getTaskID($requestData);
		if ($taskID < 0) {
			return $taskID;
		}

		$requestData['url'] = $this->configurations['url'] . '/scanning/check';
		$requestData['data'] = [
			'key' => $this->configurations['key'],
			'method' => 'google_position',
			'task_id' => $taskID
		];
		$response = $this->getTask($requestData, 0);
		if (is_numeric($response)) {
			return $response;
		}
		session(['available_units' => $this->getUnits()]);
		return $this->getUrlPositionFromResponse($params['project_url'], $response);
	}

	public function getUnits()
	{
		//return 10000;
		if (config('app.fake_http') == 1) {
			Http::fake([
				$this->configurations['url'] . '/user/*' => Http::response([
					"status" => 1,
					"units" => 999,
				], 200, ['Headers'])
			]);
		}
//		return Http::get($this->configurations['url'] . '/user/units', [
//			'key' => $this->configurations['key']
//		])['units'];
		//return 1000;
	}

	private function getTaskID($requestData)
	{
		if (config('app.fake_http') == 1) {
			Http::fake([
				$requestData['url'] . '*' => Http::response([
					"status" => 1,
					"data" => [
						"task_id" => "0f3cb1dc262abf006fde332783618e5a",
						"params" => [
							"request" => $requestData['data']['word'],
							"lr" => $requestData['data']['lr'],
							"show_page" => "0",
							"show_direct" => "0",
							"results" => 100
						]
					],
				], 200, ['Headers'])
			]);
		}

		$response = Http::get($requestData['url'], $requestData['data']);

		if ($response->status() == '200') {
			if (isset($response->json()['data']['task_id'])) {
				return $response->json()['data']['task_id'];
			}
			if ($response->json()['status'] == '-102') {
				return -4;
			}
		}
		return -1;
	}

	private function getTask($requestData, $retries = 4)
	{
		if (config('app.fake_http') == 1) {
			Http::fake([
				$requestData['url'] . '*' => Http::response([
					"status" => 1,
					"data" => [
						[
							"www" => true,
							"position" => 1,
							"domain" => "mcdermott.org",
							"path" => "/ru/printing/support/consumables-and-accessories/index.html"
						],
						[
							"www" => true,
							"position" => 2,
							"domain" => "renner.com",
							"path" => "/category/oki-zip-spare-parts-accessories/"
						],
						[
							"www" => false,
							"position" => 5,
							"domain" => "www.gottlieb.com",
							"path" => "/catalog/zapchasti-dlya-oki/"
						],
						[
							"www" => false,
							"position" => 6,
							"domain" => "white.com",
							"path" => "/catalog/zapchasti_oki/"
						],
						[
							"www" => false,
							"position" => 7,
							"domain" => "nader.net",
							"path" => "/oki/"
						],
						[
							"www" => true,
							"position" => 8,
							"domain" => "Avito.ru",
							"path" => "/sankt-peterburg/orgtehnika_i_rashodniki/zapchasti_dlya_printerov_oki_1579910120"
						]
					],
					"total" => 8000000,
					"request_id" => "0",
					"geo_dependent" => null,
					"count_show" => 0,
					"request_time" => 0.037
				], 200, ['Headers'])
			]);
		}

		$response = Http::get($requestData['url'], $requestData['data']);

		if ($response->status() == '200' && isset($response->json()['status'])) {
			if ($response->json()['status'] == '1' && isset($response->json()['data'])) {
				return $response->json()['data'];
			}
			if ($response->json()['status'] == '-102') {
				return -4;
			}
			if ($response->json()['status'] == '0' && $retries <= config('app.retries', 10)) {
				sleep(15);
				return $this->getTask($requestData, $retries + 1);
			}
		}
		throw new \ErrorException('Api Call Exception: get task');
	}
}
