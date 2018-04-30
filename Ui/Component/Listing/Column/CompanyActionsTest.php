<?php

namespace Socoda\Company\Test\Unit\Ui\Component\Listing\Column;

use Socoda\Company\Ui\Component\Listing\Column\CompanyActions;

class CompanyActionsTest extends \PHPUnit\Framework\TestCase
{
    protected $contextInterfaceMock;
    protected $uiComponentFactoryMock;
    protected $urlInterfaceMock;
    protected $companyActions;

    protected function setUp()
    {
        $this->contextInterfaceMock = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->uiComponentFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponentFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlInterfaceMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

            $this->companyActions = new CompanyActions(
                $this->contextInterfaceMock,
                $this->uiComponentFactoryMock,
                $this->urlInterfaceMock);
    }

    /**
     * @dataProvider providerTestPrepareDataSource
     */
    public function testPrepareDataSource($originalArray, $expectedArray)
    {
        $result = $this->companyActions->prepareDataSource($originalArray);

        $this->assertEquals($expectedArray, $result);
    }

    public function providerTestPrepareDataSource()
    {
        return array(
            // no if() enters
            array([],
                []),

            // enter first if() and no foreach()
            array(['data' => ['items' => []]],
                ['data' => ['items' => []]]),

            // enter first if() and foreach() and not second if()
            array(['data' => ['items' => ['firstItem', 'secondItem']]],
                ['data' => ['items' => ['firstItem', 'secondItem']]]),

            // enter second if()
            array(
                ['data' => [
                    'items' => [
                        [
                            'entity_id' => '999',
                            'name' => 'someCompany',
                        ],
                    ],
                ],
                ],
                ['data' => [
                    'items' => [
                        [
                            'entity_id' => '999',
                            'name' => 'someCompany',
                            '' => [
                                'edit' => [
                                    'href' => null,
                                    'label' => new \Magento\Framework\Phrase('Edit'),
                                ],
                                'delete' => [
                                    'href' => null,
                                    'label' => new \Magento\Framework\Phrase('Delete'),
                                    'confirm' =>[
                                        'title' => new \Magento\Framework\Phrase('Delete ${ $.$data.name }'),
                                        'message' => new \Magento\Framework\Phrase('Are you sure you want to delete ${ $.$data.name } ?'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                ]),
        );
    }
/*
sudo -uwww-data php /var/www/html/magento2/vendor/phpunit/phpunit/phpunit -c /var/www/html/magento2/dev/tests/unit/phpunit.xml.dist /var/www/html/magento2/app/code/Socoda/Company/Test/Unit/
*/
}
