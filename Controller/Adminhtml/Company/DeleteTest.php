<?php

namespace Socoda\Company\Test\Unit\Controller\Adminhtml\Company;

use Socoda\Company\Controller\Adminhtml\Company\Delete;

class DeleteTest extends \PHPUnit\Framework\TestCase
{
    protected $contextMock;

    protected $pageFactoryMock;

    protected $forwardFactoryMock;

    protected $registryMock;

    protected $companyRepositoryMock;

    protected $companyFactoryMock;

    protected $companyMock;

    protected $resultRedirectFactoryMock;

    protected $getRequestMock;

    protected $deleteController;

    protected $deleteControllerMock;

    protected $companyGetName = 'Some model name';

    protected function setUp()
    {

        $this->contextMock = $this->getMockBuilder(\Magento\Backend\App\Action\Context::class)
            ->setMethods(['getResultRedirectFactory', 'getMessageManager'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\RedirectFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageManagerMock = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterfaceFuctory::class)
            ->setMethods(['addError', 'addSuccess'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->once())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->contextMock->expects($this->once())
            ->method('getMessageManager')
            ->willReturn($this->messageManagerMock);

        $this->resultRedirectMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\Redirect::class)
            ->setMethods(['setPath'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->pageFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Result\PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $this->forwardFactoryMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\ForwardFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyRepositoryMock = $this->getMockBuilder(\Socoda\Company\Api\CompanyRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'save', 'delete', 'deleteById'])
            ->getMock();

        $this->companyFactoryMock = $this->getMockBuilder(\Socoda\Company\Api\Data\CompanyInterfaceFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->companyMock = $this->getMockBuilder(\Socoda\Company\Api\Data\CompanyInterface::class)
            ->setMethods(['getId', 'setId', 'getName', 'setName'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->getRequestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterfaceFactory::class)
            ->setMethods(['getParam'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->deleteControllerMock = $this->getMockBuilder(\Socoda\Company\Controller\Adminhtml\Company\Delete::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->pageFactoryMock,
                $this->forwardFactoryMock,
                $this->registryMock,
                $this->companyRepositoryMock,
                $this->companyFactoryMock])
            ->setMethods(['getRequest'])
            ->getMock();
    }

    // enters first if($identifier) and enters second if(!$model->getId())
    public function testDeleteActionNoModelGetId()
    {

        $this->deleteControllerMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getParam')
            ->with('id', false)
            ->willReturn(1);

        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

        $this->companyRepositoryMock->expects($this->once())
            ->method('get')
            ->with(1)
            ->willReturn($this->companyMock);

        $this->companyMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);     //null or '1'

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->with(__('This company no longer exists.'));

        $this->messageManagerMock->expects($this->never())
            ->method('addSuccess');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/index')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->deleteControllerMock->execute());

    }

    // enters first if($identifier) and enters second if(!$model->getId())
    public function testDeleteActionModelGetIdExistsAndTry()
    {

        $this->deleteControllerMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->once())
            ->method('getParam')
            ->with('id', false)
            ->willReturn(1);        // 1 or false

        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

        $this->companyRepositoryMock->expects($this->once())
            ->method('get')
            ->with(1)
            ->willReturn($this->companyMock);

        $this->companyMock->expects($this->once())
            ->method('getId')
            ->willReturn('1');     //'1' or null

        $this->companyRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->companyMock)
            ->willReturn(true);

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccess')
            ->with(__('You deleted the company %1.', $this->companyGetName));

        $this->companyMock->expects($this->once())
            ->method('getName')
            ->willReturn($this->companyGetName);     //'1' or null

        $this->messageManagerMock->expects($this->never())
            ->method('addError');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/index')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->deleteControllerMock->execute());

    }

    // enters first if($identifier) and enters second if(!$model->getId())
    public function testDeleteActionModelGetIdExistsAndThrowsException()
    {
        $errorMsg = 'Can\'t delete company';

        $this->deleteControllerMock->expects($this->exactly(2))
            ->method('getRequest')
            ->willReturn($this->getRequestMock);

        $this->getRequestMock->expects($this->at(0))
                  ->method('getParam')
                  ->with('id', false)
                  ->willReturn(1);
        $this->getRequestMock->expects($this->at(1))
                  ->method('getParam')
                  ->with('id')
                  ->willReturn(1);
        $this->getRequestMock->expects($this->exactly(2))
            ->method('getParam');


        $this->companyFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->companyMock);

        $this->companyRepositoryMock->expects($this->once())
            ->method('get')
            ->with(1)
            ->willReturn($this->companyMock);

        $this->companyMock->expects($this->once())
            ->method('getId')
            ->willReturn('1');

        $this->companyRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->companyMock)
            ->willThrowException(new \Exception(__($errorMsg)));

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->with($errorMsg);

        $this->messageManagerMock->expects($this->never())
            ->method('addSuccess');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->deleteControllerMock->execute());
    }
}
