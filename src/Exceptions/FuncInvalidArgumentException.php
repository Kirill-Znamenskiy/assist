<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Exceptions;

class FuncInvalidArgumentException extends \InvalidArgumentException {

    /**
     * @param callable $callback
     * @param string $callee
     * @param integer $parameterPosition
     * @throws static
     */
    static public function assert_callback($callback, $callee, $parameterPosition) {
        if (!is_callable($callback)) {

            if (!is_array($callback) && !is_string($callback)) {
                throw new static(
                    sprintf(
                        '%s() expected parameter %d to be a valid callback, no array, string, closure or functor given',
                        $callee,
                        $parameterPosition
                    )
                );
            }

            $type = gettype($callback);
            switch ($type) {

                case 'array':
                    $type = 'method';
                    $callback = array_values($callback);

                    $sep = '::';
                    if (is_object($callback[0])) {
                        $callback[0] = get_class($callback[0]);
                        $sep = '->';
                    }

                    $callback = join($callback, $sep);
                    break;

                default:
                    $type = 'function';
                    break;
            }

            throw new static(
                sprintf(
                    "%s() expects parameter %d to be a valid callback, %s '%s' not found or invalid %s name",
                    $callee,
                    $parameterPosition,
                    $type,
                    $callback,
                    $type
                )
            );
        }
    }

    static public function assert_collection($collection, $callee, $parameterPosition) {
        self::assert_collection_alike($collection, 'Traversable', $callee, $parameterPosition);
    }

    static public function assert_array_access($collection, $callee, $parameterPosition) {
        self::assert_collection_alike($collection, 'ArrayAccess', $callee, $parameterPosition);
    }

    static public function assert_method_name($methodName, $callee, $parameterPosition) {
        if (!is_string($methodName)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be string, %s given',
                    $callee,
                    $parameterPosition,
                    gettype($methodName)
                )
            );
        }
    }

    /**
     * @param string $propertyName
     * @param string $callee
     * @param integer $parameterPosition
     * @throws static
     */
    static public function assert_property_name($propertyName, $callee, $parameterPosition) {
        if (!is_string($propertyName) &&
            !is_integer($propertyName) &&
            !is_float($propertyName) &&
            !is_null($propertyName)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be a valid property name or array index, %s given',
                    $callee,
                    $parameterPosition,
                    gettype($propertyName)
                )
            );
        }
    }

    static public function assert_positive_integer($value, $callee, $parameterPosition) {
        if ((string)(int)$value !== (string)$value || $value < 0) {

            $type = gettype($value);
            $type = $type === 'integer' ? 'negative integer' : $type;

            throw new static(
                sprintf(
                    '%s() expects parameter %d to be positive integer, %s given',
                    $callee,
                    $parameterPosition,
                    $type
                )
            );
        }
    }

    /**
     * @param mixed $key
     * @param string $callee
     * @throws static
     */
    static public function assert_valid_array_key($key, $callee) {
        $keyTypes = ['NULL', 'string', 'integer', 'double', 'boolean'];

        $keyType = gettype($key);

        if (!in_array($keyType, $keyTypes, true)) {
            throw new static(
                sprintf(
                    '%s(): callback returned invalid array key of type "%s". Expected %4$s or %3$s',
                    $callee,
                    $keyType,
                    array_pop($keyTypes),
                    join(', ', $keyTypes)
                )
            );
        }
    }

    static public function assert_array_key_exists($collection, $key, $callee) {
        if (!isset($collection[$key])) {
            throw new static(
                sprintf(
                    '%s(): unknown key "%s"',
                    $callee,
                    $key
                )
            );
        }
    }

    /**
     * @param boolean $value
     * @param string $callee
     * @param integer $parameterPosition
     * @throws static
     */
    static public function assert_boolean($value, $callee, $parameterPosition) {
        if (!is_bool($value)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be boolean',
                    $callee,
                    $parameterPosition
                )
            );
        }
    }

    /**
     * @param boolean $value
     * @param string $callee
     * @param integer $parameterPosition
     * @throws static
     */
    static public function assert_integer($value, $callee, $parameterPosition) {
        if (!is_integer($value)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be integer',
                    $callee,
                    $parameterPosition
                )
            );
        }
    }

    /**
     * @param integer $value
     * @param integer $limit
     * @param string $callee
     * @param integer $parameterPosition
     * @throws static
     */
    static public function assert_integer_greater_than_or_equal($value, $limit, $callee, $parameterPosition) {
        if (!is_integer($value) || $value < $limit) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be an integer greater than or equal to %d',
                    $callee,
                    $parameterPosition,
                    $limit
                )
            );
        }
    }

    /**
     * @param integer $value
     * @param integer $limit
     * @param string $callee
     * @param integer $parameterPosition
     * @throws static
     */
    static public function assert_integer_less_than_or_equal($value, $limit, $callee, $parameterPosition) {
        if (!is_integer($value) || $value > $limit) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be an integer less than or equal to %d',
                    $callee,
                    $parameterPosition,
                    $limit
                )
            );
        }
    }

    static public function assert_resolvable_placeholder(array $args, $position) {
        if (count($args) === 0) {
            throw new static(
                sprintf('Cannot resolve parameter placeholder at position %d. Parameter stack is empty.', $position)
            );
        }
    }

    /**
     * @param $collection
     * @param string $className
     * @param string $callee
     * @param integer $parameterPosition
     * @throws static
     */
    static private function assert_collection_alike($collection, $className, $callee, $parameterPosition) {
        if (!(is_array($collection) OR ($collection instanceof $className))) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be array or instance of %s',
                    $callee,
                    $parameterPosition,
                    $className
                )
            );
        }
    }
}
