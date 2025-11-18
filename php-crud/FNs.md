session_start() — starts a new session or resumes an existing one so you can store and retrieve values in $\_SESSION across requests.

session_destroy() — terminates the current session and clears session data on the server; it does not automatically clear the $\_SESSION array in the running script.

header() — sends a raw HTTP header to the client; commonly used for redirects like header("Location: ..."). Must be called before any output.

exit — halts script execution immediately. Often used right after header() to prevent further code from running.

isset() — returns true if a variable exists and is not null. Useful to check whether an array key or variable was provided before using it.

empty() — returns true if a variable is empty (null, false, "", 0, [], etc.). Use to check for missing/blank values.

$\_SERVER — a superglobal array containing server and request information (e.g., $\_SERVER['REQUEST_METHOD'] or $\_SERVER['REQUEST_URI']).

$\_POST — a superglobal array of form data submitted via HTTP POST.

$\_GET — a superglobal array of query string parameters submitted via HTTP GET.

$\_SESSION — a superglobal array used to store per-session data that persists between requests for the same user session.

file_exists() — checks whether a filesystem path exists; returns true if the file or directory is present.

file_get_contents() — reads the entire contents of a file into a string; used to load users.json.

file_put_contents() — writes a string to a file (creates the file if necessary). Returns bytes written or false on failure.

json_decode() — converts a JSON string into a PHP value (array or object). Pass true for an associative array.

json_encode() — converts a PHP array/object into a JSON string; often used before saving structured data to disk.

password_hash() — creates a secure one-way hash of a password (uses a safe algorithm like bcrypt by default). Do not store plain text passwords.

password_verify() — compares a plain password to a stored hash and returns true if they match.

trim() — strips whitespace (and optionally other characters) from the start and end of a string; use it to normalize user input.

htmlspecialchars() — converts special characters to HTML entities (e.g., < → &lt;) to prevent Cross-Site Scripting (XSS) when echoing user data into HTML.

echo — language construct that outputs one or more strings to the response body; used to render HTML or debug messages.

print_r() — prints a human-readable representation of a variable (arrays/objects); useful for debugging. Often wrapped with htmlspecialchars() when shown in a browser.

random_bytes() — returns cryptographically secure random bytes; commonly used for generating tokens or IDs.

bin2hex() — converts binary data (like the output of random_bytes) into a hexadecimal string for safe display or storage.

date() — formats the local date/time according to a format string (e.g., date('c') for ISO8601 timestamps).

filter_var() — validates or sanitizes a value using a filter; for example filter_var($email, FILTER_VALIDATE_EMAIL) checks email format.

strlen() — returns the length (number of bytes/characters) of a string; used for basic password-length checks.

strtolower() — converts a string to lowercase; handy when normalizing emails for case-insensitive comparison.

hash_equals() — timing-attack-safe string comparison function; use for comparing sensitive tokens (e.g., CSRF tokens) to avoid security timing leaks.

intval() — converts a value to an integer; useful when reading numeric query params from $\_GET.
