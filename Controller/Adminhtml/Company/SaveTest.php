<?php

namespace Socoda\Company\Test\Unit\Controller\Adminhtml\Company;

use Socoda\Company\Controller\Adminhtml\Company\Save;

class SaveTest extends \PHPUnit\Framework\TestCase
{

    protected $contextMock;

    protected $resultPageFactoryMock;

    protected $resultForwardFactoryMock;

    protected $coreRegistryMock;

    protected $companyRepositoryMock;

    protected $companyFactoryMock;

    protected $saveControllerMock;

    protected $resultRedirectFactoryMock;

    protected $resultRedirectMock;

    protected $getRequestMock;

    protected $messageManagerMock;

    protected $objectManagerMock;

    protected $sessionMock;

    /*
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Socoda\Company\Api\CompanyRepositoryInterface $companyRepository,
        \Socoda\Company\Api\Data\CompanyInterfaceFactory $companyFactory,
        array $postDataHandlers = []
    ) {
        parent::__construct($context, $resultPageFactory, $resultForwardFactory, $coreRegistry, $companyRepository, $companyFactory);
        $this->postDataHandlers = $postDataHandlers;
    }
    */

    protected function setUp()
    {
        $this->contextMock = $this->getMockBuilder(\Magento\Backend\App\Action\Context::class)
            ->setMethods([
                'getResultRedirectFactory',
                'getMessageManager',
                'getObjectManager'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageManagerMock = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterfaceFactory::class)
            ->setMethods(['addError', 'addSuccessMessage', 'addErrorMessage'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\RedirectFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->once())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->contextMock->expects($this->once())
            ->method('getMessageManager')
            ->willReturn($this->messageManagerMock);

        $this->objectManagerMock = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
            ->setMethods(['get', 'create', 'configure'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->once())          //****
            ->method('getObjectManager')
            ->willReturn($this->objectManagerMock);

        $this->resultRedirectMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\Redirect::class)
            ->setMethods(['setPath'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->resultPageFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Result\PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultForwardFactoryMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\ForwardFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->coreRegistryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyRepositoryMock = $this->getMockBuilder(\Socoda\Company\Api\CompanyRepositoryInterface::class)
            ->setMethods(['save', 'get', 'delete', 'deleteById'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyFactoryMock = $this->getMockBuilder(\Socoda\Company\Api\Data\CompanyInterfaceFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyMock = $this->getMockBuilder(\Socoda\Company\Api\Data\CompanyInterface::class)
            ->setMethods(['setData', 'setStoreId', 'getId', 'setId', 'getName', 'setName'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->getRequestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterfaceFactory::class)
            ->setMethods(['getParam', 'getPostValue'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionMock = $this->getMockBuilder(\Magento\Backend\Model\Session::class)
            ->setMethods(['setFormData'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->saveControllerMock = $this->getMockBuilder(\Socoda\Company\Controller\Adminhtml\Company\Save::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->resultPageFactoryMock,
                $this->resultForwardFactoryMock,
                $this->coreRegistryMock,
                $this->companyRepositoryMock,
                $this->companyFactoryMock])
            ->setMethods(['getRequest'])
            ->getMock();

    }

    public function testWithNoData()
    {

        $this->saveControllerMock->expects($this->exactly(2))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn(null);    //->willReturnSelf() or null;

        $this->getRequestMock->expects($this->once())
            ->method('getParam')
            ->with('back', false)
            ->willReturn('back');        // 'back' or false

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->saveControllerMock->execute());
        //$this->saveControllerMock->execute();
    }

    public function testWithDataExistsAndNoSaveData()
    {
        $redirectBack = 3;
        $identifier = false;
        $storeId = 1;
        $returnParams = [
            'id' => 1
        ];

        $this->saveControllerMock->expects($this->exactly(4))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn($this->getRequestMock);            // $data
                                                            // ->willReturnSelf() or null;

        $this->getRequestMock->expects($this->at(1))
                  ->method('getParam')
                  ->with('back', false)                     // $redirectBack = 3;
                  ->willReturn($redirectBack);
        $this->getRequestMock->expects($this->at(2))
                  ->method('getParam')
                  ->with('id')                              // $identifier
                  ->willReturn($identifier);
        $this->getRequestMock->expects($this->at(3))
                  ->method('getParam')                      // $storeId
                                                                    // DEFAULT_STORE_ID = 0
                  ->with('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
                  ->willReturn($storeId);
        $this->getRequestMock->expects($this->exactly(3))
            ->method('getParam');

        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

        // FOREACH();

        $this->companyMock->expects($this->once())
            ->method('setData')
            ->with($this->getRequestMock);

        $this->companyMock->expects($this->once())
            ->method('setStoreId')
            ->with($storeId);

        $this->companyRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->companyMock)
            ->willThrowException(new \Exception('Error message.'));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage');

        $this->objectManagerMock->expects($this->once())
            ->method('get')
            ->with('Magento\Backend\Model\Session')
            ->willReturn($this->sessionMock);

        $this->sessionMock->expects($this->once())
            ->method('setFormData')
            ->with($this->getRequestMock);

        $this->companyMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', $returnParams)
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->saveControllerMock->execute());
        //$this->saveControllerMock->execute();
    }

/*
sudo -uwww-data php /var/www/html/magento2/vendor/phpunit/phpunit/phpunit -c /var/www/html/magento2/dev/tests/unit/phpunit.xml.dist /var/www/html/magento2/app/code/Socoda/Company/Test/Unit/Controller/Adminhtml/Company/SaveTest.php
*/
}
