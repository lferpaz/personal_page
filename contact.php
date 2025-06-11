<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title> Luis Fernando</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- CSS -->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column">
<main class="flex-shrink-0">
    <?php
        session_start();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrf_token = $_SESSION['csrf_token'];
        include 'navigation.php';
    ?>
    <section class="py-5">
        <div class="container px-5">
            <div class="bg-light rounded-4 py-5 px-4 px-md-5">
                <div class="text-center mb-5">
                    <div class="feature bg-primary text-white rounded-3 mb-3"><span class="text-gradient d-inline"><i class="bi bi-envelope"></i></span></div>
                    <h1 class="fw-bolder">Get in touch</h1>
                    <h2 class="fw-normal text-muted mb-0">Let's work together!</h2>
                </div>
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <form id="contactForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="name" name="name" type="text" placeholder="Your name" required />
                                <label for="name">Full Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" required />
                                <label for="email">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="phone" name="phone" type="tel" placeholder="1234567890" required />
                                <label for="phone">Phone</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="message" name="message" placeholder="Your message" style="height: 10rem;" required></textarea>
                                <label for="message">Message</label>
                            </div>
                            <div id="submitSuccessMessage" class="d-none text-center text-success mb-3">Message sent successfully!</div>
                            <div id="submitErrorMessage" class="d-none text-center text-danger mb-3">There was an error sending your message.</div>
                            <div class="d-grid">
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch('send-mail.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('submitSuccessMessage').classList.remove('d-none');
            document.getElementById('submitErrorMessage').classList.add('d-none');
            form.reset();
        } else {
            document.getElementById('submitErrorMessage').classList.remove('d-none');
            document.getElementById('submitSuccessMessage').classList.add('d-none');
        }
    })
    .catch(error => {
        document.getElementById('submitErrorMessage').classList.remove('d-none');
        document.getElementById('submitSuccessMessage').classList.add('d-none');
    });
});
</script>
</body>
</html>
