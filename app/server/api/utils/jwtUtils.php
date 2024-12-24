<?
class JwtUtils
{
	private static $secretKey = 'mateo';

	// Method to generate a token
	public static function generateToken($userId, $username)
	{
		$header = ['alg' => 'HS256', 'typ' => 'JWT'];
		$payload = [
			'user_id' => $userId,
			'username' => $username,
			'exp' => time() + 3600  // 1 hour expiry
		];

		$headerEncoded = base64_encode(json_encode($header));
		$payloadEncoded = base64_encode(json_encode($payload));

		$signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", self::$secretKey);

		return "$headerEncoded.$payloadEncoded.$signature";
	}

	// Method to validate a token
	public static function validateToken($token)
	{
		// Split the token into parts
		$tokenParts = explode('.', $token);

		if (count($tokenParts) !== 3) {
			return false; // Invalid token format
		}

		// Decode header and payload
		list($headerEncoded, $payloadEncoded, $signature) = $tokenParts;

		// Re-create the signature using the header and payload
		$signatureCheck = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", self::$secretKey);

		// If the signature doesn't match, the token is invalid
		if ($signature !== $signatureCheck) {
			return false;
		}

		// Decode the payload and check expiry
		$payload = json_decode(base64_decode($payloadEncoded), true);

		if ($payload['exp'] < time()) {
			return false; // Token has expired
		}

		return $payload; // Return payload if valid
	}

	public static function getUserIdFromToken($token)
	{
		$tokenParts = explode('.', $token);
		if (count($tokenParts) !== 3) {
			return null; // Invalid token format
		}

		$payloadEncoded = $tokenParts[1];
		$payload = json_decode(base64_decode($payloadEncoded), true);

		return $payload['user_id'] ?? null; // Return user_id if present
	}
}
?>