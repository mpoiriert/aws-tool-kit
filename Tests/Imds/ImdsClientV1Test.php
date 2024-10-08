<?php

namespace Draw\Component\AwsToolKit\Tests\Imds;

use Draw\Component\AwsToolKit\Imds\ImdsClientV1;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(ImdsClientV1::class)]
class ImdsClientV1Test extends TestCase
{
    private ImdsClientV1 $imdsClientV1;

    private HttpClientInterface&MockObject $httpClient;

    protected function setUp(): void
    {
        $this->imdsClientV1 = new ImdsClientV1(
            $this->httpClient = $this->createMock(HttpClientInterface::class)
        );
    }

    public function testGetCurrentInstanceId(): void
    {
        $this->httpClient
            ->expects(static::once())
            ->method('request')
            ->with(
                'GET',
                'http://169.254.169.254/latest/meta-data/instance-id'
            )
            ->willReturn($response = $this->createMock(ResponseInterface::class))
        ;

        $response
            ->expects(static::once())
            ->method('getContent')
            ->with()
            ->willReturn($instanceId = uniqid('instance-id-'))
        ;

        static::assertSame(
            $instanceId,
            $this->imdsClientV1->getCurrentInstanceId()
        );
    }
}
