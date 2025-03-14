window.onload = function() {
    const loadTime = performance.now();
    document.getElementById('performanceInfo').textContent = 
        `Page loaded in ${loadTime.toFixed(2)}ms`;
    // Toggle display between content box and application details table
    document.getElementById('toggleIcon').onclick = function() {
        var table = document.getElementById('appDetailsTable');
        var content = document.getElementById('contentBox');
        var icon = this.querySelector('i');
        if(table.style.display === 'none' || table.style.display === ''){
            table.style.display = 'block';
            content.style.display = 'none';
            icon.className = 'fa-solid fa-chevron-up';
        } else {
            table.style.display = 'none';
            content.style.display = 'block';
            icon.className = 'fa-solid fa-chevron-down';
        }
    };
};