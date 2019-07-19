<?php
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

        //  引入logo图片
        $logoImg = $rootPath.'/./vendor/deng-tp5/qr-code/assets/images/logo.png';

        $text = !empty($conndition['text'])?$conndition['text']:'myphp.vip';
        $logoImg = !empty($conndition['logo_img'])?$conndition['logo_img']:$logoImg;
        $bottomText = !empty($conndition['bottom_text'])?$conndition['bottom_text']:'二维码底部';

        $dir = $rootPath.'/./public/qrcode/'. date('Ym');

        // 如果文件夹不存在，将以递归方式创建该文件夹
        is_dir($dir) OR mkdir($dir, 0777, true);

        $fileName = $dir.'/'.date('d').'-'.time().'-'.rand(1000,9999).'.png';

        //  引入字体文件
        $font = $rootPath.'/./vendor/endroid/qr-code/assets/fonts/noto_sans.otf';

        // Create a basic QR code
        $qrCode = new \Endroid\QrCode\QrCode($text);
        $qrCode->setSize(300);

        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(12);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::HIGH));
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 255]);
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
