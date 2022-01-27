<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Traits;

trait ClassGetSetTrait {



    /**
     * @param string $name
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function get($name, $args = null, $options = null) {
        if (empty($args)) $args = [];
        array_unshift($args, $this);
        if (empty($options)) $options = [];
        $ret = null;
        $is_unknown = true;


        $property_name = $name;
        if (true
            AND !isset($ret)
            AND empty($options['is_skip_property'])
            AND property_exists($this,$property_name)
        ) {
            $is_unknown = false;
            $ret = $this->$property_name;
            if (isset($ret) AND ($ret instanceof \Closure) AND empty($options['is_not_call_closure'])) $ret = call_user_func_array($ret, $args);
        }


        $getter_method_name = 'get_'.$name;
        if (true
            AND !isset($ret)
            AND empty($options['is_skip_getter_method'])
            AND method_exists($this,$getter_method_name)
        ) {
            $is_unknown = false;
            $ret = $this->$getter_method_name();
            if (isset($ret) AND ($ret instanceof \Closure) AND empty($options['is_not_call_closure'])) $ret = call_user_func_array($ret, $args);
        }


        $protected_property_name = '_'.$name;
        if (true
            AND !isset($ret)
            AND empty($options['is_skip_protected_property'])
            AND property_exists($this, $protected_property_name)
        ) {
            $is_unknown = false;
            $ret = $this->$protected_property_name;
            if (isset($ret) AND ($ret instanceof \Closure) AND empty($options['is_not_call_closure'])) $ret = call_user_func_array($ret, $args);
        }


        $else_getter_method_name = 'get_'.$name.'_else';
        if (true
            AND !isset($ret)
            AND empty($options['is_skip_else_getter_method'])
            AND method_exists($this,$else_getter_method_name)
        ) {
            $is_unknown = false;
            $ret = $this->$else_getter_method_name();
            if (isset($ret) AND ($ret instanceof \Closure) AND empty($options['is_not_call_closure'])) $ret = call_user_func_array($ret, $args);
        }


        if ($is_unknown) {
            if (!empty($options['if_unknown_return_null_instead_of_throw'])) return null;
            throw new \BadMethodCallException('Try getting unknown propery: '.get_class($this).'->'.$name);
        }


        $postprocess_method_name = 'get_'.$name.'_postprocess';
        if (empty($options['is_skip_postprocess_method'])) {
            if (method_exists($this,$postprocess_method_name)) {
                $ret = $this->$postprocess_method_name($ret);
            }
            elseif (substr($name,0,3) === 'is_') {
                $ret = !empty($ret);
            }
        }


        return $ret;
    }
    /**
     * @param string $name
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __get($name) { return $this->get($name); }



    /**
     * @param string $name
     * @param mixed  $value
     * @throws \BadMethodCallException
     * @return static
     */
    public function set($name, $value, $options = null) {
        if (empty($options)) $options = [];

        $property_name = $name;
        if (true
            AND empty($options['is_skip_property'])
            AND property_exists($this, $property_name)
        ) {
            $this->$property_name = $value;
            return $this;
        }

        $setter_method_name = 'set_' . $name;
        if (true
            AND empty($options['is_skip_setter_method'])
            AND method_exists($this, $setter_method_name)
        ) {
            $this->$setter_method_name($value);
            return $this;
        }

        $protected_property_name = '_'.$name;
        if (true
            AND empty($options['is_skip_protected_property'])
            AND property_exists($this, $protected_property_name)
        ) {
            $this->$protected_property_name = $value;
            return $this;
        }

        throw new \BadMethodCallException('Try setting unknown propery: '.get_class($this).'->'.$name);
    }
    /**
     * @param string $name
     * @param mixed  $value
     * @throws \BadMethodCallException
     * @return static
     */
    public function __set($name, $value) {
        $this->set($name, $value);
        return $this;
    }


    /**
     *
     * @param string $name
     * @throws \BadMethodCallException
     * @return bool
     */
    public function __isset($name) {
        $value = $this->get($name, null, ['if_unknown_return_null_instead_of_throw' => true]);
        return isset($value);
    }


    /**
     * @param string $name
     * @throws \BadMethodCallException
     * @return void
     */
    public function __unset($name) {
        $this->set($name, null);
    }
    

}
