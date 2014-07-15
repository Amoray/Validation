<?php

/**
* test
*/
class test
{

	static		$test = [];

	static function add($name, $func, array $params = [])
	{
		if (!is_callable($func))
		{
			throw new \Exception('$func not callable');
		}

		static::$test[] = [
			"name" => $name,
			"func" => $func,
			"params" => $params,
			"error" => 0,
			"complete" => 0,
			"result" => null,
		];
	}

	static function run()
	{
		$error = 0;
		$complete = 0;
		$run = 0;

		foreach (static::$test as $key => $test)
		{
			try
			{
				$result = call_user_func_array($test['func'], $test['params']);
			}
			catch (Exception $e)
			{
				 $result = false;
			}
			$run++;

			static::$test[$key]["result"] = $result;
			if (is_array($result) && array_unique($result) === [true])
			{
				$complete++;
				static::$test[$key]["complete"] = 1;
			}
			elseif ($result instanceof \Exception)
			{
				$error++;
				static::$test[$key]["error"] = 1;

				echo "Fault @{$test['name']}: " . $result->getMessage() . "\r\n";
				var_dump($result->getTrace());
				echo "\r\n";
			}
			elseif (true !== $result)
			{
				$error++;
				static::$test[$key]["error"] = 1;

				echo "Fault @{$test['name']}: \r\n";
				echo "\r\n";
			}
			else
			{
				$complete++;
				static::$test[$key]["complete"] = 1;
			}
		}

		echo "Tests Run: {$run}\r\n";
		echo "Tests Failed: {$error}\r\n";
		echo "Tests Complete: {$complete}\r\n";
		echo "Percent: ". $complete / $run * 100 ."\r\n";

	}

}

?>
