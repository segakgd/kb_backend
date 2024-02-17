<?php

namespace App\Command;

use App\Converter\ScenarioConverter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'kb:scenario:convert',
    description: 'Convert user setting',
)]
class ConverterSettingCommand extends Command
{
    private const USER_SETTING = [
        [
            'name' => '/command1',
            'type' => 'command',
            'content' => [
                'message' => 'Что делаешь?',
                'replyMarkup' => [
                    [
                        [
                            'text' => 'Ничего'
                        ],
                        [
                            'text' => 'Что-то'
                        ],
                    ]
                ]
            ],
            'actionAfter' => [
                [
                    'type' => 'message',
                    'value' => [
                        'text' => 'Дополнительное сообщение'
                    ],
                ],
                [
                    'type' => 'message',
                    'value' => [
                        'text' => 'Дополнительное сообщение'
                    ],
                ],
            ],
            'sub' => [
                [
                    'name' => 'Хорошо',
                    'type' => 'message',
                    'content' => [
                        'message' => 'Хорошо что всё хорошо',
                    ],
                ],
                [
                    'name' => 'Плохо',
                    'type' => 'message',
                    'content' => [
                        'message' => 'Плохо что всё плохо',
                    ],
                ],
            ]
        ],
    ];

    public function __construct(
        private readonly ScenarioConverter $settingConverter,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->settingConverter->convert(self::USER_SETTING, 4842);

        } catch (Throwable $throwable){
            $io->error($throwable->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
