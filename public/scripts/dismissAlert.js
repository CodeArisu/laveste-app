document.addEventListener('DOMContentLoaded', function() {
        var alert = document.getElementById('autoDismissAlert');
        if (alert) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000); // 5 seconds
        }
    });