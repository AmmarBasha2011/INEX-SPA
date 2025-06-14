<?php

/*
DEPRECATED: This PHP animate() function is deprecated as of 2024-07-11.
Please use the new JavaScript-based motion engine.

The new `animate(elementOrSelector, animationName, durationMs)` function is available in `public/JS/motion_engine.js`.
Ensure the Motion Engine is enabled by setting `USE_ANIMATE=true` in your .env file, which will load the necessary JS and CSS files.

The old function code is preserved below for reference but should not be used in new development.

/**
 * Applies a CSS animation to a given element.
 *
 * @param string $elementSelectorOrId The CSS selector or ID of the element to animate.
 * @param string $animationName The name of the animation (e.g., 'fade-in').
 * @param int $durationMs The duration of the animation in milliseconds.
 */
function animate(string $elementSelectorOrId, string $animationName, int $durationMs): void
{
    if (getEnvValue('MOTION_ENGINE_ENABLED') !== 'true') {
        echo "<!-- Motion Engine is disabled via .env configuration -->\n";

        return;
    }

    // Input validation
    if (empty(trim($elementSelectorOrId))) {
        echo "<!-- Motion Engine: Invalid parameters (elementSelectorOrId is empty) -->\n";

        return;
    }
    if (empty(trim($animationName))) {
        echo "<!-- Motion Engine: Invalid parameters (animationName is empty) -->\n";

        return;
    }
    if ($durationMs <= 0) {
        echo "<!-- Motion Engine: Invalid parameters (durationMs must be positive) -->\n";

        return;
    }

    // Ensure $durationMs is an integer
    $durationMs = (int) $durationMs;

    // Prepare strings for JavaScript. htmlspecialchars is important for $elementSelectorOrId.
    $jsSelector = json_encode($elementSelectorOrId);
    $jsAnimationName = json_encode($animationName); // Though used in concatenation, good practice.

    echo "<script>\n";
    echo "  (function() {\n";
    echo "    let selector = {$jsSelector};\n";
    echo "    let element = document.querySelector(selector);\n";
    echo "\n";
    echo "    // If element not found, and selector looks like a bare ID (no '.', '#', '[', or space)\n";
    echo "    // then try prepending '#' to it.\n";
    echo "    if (!element && !selector.startsWith('.') && !selector.startsWith('#') && !selector.includes('[') && !selector.includes(' ')) {\n";
    echo "        element = document.querySelector('#' + selector);\n";
    echo "    }\n";
    echo "\n";
    echo "    if (element) {\n";
    echo "      element.style.animationDuration = '{$durationMs}ms';\n";
    echo "      element.classList.add('motion-animate', 'motion-' + {$jsAnimationName});\n";
    echo "      if (typeof _initMotionEngine === 'function') {\n";
    echo "        _initMotionEngine();\n";
    echo "      } else {\n";
    echo "        console.warn('Motion Engine: _initMotionEngine() function not found. Ensure motion_engine.js is loaded.');\n";
    echo "      }\n";
    echo "    } else {\n";
    echo "      console.warn('Motion Engine: Element not found for selector: ' + selector);\n";
    echo "    }\n";
    echo "  })();\n";
    echo "</script>\n";
}
