function validateForm() {
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const pass = document.getElementById("pass").value;
  const cPass = document.getElementById("cPassword").value;
  const location = document.getElementById("location").value.trim();
  const pCode = document.getElementById("pCode").value.trim();
  const terms = document.getElementById("terms").checked;
  const messageBox = document.getElementById("messageBox");

  const nameRegex = /^[A-Za-z\s]+$/;
  const aiubEmailRegex = /^[a-zA-Z0-9._%+-]+@student\.aiub\.edu$/;

  messageBox.style.display = "none";

  if (!name || !email || !pass || !cPass || !location || !pCode) {
    messageBox.innerText = "All fields are required.";
    messageBox.style.display = "block";
    return false;
  }

  if (!nameRegex.test(name)) {
    messageBox.innerText = "Full Name must only contain letters.";
    messageBox.style.display = "block";
    return false;
  }

  if (!aiubEmailRegex.test(email)) {
    messageBox.innerText = "Email must be a valid @student.aiub.edu address.";
    messageBox.style.display = "block";
    return false;
  }

  if (pass !== cPass) {
    messageBox.innerText = "Passwords do not match.";
    messageBox.style.display = "block";
    return false;
  }

  if (!terms) {
    messageBox.innerText = "You must agree to the terms.";
    messageBox.style.display = "block";
    return false;
  }

  return true; // Submit the form
}
document.addEventListener("DOMContentLoaded", function () {
  const checkboxes = document.querySelectorAll('input[type="checkbox"][name="selectedCities[]"]');
  const counter = document.getElementById("cityCount");

  function updateCount() {
    const checked = document.querySelectorAll('input[name="selectedCities[]"]:checked');
    counter.innerText = `Selected cities: ${checked.length}/10`;
  }

  checkboxes.forEach((cb) => {
    cb.addEventListener("change", (event) => {
      const checked = document.querySelectorAll('input[name="selectedCities[]"]:checked');
      if (checked.length > 10) {
        event.preventDefault();      // Stop default toggle
        event.target.checked = false; // Uncheck current one
        alert("You can select a maximum of 10 cities.");
      }
      updateCount();
    });
  });

  updateCount(); // Set initial count
});

