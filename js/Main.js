function confirmApply() {
    return confirm("Are you sure you want to apply for this task?");
}
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('applied')) {
        alert('You have successfully applied for this task!');
    }
});
document.getElementById('task-start-date')?.min = new Date().toISOString().split('T')[0];
document.getElementById('global-search')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const url = new URL(window.location.href);
        url.searchParams.set('search', this.value.trim());
        window.location.href = url.toString();
    }
});
function markTaskComplete(taskId) {
    if (confirm('Mark this task as complete?')) {
        window.location.href = `Main.php?complete_task=${taskId}`;
    }
}