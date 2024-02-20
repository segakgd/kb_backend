<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\Core\Telegram\Request\Message\MessageDto;

class ShopProductsCategoryChain
{
    public function handle(string $action, MessageDto $messageDto, ?string $content = null): void
    {
        if ($action === 'show') {
            $this->show($messageDto);
        }

        if ($action === 'save') {
            $this->save($messageDto, $content);
        }
    }

    private function show(MessageDto $messageDto): void
    {
        $replyMarkups = [
            [
                [
                    'text' => 'магнитолы'
                ],
                [
                    'text' => 'динамики'
                ],
            ],
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];

        $messageDto->setText('Отлично, 😜 выберите одну из категорий 🤘');
        $messageDto->setReplyMarkup($replyMarkups);
    }

    private function save(MessageDto $messageDto, string $content): void
    {
        // todo типа сохраняем $content

        $replyMarkups = [
            [
                [
                    'text' => 'предыдущий'
                ],
                [
                    'text' => 'подробнее о товаре'
                ],
                [
                    'text' => 'следубщий'
                ],
            ],
            [
                [
                    'text' => 'добавить в корзину'
                ],
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];

        $messageDto->setText('Вы выбрали ' . $content . ' отличный выбор, давайте подробнее рассмотрим');
        $messageDto->setReplyMarkup($replyMarkups);
    }
}
