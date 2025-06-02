<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vigenère Cipher</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Vigenère Cipher</h1>
            <p>Encrypt and decrypt messages using the Vigenère cipher</p>
        </header>

        <main>
            <div class="card">
                <form action="index.php" method="post" id="cipherForm">
                    <div class="form-group">
                        <label for="action">Choose Action:</label>
                        <div class="radio-group">
                            <label class="radio">
                                <input type="radio" name="action" value="encrypt" checked> Encrypt
                            </label>
                            <label class="radio">
                                <input type="radio" name="action" value="decrypt"> Decrypt
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="key">Key:</label>
                        <input type="text" id="key" name="key" placeholder="Enter your key" required>
                        <div class="key-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span id="strengthText">Key strength</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
                    </div>

                    <button type="submit" class="btn">Process</button>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $action = $_POST["action"];
                    $key = $_POST["key"];
                    $message = $_POST["message"];
                    
                    if (empty($key) || empty($message)) {
                        echo "<div class='result error'>Please provide both a key and a message.</div>";
                    } else {
                        // Process the message
                        $result = ($action == "encrypt") ? 
                            vigenereEncrypt($message, $key) : 
                            vigenereDecrypt($message, $key);
                        
                        echo "<div class='result'>";
                        echo "<h3>" . ucfirst($action) . "ed Message:</h3>";
                        echo "<div class='result-text'>" . htmlspecialchars($result) . "</div>";
                        echo "<button class='btn copy-btn' onclick='copyToClipboard()'>Copy to Clipboard</button>";
                        echo "</div>";
                    }
                }

                function vigenereEncrypt($plaintext, $key) {
                    $key = strtoupper(preg_replace("/[^A-Za-z]/", "", $key));
                    if (strlen($key) == 0) return "Invalid key";
                    
                    $result = "";
                    $keyLength = strlen($key);
                    $keyIndex = 0;
                    
                    for ($i = 0; $i < strlen($plaintext); $i++) {
                        $char = $plaintext[$i];
                        
                        // Only encrypt alphabetic characters
                        if (ctype_alpha($char)) {
                            $isUpper = ctype_upper($char);
                            $charCode = ord(strtoupper($char)) - 65;
                            $keyChar = ord($key[$keyIndex % $keyLength]) - 65;
                            
                            $encryptedChar = chr(((($charCode + $keyChar) % 26) + 65));
                            
                            // Preserve original case
                            if (!$isUpper) {
                                $encryptedChar = strtolower($encryptedChar);
                            }
                            
                            $result .= $encryptedChar;
                            $keyIndex++;
                        } else {
                            // Non-alphabetic characters remain unchanged
                            $result .= $char;
                        }
                    }
                    
                    return $result;
                }

                function vigenereDecrypt($ciphertext, $key) {
                    $key = strtoupper(preg_replace("/[^A-Za-z]/", "", $key));
                    if (strlen($key) == 0) return "Invalid key";
                    
                    $result = "";
                    $keyLength = strlen($key);
                    $keyIndex = 0;
                    
                    for ($i = 0; $i < strlen($ciphertext); $i++) {
                        $char = $ciphertext[$i];
                        
                        // Only decrypt alphabetic characters
                        if (ctype_alpha($char)) {
                            $isUpper = ctype_upper($char);
                            $charCode = ord(strtoupper($char)) - 65;
                            $keyChar = ord($key[$keyIndex % $keyLength]) - 65;
                            
                            // For decryption, subtract key and handle negative values
                            $decryptedChar = chr((((($charCode - $keyChar) % 26) + 26) % 26) + 65);
                            
                            // Preserve original case
                            if (!$isUpper) {
                                $decryptedChar = strtolower($decryptedChar);
                            }
                            
                            $result .= $decryptedChar;
                            $keyIndex++;
                        } else {
                            // Non-alphabetic characters remain unchanged
                            $result .= $char;
                        }
                    }
                    
                    return $result;
                }
                ?>
            </div>

            <div class="info-card">
                <h2>About Vigenère Cipher</h2>
                <p>The Vigenère cipher is a method of encrypting alphabetic text by using a simple form of polyalphabetic substitution. It employs a keyword to determine the shift of each letter in the plaintext.</p>
                
                <h3>How it works:</h3>
                <ol>
                    <li>Each letter of the key is used to encrypt one letter of the plaintext.</li>
                    <li>If the key is shorter than the plaintext, it is repeated.</li>
                    <li>Each letter in the key specifies how many positions to shift the corresponding plaintext letter.</li>
                </ol>
                
                <h3>Example:</h3>
                <p>Plaintext: <strong>HELLO</strong><br>
                Key: <strong>KEY</strong> (repeated as <strong>KEYKE</strong>)<br>
                Ciphertext: <strong>RIJVS</strong></p>
                
                <div class="cipher-table">
                    <div class="table-row header">
                        <div>Plaintext</div>
                        <div>Key</div>
                        <div>Shift</div>
                        <div>Ciphertext</div>
                    </div>
                    <div class="table-row">
                        <div>H</div>
                        <div>K</div>
                        <div>10</div>
                        <div>R</div>
                    </div>
                    <div class="table-row">
                        <div>E</div>
                        <div>E</div>
                        <div>4</div>
                        <div>I</div>
                    </div>
                    <div class="table-row">
                        <div>L</div>
                        <div>Y</div>
                        <div>24</div>
                        <div>J</div>
                    </div>
                    <div class="table-row">
                        <div>L</div>
                        <div>K</div>
                        <div>10</div>
                        <div>V</div>
                    </div>
                    <div class="table-row">
                        <div>O</div>
                        <div>E</div>
                        <div>4</div>
                        <div>S</div>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <p>&copy; 2025 Vigenère Cipher Tool</p>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>
