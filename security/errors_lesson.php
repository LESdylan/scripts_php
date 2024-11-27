<?php
/**
 * !errors that cannot be translated into Exceptions : E_ERROR; E_PARSE; E_CORE_ERROR; E_CORE_WARNING;  E_COMPILE_ERROR; E_COMPILE_WARNING; E_STRICT
 * ? other type of Exception : ErrorException; DomainException; LogicException...
 */
/**
 * Custom error handling to convert errors into exceptions.
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "An error occurred. Re-throwing it as an Exception.\n";
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

/**
 * Global exception handler for uncaught exceptions.
 */
set_exception_handler(function (Throwable $e) {
    echo "An exception has been detected: " . $e->getMessage() . PHP_EOL;
    exit(1); // Gracefully terminate the script
});

/**
 * Restores the original exception and error handlers.
 */
function cleanupHandlers(): void
{
    restore_error_handler();
    restore_exception_handler();
}

/**
 * Calculates the inverse square of a number.
 * 
 * @param float|int $n The number to calculate the inverse square for.
 * @return float The inverse square of the input.
 * @throws Exception If the input is zero or near-zero.
 */
function inverseSquare(float|int $n): float
{
    if (abs($n) < 1E-10) {
        throw new Exception('Cannot divide by zero or near-zero values.');
    }
    return 1 / ($n * $n);
}

/**
 * Main execution logic.
 */
function main(): void
{
    try {
        // Example calculations
        echo "Inverse square of 10: " . inverseSquare(10) . PHP_EOL;

        // Intentionally causes an exception
        echo "Inverse square of 0: " . inverseSquare(0) . PHP_EOL;

        echo "Finished calculations." . PHP_EOL;
    } catch (Exception $e) {
        // Handle specific exceptions gracefully
        echo "Error: Unable to calculate the inverse square. " . $e->getMessage() . PHP_EOL;
    } finally {
        // Clean up resources or restore handlers
        cleanupHandlers();
    }
}

// Run the script
try {
    trigger_error('Oups!', E_USER_WARNING); // Example error to trigger
    echo '42!' . PHP_EOL;
    main();
} catch (Throwable $e) {
    // Catch unhandled exceptions globally
    echo "Unhandled exception: " . $e->getMessage() . PHP_EOL;
    cleanupHandlers();
    exit(1);
}
?>
