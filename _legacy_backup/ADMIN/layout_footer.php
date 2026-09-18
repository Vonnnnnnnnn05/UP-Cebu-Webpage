    </main>

    <!-- Admin Footer -->
    <footer class="bg-white border-t border-border-card py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-muted">
            <div>
                © <?= date('Y') ?> Technology Transfer and Business Development Office • University of the Philippines Cebu
            </div>
            <div class="italic text-gold-deep text-[11px] font-medium">
                Nurtured to Create • Inspired to Innovate • Destined to Serve
            </div>
        </div>
    </footer>

    <!-- SweetAlert2 Admin Logout Script -->
    <script>
    function confirmAdminLogout() {
        Swal.fire({
            title: 'Sign Out?',
            text: 'Are you sure you want to end your administrative session?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7B1113', // UP Maroon
            cancelButtonColor: '#5A554D',
            confirmButtonText: 'Yes, Sign Out',
            cancelButtonText: 'Cancel',
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            customClass: {
                popup: 'rounded-2xl shadow-2xl font-sans'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Signing Out...',
                    text: 'Session ended safely. Returning to website...',
                    icon: 'success',
                    timer: 1300,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    showClass: {
                        popup: 'animate__animated animate__zoomIn animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__zoomOut animate__faster'
                    },
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl font-sans'
                    }
                });
                setTimeout(() => {
                    window.location.href = 'logout.php?instant=1';
                }, 1100);
            }
        });
    }
    </script>

</body>
</html>
