<?php

namespace Framework\Console\Commands;

/**
 * Class Maintenance
 *
 * This class defines maintenance mode commands to control and check the status of maintenance mode in the framework.
 * It allows enabling and disabling maintenance mode and provides feedback on its status.
 *
 * @package Framework\Console\Commands
 */
class Maintenance {
    private $maintenanceFilePath = 'storage/framework/maintenance.php';

    /**
     * Execute the default command and provide feedback on maintenance status.
     */
    public function execute() {
        echo $this->isMaintenanceEnabled()
        ? <<<EOT
        Maintenance mode is currently active.
        You can disable it with command "php dfork maintenance:up"
        EOT
        : <<<EOT
        Maintenance mode is currently inactive.
        You can enable it with command "php dfork maintenance:down"
        EOT;
    }

    /**
     * Enable maintenance mode, displaying a custom HTML page or a default message.
     * This method creates the 'maintenance.php' file with the specified content.
     *
     * @throws \Exception If unable to create the 'maintenance.php' file.
     */
    public function down() {
        if ($this->isMaintenanceEnabled()) {
            throw new \Exception("Maintenance mode is already active.");
        }

        $content = <<<'EOT'
        <?php
        $maintenanceIndexHtmlPath = __DIR__.'/resources/index.php';

        function asset($file) {
            return '/storage/framework/resources/'.$file;
        }

        if (file_exists($maintenanceIndexHtmlPath)) {
            $htmlContent = file_get_contents($maintenanceIndexHtmlPath);

            // Find all occurrences of {{ asset(...) }}
            $pattern = '/{{ asset\((.*?)\) }}/';
            preg_match_all($pattern, $htmlContent, $matches);

            // Iterate through found expressions and replace them with the appropriate paths
            foreach ($matches[0] as $match) {
                $assetExpression = $match;
                $assetPath = trim($matches[1][array_search($match, $matches[0])], " '\"");

                // Replace the expression with the actual path
                $replacement = asset($assetPath);
                $htmlContent = str_replace($assetExpression, $replacement, $htmlContent);
            }

            // Display the processed HTML
            eval("?>" .$htmlContent);
        }
        else {
            echo "The application is currently unavailable due to maintenance work. Please try again later.\n";
        }

        exit();

        EOT;

        if (file_put_contents($this->maintenanceFilePath, $content) === false) {
            throw new \Exception("Failed to create the 'maintenance.php' file.");
        }

        echo "Maintenance mode has been activated.\n";
    }

    /**
     * Disable maintenance mode by removing the 'maintenance.php' file.
     *
     * @throws \Exception If unable to disable maintenance mode.
     */
    public function up() {
        if (!$this->isMaintenanceEnabled()) {
            throw new \Exception("Maintenance mode is not active.");
        }

        if (unlink($this->maintenanceFilePath)) {
            echo "Maintenance mode has been deactivated.\n";
        } else {
            throw new \Exception("Failed to disable maintenance mode.");
        }
    }

    /**
     * Check if maintenance mode is enabled by verifying the existence of the 'maintenance.php' file.
     *
     * @return bool True if maintenance mode is enabled; otherwise, false.
     */
    private function isMaintenanceEnabled() {
        return file_exists($this->maintenanceFilePath);
    }
}