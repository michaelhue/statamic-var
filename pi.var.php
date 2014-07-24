<?php
/**
 * Plugin_var
 *
 * Helps with handling variables in templates.
 *
 * @author Michael Hüneburg <hello@michaelhue.com>
 * @link https://github.com/michaelhue/statamic-var
 */
class Plugin_var extends Plugin
{
    /**
     * Set or get a variable.
     *
     * Parameters:
     *
     *   - name: Variable name.
     *   - is: If specified, the variable will be set to this value.
     *
     * Examples:
     *
     *  {{ var:with name="color" is="red" }}
     *  {{ var:with name="color" }} // output: red
     *  {{ var:with name="color" }}blue{{ /var:with }}
     *
     * @return string
     */
    public function with()
    {
        $name = $this->fetchParam('name');
        $value = $this->fetchValueParam();
        $default = $this->fetchParam('default');

        if ($value === false) {
            return $this->retrieve($name);
        } else {
            $this->store($name, $value, $default);
        }
        return '';
    }

    /**
     * Check if a variable is set.
     *
     * Examples:
     *
     *   {{ var:exists name="color" }} // false
     *   {{ var:color is="blue" }}
     *   {{ var:exists name="color" }) // true
     *
     * @param string $name
     * @return boolean
     */
    public function exists()
    {
        $name = $this->fetchParam('name');
        return $this->blink->exists($name);
    }

    /**
     * Extracts variables into the current context.
     *
     * Examples:
     *
     *   {{ var:color is="green" }}
     *   {{ var:extract }}
     *       "How do you like my new {{ color }} tie?"
     *       - "{{ color|upper }}!? HOW DARE YOU!"
     *   {{ /var:extract }}
     *
     * @return string
     */
    public function extract()
    {
        $storage = $this->blink;
        $data = $storage::$data;
        return Parse::template($this->content, $data, true, $this->context);
    }

    /**
     * Magic method for getting/setting variables with a shorter syntax.
     *
     * Parameters:
     *   - is: If provided, the variable will be set to this value.
     *
     * Examples:
     *
     *   {{ var:color is="red" }}
     *   {{ var:color }}red{{ /var:color }}
     *   {{ var:color }}
     *
     *   // Equivalent to...
     *   {{ var:with name="color" is="red" }}
     *   {{ var:with name="color" }}red{{ /var:with }}
     *   {{ var:with name="color" }}
     *
     * @param string $name
     * @param array $arguments
     * @return
     */
    public function __call($name, $arguments)
    {
        $value = $this->fetchValueParam();

        if ($value === false) {
            return $this->retrieve($name);
        } else {
            $this->store($name, $value);
            return '';
        }
    }

    /**
     * Stores a variable with a value.
     *
     * @param string $var Variable name.
     * @param mixed $val Variable value.
     */
    protected function store($var, $val)
    {
        $this->blink->set($var, $val);
    }

    /**
     * Retrieves a variable with optional fallback.
     *
     * @param string $var Variable name.
     * @param mixed $default Fallback value.
     * @return mixed
     */
    protected function retrieve($var, $default = null)
    {
        return $this->blink->get($var, $default);
    }

    /**
     * Tries to fetch a value from different sources.
     *
     * @return string|boolean
     */
    protected function fetchValueParam()
    {
        if (!empty($this->content)) {
            $value = trim(Parse::template($this->content, $this->context));
        } else {
            $value = $this->fetchParam(['is', 'value', 'val'], false, null, false, false);
        }

        return $value;
    }
}
