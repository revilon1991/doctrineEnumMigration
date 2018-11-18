<?php

declare(strict_types=1);

namespace DoctrineEnumMigration\Tools;

use DoctrineEnumMigration\Dto\EnumColumnDto;

class EnumComparator
{
    public function diffExist(EnumColumnDto $currentEnumColumnDto, EnumColumnDto $freshEnumColumnDto): bool
    {
        $firstValueList = $currentEnumColumnDto->valueList;
        $secondValueList = $freshEnumColumnDto->valueList;

        sort($firstValueList);
        sort($secondValueList);

        return $firstValueList !== $secondValueList;
    }
}
