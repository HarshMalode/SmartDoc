<!DOCTYPE html>
<html>
<head>
    <title>Doctor Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f9f9f9;
        }
        .form-container {
            width: 450px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, textarea, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #aaa;
        }
        button {
            background: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .success {
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>

<?php
// ✅ PHP: Store feedback in database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "edoc";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $patient_name = $_POST['patient_name'];
    $doctor_name = $_POST['doctor_name'];
    $feedback_text = $_POST['feedback_text'];

    $sql = "INSERT INTO feedback (patient_name, doctor_name, feedback_text)
            VALUES ('$patient_name', '$doctor_name', '$feedback_text')";
    $conn->query($sql);
    $conn->close();
}
?>

<div class="form-container">
    <h2>Patient Feedback Form</h2>
    <form id="feedbackForm">
        <label>Patient Name:</label>
        <input type="text" name="patient_name" required>

        <label>Doctor Name:</label>
        <input type="text" name="doctor_name" required>

        <label>Feedback:</label>
        <textarea name="feedback_text" rows="5" required></textarea>

        <button type="submit">Submit Feedback</button>
    </form>
    <p id="status" class="success"></p>
</div>

<script>
const form = document.getElementById('feedbackForm');
const status = document.getElementById('status');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const data = new FormData(form);

  // ✅ Send to your PHP (for database)
  fetch(window.location.href, {
    method: 'POST',
    body: data
  });

  // ✅ Send to Formspree (for email)
  const response = await fetch("https://formspree.io/f/xyzbnqeg", {
    method: "POST",
    body: data,
    headers: { 'Accept': 'application/json' }
  });

  if (response.ok) {
    status.innerHTML = "✅ Feedback submitted and email sent successfully!";
    form.reset();
  } else {
    status.innerHTML = "❌ There was a problem sending the feedback.";
  }
});
</script>

</body>
</html>

