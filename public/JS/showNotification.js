function showNotification(message, position = 'bottom-right', duration = 3000, type = 'primary') {
    let wrapper = document.querySelector(`.notification-wrapper.${position}`);
    
    if (!wrapper) {
      wrapper = document.createElement('div');
      wrapper.classList.add('notification-wrapper', position);
      document.body.appendChild(wrapper);
    }

    const notification = document.createElement('div');
    notification.classList.add('notification', type);
    notification.innerHTML = `
      <span class="close-btn">✖</span>
      ${message}
    `;

    wrapper.appendChild(notification);

    setTimeout(() => {
      notification.classList.add('show');
    }, 10);

    notification.querySelector('.close-btn').onclick = () => {
      notification.classList.remove('show');
      notification.classList.add('hide');
      setTimeout(() => notification.remove(), 500);
    };

    setTimeout(() => {
      if (notification.parentElement) {
        notification.classList.remove('show');
        notification.classList.add('hide');
        setTimeout(() => notification.remove(), 500);
      }
    }, duration);
}