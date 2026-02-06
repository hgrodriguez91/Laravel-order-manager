<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Application\Order\DTOs\CreateOrderDTO;
use App\Application\Order\UseCases\CreateOrderUseCase;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;

class CreateOrderController extends Controller
{
     public function __invoke(
        CreateOrderRequest $request,
        CreateOrderUseCase $useCase
    ) {
        $dto = new CreateOrderDTO(
            $request->customer_id,
            $request->items
        );

        $order = $useCase->execute($dto);

        return new OrderResource($order);
    }
}
