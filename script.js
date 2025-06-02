document.addEventListener("DOMContentLoaded", () => {
  const keyInput = document.getElementById("key")
  const strengthFill = document.getElementById("strengthFill")
  const strengthText = document.getElementById("strengthText")

  // Update key strength indicator when key changes
  keyInput.addEventListener("input", updateKeyStrength)

  function updateKeyStrength() {
    const key = keyInput.value
    let strength = 0
    let message = ""

    if (key.length === 0) {
      message = "Key strength"
    } else if (key.length < 4) {
      strength = 25
      message = "Weak"
    } else if (key.length < 8) {
      strength = 50
      message = "Moderate"
    } else if (key.length < 12) {
      strength = 75
      message = "Strong"
    } else {
      strength = 100
      message = "Very Strong"
    }

    // Additional checks for key complexity
    if (key.length > 0) {
      const hasUppercase = /[A-Z]/.test(key)
      const hasLowercase = /[a-z]/.test(key)
      const hasNumbers = /[0-9]/.test(key)
      const hasSpecialChars = /[^A-Za-z0-9]/.test(key)

      // Adjust strength based on complexity
      if (hasUppercase && hasLowercase && (hasNumbers || hasSpecialChars) && key.length >= 8) {
        strength = 100
        message = "Very Strong"
      } else if ((hasUppercase || hasLowercase) && (hasNumbers || hasSpecialChars) && key.length >= 6) {
        strength = Math.max(strength, 75)
        message = "Strong"
      }
    }

    // Update the UI
    strengthFill.style.width = `${strength}%`

    // Change color based on strength
    if (strength <= 25) {
      strengthFill.style.backgroundColor = "var(--danger-color)"
    } else if (strength <= 50) {
      strengthFill.style.backgroundColor = "var(--warning-color)"
    } else if (strength <= 75) {
      strengthFill.style.backgroundColor = "var(--info-color)"
    } else {
      strengthFill.style.backgroundColor = "var(--success-color)"
    }

    strengthText.textContent = message
  }

  // Initialize key strength on page load
  updateKeyStrength()

  // Form validation
  const form = document.getElementById("cipherForm")
  form.addEventListener("submit", (event) => {
    const key = keyInput.value
    const message = document.getElementById("message").value

    if (!key || !message) {
      event.preventDefault()
      alert("Please provide both a key and a message.")
    }
  })
})

// Function to copy result to clipboard
function copyToClipboard() {
  const resultText = document.querySelector(".result-text")

  if (resultText) {
    // Create a temporary textarea element
    const textarea = document.createElement("textarea")
    textarea.value = resultText.textContent
    document.body.appendChild(textarea)

    // Select and copy the text
    textarea.select()
    document.execCommand("copy")

    // Remove the temporary element
    document.body.removeChild(textarea)

    // Provide feedback
    alert("Copied to clipboard!")
  }
}
