<?php namespace ZN\Captcha;

use Captcha;

class BackgroundTest extends CaptchaExtends
{
    public function testBackgroundColor()
    {
        Captcha::path(self::directory)->bgColor('30|89|178')->create(true);

        $this->isEquals();
    }

    public function testBackgroundSize()
    {
        Captcha::path(self::directory)->size(400, 400)->create(true);

        $this->isEquals();
    }

    public function testBackgroundImage()
    {
        Captcha::path(self::directory)->bgImage
        ([
            self::directory . 'images/1.png',
            self::directory . 'images/2.png'
            
        ])->size(400, 400)->create(true);

        $this->isEquals();
    }
}