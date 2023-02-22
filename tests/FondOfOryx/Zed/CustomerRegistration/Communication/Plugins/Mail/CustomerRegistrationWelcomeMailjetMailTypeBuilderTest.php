<?php

namespace FondOfOryx\Zed\CustomerRegistration\Communication\Plugins\Mail;

use Codeception\Test\Unit;
use FondOfOryx\Zed\CustomerRegistration\CustomerRegistrationConfig;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;

class CustomerRegistrationWelcomeMailjetMailTypeBuilderTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\MailTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $mailTransferMock;

    /**
     * @var \FondOfOryx\Zed\CustomerRegistration\CustomerRegistrationConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerTransferMock;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $localeTransferMock;

    /**
     * @var \FondOfOryx\Zed\CustomerRegistration\Communication\Plugins\Mail\CustomerRegistrationWelcomeMailjetMailTypeBuilder
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->mailTransferMock = $this->getMockBuilder(MailTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeTransferMock = $this->getMockBuilder(LocaleTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(CustomerRegistrationConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new CustomerRegistrationWelcomeMailjetMailTypeBuilder();
        $this->plugin->setConfig($this->configMock);
    }

    /**
     * @return void
     */
    public function testBuild(): void
    {
        $this->mailTransferMock->expects(static::atLeastOnce())
            ->method('getCustomer')
            ->willReturn($this->customerTransferMock);

        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('getLocale')
            ->willReturn($this->localeTransferMock);

        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('getFirstName')
            ->willReturn('John');

        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('getLastName')
            ->willReturn('Doe');

        $this->mailTransferMock->expects(static::atLeastOnce())
            ->method('getOneTimePasswordLoginLink')
            ->willReturn('ONE_TIME_PASSWORD_LINK');

        $this->localeTransferMock->expects(static::atLeastOnce())
            ->method('getLocaleName')
            ->willReturn('de_DE');

        $this->configMock->expects(static::atLeastOnce())
            ->method('getCustomerRegistrationWelcomeMailTemplateIdByLocale')
            ->with('de_DE')
            ->willReturn(123456);

        $this->mailTransferMock->expects(static::atLeastOnce())
            ->method('setMailjetTemplate')
            ->willReturnSelf();

        static::assertEquals($this->mailTransferMock, $this->plugin->build($this->mailTransferMock));
        static::assertEquals($this->mailTransferMock->getOneTimePasswordLoginLink(), 'ONE_TIME_PASSWORD_LINK');
    }
}
