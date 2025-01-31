<?php

if (!defined('FCPATH')) {
    define('FCPATH', dirname(__FILE__) . '/');
}

if (!function_exists('create_captcha')) {
    function create_captcha($data = [])
    {
        $defaults = [
            'word' => '',
            'img_path' => 'captcha/',
            'img_url' => 'http://example.com/captcha/',
            'font_path' => FCPATH . 'public/fonts/arial.ttf',
            'img_width' => 150,
            'img_height' => 50,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 20,
            'img_id' => '',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => [
                'background' => [255, 255, 255],
                'border' => [0, 0, 0],
                'text' => [0, 0, 0],
                'grid' => [200, 200, 200]
            ]
        ];

        // Merge custom data
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $defaults[$key] = $val;
            }
        } else {
            $defaults['word'] = $data;
        }

        $img_path = rtrim($defaults['img_path'], '/') . '/';
        $img_url = rtrim($defaults['img_url'], '/') . '/';
        $font_path = $defaults['font_path'];

        if ($img_path == '' || $img_url == '' || !file_exists($font_path)) {
            return false;
        }

        if (!is_dir($img_path)) {
            mkdir($img_path, 0777, true);
        }

        if (!is_writable($img_path)) {
            return false;
        }

        if ($defaults['word'] == '') {
            $defaults['word'] = generateRandomString($defaults['word_length'], $defaults['pool']);
        }

        if ($defaults['expiration'] > 0) {
            $now = time();
            foreach (glob($img_path . '*.jpg') as $file) {
                if (filemtime($file) + $defaults['expiration'] < $now) {
                    unlink($file);
                }
            }
        }

        $filename = time() . '.jpg';
        $img = imagecreatetruecolor($defaults['img_width'], $defaults['img_height']);

        // Warna
        $bg_color = imagecolorallocate($img, ...$defaults['colors']['background']);
        $border_color = imagecolorallocate($img, ...$defaults['colors']['border']);
        $text_color = imagecolorallocate($img, ...$defaults['colors']['text']);
        $grid_color = imagecolorallocate($img, ...$defaults['colors']['grid']);

        // Buat latar belakang dan border
        imagefilledrectangle($img, 0, 0, $defaults['img_width'], $defaults['img_height'], $bg_color);
        imagerectangle($img, 0, 0, $defaults['img_width'] - 1, $defaults['img_height'] - 1, $border_color);

        // Tambahkan garis acak
        for ($i = 0; $i < ($defaults['img_width'] * $defaults['img_height']) / 150; $i++) {
            imageline(
                $img,
                mt_rand(0, $defaults['img_width']),
                mt_rand(0, $defaults['img_height']),
                mt_rand(0, $defaults['img_width']),
                mt_rand(0, $defaults['img_height']),
                $grid_color
            );
        }

        // Tambahkan teks
        $x = rand(80, 100);
        $y = $defaults['img_height'] - rand(30, 40);
        imagettftext($img, $defaults['font_size'], rand(-10, 10), $x, $y, $text_color, $font_path, $defaults['word']);

        // Simpan gambar
        imagejpeg($img, $img_path . $filename);
        imagedestroy($img);

        return [
            'word' => $defaults['word'],
            'image' => '<img src="' . $img_url . $filename . '" alt="CAPTCHA" ' . $defaults['img_id'] . ' />',
            'time' => time(),
            'filename' => $filename
        ];
    }

    function generateRandomString($length = 6, $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($pool);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $pool[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
