<?php namespace ZN\Helpers;

use Converter;

class ConverterTest extends \PHPUnit\Framework\TestCase
{
    public function testByte()
    {
        $this->assertSame('976.56KB', Converter::byte(1000000, 2));
        $this->assertSame('976.6', Converter::byte(1000000, 1, false));
        $this->assertSame('976.56 KB', Converter::byte(1000000, 2, true, ' '));
        $this->assertSame('100B', Converter::byte(100));
        $this->assertSame('9.5MB', Converter::byte(10000000));
        $this->assertSame('9.3GB', Converter::byte(10000000000));
        $this->assertSame('9.1TB', Converter::byte(10000000000000));
        $this->assertSame('8.9PB', Converter::byte(10000000000000000));
        $this->assertSame('8.7EB', Converter::byte(10000000000000000000));
        $this->assertSame('10.000.000.000.000.000.000.000B', Converter::byte(10000000000000000000000));
    }

    public function testToBytes()
    {
        $this->assertSame(1024, (int) Converter::toBytes('1KB'));
    
    }

    public function testMoney()
    {
        $this->assertSame('1.000.000,00 £', Converter::money(1000000, '£'));
        $this->assertSame('1.000.000 $', Converter::money(1000000, '$', false));
        $this->assertSame('£ 1.000.000,00', Converter::money(1000000, '!£'));
    }

    public function testMoneyToNumber()
    {
        $this->assertSame(1000000, (int) Converter::moneyToNumber('1.000.000'));
    }

    public function testTime()
    {
        $this->assertSame(2, (int) Converter::time(120, 'second', 'minute'));
        $this->assertSame(4, (int) Converter::time(120, 'day', 'month'));
        $this->assertSame(1, (int) Converter::time(12, 'month', 'year'));
        $this->assertSame(12, (int) Converter::time(1, 'year', 'month'));
    }

    public function testSlug()
    {
        $this->assertSame('example-file.php', Converter::slug('Example File.php', true));
        $this->assertSame('example-file-php', Converter::slug('Example File.php'));
    }

    public function testUrlWord()
    {
        $this->assertSame('example-file-php', Converter::urlWord('Example File.php'));
    }

    public function testWord()
    {
        $this->assertSame('Hi, [badwords] Guys', Converter::word('Hi, ? Guys', '?'));
        $this->assertSame('Hi[badwords] [badwords] Guys', Converter::word('Hi, ? Guys', [',', '?']));
        $this->assertSame('Hix y Guys', Converter::word('Hi, ? Guys', [',', '?'], ['x', 'y'])); 
    }

    public function testAnchor()
    {
        $this->assertSame
        ( 
            '<a href="https://www.znframework.com" id="convert">znframework</a>',
            Converter::anchor('https://www.znframework.com', 'short', ['id' => 'convert']) 
        );
        $this->assertSame
        ( 
            '<a href="https://www.znframework.com" id="convert">https://www.znframework.com</a>',
            Converter::anchor('https://www.znframework.com', 'long', ['id' => 'convert']) 
        );
    }

    public function testChar()
    {
        $this->assertSame
        (
            '69 120 97 109 112 108 101  68 97 116 97',
            Converter::char('Example Data', 'char', 'dec') 
        );

        $this->assertSame
        ( 
            '45 78 61 6D 70 6C 65  44 61 74 61',
            Converter::char('Example Data', 'char', 'hex') 
        );

        try
        {
            Converter::char('Example Data', 'charx', 'hex');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertIsString($e->getMessage());
        } 

        try
        {
            Converter::char('Example Data', 'char', 'char');
        }
        catch( Exception\LogicException $e )
        {
            $this->assertIsString($e->getMessage());
        } 
    }

    public function testAccent()
    {
        $this->assertSame('Accent', Converter::accent('Åççeňt'));
    }

    public function testHighLight()
    {
        $this->assertStringContainsString('<code>', Converter::highLight('<?php echo 1;'));
    }

    public function testCharset()
    {
        $this->assertSame('ﾃ?ﾃｧﾃｧeﾅ?t', Converter::charset('Åççeňt', 'UTF-8', 'JIS'));
    }

    public function testToString()
    {
        $this->assertSame('Z N', Converter::toString(['Z', 'N']));
        $this->assertSame('ZN', Converter::toString('ZN'));
    }

    public function testToObjectRecursive()
    {
        $this->assertIsObject(Converter::toObjectRecursive(['a' => 'a', ['b', 'c' => 'c']]));
    }

    public function testToConstant()
    {
        $this->assertSame(PHP_VERSION, Converter::toConstant('phpVersion') );
        $this->assertSame(PHP_VERSION, Converter::toConstant('php', '', '_VERSION') );
        $this->assertSame(PHP_VERSION, Converter::toConstant('version', 'php_') );
        $this->assertSame(PHP_VERSION, Converter::toConstant('PHP_VERSION') );
        $this->assertSame(PHP_VERSION, Converter::toConstant('VERSION', 'PHP_') );
        $this->assertSame(PHP_VERSION, Converter::toConstant('VER', 'PHP_', 'SION') ); 
    }
}