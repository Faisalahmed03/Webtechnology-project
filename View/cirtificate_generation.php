<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Question Randomization Settings</title>
  <link rel="stylesheet" href="../Assets/certificate.css" />
</head>
<body>

  
    <h1>Certificate Generation</h1>
    <div class="certificate-section">
    <h2>Customize Certificate</h2>
    <label for="upload-logo">Upload Logo</label>
    <input type="file" id="upload-logo" name="upload-logo" accept="image/*">

    <label for="upload-signature">Upload Signature</label>
    <input type="file" id="upload-signature" name="upload-signature" accept="image/*">

    <label for="passing-score">Set Passing Score (%)</label>
    <input type="number" id="passing-score" name="passing-score" placeholder="e.g., 70" min="0" max="100">

    <button type="button" id="generate-certificate-btn">Generate Certificate</button>
  </div>
     <button type="button" class="button"  onclick="window.location.href=' teacher_dashboard.php'">Back</button>


</body>
</html>
