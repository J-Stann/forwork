document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.view-task').forEach(btn => {
            btn.addEventListener('click', function() {
                const taskId = this.closest('.task-row').dataset.taskId;
                fetch(`/admin/api/get_task.php?id=${taskId}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('taskDetailsContent').innerHTML = html;
                        document.getElementById('taskModal').style.display = 'block';
                    });
            });
        });
        document.querySelector('.close-modal')?.addEventListener('click', () => {
            document.getElementById('taskModal').style.display = 'none';
        });
        document.getElementById('taskModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
        document.getElementById('taskSearch')?.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.task-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });