<?php
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

require_once '../vendor/autoload.php';
$client = new Client(['base_uri' => 'https://date.nager.at/api/v3/']);

$promises = [
  'NextPublicHolidaysWorldWide' => $client->getAsync('NextPublicHolidaysWorldWide'),
    'Countries' => $client->getAsync('AvailableCountries')
];

$responses = Promise\Utils::settle($promises)->wait();


$getCountry = function ($responses, $countryCode) {
  if ($responses['Countries']['state'] == 'fulfilled') {
      foreach (json_decode($responses['Countries']['value']->getBody()) as $Country) {
          if ($Country->countryCode == $countryCode) {
              return $Country->name;
          }
      }
  } else return 'somewhere';
  return 'somewhere';
};
//$getCountry = function ($countryCode): string {
  //  $client = new Client(['base_uri' => 'https://date.nager.at/api/v3/']);
  //return (json_decode(($client->request('GET', 'https://date.nager.at/api/v3/CountryInfo/' . $countryCode))->getBody()))->commonName;
//};
  $jsonHoliday = json_decode($responses['NextPublicHolidaysWorldWide']['value']->getBody())
 //$jsonHoliday = json_decode($client->request('GET', 'NextPublicHolidaysWorldWide')->getBody());
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>

<body>
@foreach ($jsonHoliday as $instance)
    <div class="box">
            <div class="box">
                {{$instance->name}} | {{$instance->date}}
                <hr>
                Locally known as
                {{$instance->localName}},
                this holiday will be celebrated in
                    {{$getCountry($responses, $instance->countryCode)}}
                on {{$instance->date}}.
            </div>
    </div>
@endforeach
</body>
</html>




