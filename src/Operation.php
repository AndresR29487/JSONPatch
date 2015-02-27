<?php

namespace gamringer\JSONPatch;

abstract class Operation
{

    const OP_TEST = 'test';
    const OP_ADD = 'add';
    const OP_REMOVE = 'remove';
    const OP_REPLACE = 'replace';
    const OP_MOVE = 'move';
    const OP_COPY = 'copy';

    protected $path;

    public static function fromDecodedJSON($operationContent)
    {
        static::assertValidOperationContent($operationContent);

        switch ($operationContent->op) {
            case static::OP_TEST:
                return Operation\Test::fromDecodedJSON($operationContent);
            case static::OP_ADD:
                return Operation\Add::fromDecodedJSON($operationContent);
            case static::OP_REMOVE:
                return Operation\Remove::fromDecodedJSON($operationContent);
            case static::OP_REPLACE:
                return Operation\Replace::fromDecodedJSON($operationContent);
            case static::OP_MOVE:
                return Operation\Move::fromDecodedJSON($operationContent);
            case static::OP_COPY:
                return Operation\Copy::fromDecodedJSON($operationContent);
        }
    }

    public function getPath()
    {
        return $this->path;
    }

    private static function assertValidOperationContent($operationContent)
    {
        if (!($operationContent instanceof \stdClass)) {
            throw new Operation\Exception('Operation Content is not an object');
        }

        if (!isset($operationContent->op)) {
            throw new Operation\Exception('All Operations must contain exactly one "op" member');
        }

        $possibleOperations = [OP_TEST, OP_ADD, OP_REMOVE, OP_REPLACE, OP_MOVE, OP_COPY];
        if (!in_array($operationContent->op, $possibleOperations)) {
            throw new Operation\Exception('Operation must be one of "'.implode('", "', $possibleOperations).'"');
        }
    }
}
