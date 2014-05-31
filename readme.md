#Validation
##About

A simple validation class because a got bored one afternoon and didn't want to work on a project

##Roadmap
* Seperate validations from class and create a factory
* Seperate errors from class allowing for custom error messages to be defined prior

##Using

The validator can pull directly from $_POST if no second parameter is provided.
If you'd like, you may use a second parameter and validate that rather than whatever is in post

`
$validate = new Validator;

$validate('first_name');

$validate('last_name', $last_name);
`

Invoking the validate variable like so starts the validation for the passed parameters.

`
$validate('first_name');
$validate->required();

$validate('email_address', $email_address);
$validate->required();
$validate->email();
`

You can also chain, because who doesn't like chaining

`
$validate('address')->required();
`

Some validations can have parameters too.

`
$validate('phone_number')->required()->phone_min(7)->phone_max(10); //Phone number is required and must be between 7 and 10 digits in length.

$validate('items', $item_count)->required()->greater(5); //Item count is required and must be greater than 5
`

You may want to stop a validation if a condition is false

`
$validate('membership')->required()->condition( $is_gold_membership )->verify_gold();
// membership is required, if member is gold, verify gold. 
`

To check if you validation was successful or not

`
$validate->success();
`

Retrieving errors can also be done.

`
$validate->checklist('first_name');
// Returns array
array(
	"name" => "first_name",
	"value" => "The Doctor",
	"error" => Exception
)
`