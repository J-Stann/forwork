document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.view-more-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const messageCell = this.closest('.message-cell');
                const preview = messageCell.querySelector('.message-preview');
                const fullMessage = messageCell.querySelector('.full-message');

                if (fullMessage.style.display === 'none') {
                    preview.style.display = 'none';
                    fullMessage.style.display = 'block';
                    this.textContent = 'View Less';
                } else {
                    preview.style.display = 'block';
                    fullMessage.style.display = 'none';
                    this.textContent = 'View More';
                }
            });
        });
    });