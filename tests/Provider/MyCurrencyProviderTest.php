<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\CurrencyConverter\Tests\Provider;

use Thelia\CurrencyConverter\Exception\CurrencyNotFoundException;
use Thelia\CurrencyConverter\Provider\MyCurrencyProvider;
use Thelia\Math\Number;

class MyCurrencyProviderTest extends \PHPUnit_Framework_TestCase
{
    public $jsonData = <<<JSON
[{"currency_code":"AED","rate":3.67204,"code":"AE","name":"United Arab Emirates"},{"currency_code":"AFN","rate":68.15,"code":"AF","name":"Afghanistan"},{"currency_code":"ALL","rate":112.9,"code":"AL","name":"Albania"},{"currency_code":"AMD","rate":477.91,"code":"AM","name":"Armenia"},{"currency_code":"ANG","rate":1.7825,"code":"AW","name":"Aruba"},{"currency_code":"AOA","rate":165.096,"code":"AO","name":"Angola"},{"currency_code":"ARS","rate":17.288,"code":"AR","name":"Argentina"},{"currency_code":"AUD","rate":1.2745,"code":"AU","name":"Australian"},{"currency_code":"AZN","rate":1.6997,"code":"AZ","name":"Azerbaijan"},{"currency_code":"BAM","rate":1.6577,"code":"BA","name":"Bosnia and Herzegovina"},{"currency_code":"BBD","rate":2,"code":"BB","name":"Barbados"},{"currency_code":"BDT","rate":82.1,"code":"BD","name":"Bangladesh"},{"currency_code":"BGN","rate":1.6566,"code":"BG","name":"Bulgaria"},{"currency_code":"BHD","rate":0.376704,"code":"BH","name":"Bahrain"},{"currency_code":"BIF","rate":1735.49,"code":"BI","name":"Burundi"},{"currency_code":"BMD","rate":1,"code":"BM","name":"Bermuda"},{"currency_code":"BND","rate":1.3564,"code":"BN","name":"Brunei Darussalam"},{"currency_code":"BOB","rate":6.8604,"code":"BO","name":"Bolivia"},{"currency_code":"BRL","rate":3.1632,"code":"BR","name":"Brazil"},{"currency_code":"BSD","rate":1,"code":"BS","name":"Bahamas"},{"currency_code":"BWP","rate":10.3043,"code":"BW","name":"Botswana"},{"currency_code":"BYR","rate":20020,"code":"BY","name":"Belarus"},{"currency_code":"BZD","rate":1.9978,"code":"BZ","name":"Belize"},{"currency_code":"CAD","rate":1.2464,"code":"CA","name":"Canada"},{"currency_code":"CDF","rate":1559.54,"code":"CD","name":"Congo (Kinshasa)"},{"currency_code":"CHF","rate":0.96862,"code":"LI","name":"Liechtenstein"},{"currency_code":"CLP","rate":639.504,"code":"CL","name":"Chile"},{"currency_code":"CNY","rate":6.6532,"code":"CN","name":"China"},{"currency_code":"COP","rate":2931.7,"code":"CO","name":"Colombia"},{"currency_code":"CRC","rate":570.52,"code":"CR","name":"Costa Rica"},{"currency_code":"CUP","rate":26.5,"code":"CU","name":"Cuba"},{"currency_code":"CVE","rate":93.35,"code":"CV","name":"Cape Verde"},{"currency_code":"CZK","rate":21.971,"code":"CZ","name":"Czech Republic"},{"currency_code":"DJF","rate":176.7,"code":"DJ","name":"Djibouti"},{"currency_code":"DKK","rate":6.29456,"code":"DK","name":"Denmark"},{"currency_code":"DOP","rate":47.0204,"code":"DO","name":"Dominican Republic"},{"currency_code":"DZD","rate":112.733,"code":"DZ","name":"Algeria"},{"currency_code":"EEK","rate":13.947,"code":"EE","name":"Estonia"},{"currency_code":"EGP","rate":17.63,"code":"EG","name":"Egypt"},{"currency_code":"ETB","rate":23.3504,"code":"ER","name":"Eritrea"},{"currency_code":"EUR","rate":0.846104,"code":"AS","name":"American Samoa"},{"currency_code":"FJD","rate":2.0455,"code":"FJ","name":"Fiji"},{"currency_code":"FKP","rate":0.745804,"code":"FK","name":"Falkland Islands (Malvinas)"},{"currency_code":"GBP","rate":0.746404,"code":"GS","name":"South Georgia and the South Sandwich Islands"},{"currency_code":"GEL","rate":2.4735,"code":"GE","name":"Georgia"},{"currency_code":"GGP","rate":0.746465,"code":"GG","name":"Guernsey"},{"currency_code":"GHS","rate":4.3665,"code":"GH","name":"Ghana"},{"currency_code":"GIP","rate":0.74604,"code":"GI","name":"Gibraltar"},{"currency_code":"GMD","rate":44.85,"code":"GM","name":"Gambia"},{"currency_code":"GNF","rate":8897,"code":"GN","name":"Guinea"},{"currency_code":"GTQ","rate":7.31404,"code":"GT","name":"Guatemala"},{"currency_code":"GYD","rate":204.43,"code":"GY","name":"Guyana"},{"currency_code":"HKD","rate":7.8099,"code":"HK","name":"Hong Kong"},{"currency_code":"HNL","rate":23.331,"code":"HN","name":"Honduras"},{"currency_code":"HRK","rate":6.3419,"code":"HR","name":"Croatia (Hrvatska)"},{"currency_code":"HTG","rate":61.31,"code":"HT","name":"Haiti"},{"currency_code":"HUF","rate":263.76,"code":"HU","name":"Hungary"},{"currency_code":"IDR","rate":13470,"code":"TP","name":"East Timor"},{"currency_code":"ILS","rate":3.5304,"code":"IL","name":"Israel"},{"currency_code":"INR","rate":65.28,"code":"BT","name":"Bhutan"},{"currency_code":"IQD","rate":1166,"code":"IQ","name":"Iraq"},{"currency_code":"IRR","rate":33805,"code":"IR","name":"Iran (Islamic Republic of)"},{"currency_code":"ISK","rate":106.37,"code":"IS","name":"Iceland"},{"currency_code":"JMD","rate":128.29,"code":"JM","name":"Jamaica"},{"currency_code":"JOD","rate":0.706904,"code":"JO","name":"Jordan"},{"currency_code":"JPY","rate":112.428,"code":"JP","name":"Japan"},{"currency_code":"KES","rate":102.95,"code":"KE","name":"Kenya"},{"currency_code":"KGS","rate":68.633,"code":"KG","name":"Kyrgyzstan"},{"currency_code":"KHR","rate":4032.8,"code":"KH","name":"Cambodia"},{"currency_code":"KMF","rate":417.18,"code":"KM","name":"Comoros"},{"currency_code":"KPW","rate":900,"code":"KP","name":"Korea North"},{"currency_code":"KRW","rate":1143.79,"code":"KR","name":"Korea South"},{"currency_code":"KWD","rate":0.301604,"code":"KW","name":"Kuwait"},{"currency_code":"KYD","rate":0.820383,"code":"KY","name":"Cayman Islands"},{"currency_code":"KZT","rate":340.18,"code":"KZ","name":"Kazakhstan"},{"currency_code":"LAK","rate":8284.7,"code":"LA","name":"Laos"},{"currency_code":"LBP","rate":1505.5,"code":"LB","name":"Lebanon"},{"currency_code":"LKR","rate":153.05,"code":"LK","name":"Sri Lanka"},{"currency_code":"LRD","rate":117.1,"code":"LR","name":"Liberia"},{"currency_code":"LSL","rate":13.5504,"code":"LS","name":"Lesotho"},{"currency_code":"LTL","rate":3.0487,"code":"LT","name":"Lithuania"},{"currency_code":"LVL","rate":0.62055,"code":"LV","name":"Latvia"},{"currency_code":"LYD","rate":1.3675,"code":"LY","name":"Libyan Arab Jamahiriya"},{"currency_code":"MAD","rate":9.4082,"code":"MA","name":"Morocco"},{"currency_code":"MDL","rate":17.513,"code":"MD","name":"Moldova Republic of"},{"currency_code":"MGA","rate":3185,"code":"MG","name":"Madagascar"},{"currency_code":"MKD","rate":51.8404,"code":"MK","name":"Macedonia"},{"currency_code":"MMK","rate":1362.5,"code":"MM","name":"Myanmar"},{"currency_code":"MNT","rate":2454,"code":"MN","name":"Mongolia"},{"currency_code":"MOP","rate":8.0442,"code":"MO","name":"Macao S.A.R."},{"currency_code":"MRO","rate":361,"code":"MR","name":"Mauritania"},{"currency_code":"MUR","rate":33.83,"code":"MU","name":"Mauritius"},{"currency_code":"MVR","rate":15.5304,"code":"MV","name":"Maldives"},{"currency_code":"MWK","rate":716.21,"code":"MW","name":"Malawi"},{"currency_code":"MXN","rate":18.2504,"code":"MX","name":"Mexico"},{"currency_code":"MYR","rate":4.21904,"code":"MY","name":"Malaysia"},{"currency_code":"MZN","rate":60.7304,"code":"MZ","name":"Mozambique"},{"currency_code":"NAD","rate":13.546,"code":"NA","name":"Namibia"},{"currency_code":"NGN","rate":353,"code":"NG","name":"Nigeria"},{"currency_code":"NIO","rate":29.9804,"code":"NI","name":"Nicaragua"},{"currency_code":"NOK","rate":7.95704,"code":"BV","name":"Bouvet Island"},{"currency_code":"NPR","rate":104.05,"code":"NP","name":"Nepal"},{"currency_code":"NZD","rate":1.3869,"code":"NZ","name":"New Zealand"},{"currency_code":"OMR","rate":0.384404,"code":"OM","name":"Oman"},{"currency_code":"PAB","rate":1,"code":"PA","name":"Panama"},{"currency_code":"PEN","rate":3.2604,"code":"PE","name":"Peru"},{"currency_code":"PGK","rate":3.2015,"code":"PG","name":"Papua New Guinea"},{"currency_code":"PHP","rate":50.88,"code":"PH","name":"Philippines"},{"currency_code":"PKR","rate":105.2,"code":"PK","name":"Pakistan"},{"currency_code":"PLN","rate":3.6497,"code":"PL","name":"Poland"},{"currency_code":"PYG","rate":5650.6,"code":"PY","name":"Paraguay"},{"currency_code":"QAR","rate":3.7098,"code":"QA","name":"Qatar"},{"currency_code":"RON","rate":3.8844,"code":"RO","name":"Romania"},{"currency_code":"RSD","rate":101.025,"code":"RS","name":"Serbia"},{"currency_code":"RUB","rate":57.507,"code":"RU","name":"Russian Federation"},{"currency_code":"RWF","rate":828.87,"code":"RW","name":"Rwanda"},{"currency_code":"SAR","rate":3.7497,"code":"SA","name":"Saudi Arabia"},{"currency_code":"SBD","rate":7.8437,"code":"SB","name":"Solomon Islands"},{"currency_code":"SCR","rate":13.676,"code":"SC","name":"Seychelles"},{"currency_code":"SDG","rate":6.6598,"code":"SD","name":"Sudan"},{"currency_code":"SEK","rate":8.1403,"code":"SE","name":"Sweden"},{"currency_code":"SGD","rate":1.35683,"code":"SG","name":"Singapore"},{"currency_code":"SLL","rate":7620,"code":"SL","name":"Sierra Leone"},{"currency_code":"SOS","rate":557,"code":"SO","name":"Somalia"},{"currency_code":"SRD","rate":7.38037,"code":"SR","name":"Suriname"},{"currency_code":"STD","rate":20741.6,"code":"ST","name":"Sao Tome and Principe"},{"currency_code":"SVC","rate":8.75037,"code":"SV","name":"El Salvador"},{"currency_code":"SYP","rate":514.98,"code":"SY","name":"Syrian Arab Republic"},{"currency_code":"SZL","rate":13.546,"code":"SZ","name":"Swaziland"},{"currency_code":"THB","rate":33.31,"code":"TH","name":"Thailand"},{"currency_code":"TJS","rate":8.8008,"code":"TJ","name":"Tajikistan"},{"currency_code":"TMT","rate":3.4,"code":"TM","name":"Turkmenistan"},{"currency_code":"TND","rate":2.4645,"code":"TN","name":"Tunisia"},{"currency_code":"TOP","rate":2.2222,"code":"TO","name":"Tonga"},{"currency_code":"TRY","rate":3.5648,"code":"TR","name":"Turkey"},{"currency_code":"TTD","rate":6.75604,"code":"TT","name":"Trinidad and Tobago"},{"currency_code":"TWD","rate":30.292,"code":"TW","name":"Taiwan"},{"currency_code":"TZS","rate":2237,"code":"TZ","name":"Tanzania"},{"currency_code":"UAH","rate":26.5904,"code":"UA","name":"Ukraine"},{"currency_code":"UGX","rate":3595,"code":"UG","name":"Uganda"},{"currency_code":"USD","rate":1,"code":"IO","name":"British Indian Ocean Territory"},{"currency_code":"UYU","rate":29.14,"code":"UY","name":"Uruguay"},{"currency_code":"UZS","rate":8055,"code":"UZ","name":"Uzbekistan"},{"currency_code":"VEF","rate":9.97504,"code":"VE","name":"Venezuela"},{"currency_code":"VND","rate":22719,"code":"VN","name":"Vietnam"},{"currency_code":"VUV","rate":104.1,"code":"VU","name":"Vanuatu"},{"currency_code":"XAF","rate":554.69,"code":"CM","name":"Cameroon"},{"currency_code":"XCD","rate":2.70361,"code":"AI","name":"Anguilla"},{"currency_code":"XOF","rate":551,"code":"BJ","name":"Benin"},{"currency_code":"XPF","rate":101.375,"code":"PF","name":"French Polynesia"},{"currency_code":"YER","rate":249.95,"code":"YE","name":"Yemen"},{"currency_code":"ZAR","rate":13.5459,"code":"ZA","name":"South Africa"},{"currency_code":"ZMK","rate":9001.2,"code":"ZM","name":"Zambia"}]    
JSON;

    /**
     * @var \Thelia\CurrencyConverter\Provider\MyCurrencyProvider
     */
    public $provider;

    public function setUp()
    {
        $this->provider = new MyCurrencyProvider(false);

        $this->provider->loadFromJSON($this->jsonData);
    }

    /**
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::convert
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::retrieveRateFactor
     */
    public function testFromEuro()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $usdRate = $provider->from('EUR')->to('USD')->convert($number);

        $this->assertInstanceOf('Thelia\Math\Number', $usdRate, "the provider must return an instance of Number");
        $this->assertEquals('1.1818878057543754', $usdRate->getNumber(-1), "the expected result from EUR to USD is 1.1818878057543754");

        $gbpRate = $provider->from('EUR')->to('GBP')->convert($number);

        $this->assertInstanceOf('Thelia\Math\Number', $gbpRate, "the provider must return an instance of Number");
        $this->assertEquals('0.88216578576628879', $gbpRate->getNumber(-1), "the expected result from EUR to GBP is 0.88216578576628879");
    }

    /**
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::convert
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::retrieveRateFactor
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::convertToOther
     */
    public function testFromUsd()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $euroRate = $provider->from('USD')->to('EUR')->convert($number);

        $this->assertInstanceOf('Thelia\Math\Number', $euroRate, "the provider must return an instance of Number");
        $this->assertEquals('0.84610399999999997', $euroRate->getNumber(-1), "the expected result from USD to EUR is 0.84610399999999997");

        $gbpRate = $provider->from('USD')->to('GBP')->convert($number);

        $this->assertInstanceOf('Thelia\Math\Number', $gbpRate, "the provider must return an instance of Number");
        $this->assertEquals('0.74640399999999996', $gbpRate->getNumber(-1), "the expected result from USD to GBP is 0.74640399999999996");
    }

    /**
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::convert
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::retrieveRateFactor
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::convertToOther
     */
    public function testFromGbp()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $euroRate = $provider->from('GBP')->to('EUR')->convert($number);

        $this->assertInstanceOf('Thelia\Math\Number', $euroRate, "the provider must return an instance of Number");
        $this->assertEquals('1.1335737750601551', $euroRate->getNumber(-1), "the expected result from GBP to EUR is 1.1335737750601551");

        $usdRate = $provider->from('GBP')->to('USD')->convert($number);

        $this->assertInstanceOf('Thelia\Math\Number', $usdRate, "the provider must return an instance of Number");
        $this->assertEquals('1.3397570216665506', $usdRate->getNumber(-1), "the expected result from GBP to USD is 1.3397570216665506");
    }

    /**
     * @expectedException \Thelia\CurrencyConverter\Exception\CurrencyNotFoundException
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::retrieveRateFactor
     */
    public function testResolveWithUnknownCurrencies()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $rate = $provider->from('FOO')->to('BAR')->convert($number);
    }

    /**
     * @expectedException \Thelia\CurrencyConverter\Exception\CurrencyNotFoundException
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::retrieveRateFactor
     */
    public function testConvertWithUnknowFrom()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $rate = $provider->from('FOO')->to('USD')->convert($number);
    }

    /**
     * @expectedException \Thelia\CurrencyConverter\Exception\CurrencyNotFoundException
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::retrieveRateFactor
     * @covers \Thelia\CurrencyConverter\Provider\MyCurrencyProvider::convertToOther
     */
    public function testConvertWithUnknownTo()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $provider->from('EUR')->to('FOO')->convert($number);
    }

    public function testConvertWithException()
    {
        try {
            $provider = $this->provider;
            $number = new Number(1);

            $rate = $provider->from('FOO')->to('USD')->convert($number);
        } catch (CurrencyNotFoundException $e) {
            $this->assertEquals('FOO', $e->getCurrency());
            return;
        }

        $this->fail('try converting with unknown currencies must fail');
    }
}
