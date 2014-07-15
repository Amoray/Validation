<?php

require 'test.php';
require '../src/Amoray/Validation/Validator.php';

use Amoray\Validation\Validator AS Validator;


test::add("validate->callback() check", function ()
{
	$validate = new Validator();
	$validate('a', 'Bruce')->callback(function ($name)
	{
		if ($name !== "Bruce")
		{
			return new \Exception('Name was not bruce');
		}

		return true;
	});

	return [
		true === $validate->success(),
	];
});


test::add("validate->callback() check fail", function ()
{
	$validate = new Validator();
	$validate('a', 'Bruc')->callback(function ($name)
	{
		if ($name !== "Bruce")
		{
			return new \Exception('Name was not bruce');
		}

		return true;
	});

	$check = new \Exception('Name was not bruce');

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});



test::add("validate->required() check", function ()
{
	$validate = new Validator();
	$validate('a', 'test')->required();

	return [
		true === $validate->success(),
	];
});


test::add("validate->required() check fail", function ()
{
	$validate = new Validator();
	$validate('a', '')->required();

	$check = new \Exception("Required field");

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});


test::add("validate->phone() check", function ()
{
	$validate = new Validator();
	$validate('a', '+1(123)345-4567')->phone(7,11);
	$validate('b', '+1 123 345-4567')->phone(7,11);
	$validate('c', '123 456 7890')->phone(7,10);
	$validate('d', '456 7890')->phone(7,7);

	return [
		true === $validate->success(),
	];
});


test::add("validate->phone() check fail", function ()
{
	$validate = new Validator();
	$validate('a', '+1(123)345-4567 ext55')->phone(11,11);
	$validate('b', '123')->phone(11,11);
	$validate('c', '1234567890123456')->phone(11,11);

	$validate('d', '+1 123 345-4567 ext55')->phone(11,11);
	$validate('e', '123')->phone(11,11);
	$validate('f', '1234567890123456')->phone(11,11);

	$validate('g', '123 456 7890 ext 55')->phone(10,10);
	$validate('h', '123')->phone(10,10);
	$validate('i', '1234567890123456')->phone(10,10);

	$validate('j', '456 7890 ext 55')->phone(7,7);
	$validate('k', '123')->phone(7,7);
	$validate('l', '1234567890123456')->phone(7,7);

	$max11 = new \Exception("Must be 11 digits or fewer.");
	$max10 = new \Exception("Must be 10 digits or fewer.");
	$max7 = new \Exception("Must be 7 digits or fewer.");

	$min11 = new \Exception("Must be 11 digits or more.");
	$min10 = new \Exception("Must be 10 digits or more.");
	$min7 = new \Exception("Must be 7 digits or more.");

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $max11->getCode(),
		$validate->checklist('a')['error']->getMessage() == $max11->getMessage(),

		$validate->checklist('b')['error']->getCode() == $min11->getCode(),
		$validate->checklist('b')['error']->getMessage() == $min11->getMessage(),

		$validate->checklist('c')['error']->getCode() == $max11->getCode(),
		$validate->checklist('c')['error']->getMessage() == $max11->getMessage(),

		$validate->checklist('d')['error']->getCode() == $max11->getCode(),
		$validate->checklist('d')['error']->getMessage() == $max11->getMessage(),

		$validate->checklist('e')['error']->getCode() == $min11->getCode(),
		$validate->checklist('e')['error']->getMessage() == $min11->getMessage(),

		$validate->checklist('f')['error']->getCode() == $max11->getCode(),
		$validate->checklist('f')['error']->getMessage() == $max11->getMessage(),

		$validate->checklist('g')['error']->getCode() == $max10->getCode(),
		$validate->checklist('g')['error']->getMessage() == $max10->getMessage(),

		$validate->checklist('h')['error']->getCode() == $min10->getCode(),
		$validate->checklist('h')['error']->getMessage() == $min10->getMessage(),

		$validate->checklist('i')['error']->getCode() == $max10->getCode(),
		$validate->checklist('i')['error']->getMessage() == $max10->getMessage(),

		$validate->checklist('j')['error']->getCode() == $max7->getCode(),
		$validate->checklist('j')['error']->getMessage() == $max7->getMessage(),

		$validate->checklist('k')['error']->getCode() == $min7->getCode(),
		$validate->checklist('k')['error']->getMessage() == $min7->getMessage(),

		$validate->checklist('l')['error']->getCode() == $max7->getCode(),
		$validate->checklist('l')['error']->getMessage() == $max7->getMessage(),
	];
});


test::add("validate->max() check", function ()
{
	$validate = new Validator();
	$validate('a', '123456')->max(7);

	return [
		true === $validate->success(),
	];
});


test::add("validate->max() check fail", function ()
{
	$validate = new Validator();
	$validate('a', '12345678')->max(7);

	$check = new \Exception("This field must be 7 characters or fewer.");

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});


test::add("validate->min() check", function ()
{
	$validate = new Validator();
	$validate('a', '12345678')->min(7);

	return [
		true === $validate->success(),
	];
});


test::add("validate->min() check fail", function ()
{
	$validate = new Validator();
	$validate('a', '123456')->min(7);

	$check = new \Exception("This field must be 7 characters or more.");

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});


test::add("validate->more() check", function ()
{
	$validate = new Validator();
	$validate('a', 12)->more(7);

	return [
		true === $validate->success(),
	];
});


test::add("validate->more() check fail", function ()
{
	$validate = new Validator();
	$validate('a', -5)->more(7);

	$check = new \Exception("This field must be 7 or greater.");

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});


test::add("validate->less() check", function ()
{
	$validate = new Validator();
	$validate('a', -5)->less(7);

	return [
		true === $validate->success(),
	];
});


test::add("validate->less() check fail", function ()
{
	$validate = new Validator();
	$validate('a', 12)->less(7);

	$check = new \Exception("This field must be 7 or lesser.");

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});


test::add("validate->numeric() check", function ()
{
	$validate = new Validator();
	$validate('a', 5)->numeric();
	$validate('b', -5)->numeric();
	$validate('c', 5.5)->numeric();
	$validate('d', 5.5e2)->numeric();
	$validate('e', 0x4)->numeric();

	return [
		true === $validate->success(),
	];
});


test::add("validate->numeric() check fail", function ()
{
	$validate = new Validator();
	$validate('a', 'abcd')->numeric();

	$check = new \Exception('This field must be numeric');

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});


test::add("validate->email() check", function ()
{
	$validate = new Validator();
	$validate('a', 'test@test.com')->email();
	$validate('b', 'test@test')->email();

	return [
		true === $validate->success(),
	];
});


test::add("validate->email() check fail", function ()
{
	$validate = new Validator();
	$validate('a', 'abcd')->email();

	$check = new \Exception('Not a valid email address');

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check->getMessage(),
	];
});


test::add("validate->uri() check", function ()
{
	$validate = new Validator();
	$validate('a', 'test.com')->uri();
	$validate('b', 'test.com/?foo=bar')->uri();
	$validate('c', 'test.com/?foo=bar#baz')->uri();
	$validate('d', 'http://test.com/?foo=bar#baz')->uri();
	$validate('e', 'http://test.com/test/?foo=bar#baz')->uri();
	$validate('f', 'http://www.test.com/test/?foo=bar#baz')->uri();

	return [
		true === $validate->success(),
	];
});


test::add("validate->uri() check fail", function ()
{
	$validate = new Validator();
	$validate('a', '://test.com')->uri();
	$validate('b', 'derp:ead.aeaf/ea??Ea$as')->uri();

	$check1 = new \Exception('Missing scheme ( I.E. http:// )');
	$check2 = new \Exception('Invalid formatting.');

	return [
		false === $validate->success(),
		$validate->checklist('a')['error']->getCode() == $check1->getCode(),
		$validate->checklist('a')['error']->getMessage() == $check1->getMessage(),
		$validate->checklist('b')['error']->getCode() == $check2->getCode(),
		$validate->checklist('b')['error']->getMessage() == $check2->getMessage(),
	];
});

test::run();

?>
