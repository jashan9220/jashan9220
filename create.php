<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $address = $model = $delivery_date = $payment_method = "";
$name_err = $address_err = $model_err = $delivery_date_err = $payment_method_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate address
    $input_address = trim($_POST["address"]);
    if (empty($input_address)) {
        $address_err = "Please enter an address.";
    } else {
        $address = $input_address;
    }

    // Validate model
    $input_model = trim($_POST["model"]);
    if (empty($input_model)) {
        $model_err = "Please enter a model.";
    } else {
        $model = $input_model;
    }

    // Validate delivery date
    $input_delivery_date = trim($_POST["delivery_date"]);
    if (empty($input_delivery_date)) {
        $delivery_date_err = "Please enter a delivery date.";
    } else {
        $delivery_date = $input_delivery_date;
    }

    // Validate payment method
    $input_payment_method = trim($_POST["payment_method"]);
    if (empty($input_payment_method)) {
        $payment_method_err = "Please enter a payment method.";
    } else {
        $payment_method = $input_payment_method;
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($address_err) && empty($model_err) && empty($delivery_date_err) && empty($payment_method_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tractor_records (name, address, model, delivery_date, payment_method) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_address, $param_model, $param_delivery_date, $param_payment_method);

            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_model = $model;
            $param_delivery_date = $delivery_date;
            $param_payment_method = $payment_method;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: main.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to register your record in the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($name); ?>">
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($address); ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" name="model" class="form-control <?php echo (!empty($model_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($model); ?>">
                            <span class="invalid-feedback"><?php echo $model_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control <?php echo (!empty($delivery_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($delivery_date); ?>">
                            <span class="invalid-feedback"><?php echo $delivery_date_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <input type="text" name="payment_method" class="form-control <?php echo (!empty($payment_method_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($payment_method); ?>">
                            <span class="invalid-feedback"><?php echo $payment_method_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="main.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
