<?php

namespace App\Enum;

enum VisitorEventStatusEnum: string
{
    /** Завершёно */
    case Done = 'done';

    /** В процессе */
    case InProcess = 'in_process';

    /** Новое */
    case New = 'new';

    /** Повтор в цепи */
    case Repeat = 'repeat';

    /** Прыжок на другое сообщение */
    case Jumped = 'jumped';

    /** Прыжок на другое сообщение в цепи */
    case JumpedToChain = 'jumped_to_chain';

    /** Ожидание - сигнализирует нам что сейчас выполняется цепочка, ожидающая следующее действие пользователя */
    case Waiting = 'waiting';

    /** Я упал */
    case Failed = 'failed';
}
