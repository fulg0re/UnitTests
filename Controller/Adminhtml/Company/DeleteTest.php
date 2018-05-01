<?php

namespace Socoda\Company\Test\Unit\Controller\Adminhtml\Company;

use Socoda\Company\Controller\Adminhtml\Company\Delete;

class DeleteTest extends \PHPUnit\Framework\TestCase
{
    protected $contextMock;

    protected $pageFactoryMock;

    protected $forwardFactoryMock;

    protected $registryMock;

    protected $companyRepositoryInterfaceMock;

    protected $companyInterfaceFactoryMock;

    protected $companyInterfaceMock;

    protected $deleteController;

    protected function setUp()
    {
        /*
            public function __construct(
                \Magento\Backend\App\Action\Context $context,
                \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
                \Magento\Framework\Registry $coreRegistry,
                \Socoda\Company\Api\CompanyRepositoryInterface $companyRepository,
                \Socoda\Company\Api\Data\CompanyInterfaceFactory $companyFactory
        */


        /**
         *  $this->resultRedirectFactory->create();
         *                              ->setPath();
         *  $this->getRequest->getParam('id', false);
         */
        $this->contextMock = $this->getMockBuilder(\Magento\Backend\App\Action\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $this->pageFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Result\PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $this->forwardFactoryMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\ForwardFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyRepositoryInterfaceMock = $this->getMockBuilder(\Socoda\Company\Api\CompanyRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();



        $this->companyInterfaceFactoryMock = $this->getMockBuilder(\Socoda\Company\Api\Data\CompanyInterfaceFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->companyInterfaceMock = $this->getMockBuilder(\Socoda\Company\Api\Data\CompanyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->deleteController = new \Socoda\Company\Controller\Adminhtml\Company\Delete(
            $this->contextMock,
            $this->pageFactoryMock,
            $this->forwardFactoryMock,
            $this->registryMock,
            $this->companyRepositoryInterfaceMock,
            $this->companyInterfaceFactoryMock);
    }

    public function testDeleteAction()
    {
        $this->companyInterfaceFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->companyInterfaceMock);

        //$this->deleteController->execute();

//var_dump($this->companyInterfaceMock);
//var_dump('_____________________________________________________________________');
//var_dump($this->deleteController);
//var_dump('_____________________________________________________________________');
//var_dump(get_class_methods($this->deleteController));


        //$this->assertSame($this->companyInterfaceMock, $this->deleteController->execute());

    }

/*
sudo -uwww-data php /var/www/html/magento2/vendor/phpunit/phpunit/phpunit -c /var/www/html/magento2/dev/tests/unit/phpunit.xml.dist /var/www/html/magento2/app/code/Socoda/Company/Test/Unit/
*/
}
