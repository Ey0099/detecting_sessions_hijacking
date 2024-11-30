
<section class="py-0">
    <div style="background-color: #24364c !important;" class="bg-primary">
        <div class="d-flex justify-content-between py-4">
            <div class="col-md-6 text-center text-md-start">
                <p class="fs--1 my-2 fw-bold text-white text-center">All rights Reserved &copy; HijackGuard, 2024</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="fs--1 my-2 text-white text-center">Built with&nbsp;<svg class="bi bi-suit-heart-fill" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#EB6453" viewBox="0 0 16 16">
                        <path d="M4 1c2.21 0 4 1.755 4 3.92 0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"></path>
                    </svg>&nbsp;by US</a>
                </p>
            </div>
        </div>
    </div>
</section>
</main>

<!-- JavaScripts -->
<script src="../src/vendors/@popperjs/popper.min.js"></script>
<script src="../src/vendors/bootstrap/bootstrap.min.js"></script>
<script src="../src/vendors/is/is.min.js"></script>
<script src="../src/vendors/fontawesome/all.min.js"></script>
<script src="../src/assets/js/theme.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    setInterval(function () {
        fetch('monitor_session.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'anomaly') {
                    Swal.fire({
                        title: 'Suspicious Activity Detected!',
                        text: 'You will be logged out for security reasons.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }, 900000000);
</script>

</body>
</html>
