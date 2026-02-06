<?php

namespace Tests\Unit\Application\Order;

use Tests\TestCase;
use App\Application\Order\UseCases\CreateOrderUseCase;
use App\Application\Order\DTOs\CreateOrderDTO;
use App\Domain\Order\Repositories\OrderRepository;
use Mockery;
use App\Domain\Order\ValueObjects\Money;

class CreateOrderUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close(); // 🔥 importante para que Mockery verifique expectativas
    }

    /** @test */
    public function it_creates_and_saves_an_order()
    {
        $repo = Mockery::mock(OrderRepository::class);
        $repo->shouldReceive('save')->once();

        $useCase = new CreateOrderUseCase($repo);

        $dto = new CreateOrderDTO(
            customerId: 1,
            items: [100, 50]
        );

        $order = $useCase->execute($dto);

        $this->assertTrue($order->isConfirmed());
        $this->assertEquals(150, $order->total()->value());
    }
}
