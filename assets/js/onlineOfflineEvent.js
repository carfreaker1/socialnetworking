async function checkInternet() {
    try {
        const response = await fetch('https://images.unsplash.com/photo-1607746882042-944635dfe10e?q=80&w=640', { method: 'HEAD', cache: 'no-store' });
        return response.ok;
    } catch (error) {
        return false;
    }
}

async function updateDotColor() {
    const dots = document.querySelectorAll('.dot');
    const isOnline = await checkInternet();
    
    if (isOnline) {
        dots.forEach(dot => dot.style.background = 'green');
        console.log("You are online!");
    } else {
        dots.forEach(dot => dot.style.background = 'red');
        console.log("You are offline!");
    }
}

updateDotColor();
window.addEventListener('online', updateDotColor);
window.addEventListener('offline', updateDotColor);