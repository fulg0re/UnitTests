<?php

namespace Socoda\Company\Test\Unit\Controller\Adminhtml\Company;

use Socoda\Company\Controller\Adminhtml\Company\Save;

class SaveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Backend\App\Action\Context
     */
    protected $contextMock;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactoryMock;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactoryMock;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistryMock;

    /**
     * @var \Socoda\Company\Api\CompanyRepositoryInterface
     */
    protected $companyRepositoryMock;

    /**
     * @var \Socoda\Company\Api\Data\CompanyInterfaceFactory
     */
    protected $companyFactoryMock;

    /**
     * @var \Socoda\Company\Controller\Adminhtml\Company\Save
     */
    protected $saveControllerMock;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactoryMock;

    /**
     * @var \Magento\Framework\Controller\Result\Redirect
     */
    protected $resultRedirectMock;

    /**
     * @var \Magento\Framework\App\RequestInterfaceFactory
     */
    protected $getRequestMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterfaceFactory
     */
    protected $messageManagerMock;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManagerMock;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $sessionMock;

    /**
     * @var \Socoda\Company\Api\CompanyPostDataHandlerInterface
     */
    protected $firstPostDataHandlerMock;

    /**
     * @var \Socoda\Company\Api\CompanyPostDataHandlerInterface
     */
    protected $secondPostDataHandlerMock;
   
    protected function makeMockObject(array $postDataHandlers)
    {
        $this->saveControllerMock = $this->getMockBuilder(\Socoda\Company\Controller\Adminhtml\Company\Save::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->resultPageFactoryMock,
                $this->resultForwardFactoryMock,
                $this->coreRegistryMock,
                $this->companyRepositoryMock,
                $this->companyFactoryMock,
                $postDataHandlers])
            ->setMethods(['getRequest'])
            ->getMock();
    }

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

        $this->contextMock->expects($this->once())
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

        $this->firstPostDataHandlerMock = $this->getMockBuilder(\Socoda\Company\Api\CompanyPostDataHandlerInterface::class)
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->secondPostDataHandlerMock = $this->getMockBuilder(\Socoda\Company\Api\CompanyPostDataHandlerInterface::class)
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMock();

    }
    
    public function testWithNoData()
    {

        $this->makeMockObject([]);

        $this->saveControllerMock->expects($this->exactly(2))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn(null);

        $this->getRequestMock->expects($this->once())
            ->method('getParam')
            ->with('back', false)
            ->willReturn('back');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->saveControllerMock->execute());
    }

    public function testWithDataExistsAndNoSaveDataAndThrowException()
    {
        $redirectBack = 3;
        $identifier = false;
        $storeId = 1;
        $returnParams = [
            'id' => 1
        ];

        $this->makeMockObject([]);

        $this->saveControllerMock->expects($this->exactly(4))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->at(1))
            ->method('getParam')
            ->with('back', false)
            ->willReturn($redirectBack);
        $this->getRequestMock->expects($this->at(2))
            ->method('getParam')
            ->with('id')
            ->willReturn($identifier);
        $this->getRequestMock->expects($this->at(3))
            ->method('getParam')
            ->with('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            ->willReturn($storeId);
        $this->getRequestMock->expects($this->exactly(3))
            ->method('getParam');

        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

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
    }

    public function testWithIdentifierExistsAndNoMadelGetId()
    {
        $redirectBack = 3;
        $identifier = 1;
        $storeId = 1;

        $this->makeMockObject([]);

        $this->saveControllerMock->expects($this->exactly(4))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturnSelf();

        $this->getRequestMock->expects($this->at(1))
            ->method('getParam')
            ->with('back', false)
            ->willReturn($redirectBack);
        $this->getRequestMock->expects($this->at(2))
            ->method('getParam')
            ->with('id')
            ->willReturn($identifier);
        $this->getRequestMock->expects($this->at(3))
            ->method('getParam')
            ->with('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            ->willReturn($storeId);
        $this->getRequestMock->expects($this->exactly(3))
            ->method('getParam');

        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

        $this->companyRepositoryMock->expects($this->once())
            ->method('get')
            ->with($identifier)
            ->willReturn($this->companyMock);

        $this->companyMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->with(new \Magento\Framework\Phrase('This company no longer exists.'));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->saveControllerMock->execute());
    }

    public function testTryAndNoException()
    {
        $redirectBack = false;
        $identifier = null;
        $storeId = 1;
        $modelGetName = ['Some model name'];

        $this->makeMockObject([]);

        $this->saveControllerMock->expects($this->exactly(4))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturnSelf();

        $this->getRequestMock->expects($this->at(1))
            ->method('getParam')
            ->with('back', false)
            ->willReturn($redirectBack);
        $this->getRequestMock->expects($this->at(2))
            ->method('getParam')
            ->with('id')
            ->willReturn($identifier);
        $this->getRequestMock->expects($this->at(3))
            ->method('getParam')
            ->with('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            ->willReturn($storeId);
        $this->getRequestMock->expects($this->exactly(3))
            ->method('getParam');

        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

        $this->firstPostDataHandlerMock->expects($this->never())
            ->method('getData');

        $this->secondPostDataHandlerMock->expects($this->never())
            ->method('getData');

        $this->companyMock->expects($this->once())
            ->method('setData')
            ->with($this->getRequestMock);

        $this->companyMock->expects($this->once())
            ->method('setStoreId')
            ->with($storeId);

        $this->companyRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->companyMock);

        $this->companyMock->expects($this->once())
            ->method('getName')
            ->willReturn($modelGetName);

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(new \Magento\Framework\Phrase('You saved the company %1.', $modelGetName));

        $this->objectManagerMock->expects($this->once())
            ->method('get')
            ->with('Magento\Backend\Model\Session')
            ->willReturn($this->sessionMock);

        $this->sessionMock->expects($this->once())
            ->method('setFormData')
            ->with(false);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->saveControllerMock->execute());
    }

    public function testForeachAndRedirectBackExistsAndStoreIdNotNull()
    {
        $redirectBack = 'back';
        $identifier = null;
        $storeId = 1;
        $modelGetName = ['Some model name'];
        $redirectParams = [
            'id' => 999,
            'store' => 1
        ];

        $this->makeMockObject([
            $this->firstPostDataHandlerMock,
            $this->secondPostDataHandlerMock]);

        $this->saveControllerMock->expects($this->exactly(4))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturnSelf();

        $this->getRequestMock->expects($this->at(1))
            ->method('getParam')
            ->with('back', false)
            ->willReturn($redirectBack);
        $this->getRequestMock->expects($this->at(2))
            ->method('getParam')
            ->with('id')
            ->willReturn($identifier);
        $this->getRequestMock->expects($this->at(3))
            ->method('getParam')
            ->with('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            ->willReturn($storeId);
        $this->getRequestMock->expects($this->exactly(3))
            ->method('getParam');

        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

        $this->firstPostDataHandlerMock->expects($this->once())
            ->method('getData')
            ->willReturn($this->getRequestMock);
        $this->secondPostDataHandlerMock->expects($this->once())
            ->method('getData')
            ->willReturn($this->getRequestMock);

        $this->companyMock->expects($this->once())
            ->method('setData')
            ->with($this->getRequestMock);

        $this->companyMock->expects($this->once())
            ->method('setStoreId')
            ->with($storeId);

        $this->companyRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->companyMock);

        $this->companyMock->expects($this->once())
            ->method('getName')
            ->willReturn($modelGetName);

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(new \Magento\Framework\Phrase('You saved the company %1.', $modelGetName));

        $this->objectManagerMock->expects($this->once())
            ->method('get')
            ->with('Magento\Backend\Model\Session')
            ->willReturn($this->sessionMock);

        $this->sessionMock->expects($this->once())
            ->method('setFormData')
            ->with(false);

        $this->companyMock->expects($this->once())
            ->method('getId')
            ->willReturn(999);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', $redirectParams)
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->saveControllerMock->execute());
    }
}
