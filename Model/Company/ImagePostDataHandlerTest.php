<?php

namespace Socoda\Company\Test\Unit\Model\Company;

use Socoda\Company\Model\Company\ImagePostDataHandler;

class ImagePostDataHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $imagePostDataHandler;

    protected $companyInterfaceMock;

    protected function setUp()
    {
        $this->companyInterfaceMock = $this->getMockBuilder(\Socoda\Company\Api\Data\CompanyInterface::class)
            ->getMock();

        $this->imagePostDataHandler = new ImagePostDataHandler();
    }

    /**
     * @dataProvider providerTestGetData
     */
    public function testGetData($originalArray, $expectedArray)
    {
        $result = $this->imagePostDataHandler->getData(
            $this->companyInterfaceMock,
            $originalArray);

        $this->assertEquals($expectedArray, $result);
    }

    public function providerTestGetData()
    {
        return array(
            // if (empty($data['image']))
            array(['image' => []],
                ['image' => null]),
            array(['image' => 0],
                ['image' => null]),

            // when enter no if's
            array(['image' => 'some string'],
                ['image' => 'some string']),
            array(['image' => 666],
                ['image' => 666]),
            array(['image' => true],
                ['image' => true]),

            // if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name']))
            array(['image' => [['name' => 'Fred', 'tmp_name' => 'Fredy']]],
                ['image' => 'Fred']),

            // else condation
            array(['image' => [['name' => 'Fred']]],
                []),
        );
    }

/*
sudo -uwww-data php /var/www/html/magento2/vendor/phpunit/phpunit/phpunit -c /var/www/html/magento2/dev/tests/unit/phpunit.xml.dist /var/www/html/magento2/app/code/Socoda/Company/Test/Unit/
*/

}
