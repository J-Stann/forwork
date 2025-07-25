document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.role-toggle').addEventListener('click', function(e) {
            if (e.target.classList.contains('toggle-btn')) {
                document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');

                document.querySelectorAll('.user-table').forEach(table => table.classList.remove(
                    'active'));
                document.getElementById(`${e.target.dataset.role}-table`).classList.add('active');
            }
        });
        document.getElementById('userSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const activeTable = document.querySelector('.user-table.active');

            activeTable.querySelectorAll('tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        document.getElementById('refreshProviders').addEventListener('click', () => location.reload());
        document.getElementById('refreshClients').addEventListener('click', () => location.reload());
    });