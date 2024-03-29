<?php
declare(strict_types=1);

namespace Database\Autowired;

use Autowired\Autowired;
use Autowired\Handler\InterfaceHandler;
use Database\Attributes\Query;
use Database\CrudRepositoryInterface;
use Database\FunctionSignature;
use ReflectionClass;
use ReflectionMethod;

class AutowiredHandler implements InterfaceHandler
{
    private array $scalarTypes = [
        'int',
        'string',
        'array',
        'float',
        'double',
        'null'
    ];

    private array $reservedTypes = [
        "array",
        "string",
        "int",
        "bool",
        "float",
        "object",
        "stdClass",
    ];

    public function autowire(Autowired $autowiredAttribute, ReflectionClass $typed): string
    {
        $ds = DIRECTORY_SEPARATOR;

        if (in_array(CrudRepositoryInterface::class, $typed->getInterfaceNames())) {
            $skeleton = file_get_contents(realpath(__DIR__ . $ds . '..' . $ds .'Tmp' . $ds . 'CrudSkeleton.txt'));
        } else {
            $skeleton = file_get_contents(realpath(__DIR__ . $ds . '..' . $ds .'Tmp' . $ds . 'BaseSkeleton.txt'));
        }

        $className = 'Database\\Tmp\\%sRepository';

        $use = [$typed->getName()];

        $name = $typed->getName();

        $classNameExploded = explode('\\', $name);
        $interfaceName = end($classNameExploded);
        $hash = substr(
            md5($interfaceName),
            0,
            8
        );

        $className = sprintf($className, $hash);
        $filePath = __DIR__ . $ds . '..' .$ds. 'Tmp' . $ds . $hash .'Repository.php';

        if (file_exists($filePath)) {
            return $className;
        }

        $bodyContent = $this->renderBodyContent($typed, $use);
        $uses = '';
        foreach ($use as $u) {
            $uses .= 'use ' . $u . ';' . PHP_EOL;
        }

        $classBody = str_replace(
            [
                '__USE__',
                '__HASH__',
                '__INTERFACE__',
                '__BODY__',
            ],
            [
                $uses,
                $hash,
                $interfaceName,
                $bodyContent,
            ],
            $skeleton
        );

        file_put_contents(__DIR__ . $ds . '..' .$ds. 'Tmp' . $ds . $hash .'Repository.php', $classBody);

        return $className;
    }

    private function renderBodyContent(ReflectionClass $interfaceReflection, array &$use): string
    {
        $body = '';

        foreach ($interfaceReflection->getMethods() as $method) {
            $queryAttribute = $method->getAttributes(Query::class)[0] ?? null;

            if ($queryAttribute !== null) {
                $returnType = $method->getReturnType()->getName();
                if (!in_array($returnType, $use, true)) {
                    $use[] = $returnType;
                }
                [$parameters, $parameterVariable, $use] = $this->resolveParameters($method, $use);

                $returnNameExploded = explode('\\', $returnType);

                /** @var Query $query */
                $query = $queryAttribute->newInstance();

                $functionSignature = new FunctionSignature(
                    $method->getName(),
                    ($method->getReturnType()->allowsNull() ? '?' : '') . end($returnNameExploded),
                    $query->getQuery(),
                    mb_substr($parameters, 0, -1),
                    mb_substr($parameterVariable, 0, -1)
                );

                $body .= $this->getFunctionTemplate($functionSignature);
            }
        }

        return $body;
    }

    private function resolveParameters(ReflectionMethod $method, array $use): array
    {
        $parameters = '';
        $parameterVariable = '';
        foreach ($method->getParameters() as $parameter) {
            $parameterKey = $parameter->getType();
            if (!in_array($parameter->getType(), $this->reservedTypes, true)) {
                $name = $parameter->getType()->getName();

                if (!in_array($name, $use, true) && !in_array($name, $this->scalarTypes, true)) {
                    $use[] = $name;
                }
                $parameterObject = explode('\\', $parameter->getType()->getName());
                $parameterKey = end($parameterObject);
            }
            $parameters .= sprintf(
                '%s $%s,',
                $parameterKey,
                $parameter->getName()
            );

            $parameterVariable .= '\'' . lcfirst($parameter->getName()) . '\' => $' . $parameter->getName() . ',';
        }

        return [$parameters, $parameterVariable, $use];
    }

    private function getFunctionTemplate(FunctionSignature $functionSignature): string
    {

        if (str_contains('Collection', $functionSignature->getReturnParam())) {

            return sprintf('        
        public function %2$s(%3$s): %4$s {return $this->handleQueryMultipleEntities(\'%1$s\', [' . $functionSignature->getParameterVariable() . ']);}',
                    $functionSignature->getQueryValue(),
                    $functionSignature->getMethodName(),
                    $functionSignature->getParameters(),
                    $functionSignature->getReturnParam()
                )
                . PHP_EOL;
        }

        return sprintf('        
        public function %2$s(%3$s): %4$s {return $this->handleQuerySingleEntity(\'%1$s\', [' . $functionSignature->getParameterVariable() . ']);}',
                $functionSignature->getQueryValue(),
                $functionSignature->getMethodName(),
                $functionSignature->getParameters(),
                $functionSignature->getReturnParam()
            )
            . PHP_EOL;
    }
}
