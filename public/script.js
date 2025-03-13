window.onload = function() {
    const loadTime = performance.now();
    document.getElementById('performanceInfo').textContent = 
    `Page loaded in ${loadTime.toFixed(2)}ms`;
};