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
     *
     * @param $text
     * @param $a
     * @return QrCodeResponse
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     * @author:  deng    (2019/7/18 18:07)
     */
    public static function createQrcode($text,$a = '')
    {
        $root_path = \think\facade\Env::get('root_path');
        $date = date('Ym');
        $dir = $root_path.'/./public/qrcode/'.$date;
        $file_name = $dir.'/'.date('d').'-'.time().'-'.rand(1000,9999).'.png';

        // 如果文件夹不存在，将以递归方式创建该文件夹
        is_dir($dir) OR mkdir($dir, 0777, true);

        $path = $root_path.'/./vendor/endroid/qr-code/assets/fonts/noto_sans.otf';
        $img = $root_path.'/./vendor/endroid/qr-code/assets/images/symfonyx.png';

        // Create a basic QR code
        $qrCode = new \Endroid\QrCode\QrCode('Life is too short to be generating QR codes');
        $qrCode->setSize(300);

        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::HIGH));
        $qrCode->setForegroundColor(['r' => 0, 'g' => 140, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLabel('二维码底部', 16, $path, \Endroid\QrCode\LabelAlignment::CENTER);
        $qrCode->setLogoPath($img);
        $qrCode->setLogoSize(120, 120);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);

        // Directly output the QR code
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
        exit;
        // Save it to a file
        $qrCode->writeFile($file_name);

        // Create a response object
        $response = new \Endroid\QrCode\Response\QrCodeResponse($qrCode);

        return $response;
    }

}