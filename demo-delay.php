<?php
/**
 * DEMO ONLY: intentionally adds three seconds before each PHP page response.
 * This does not protect source code or affect static images/CSS/JavaScript.
 * Set the value below to 0 to restore normal response speed.
 */
$demoDelaySeconds = 1;
if ($demoDelaySeconds > 0) {
    usleep((int) (min(3, $demoDelaySeconds) * 1000000));
}
