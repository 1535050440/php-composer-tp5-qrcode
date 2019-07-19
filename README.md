基于ThinkPHP5实现【二维码生成】 严格按照TP5框架目录方式上传

有些功能大家可以加以修改直接做成一个公共接口，放在application的common.php中，这样在其他控制器的方法中有需要调用的时候，直接传入参数进行调用

<br><br>（微信自动登录、微信支付、支付宝支付、Web推送、阿里云短信、极光推送。正在开发中）
<br>
在线颜色网址：http://tool.oschina.net/commons?type=3

-----------------

# QR Code

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/qr-code.svg)](https://packagist.org/packages/endroid/qr-code)
[![Build Status](http://img.shields.io/travis/endroid/qr-code.svg)](http://travis-ci.org/endroid/qr-code)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/qr-code.svg)](https://packagist.org/packages/endroid/qr-code)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/qr-code.svg)](https://packagist.org/packages/endroid/qr-code)
[![License](http://img.shields.io/packagist/l/endroid/qr-code.svg)](https://packagist.org/packages/endroid/qr-code)

This library helps you generate QR codes in a jiffy. Makes use of [bacon/bacon-qr-code](https://github.com/Bacon/BaconQrCode)
to generate the matrix and [khanamiryan/qrcode-detector-decoder](https://github.com/khanamiryan/php-qrcode-detector-decoder)
for validating generated QR codes. Further extended with Twig extensions, generation routes, a factory and a
Symfony bundle for easy installation and configuration.

````php
composer require deng-tp5/qr-code
````
## common.php

````php

$url = 'myphp.vip';
create_qrcode($url);        //  引用commmon里的方法 

/**
 * 生成二维码公共方法
 * @param $url
 * @return \Endroid\QrCode\Response\QrCodeResponse
 * @throws \Endroid\QrCode\Exception\InvalidPathException
 * @author:  deng    (2019/7/17 16:04)
 */
function create_qrcode($url)
{
    $url = "http://myphp.vip";
    $condition['text'] = $url;
    return QrCodeDeng::createQrcodeAdvanced($condition);
}
````

## Deng-qr-code用法【已封装】

````php
/**
 * Created by PhpStorm.
 * User: 12155
 * Date: 2019/7/18
 * Time: 14:27
 */

namespace DengTp5;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class QrCodeDeng
{
    /**
     *  基础用法
     * @param $text
     * @param $a
     * @return QrCodeResponse
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     * @author:  deng    (2019/7/18 18:07)
     */
    public static function createQrcodeBase($text = 'demo')
    {
        $qrCode = new QrCode($text);

        header('Content-Type: ' . $qrCode->getContentType());
        echo $qrCode->writeString();
        exit;
    }

    /**
     * 高级用法
     * @return QrCodeResponse
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     * @author:  deng    (2019/7/18 21:37)
     */
    public static function createQrcodeAdvanced($conndition = [], $is_save = false)
    {
        //  定义当前框架所在目录
        $rootPath = \think\facade\Env::get('root_path');

        //  引入字体文件
        $font = $rootPath.'/./vendor/deng-tp5/qr-code/assets/fonts/noto_sans.otf';
        //  引入logo图片
        $logoImg = $rootPath.'/./vendor/deng-tp5/qr-code/assets/images/logo.png';

        $text = !empty($conndition['text'])?$conndition['text']:'myphp.vip';
        $logoImg = !empty($conndition['logo_img'])?$conndition['logo_img']:$logoImg;
        $bottomText = !empty($conndition['bottom_text'])?$conndition['bottom_text']:'二维码底部';
        $size = !empty($conndition['size'])?$conndition['size']:'300';
        $margin = !empty($conndition['margin'])?$conndition['margin']:'12';

        //  是否传递二维码
        $foregroundColor = !empty($conndition['foreground_color'])?$conndition['foreground_color']:'0,0,0';
        $foregroundColorArray = explode(',',$foregroundColorArray);

        //  是否传递二维码背景颜色
        $backgroundColor = !empty($conndition['background_color'])?$conndition['background_color']:'255,255,255';
        $backgroundColorArray = explode(',',$backgroundColor);



        //  -------------构建参数end

        $dir = $rootPath.'/./public/qrcode/'. date('Ym');

        // 如果文件夹不存在，将以递归方式创建该文件夹
        is_dir($dir) OR mkdir($dir, 0777, true);

        $fileName = $dir.'/'.date('d').'-'.time().'-'.rand(1000,9999).'.png';

        

        // Create a basic QR code
        $qrCode = new \Endroid\QrCode\QrCode($text);
        $qrCode->setSize($size);

        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin($margin);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::HIGH));
        //  底色
        $qrCode->setForegroundColor(['r' => $foregroundColorArray[0], 'g' => $foregroundColorArray[1], 'b' => $foregroundColorArray[2], 'a' => 0]);
        //  二维码背景颜色
        $qrCode->setBackgroundColor(['r' => $backgroundColorArray[0], 'g' => $backgroundColorArray[1], 'b' => $backgroundColorArray[2], 'a' => 0]);

        $qrCode->setLabel($bottomText, 16, $font, \Endroid\QrCode\LabelAlignment::CENTER);
        $qrCode->setLogoPath($logoImg);
        $qrCode->setLogoSize(120, 120);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);

        // Directly output the QR code
        header('Content-Type: '.$qrCode->getContentType());

        $writeString = $qrCode->writeString();

        if ($is_save) {
            // Save it to a file
            $qrCode->writeFile($fileName);

            // Create a response object
            $response = new \Endroid\QrCode\Response\QrCodeResponse($qrCode);
        }

        echo $writeString;exit;
    }



}

````
![QR Code](https://guangjiaoge.com/uploads/20190718/be3a8e56518b95964a6ad179c6acae37.png)

===============================================

## Installation

Use [Composer](https://getcomposer.org/) to install the library.



``` bash
$ composer require endroid/qr-code
```



## Basic usage

```php
use Endroid\QrCode\QrCode;

$qrCode = new QrCode('Life is too short to be generating QR codes');

header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();
```

## Advanced usage

```php
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

// Create a basic QR code
$qrCode = new QrCode('Life is too short to be generating QR codes');
$qrCode->setSize(300);

// Set advanced options
$qrCode->setWriterByName('png');
$qrCode->setMargin(10);
$qrCode->setEncoding('UTF-8');
$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
$qrCode->setLabel('Scan the code', 16, __DIR__.'/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER);
$qrCode->setLogoPath(__DIR__.'/../assets/images/symfony.png');
$qrCode->setLogoSize(150, 200);
$qrCode->setRoundBlockSize(true);
$qrCode->setValidateResult(false);
$qrCode->setWriterOptions(['exclude_xml_declaration' => true]);

// Directly output the QR code
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();

// Save it to a file
$qrCode->writeFile(__DIR__.'/qrcode.png');

// Create a response object
$response = new QrCodeResponse($qrCode);
```

![QR Code](https://endroid.nl/qr-code/Life%20is%20too%20short%20to%20be%20generating%20QR%20codes.png)

## Built-in validation reader

You can enable the built-in validation reader (disabled by default) by calling
setValidateResult(true). This validation reader does not guarantee that the QR
code will be readable by all readers but it helps you provide a minimum level
of quality.
 
The readability of a QR code is primarily determined by the size, the input
length, the error correction level and any possible logo over the image so you
can tweak these parameters if you are looking for optimal results. You can also
check $qrCode->getRoundBlockSize() value to see if block dimensions are rounded
so that the image is more sharp and readable.

Take note that the validator can consume quite amount of additional resources.

## Symfony integration

The [endroid/qr-code-bundle](https://github.com/endroid/qr-code-bundle)
integrates the QR code library in Symfony for an even better experience.

* Configure your defaults (like image size, default writer etc.)
* Generate QR codes quickly from anywhere via the factory service
* Generate QR codes directly by typing an URL like /qr-code/\<text>.png?size=300
* Generate QR codes or URLs directly from Twig using dedicated functions
 
Read the [bundle documentation](https://github.com/endroid/qr-code-bundle)
for more information.

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
