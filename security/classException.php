<?php
class MyException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        // Add your custom logic here, if needed
        // Example: Logging the exception message or additional data
        error_log("Custom Exception Thrown: {$message}");
    }
}

// Throwing an instance of the custom exception
throw new MyException("This is a custom exception", 1);
