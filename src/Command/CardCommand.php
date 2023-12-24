<?php

namespace App\Command;

use App\Dto\Core\Telegram\Invoice\InvoiceDto;
use App\Dto\deprecated\Ecommerce\ProductDto;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\Visitor\Card\CardServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'tg:product',
    description: '',
)]
class CardCommand extends Command
{
    public function __construct(
        private TelegramService $telegramService,
        private CardServiceInterface $cardService,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $replyMarkup = [
            [
                [
                    'text' => 'go go go',
                    'pay' => true
                ],
                [
                    'text' => 'Что-то',
                    'callback_data'=>'{"action":"like","count":0,"text":":like:"}'
                ],
                [
                    'text' => 'Что-то',
                    'callback_data'=>'{"action":"like","count":0,"text":":like:"}'
                ],
                [
                    'text' => 'Что-то',
                    'callback_data'=>'{"action":"like","count":0,"text":":like:"}'
                ],
            ],
            [
                [
                    'text' => 'Что-то',
                    'callback_data'=>'{"action":"like","count":0,"text":":like:"}'
                ],
                [
                    'text' => 'Что-то',
                    'callback_data'=>'{"action":"like","count":0,"text":":like:"}'
                ],
                [
                    'text' => 'Что-то',
                    'callback_data'=>'{"action":"like","count":0,"text":":like:"}'
                ],
            ]
        ];

        $chatId = 873817360;

        $products = [new ProductDto()];
        $product = null;

        /** @var ProductDto $product */
        if ($products[0] instanceof ProductDto){
            $product = $products[0];
        }

        $invoiceDto = (new InvoiceDto())
            ->setChatId($chatId)
            ->setTitle($product?->getName() ?? 'bla bla bla')
            ->setDescription('его тут пока что нет')
            ->setPayload("200")
            ->setProviderToken("381764678:TEST:60367")
            ->setCurrency("RUB")
            ->setPrices( json_encode([
                [
                    'label' => 'first',
                    'amount' => $product?->getPrice()->getValue(),
                ] ])
            )
            ->setPhotoUrl($product?->getImage() ?? '')
            ->setReplyMarkup($replyMarkup)
        ;

        $this->cardService->recalculate(); // ...


//        dd($invoiceDto->getArray());

        $this->telegramService->sendInvoice($invoiceDto, '5109953245:AAE7TIhplLRxJdGmM27YSeSIdJdOh4ZXVVY');

        return Command::SUCCESS;
    }
}
