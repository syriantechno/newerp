@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messages = {
                success: @json(session('success')),
                error: @json(session('error')),
                warning: @json(session('warning')),
                info: @json(session('info'))
            };

            const colors = {
                success: 'bg-gradient-success',
                error: 'bg-gradient-danger',
                warning: 'bg-gradient-warning',
                info: 'bg-gradient-info'
            };

            Object.keys(messages).forEach(type => {
                if (!messages[type]) return;
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3';
                toast.style.zIndex = 9999;
                toast.innerHTML = `
                    <div class="toast align-items-center text-white ${colors[type]} border-0 show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">${messages[type]}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 4000);
            });
        });
    </script>
@endif
