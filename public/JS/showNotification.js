/**
 * Displays a temporary, non-blocking notification message on the screen.
 *
 * This function creates and manages a "toast-style" notification. It dynamically
 * creates a notification element, appends it to the correct position on the screen,
 * and handles its appearance and disappearance with CSS animations. Notifications
 * can be dismissed manually or will hide automatically after a specified duration.
 *
 * @param {string} message - The text message to be displayed in the notification.
 * @param {string} [position='bottom-right'] - The position on the screen where the
 *   notification should appear. Valid options are 'bottom-right', 'bottom-left',
 *   'top-right', and 'top-left'.
 * @param {number} [duration=3000] - The duration in milliseconds for which the
 *   notification will be visible before automatically fading out.
 * @param {string} [type='primary'] - The type of notification, which affects its
 *   styling. Valid options include 'primary', 'success', 'danger', 'info', etc.
 * @returns {void}
 */
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
