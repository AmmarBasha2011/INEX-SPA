function animate(elementOrSelector, animationName, durationMs) {
  // 1. Element Resolution
  let element;
  if (typeof elementOrSelector === 'string') {
    element = document.querySelector(elementOrSelector);
  } else if (elementOrSelector instanceof HTMLElement) {
    element = elementOrSelector;
  }

  if (!element) {
    console.error('Motion Engine: Element not found for selector or element provided:', elementOrSelector);
    return;
  }

  // 2. Parameter Validation
  if (typeof animationName !== 'string' || animationName.trim() === '') {
    console.error('Motion Engine: animationName must be a non-empty string.');
    return;
  }
  if (typeof durationMs !== 'number' || durationMs <= 0) {
    console.error('Motion Engine: durationMs must be a positive number.');
    return;
  }

  // 3. Apply Animation
  element.style.animationDuration = `${durationMs}ms`;
  element.classList.add('motion-animate', `motion-${animationName}`);

  // 4. Handle Animation End
  function handleAnimationEnd() {
    element.classList.remove('motion-animate', `motion-${animationName}`);
    element.style.animationDuration = ''; // Reset duration
    // Remove the event listener to prevent it from running again
    element.removeEventListener('animationend', handleAnimationEnd);
  }

  // Add the event listener.
  // The self-removal in handleAnimationEnd should generally suffice.
  element.addEventListener('animationend', handleAnimationEnd);
}
