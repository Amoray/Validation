<?php

namespace Amelia\Validation;

/**
* Validation Class
*/
class Validator
{
	protected	$checklist	= array();
	protected	$success	= true;

	public function __invoke($key, $value = null)
	{
		if (is_null($value))
		{
			$this->checklist[$key] = array(
				"value" => $_POST[$key],
				"condition" => true
			);
		}
		else
		{
			$this->checklist[$key] = array(
				"value" => $value,
				"condition" => true
			);
		}

		return $this;
	}

	private function end()
	{
		$test = end($this->checklist);
		$key = key($this->checklist);

		return array($test['value'], $key);
	}

	private function fail(\Exception $e)
	{
		list($test, $key) = $this->end();

		$this->success = false;
		$this->checklist[$key]['error'] = $e;
	}

	public function dependancy($key, $match = null)
	{
		$value = $_POST[$key];

		if ($value == $match) 
		{
			$this->setConditional(true);
		}
		elseif (is_null($match) && !empty($value)) 
		{
			$this->setConditional(true);
		}
		else
		{
			$this->setConditional(false);
		}

		return $this;
	}

	public function condition($value)
	{
		$this->setConditional((bool)$value);

		return $this;
	}

	private function setConditional($switch = true)
	{
		// Stop processing additional checks
		// 
		list($test, $key) = $this->end();

		$this->checklist[$key]['condition'] = $switch;

		return $this;

	}

	private function conditional($key)
	{
		return $this->checklist[$key]['condition'];
	}

	public function required()
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			if (is_object($test) && empty($test)) 
			{
				$this->fail(new \Exception("Required field"));
			}
			elseif (is_array($test) && empty($test)) 
			{
				$this->fail(new \Exception("Required field"));
			}
			elseif (is_string($test) && 0 === strlen($test)) 
			{
				$this->fail(new \Exception("Required field"));
			}
			elseif (is_numeric($test) && is_null($test)) 
			{
				$this->fail(new \Exception("Required field"));
			}
		}

		return $this;
	}

	public function phone_max($length)
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			$test = preg_replace("/[^\+0-9]/", "", $test);

			if (strlen($test) > $length) 
			{
				$this->fail(new \Exception("Must be {$length} digits or more."));
			}
		}

		return $this;
	}

	public function phone_min($length)
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			$test = preg_replace("/[^\+0-9]/", "", $test);

			if (strlen($test) < $length) 
			{
				$this->fail(new \Exception("Must be {$length} digits or more."));
			}
		}

		return $this;
	}

	public function max($length)
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			if (strlen($test) > $length) 
			{
				$this->fail(new \Exception("This field must be {$length} characters or fewer."));
			}
		}

		return $this;
	}

	public function min($length)
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			if (strlen($test) < $length) 
			{
				$this->fail(new \Exception("This field must be {$length} characters or more."));
			}
		}

		return $this;
	}

	public function greater($value = 0)
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			if ($test < $value) 
			{
				$this->fail(new \Exception("This field must be {$value} or greater."));
			}
		}

		return $this;
	}

	public function lesser($value = 0)
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			if ($test > $value) 
			{
				$this->fail(new \Exception("This field must be {$value} or fewer."));
			}
		}

		return $this;
	}

	public function numeric()
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			if (false === is_numeric($test)) 
			{
				$this->fail(new \Exception('This field must be numeric'));
			}
		}

		return $this;
	}

	public function email()
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			if (false === strpos($test, '@')) 
			{
				$this->fail(new \Exception('Not a valid email address'));
			}
		}

		return $this;
	}

	public function website()
	{
		list($test, $key) = $this->end();

		if ($this->conditional($key)) 
		{
			list($scheme, $hierarchy) = $test->toValidator();

			if (empty($scheme)) 
			{
				$this->fail(new \Exception('Missing scheme ( I.E. http:// )'));
			}

			if (empty($hierarchy)) 
			{
				$this->fail(new \Exception('Invalid formatting.'));
			}
		}
	}

	public function success()
	{
		return $this->success;
	}

	public function checklist($key = null)
	{
		if (array_key_exists($key, $this->checklist)) 
		{
			return $this->checklist[$key];
		}
		else
		{
			return array();
		}
	}

}

?>