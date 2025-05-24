// Flag to ensure _initMotionEngine is called only once
window.motionEngineInitialized = false;

function _initMotionEngine() {
  // Check if already initialized
  if (window.motionEngineInitialized) {
    return;
  }

  const elements = document.querySelectorAll('.motion-animate');

  elements.forEach(element => {
    element.addEventListener('animationend', function handler(event) {
      // Iterate over classList to find and remove the specific animation class
      for (const cls of element.classList) {
        if (cls.startsWith('motion-') && cls !== 'motion-animate') {
          element.classList.remove(cls);
          // Assuming only one such animation class is active at a time
          // If multiple could be, this logic might need adjustment or a data-attribute approach
          break; 
        }
      }

      // Remove the base animation class
      element.classList.remove('motion-animate');

      // Reset animation duration
      element.style.animationDuration = '';

      // Optional: remove the event listener if it's meant to be a one-time cleanup for this specific animation instance
      // element.removeEventListener('animationend', handler); 
      // For now, keeping it to clean up any future animations on the same element if re-applied.
    });
  });

  // Set the flag to true after initialization
  window.motionEngineInitialized = true;
  // console.log('Motion engine initialized.'); // For debugging
}

// Example of how it might be called - this line might be removed later if PHP handles the call.
// document.addEventListener('DOMContentLoaded', _initMotionEngine);
